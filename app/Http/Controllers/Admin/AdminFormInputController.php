<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormInput;
use App\Models\Layanan;
use Illuminate\Http\Request;

class AdminFormInputController extends Controller
{
    public function index(Layanan $layanan)
    {
        // ✅ Tambahkan orderBy('queue', 'asc') agar tampilan list urut dari 1, 2, 3...
        $inputs = $layanan->fields()->orderBy('queue', 'asc')->paginate(10);

        return view('admin.form-input.index', [
            'layanan' => $layanan,
            'inputs' => $inputs
        ]);
    }

    public function store(Request $request, Layanan $layanan)
    {
        $request->validate([
            'label' => 'required',
            'name' => 'required',
            'type' => 'required',
            'options' => 'nullable',
        ]);

        // 🔥 LOGIKA AUTO QUEUE 🔥
        // 1. Cari nilai queue tertinggi untuk layanan ini
        $lastQueue = FormInput::where('layanan_id', $layanan->id)->max('queue');

        // 2. Jika belum ada data, mulai dari 1. Jika ada, tambah 1.
        $nextQueue = $lastQueue ? $lastQueue + 1 : 1;

        FormInput::create([
            'layanan_id' => $layanan->id,
            'label' => $request->label,
            'name' => $request->name,
            'type' => $request->type,
            'options' => $request->options ? explode(',', $request->options) : null,
            'is_required' => $request->is_required ? 1 : 0,
            'queue' => $nextQueue, // ✅ Masukkan nilai queue otomatis
        ]);

        return back()->with('success', 'Input form berhasil ditambahkan.');
    }

    public function destroy(FormInput $input)
    {
        $input->delete();
        return back()->with('success', 'Field berhasil dihapus.');
    }
}
