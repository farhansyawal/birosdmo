<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanLayanan;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik
        $stats = [
            'total'   => PengajuanLayanan::where('user_id', $user->id)->count(),
            'proses'  => PengajuanLayanan::where('user_id', $user->id)->whereIn('status', ['pending', 'menunggu_koordinator', 'menunggu_verifikator', 'menunggu_kabiro'])->count(),
            'selesai' => PengajuanLayanan::where('user_id', $user->id)->where('status', 'diterima')->count(),
            'revisi'  => PengajuanLayanan::where('user_id', $user->id)->whereIn('status', ['ditolak', 'perlu_revisi'])->count(),
        ];

        // Aktivitas Terbaru (Ambil 5)
        $recentActivities = PengajuanLayanan::with('layanan')
                            ->where('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('user.dashboard', compact('user', 'stats', 'recentActivities'));
    }
}