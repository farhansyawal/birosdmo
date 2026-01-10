<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanLayanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatPengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // OPTIMASI QUERY (N+1 & SELECT):
        // 1. with('layanan'): Mencegah N+1 saat memanggil nama layanan.
        // 2. select(...): Hanya ambil kolom yang butuh ditampilkan untuk menghemat memori.
        $riwayat = PengajuanLayanan::with([
            'layanan' => function ($query) {
                $query->select('id', 'nama', 'deskripsi');
            }
        ])
            ->where('user_id', $user->id)
            // HAPUS 'nomor_surat' DARI SINI:
            ->select('id', 'user_id', 'layanan_id', 'status', 'created_at', 'data_umum', 'data_layanan', 'catatan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.pages.riwayat-pengajuan', compact('riwayat'));
    }

    // Method show tetap ada untuk AJAX call pada menu EDIT (agar data realtime)
    public function show($id)
    {
        $pengajuan = PengajuanLayanan::where('user_id', Auth::id())->findOrFail($id);

        // Logic decoding tetap sama untuk kebutuhan edit via AJAX
        $dataUmum = json_decode($pengajuan->data_umum ?? '{}', true);
        $dataLayanan = json_decode($pengajuan->data_layanan ?? '{}', true);

        // Kita format ulang untuk respons JSON Edit
        $formattedLayanan = [];
        if ($dataLayanan) {
            foreach ($dataLayanan as $key => $value) {
                // Deteksi file
                if (is_string($value) && (str_contains($value, 'uploads/') || str_contains($value, 'storage/'))) {
                    $formattedLayanan[$key] = ['type' => 'file', 'value' => asset('storage/' . $value)]; // Kirim URL lengkap untuk preview
                } else {
                    $formattedLayanan[$key] = ['type' => 'text', 'value' => $value];
                }
            }
        }

        return response()->json([
            'success' => true,
            'id' => $pengajuan->id,
            'layanan_id' => $pengajuan->layanan_id, // <--- TAMBAHKAN BARIS INIx
            'status' => $pengajuan->status,
            'catatan' => $pengajuan->catatan,
            'data_umum' => $dataUmum,
            'data_layanan' => $formattedLayanan
        ]);
    }

    // Method update (Simpan Revisi)
   public function update(Request $request, $id)
    {
        // 1. Cari data & Validasi Pemilik
        $pengajuan = PengajuanLayanan::with('layanan.fields')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // 2. Validasi Status
        if ($pengajuan->status !== 'perlu_revisi') {
            return response()->json(['message' => 'Data tidak dapat diedit saat ini.'], 403);
        }

        // 3. Ambil data lama
        $dataUmum = json_decode($pengajuan->data_umum, true) ?? [];
        $dataLayanan = json_decode($pengajuan->data_layanan, true) ?? [];
        $fields = $pengajuan->layanan->fields ?? collect();
        $fileFields = $fields->where('type', 'file')->pluck('name')->toArray();

        // 4. Update Data Umum
        foreach ($request->except(['_token', '_method', 'submission_id']) as $key => $value) {
            if (!$request->hasFile($key) && array_key_exists($key, $dataUmum)) {
                $dataUmum[$key] = $value;
            }
        }

        // 5. Update Data Layanan & Handle File Upload
        foreach ($fileFields as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time() . '_' . $file->getClientOriginalName();
                $newPath = $file->storeAs('uploads/revisi', $filename, 'public');

                // Hapus file lama jika ada
                if (isset($dataLayanan[$key]) && is_string($dataLayanan[$key])) {
                    $oldPath = str_replace(asset('storage/'), '', $dataLayanan[$key]);
                    if(Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $dataLayanan[$key] = $newPath;
            }
        }

        // 6. Update Data Layanan (Text)
        foreach ($dataLayanan as $key => $val) {
            if (!in_array($key, $fileFields) && $request->has($key)) {
                $dataLayanan[$key] = $request->input($key);
            }
        }

        // 7. Simpan ke Database
        $pengajuan->data_umum = json_encode($dataUmum);
        $pengajuan->data_layanan = json_encode($dataLayanan);
        
        // --- PERBAIKAN DI SINI ---
        $pengajuan->status = 'menunggu_verifikator'; // Status kembali ke Verifikator
        $pengajuan->catatan = null;     // Hapus catatan revisi agar bersih
        // -------------------------
        
        $pengajuan->save();

        return response()->json(['success' => true, 'message' => 'Perbaikan berhasil dikirim ke Verifikator.']);
    }
}