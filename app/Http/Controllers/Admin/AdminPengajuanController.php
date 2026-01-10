<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminPengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanLayanan::with(['user', 'layanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanLayanan::with(['user', 'layanan.fields'])->findOrFail($id);

        $dataUmumRaw = json_decode($pengajuan->data_umum ?? '{}', true) ?? [];
        $dataLayananRaw = json_decode($pengajuan->data_layanan ?? '{}', true) ?? [];

        $dataForm = array_merge($dataUmumRaw, $dataLayananRaw);
        $fields = $pengajuan->layanan->fields->sortBy('queue');
        $formattedLayanan = [];

        if ($fields->isNotEmpty()) {
            foreach ($fields as $field) {
                $name = $field->name;
                if (in_array($name, ['nomor_surat_usulan', 'perihal_surat_usulan', 'tanggal_surat_usulan', 'unit_kerja'])) {
                    continue;
                }

                $label = $field->label ?? ucfirst(str_replace('_', ' ', $name));
                $type = $field->type ?? 'text';
                $value = $dataForm[$name] ?? null;

                if ($type === 'file' && $value) {
                    if (!str_starts_with($value, 'http')) {
                        $value = asset('storage/' . $value);
                    }
                }

                if (in_array($type, ['date']) && $value) {
                    try {
                        $value = Carbon::parse($value)->translatedFormat('d F Y');
                    } catch (\Exception $e) {
                    }
                }

                $formattedLayanan[$name] = [
                    'label' => $label,
                    'type' => $type,
                    'value' => $value
                ];
            }
        } else {
            foreach ($dataLayananRaw as $k => $v) {
                if (is_string($v) && (str_contains($v, 'uploads/') || str_contains($v, 'documents/'))) {
                    $v = asset('storage/' . $v);
                }
                $formattedLayanan[$k] = [
                    'label' => ucfirst(str_replace('_', ' ', $k)),
                    'type' => 'text',
                    'value' => $v
                ];
            }
        }

        $unitKerja = $pengajuan->user->unit_kerja ?? $dataUmumRaw['unit_kerja'] ?? '-';

        // --- PERBAIKAN LABEL STATUS (MAPPING) ---
        $statusLabels = [
            'pending' => 'Pending (TU)',
            'menunggu_koordinator' => 'Menunggu Koordinator',
            'menunggu_verifikator' => 'Menunggu Verifikator',
            'diterima' => 'Diterima (Selesai)',
            'ditolak' => 'Ditolak',
            'perlu_revisi' => 'Perlu Revisi'
        ];

        // Ambil label dari array, jika tidak ada gunakan default formatter
        $statusDisplay = $statusLabels[$pengajuan->status] ?? ucfirst(str_replace('_', ' ', $pengajuan->status));

        $dataUmumFormatted = [
            'Nomor Surat' => $dataUmumRaw['nomor_surat_usulan'] ?? '-',
            'Perihal' => $dataUmumRaw['perihal_surat_usulan'] ?? '-',
            'Tanggal Surat' => isset($dataUmumRaw['tanggal_surat_usulan']) ? Carbon::parse($dataUmumRaw['tanggal_surat_usulan'])->translatedFormat('d F Y') : '-',
            'Unit Kerja' => $unitKerja,
            'Status Saat Ini' => $statusDisplay, // Menggunakan Label yang Benar
        ];

        return response()->json([
            'data_umum' => $dataUmumFormatted,
            'data_layanan' => $formattedLayanan,
            'status' => $pengajuan->status,
            'catatan' => $pengajuan->catatan
        ]);
    }

    public function destroy($id)
    {
        try {
            $pengajuan = PengajuanLayanan::findOrFail($id);

            $dataLayanan = json_decode($pengajuan->data_layanan ?? '{}', true);

            if ($dataLayanan) {
                foreach ($dataLayanan as $val) {
                    if (is_string($val) && (str_contains($val, 'uploads/') || str_contains($val, 'storage/'))) {
                        $path = str_replace(asset('storage/'), '', $val);
                        $path = ltrim($path, '/');
                        if (Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->delete($path);
                        }
                    }
                }
            }

            $pengajuan->delete();

            return redirect()->back()->with('success', 'Data pengajuan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }


    public function forceUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $pengajuan = PengajuanLayanan::findOrFail($id);
        $pengajuan->status = $request->status;

        if ($request->filled('catatan')) {
            $pengajuan->catatan = $request->catatan;
        }

        if ($request->status == 'pending')
            $pengajuan->progress = 25;
        if ($request->status == 'diterima')
            $pengajuan->progress = 100;

        $pengajuan->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diubah paksa.']);
    }
}