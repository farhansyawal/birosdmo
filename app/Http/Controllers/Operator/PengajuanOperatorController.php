<?php

namespace App\Http\Controllers\Operator;

use App\Models\PengajuanLayanan;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PengajuanOperatorController extends Controller
{
    /**
     * Menampilkan daftar pengajuan.
     */
    public function index()
    {
        $pengajuans = PengajuanLayanan::with(['user', 'layanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // --- TAMBAHKAN BAGIAN INI ---
        // Mengambil data user berdasarkan posisi untuk ditampilkan di dropdown
        $koordinator = User::where('position', 'koordinator')->first();
        $kabiro = User::where('position', 'kepala_biro')->first();
        $verifikator = User::where('position', 'verifikator')->first();
        // ----------------------------

        // Jangan lupa tambahkan variabel baru ke dalam compact()
        return view('operator.pages.pengajuan.index', compact('pengajuans', 'koordinator', 'kabiro', 'verifikator'));
    }

    /**
     * Mengambil detail data pengajuan untuk Modal View.
     */
    public function show($id)
    {
        $pengajuan = PengajuanLayanan::with(['user', 'layanan.fields'])->findOrFail($id);

        // --- DECODE DATA MENTAH DARI DB ---
        $dataUmumRaw = json_decode($pengajuan->data_umum ?? '{}', true) ?? [];
        $dataLayananRaw = json_decode($pengajuan->data_layanan ?? '{}', true) ?? [];
        $dataForm = array_merge($dataUmumRaw, $dataLayananRaw);

        $user = $pengajuan->user;
        $layanan = $pengajuan->layanan;
        $fields = $layanan->fields ?? collect([]);
        $formatted = [];

        if ($fields->isNotEmpty()) {
            foreach ($fields->sortBy('queue') as $field) {
                $name = $field->name;
                $label = $field->label ?? ucfirst(str_replace('_', ' ', $name));
                $type = $field->type ?? 'text';
                $value = $dataForm[$name] ?? null;

                // Format Tanggal
                if (in_array($type, ['month', 'date']) && $value) {
                    try {
                        $value = \Carbon\Carbon::parse($value)->translatedFormat('d F Y');
                    } catch (\Exception $e) {
                    }
                }

                // Format File (Pastikan path lengkap untuk view)
                if ($type === 'file' && $value) {
                    if (!str_starts_with($value, 'http')) {
                        $value = asset('storage/' . $value);
                    }
                }

                $formatted[$name] = ['label' => $label, 'type' => $type, 'value' => $value];
            }
        }

        // Data Umum (Header) - Amankan dari user/layanan yang null
        $informasiUmum = [
            'Nomor Surat' => $dataUmumRaw['nomor_surat_usulan'] ?? '-',
            'Perihal' => $dataUmumRaw['perihal_surat_usulan'] ?? '-',
            'Tanggal Surat' => isset($dataUmumRaw['tanggal_surat_usulan']) ? \Carbon\Carbon::parse($dataUmumRaw['tanggal_surat_usulan'])->translatedFormat('d F Y') : '-',
            'Unit Kerja' => $dataUmumRaw['unit_kerja'] ?? '-',
            'Status Saat Ini' => ucfirst(str_replace('_', ' ', $pengajuan->status)),
        ];

        // Filter Data Layanan (Data berkas & field non-umum)
        $excluded = ['nomor_surat_usulan', 'perihal_surat_usulan', 'tanggal_surat_usulan', 'unit_kerja'];
        $dataLayanan = collect($formatted)->reject(fn($_, $key) => in_array($key, $excluded))->toArray();

        // BLOKIR AKSES BERKAS UNTUK KEPALA BIRO (Router)
        if (Auth::user()->position === 'kepala_biro') {
            foreach ($dataLayanan as $key => &$item) {
                if ($item['type'] === 'file' || str_contains(strtolower($key), 'upload')) {
                    $item['value'] = 'Akses Berkas Dibatasi untuk Kepala Biro';
                    $item['type'] = 'text';
                }
            }
            unset($item);
        }

        return response()->json([
            'data_umum' => $informasiUmum,
            'data_layanan' => $dataLayanan,
            'status' => $pengajuan->status,
            'catatan' => $pengajuan->catatan,
            'layanan_nama' => $layanan->nama ?? 'Nama Layanan Tidak Ditemukan', // Tambahan untuk UI
        ]);
    }

    /**
     * Menyimpan hasil checklist verifikasi TANPA mengubah status.
     */
    public function saveVerifikasi(Request $request, $id)
    {
        $request->validate(['hasil' => 'required|array']);
        $pengajuan = PengajuanLayanan::findOrFail($id);
        $this->authorizeAction(Auth::user(), $pengajuan);

        $pengajuan->verifikasi = json_encode($request->hasil);
        $pengajuan->save();

        return response()->json(['success' => true, 'message' => 'Checklist verifikasi tersimpan.']);
    }

    /**
     * Mengubah Status Dokumen (Pindah Tangan / Selesai) & Handle File Reset.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string', 'catatan' => 'nullable|string']);

        $pengajuan = PengajuanLayanan::findOrFail($id);
        $user = Auth::user();
        $this->authorizeAction($user, $pengajuan);

        $pengajuan->status = $request->status;

        // Handle Revisi / Tolak (Termasuk Reset Data)
        if ($request->status === 'perlu_revisi') {
            // HANYA VERIFIKATOR YANG BOLEH MENGATUR STATUS KE 'PERLU_REVISI'
            if (Auth::user()->position !== 'verifikator') {
                abort(403, 'Akses Ditolak: Hanya Verifikator yang dapat meminta revisi.');
            }

            if ($request->status == 'perlu_revisi') {
                $verifikasi = json_decode($pengajuan->verifikasi ?? '{}', true);
                $dataLayanan = json_decode($pengajuan->data_layanan ?? '{}', true);
                $isDataChanged = false;

                foreach ($verifikasi as $key => $statusCheck) {
                    if ($statusCheck === 'Belum Lengkap') {
                        if (isset($dataLayanan[$key]) && is_string($dataLayanan[$key])) {
                            $relativePath = str_replace(asset('storage/'), '', $dataLayanan[$key]);

                            if (Storage::disk('public')->exists($relativePath)) {
                                Storage::disk('public')->delete($relativePath); // Hapus file lama
                            }

                            $dataLayanan[$key] = null; // Kosongkan data
                            $isDataChanged = true;
                        }
                    }
                }

                if ($isDataChanged) {
                    $pengajuan->data_layanan = json_encode($dataLayanan);
                    $pengajuan->status = 'perlu_revisi'; // Reset ke pending
                }
            }
        } else {
            $pengajuan->catatan = null;
        }

        $pengajuan->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
    }

    /**
     * Helper: Mengecek apakah user berhak mengedit dokumen ini.
     */
    private function authorizeAction($user, $pengajuan)
    {
        $pos = $user->position;
        $status = $pengajuan->status;

        if ($user->role === 'admin')
            return true;

        // TU (Acts on pending)
        if ($pos === 'tu' && $status === 'pending')
            return true;

        // KOORDINATOR (Acts on menunggu_koordinator)
        if ($pos === 'koordinator' && $status === 'menunggu_koordinator')
            return true;

        // VERIFIKATOR (Acts on menunggu_verifikator) - Sekarang FINAL DECISION MAKER
        if ($pos === 'verifikator' && $status === 'menunggu_verifikator')
            return true;

        // KEPALA BIRO (Acts on menunggu_kabiro) - Sekarang ROUTER/DISPOSER
        if ($pos === 'kepala_biro' && $status === 'menunggu_kabiro') {
            return true;
        }

        abort(403, 'Anda tidak memiliki otoritas untuk memproses dokumen ini pada tahap sekarang.');
    }

    /**
     * Export ke Excel
     */
    public function exportExcel()
    {
        $pengajuans = PengajuanLayanan::with(['user', 'layanan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['No', 'Nama Pengguna', 'Unit Kerja', 'Layanan', 'Status', 'Tanggal Pengajuan'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
            $col++;
        }

        // Style Header Background
        $sheet->getStyle('A1:F1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('198754');
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Isi Data
        $row = 2;
        $no = 1;
        foreach ($pengajuans as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->user->name ?? '-');
            $sheet->setCellValue('C' . $row, $item->user->unit_kerja ?? '-');
            $sheet->setCellValue('D' . $row, $item->layanan->nama ?? '-');
            $sheet->setCellValue('E' . $row, ucfirst(str_replace('_', ' ', $item->status)));
            $sheet->setCellValue('F' . $row, $item->created_at->format('d/m/Y H:i'));
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'data_pengajuan_' . now()->format('Y_m_d_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}