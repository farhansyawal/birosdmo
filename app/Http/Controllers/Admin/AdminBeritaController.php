<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminBeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::orderBy('tanggal', 'desc')->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    // API untuk Modal Edit (Mengembalikan JSON)
    public function show($id)
    {
        $berita = Berita::findOrFail($id);

        return response()->json([
            'id' => $berita->id,
            'judul' => $berita->judul,
            'tanggal' => $berita->tanggal, // Pastikan format YYYY-MM-DD di database atau model
            'isi' => $berita->isi,
            'gambar_url' => $berita->gambar ? asset('storage/' . $berita->gambar) : null
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/berita', $filename, 'public');
            $data['gambar'] = $path;
        }

        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diterbitkan.');
    }


    public function getJson($id)
    {
        $berita = Berita::findOrFail($id);

        return response()->json([
            'id' => $berita->id,
            'judul' => $berita->judul,
            'tanggal' => $berita->tanggal,
            'isi' => $berita->isi,
            'gambar_url' => $berita->gambar
                ? asset('storage/' . $berita->gambar)
                : null
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $berita = Berita::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/berita', $filename, 'public');
            $data['gambar'] = $path;
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}