<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanLayanan;
use Illuminate\Support\Facades\DB;

class DashboardOperatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Query Dasar
        $query = PengajuanLayanan::query();

        // Jika ingin filter berdasarkan jabatan (Opsional, sesuaikan kebutuhan)
        /*
        if ($user->position == 'tu') {
            $query->where('status', 'pending');
        } elseif ($user->position == 'koordinator') {
            $query->where('status', 'menunggu_koordinator');
        }
        */

        // 1. Statistik Cards
        $totalMasuk = PengajuanLayanan::count();
        $totalPending = PengajuanLayanan::whereIn('status', ['pending', 'menunggu_koordinator', 'menunggu_verifikator'])->count();
        $totalSelesai = PengajuanLayanan::where('status', 'diterima')->count();
        $totalDitolak = PengajuanLayanan::whereIn('status', ['ditolak', 'perlu_revisi'])->count();

        // 2. Grafik Area (Aktivitas Bulanan Tahun Ini)
        $aktivitas = PengajuanLayanan::select(
                DB::raw('MONTH(created_at) as bulan'), 
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $dataBulanan = [];
        for ($i = 1; $i <= 12; $i++) $dataBulanan[] = $aktivitas[$i] ?? 0;
        $bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        // 3. Tugas Terbaru (5 Teratas)
        // Filter tugas sesuai jabatan agar relevan
        $tugasTerbaru = PengajuanLayanan::with(['user', 'layanan'])
            ->when($user->position == 'tu', function($q) { $q->where('status', 'pending'); })
            ->when($user->position == 'koordinator', function($q) { $q->where('status', 'menunggu_koordinator'); })
            ->when($user->position == 'verifikator', function($q) { $q->where('status', 'menunggu_verifikator'); })
            ->orderBy('created_at', 'asc') // Yang lama dulu (FIFO)
            ->take(5)
            ->get();

        return view('operator.dashboard', compact(
            'totalMasuk', 'totalPending', 'totalSelesai', 'totalDitolak',
            'dataBulanan', 'bulanLabels', 'tugasTerbaru'
        ));
    }
}