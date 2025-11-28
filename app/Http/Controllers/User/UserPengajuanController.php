<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\FormInput;
use App\Models\PengajuanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserPengajuanController extends Controller
{
    /**
     * Tampilkan halaman pengajuan layanan untuk user.
     */
    public function index()
    {
        // Ambil semua layanan untuk ditampilkan di halaman pengajuan
        
        $layanans = Layanan::where('is_active', true)->get();

        return view('user.pages.pengajuan', compact('layanans'));
    }

    /**
     * Simpan data pengajuan layanan dari user.
     */
    public function submit(Request $request, $layananId)
    {
        $layanan = Layanan::with('fields')->findOrFail($layananId);

        // Validasi 4 form penting
        $request->validate([
            'nomor_surat_usulan' => 'required|string|max:255',
            'perihal_surat_usulan' => 'required|string|max:255',
            'tanggal_surat_usulan' => 'required|date',
            'unit_kerja' => 'required|string',
        ]);

        // Simpan data umum
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
                $dataLayanan[$name] = asset('storage/' . $path);
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

        // Redirect ke halaman riwayat pengajuan
        return redirect()->route('user.pages.riwayat-pengajuan')
            ->with('success', 'Pengajuan layanan berhasil dikirim!');
    }
}
