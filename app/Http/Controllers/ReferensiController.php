<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        return $this->getSheetData('REFERENSI PANGKAT DAN GOLONGAN', 'A');
    }

    private function getSheetData($sheetName, $column)
    {
        if (!file_exists($this->path)) {
            return response()->json(['error' => 'File Excel tidak ditemukan.'], 404);
        }

        try {
            $spreadsheet = IOFactory::load($this->path);
            $sheet = $spreadsheet->getSheetByName($sheetName);

            if (!$sheet) {
                return response()->json(['error' => "Sheet '{$sheetName}' tidak ditemukan."], 404);
            }

            $rows = $sheet->toArray(null, true, true, true);

            $values = collect($rows)
                ->skip(1)
                ->map(fn($r) => trim($r[$column] ?? ''))
                ->filter()
                ->unique()
                ->values()
                ->map(fn($v) => ['value' => $v, 'label' => $v])
                ->values();

            return response()->json($values);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Gagal membaca file: ' . $e->getMessage()], 500);
        }
    }
}
