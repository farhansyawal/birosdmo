<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        // Nanti bisa diganti ambil dari database
        $layananList = [
            'Layanan 1',
            'Layanan 2',
            'Layanan 3',
            'Layanan 4',
            // dst sampai 28 layanan
        ];

        return view('layanan', compact('layananList'));
    }
}
