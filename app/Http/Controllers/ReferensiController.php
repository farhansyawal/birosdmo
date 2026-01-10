<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Cache; // Tambahkan Cache biar cepat

class ReferensiController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = storage_path('app/public/Kebutuhan Formulir Layanan.xlsx');
    }

    public function getUnitKerja()
    {
        return $this->getSheetData('REFERENSI UNIT KERJA PENGUSUL', 'A');
    }

    public function getJabatan()
    {
        return $this->getSheetData('REFERENSI NAMA JABATAN', 'A');
    }

    public function getPangkat()
    {
        // Pastikan besar kecil huruf sesuai nama Sheet di Excel
        // Cek apakah 'REFERENSI PANGKAT DAN GOLONGAN' atau 'REFERENSI Pangkat dan Golongan'
        return $this->getSheetData('REFERENSI Pangkat dan Golongan', 'A'); 
    }

    public function getPensiun()
    {
        // PERBAIKAN DISINI: Ganti nama sheet jadi PENSIUN
        return $this->getSheetData('REFERENSI JENIS PENSIUN', 'A');
    }

    public function getKenaikanPangkat(): mixed
    {
        // PERBAIKAN DISINI: Ganti nama sheet jadi PENSIUN
        return $this->getSheetData('Referensi Jenis Kenaikan pangka', 'A');
    }

    private function getSheetData($sheetName, $column)
    {
        // Gunakan Cache 60 menit agar tidak load Excel terus menerus (Berat)
        return Cache::remember('ref_' . $sheetName, 60 * 60, function () use ($sheetName, $column) {
            if (!file_exists($this->path)) {
                return [];
            }

            try {
                $spreadsheet = IOFactory::load($this->path);
                $sheet = $spreadsheet->getSheetByName($sheetName);

                if (!$sheet) {
                    return [];
                }

                $rows = $sheet->toArray(null, true, true, true);

                return collect($rows)
                    ->skip(1) // Lewati Header
                    ->map(fn($r) => trim($r[$column] ?? ''))
                    ->filter() // Hapus baris kosong
                    ->unique() // Hapus duplikat
                    ->values()
                    // Format output agar konsisten: [{value: 'X', label: 'X'}]
                    ->map(fn($v) => ['value' => $v, 'label' => $v]) 
                    ->toArray();

            } catch (\Throwable $e) {
                return [];
            }
        });
    }
}