<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Cache; // Disarankan pakai Cache agar tidak berat

class DataMasterController extends Controller
{
    private $excelPath;

    public function __construct()
    {
        $this->excelPath = storage_path('app/public/Kebutuhan Formulir Layanan.xlsx');
    }

    // Helper function untuk load sheet agar codingan lebih rapi
    private function getSheetData($sheetName)
    {
        // Gunakan Caching selama 60 menit agar tidak perlu load Excel terus menerus (Berat!)
        return Cache::remember('excel_' . $sheetName, 60 * 60, function () use ($sheetName) {
            if (!file_exists($this->excelPath)) {
                return [];
            }

            try {
                $spreadsheet = IOFactory::load($this->excelPath);
                $sheet = $spreadsheet->getSheetByName($sheetName);

                if (!$sheet) {
                    return [];
                }

                return $sheet->toArray(null, true, true, true);
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public function unitKerja()
    {
        // PERBAIKAN: Nama sheet disesuaikan dengan file CSV yang diupload
        $rows = $this->getSheetData('REFERENSI UNIT KERJA PENGUSUL');

        $list = array_filter(array_column($rows, 'A'));

        // Hapus Header jika ada (baris pertama)
        if (!empty($list) && (str_contains(strtoupper(reset($list)), 'UNIT KERJA') || strtoupper(reset($list)) === 'NO')) {
            array_shift($list);
        }

        return response()->json(array_values($list));
    }

    public function jabatan()
    {
        // PERBAIKAN: Nama sheet disesuaikan
        $rows = $this->getSheetData('REFERENSI NAMA JABATAN');

        $list = [];
        foreach ($rows as $index => $row) {
            // Skip Header (Baris 1)
            if ($index == 1)
                continue;

            if (!empty($row['A']))
                $list[] = $row['A']; // Jabatan Fungsional? (Sesuaikan kolom)
            if (!empty($row['B']))
                $list[] = $row['B']; // Jabatan Pelaksana? (Sesuaikan kolom)
        }

        // Hapus duplikat & nilai kosong
        $list = array_unique(array_filter($list));
        // Reset index array supaya jadi JSON array cantik [ "a", "b" ] bukan { "1": "a", "5": "b" }
        return response()->json(array_values($list));
    }

    public function pangkat()
    {
        // PERBAIKAN: Nama sheet disesuaikan (Case sensitive biasanya berpengaruh)
        $rows = $this->getSheetData('REFERENSI Pangkat dan Golongan');

        $list = array_filter(array_column($rows, 'A'));

        // Hapus Header
        if (!empty($list)) {
            $firstItem = strtoupper(reset($list));
            if (str_contains($firstItem, 'PANGKAT') || str_contains($firstItem, 'GOLONGAN')) {
                array_shift($list);
            }
        }

        return response()->json(array_values($list));
    }

    public function pensiun()
    {
        // PERBAIKAN: Nama sheet disesuaikan
        $rows = $this->getSheetData('REFERENSI JENIS PENSIUN');

        // Ambil kolom A
        $list = array_filter(array_column($rows, 'A'));

        // Hapus Header "JENIS PENSIUN" jika ada
        if (!empty($list) && strtoupper(reset($list)) === 'JENIS PENSIUN') {
            array_shift($list);
        }

        return response()->json(array_values($list));
    }

    public function kenaikanPangkat()
    {
        // PERBAIKAN: Nama sheet disesuaikan
        $rows = $this->getSheetData('Referensi Jenis Kenaikan pangka');

        // Ambil kolom A
        $list = array_filter(array_column($rows, 'A'));

        // Hapus Header "JENIS PENSIUN" jika ada
        if (!empty($list) && strtoupper(reset($list)) === 'JENIS KENAIKAN PANGKAT') {
            array_shift($list);
        }

        return response()->json(array_values($list));
    }
}