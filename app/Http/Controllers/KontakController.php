<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kontak;
class KontakController extends Controller
{
    public function index ()
{
    return view ('kontak');
}
public function store (request $request)
{
    $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email',
            'pesan' => 'required|string|max:1000',
        ]);

        Kontak::create($request->only('nama', 'email', 'pesan'));

        return redirect()->route('kontak.index')->with('success', 'Pesan Anda telah terkirim!');
    }
}
 