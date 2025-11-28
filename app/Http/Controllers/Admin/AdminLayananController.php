<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;

class AdminLayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::orderBy('created_at', 'DESC')->get();
        return view('admin.layanan.index', compact('layanan'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|max:255',
            'deskripsi' => 'required'
        ]);

        // 1. Cek apakah checkbox 'is_active' dicentang
        // Jika ada di request, simpan 1. Jika tidak, simpan 0.
        $isActive = $request->has('is_active') ? 1 : 0;

        Layanan::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_active' => $isActive // Masukkan status manual
        ]);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    // Ganti (Layanan $layanan) menjadi ($id) agar sesuai dengan Route {id}
    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('admin.layanan.edit', compact('layanan'));
    }

    // Ganti (Layanan $layanan) menjadi ($id)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'      => 'required|max:255',
            'deskripsi' => 'required'
        ]);

        $layanan = Layanan::findOrFail($id);

        // 2. Cek logika checkbox saat update
        // Kalau user uncheck, html tidak kirim apa-apa, jadi harus kita set 0 manual
        $isActive = $request->has('is_active') ? 1 : 0;

        $layanan->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_active' => $isActive
        ]);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil dihapus'
        ]);
    }

    public function toggle($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->is_active = !$layanan->is_active;
        $layanan->save();

        return response()->json([
            'success' => true,
            'message' => $layanan->is_active 
                ? 'Layanan berhasil diaktifkan.' 
                : 'Layanan berhasil dinonaktifkan.'
        ]);
    }
}