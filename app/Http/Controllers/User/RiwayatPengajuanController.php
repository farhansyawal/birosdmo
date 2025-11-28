<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanLayanan;
use Illuminate\Support\Facades\Auth;


class RiwayatPengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $riwayat = PengajuanLayanan::with('layanan')
            ->where('user_id', $user->id) // pastikan kolomnya user_id
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.pages.riwayat-pengajuan', compact('riwayat'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanLayanan::with('layanan')->findOrFail($id);

        return response()->json([
            'layanan' => $pengajuan->layanan->nama_layanan ?? '-',
            'status' => ucfirst($pengajuan->status),
            'tanggal' => $pengajuan->created_at->format('d M Y H:i'),
            'keterangan' => $pengajuan->keterangan ?? null,
        ]);
    }
}
