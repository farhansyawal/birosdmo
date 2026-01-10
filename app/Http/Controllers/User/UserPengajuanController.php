<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\PengajuanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan untuk hapus file lama

class UserPengajuanController extends Controller
{
/**
 * Tampilkan halaman pengajuan layanan untuk user.
 */
public function index()
{
    $layanans = Layanan::where('is_active', true)->get();
    return view('user.pages.pengajuan', compact('layanans'));
}

/**
 * Mengambil detail data pengajuan untuk Modal View & Edit Form.
 */
public function show($id)
{
    // 1. Pastikan hanya mengambil data milik user yang sedang login (Security)
    $pengajuan = PengajuanLayanan::with(['layanan.fields'])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    // 2. Decode JSON data
    $dataUmum = json_decode($pengajuan->data_umum ?? '{}', true) ?? [];
    $dataLayanan = json_decode($pengajuan->data_layanan ?? '{}', true) ?? [];

    $fields = $pengajuan->layanan->fields;

    // 3. Reformat Data Layanan untuk Frontend (Gabungkan type & value)
    $formattedLayanan = [];
    foreach ($fields as $field) {
        $name = $field->name;
        $type = $field->type ?? 'text';
        $value = $dataLayanan[$name] ?? null;

        if ($type === 'file' && $value) {
            // Kirim URL Lengkap ke JS, agar bisa ditampilkan
            if (!str_starts_with($value, 'http')) {
                $value = asset('storage/' . $value);
            }
        }

        $formattedLayanan[$name] = [
            'type' => $type,
            'value' => $value,
            'label' => $field->label
        ];
    }

    // 4. Return JSON Lengkap
    return response()->json([
        'success' => true,
        'status' => $pengajuan->status,
        'catatan' => $pengajuan->catatan,
        'data_umum' => $dataUmum, // Data umum tetap flat array (nomor_surat, perihal, dll.)
        'data_layanan' => $formattedLayanan // Data Layanan dikirim terstruktur
    ]);
}

/**
 * Simpan data pengajuan layanan dari user.
 */
public function submit(Request $request, $layananId)
{
    $layanan = Layanan::with('fields')->findOrFail($layananId);

    // --- VALIDASI AWAL ---
    $request->validate([
        'nomor_surat_usulan' => 'required|string|max:255',
        'perihal_surat_usulan' => 'required|string|max:255',
        'tanggal_surat_usulan' => 'required|date',
        'unit_kerja' => 'required|string',
    ]);

    $dataUmum = [
        'nomor_surat_usulan' => $request->nomor_surat_usulan,
        'perihal_surat_usulan' => $request->perihal_surat_usulan,
        'tanggal_surat_usulan' => $request->tanggal_surat_usulan,
        'unit_kerja' => $request->unit_kerja,
    ];

    // Simpan data layanan (form dinamis)
    $dataLayanan = [];
    foreach ($layanan->fields as $field) {
        $name = $field->name;
        if ($field->type === 'file' && $request->hasFile($name)) {
            $file = $request->file($name);
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');

            // KOREKSI FILE PATH: Simpan hanya RELATIVE PATH ke database
            $dataLayanan[$name] = $path;
        } else {
            $dataLayanan[$name] = $request->input($name);
        }
    }

    // Simpan ke database
    PengajuanLayanan::create([
        'user_id' => Auth::id(),
        'layanan_id' => $layanan->id,
        'data_umum' => json_encode($dataUmum),
        'data_layanan' => json_encode($dataLayanan),
        'status' => 'pending',
        'progress' => 100,
    ]);

    return redirect()->route('user.pages.riwayat-pengajuan')
        ->with('success', 'Pengajuan layanan berhasil dikirim!');
}

/**
 * Menyimpan Perbaikan (Revisi)
 */
public function update(Request $request, $id)
{
    // 1. Cari data & Validasi Pemilik
    $pengajuan = PengajuanLayanan::with('layanan.fields')
        ->where('user_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    // 2. Validasi Status (Hanya boleh edit jika status 'perlu_revisi')
    if ($pengajuan->status !== 'perlu_revisi') {
        return response()->json(['message' => 'Data tidak dapat diedit saat ini.'], 403);
    }

    // 3. Ambil data lama & Definisikan Fields
    $dataUmum = json_decode($pengajuan->data_umum, true) ?? [];
    $dataLayanan = json_decode($pengajuan->data_layanan, true) ?? [];
    $fields = $pengajuan->layanan->fields ?? collect();

    // Mapping Field Tipe untuk membedakan File vs Text
    $fileFields = $fields->where('type', 'file')->pluck('name')->toArray();

    // 4. Update Data Umum (Tetap menggunakan input form)
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

            // Hapus file lama jika ada (optional)
            if (isset($dataLayanan[$key]) && is_string($dataLayanan[$key])) {
                $oldPath = str_replace(asset('storage/'), '', $dataLayanan[$key]);
                Storage::disk('public')->delete($oldPath);
            }

            // SIMPAN HANYA RELATIVE PATH
            $dataLayanan[$key] = $newPath;
        }
    }

    // 6. Update Data Layanan (Untuk field yang berupa TEXT)
    foreach ($dataLayanan as $key => $val) {
        if (!in_array($key, $fileFields) && $request->has($key)) {
            $dataLayanan[$key] = $request->input($key);
        }
    }


    // 7. Simpan ke Database & Tentukan Status Tujuan
    $pengajuan->data_umum = json_encode($dataUmum);
    $pengajuan->data_layanan = json_encode($dataLayanan);

    // --- KOREKSI: Status harus kembali ke VERIFIKATOR ---
    if ($pengajuan->status === 'perlu_revisi') {
        // Karena HANYA Verifikator yang bisa set revisi, dokumen kembali ke mereka.
        $pengajuan->status = 'menunggu_verifikator';
    } else {
        // Fallback jika ada logic lain
        $pengajuan->status = 'pending';
    }

    $pengajuan->catatan = null; // Hapus catatan revisi
    $pengajuan->save();

    return response()->json(['success' => true, 'message' => 'Perbaikan berhasil dikirim.']);
}
}