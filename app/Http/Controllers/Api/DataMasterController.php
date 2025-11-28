<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataMasterController extends Controller
{
    private $excelPath;

    public function __construct()
    {
        $this->excelPath = storage_path('app/public/Kebutuhan Formulir Layanan.xlsx'); // sesuaikan path Excel kamu
    }

    public function unitKerja()
    {
        $sheet = IOFactory::load($this->excelPath)->getSheetByName('DATA BASE UNIT KERJA');
        $rows = $sheet->toArray(null, true, true, true);
        $list = array_filter(array_column($rows, 'A'));
        return response()->json(array_values($list));
    }

    public function jabatan()
    {
        $sheet = IOFactory::load($this->excelPath)->getSheetByName('DATA BASE NAMA JABATAN');
        $rows = $sheet->toArray(null, true, true, true);
        $list = [];
        foreach ($rows as $row) {
            if (!empty($row['A'])) $list[] = $row['A']; // jabatan fungsional
            if (!empty($row['B'])) $list[] = $row['B']; // jabatan pelaksana
        }
        $list = array_unique(array_filter($list));
        return response()->json(array_values($list));
    }

    public function pangkat()
    {
        $sheet = IOFactory::load($this->excelPath)->getSheetByName('DATA BASE PANGKAT DAN GOLONGAN');
        $rows = $sheet->toArray(null, true, true, true);
        $list = array_filter(array_column($rows, 'A')); // misalnya kolom A berisi nama pangkat
        return response()->json(array_values($list));
    }
}
