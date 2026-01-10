<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

class BerandaController extends Controller
{
    public function index()
    {
        // Mengambil 3 berita terbaru berdasarkan tanggal
        $featuredNews = Berita::orderBy('tanggal', 'desc')->take(3)->get();

        return view('beranda', compact('featuredNews'));
    }


    public function compressLocal()
    {
        $source = public_path('img/corporate.jpg');
        $destination = public_path('img/corporate-min.jpg');

        // Kompres file JPG
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, 60);
        imagedestroy($image);

        // Kirim nama file ke view
        return view('beranda', [
            'compressedImage' => 'img/corporate-min.jpg'
        ]);
    }
}
