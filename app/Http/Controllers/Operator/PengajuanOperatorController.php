<?php

namespace App\Http\Controllers\Operator;

use App\Models\PengajuanLayanan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Response;

class PengajuanOperatorController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanLayanan::with(['user', 'layanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('operator.pages.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanLayanan::with(['user', 'layanan.fields'])->findOrFail($id);
        $dataForm = $pengajuan->data ?? [];

        // 1. Urutkan field berdasarkan 'queue' (ASC) agar tampil urut
        $fields = $pengajuan->layanan->fields->sortBy('queue');

        $formatted = [];

        if ($fields) {
            foreach ($fields as $field) {
                $name = $field->name;
                $label = $field->label ?? ucfirst(str_replace('_', ' ', $name));
                $type = $field->type ?? 'text';
                $value = $dataForm[$name] ?? null;

                // Format Tanggal (jika ada)
                if (in_array($type, ['month', 'date']) && $value) {
                    try {
                        $value = \Carbon\Carbon::parse($value)->translatedFormat('d F Y');
                    } catch (\Exception $e) {
                        if (preg_match('/^\d{4}-\d{2}$/', $value)) {
                            [$year, $month] = explode('-', $value);
                            $value = \Carbon\Carbon::create($year, $month)->translatedFormat('F Y');
                        }
                    }
                }

                // Format File (jika ada)
                if ($type === 'file' && $value) {
                    $value = asset('storage/' . $value);
                }

                $formatted[$name] = [
                    'label' => $label,
                    'type' => $type,
                    'value' => $value,
                    'queue' => $field->queue // Simpan queue untuk referensi jika perlu
                ];
            }
        }

        // Data Umum (Header)
        $informasiUmum = [
            'Nomor Surat Usulan' => $dataForm['nomor_surat_usulan'] ?? '-',
            'Perihal Surat Usulan' => $dataForm['perihal_surat_usulan'] ?? '-',
            'Tanggal Surat Usulan' => isset($dataForm['tanggal_surat_usulan']) ? \Carbon\Carbon::parse($dataForm['tanggal_surat_usulan'])->translatedFormat('d F Y') : '-',
            'Unit Kerja' => $pengajuan->user->unit_kerja ?? '-',
            'Status' => ucfirst($pengajuan->status),
        ];

        // 2. Filter Data Layanan (Body)
        // Hapus 'jabatan_pegawai...' dan 'pangkat...' dari excluded agar MUNCUL di detail
        $excluded = [
            'nomor_surat_usulan',
            'perihal_surat_usulan',
            'tanggal_surat_usulan',
            'unit_kerja' // Unit kerja sudah ada di data umum, jadi exclude dari body
        ];

        // Kita ambil data dari $formatted yang sudah terurut tadi
        $dataLayanan = collect($formatted)
            ->reject(fn($_, $key) => in_array($key, $excluded))
            ->toArray();

        return response()->json([
            'data_umum' => $informasiUmum,
            'data_layanan' => $dataLayanan, // Ini sekarang sudah terurut sesuai 'queue'
            'status' => $pengajuan->status,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|string',
            ]);

            $pengajuan = PengajuanLayanan::findOrFail($id);
            $pengajuan->status = $request->status;
            $pengajuan->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'status' => $pengajuan->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVerifikasi($id)
    {
        $pengajuan = PengajuanLayanan::findOrFail($id);

        return response()->json([
            'success' => true,
            'hasil' => json_decode($pengajuan->verifikasi ?? '{}', true),
        ]);
    }

    public function saveVerifikasi(Request $request, $id)
    {
        $request->validate([
            'hasil' => 'required|array',
        ]);

        $pengajuan = PengajuanLayanan::findOrFail($id);

        // Simpan hasil verifikasi (checkbox)
        $pengajuan->verifikasi = json_encode($request->hasil);

        // Jika semua checkbox 'Lengkap', maka status "Sudah Diverifikasi"
        $semuaLengkap = collect($request->hasil)->every(fn($v) => $v === 'Lengkap');
        $pengajuan->status = $semuaLengkap ? 'Sudah Diverifikasi' : 'Belum Lengkap';

        $pengajuan->save();

        return response()->json([
            'success' => true,
            'status' => $pengajuan->status,
            'message' => 'Data verifikasi berhasil disimpan.'
        ]);
    }

    public function exportExcel()
    {
        // Ambil data
        $pengajuans = \App\Models\PengajuanLayanan::with(['user', 'layanan'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // === Header ===
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Pengguna');
        $sheet->setCellValue('C1', 'Unit Kerja');
        $sheet->setCellValue('D1', 'Layanan');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Tanggal Pengajuan');

        // Style header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '198754']], // hijau
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // === Isi Data ===
        $row = 2;
        $no = 1;

        foreach ($pengajuans as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->user->name ?? '-');
            $sheet->setCellValue('C' . $row, $item->user->unit_kerja ?? '-');
            $sheet->setCellValue('D' . $row, $item->layanan->nama ?? '-');
            $sheet->setCellValue('E' . $row, ucfirst($item->status));
            $sheet->setCellValue('F' . $row, $item->created_at->format('d/m/Y H:i'));
            $row++;
        }

        // Auto size kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // === Style border data ===
        $sheet->getStyle('A1:F' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'AAAAAA']]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // === Nama file ===
        $fileName = 'data_pengajuan_' . now()->format('Y_m_d_His') . '.xlsx';

        // === Simpan ke output ===
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}
