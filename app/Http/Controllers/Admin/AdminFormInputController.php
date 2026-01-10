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

    // LOGIKA AUTO QUEUE
    $lastQueue = FormInput::where('layanan_id', $layanan->id)->max('queue');
    $nextQueue = $lastQueue ? $lastQueue + 1 : 1;

    // ==========================
    // 🔥 FIX PENTING UNTUK OPTIONS 🔥
    // ==========================
    $options = null;

    if ($request->type === 'select' && $request->filled('options')) {

        $raw = $request->options;

        // Jika user memasukkan JSON valid seperti ["Jan","Feb"]
        $decoded = json_decode($raw, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // Jika valid JSON → simpan apa adanya
            $options = json_encode($decoded);
        } else {
            // Jika user isi seperti: Januari, Februari, Maret
            $explode = explode(',', $raw);
            $clean = array_map('trim', $explode);

            $options = json_encode($clean);
        }
    }

    // SIMPAN DATA
    FormInput::create([
        'layanan_id'  => $layanan->id,
        'label'       => $request->label,
        'name'        => $request->name,
        'type'        => $request->type,
        'options'     => $options, 
        'is_required' => $request->is_required ? 1 : 0,
        'queue'       => $nextQueue,
    ]);

    return back()->with('success', 'Input form berhasil ditambahkan.');
}

    public function destroy(FormInput $input)
    {
        $input->delete();
        return back()->with('success', 'Field berhasil dihapus.');
    }
}
