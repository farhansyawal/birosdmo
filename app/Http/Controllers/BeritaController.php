<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Menampilkan daftar berita (Index).
     */
    public function index()
    {
        // Ambil berita terbaru, urutkan berdasarkan tanggal terbaru
        // Pagination 6 item per halaman agar grid terlihat rapi
        $beritas = Berita::orderBy('tanggal', 'desc')->paginate(6);

        // Ambil berita utama (opsional, misal yang paling baru untuk featured)
        $featuredNews = Berita::orderBy('tanggal', 'desc')->first();

        return view('berita', compact('beritas', 'featuredNews'));
    }

    /**
     * Menampilkan detail berita (Show).
     */
    public function show($id)
    {
        $berita = Berita::findOrFail($id);

        // berita lain (sidebar)
        $otherNews = Berita::where('id', '!=', $id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('berita.show', compact('berita', 'otherNews'));
    }

    public function getJson($id)
    {
        $berita = Berita::findOrFail($id);

        return response()->json([
            'judul' => $berita->judul,
            'tanggal' => \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('l, d F Y'),
            'isi' => $berita->isi, // Pastikan ini menyimpan HTML dari summernote/text editor
            'gambar' => $berita->gambar ? asset('storage/' . $berita->gambar) : null,
            'penulis' => 'Admin' // Atau ambil dari relasi user jika ada
        ]);
    }
}