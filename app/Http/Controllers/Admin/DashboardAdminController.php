<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Layanan;
use App\Models\PengajuanLayanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Import Cache

class DashboardAdminController extends Controller
{
    public function index()
    {
        // ==========================================
        // TEKNIK CACHING (Cache selama 10 Menit)
        // ==========================================
        // Data ini hanya akan query ke DB jika cache kosong atau kadaluarsa.
        
        $stats = Cache::remember('dashboard_stats_v1', 600, function () {
            return [
                'totalUser'      => User::count('id'),
                'totalLayanan'   => Layanan::count('id'),
                'totalPengajuan' => PengajuanLayanan::count('id'),
                'totalAdmin'     => User::where('role', 'admin')->count('id'),
            ];
        });

        // Cache Grafik Bulanan (Berat jika data ribuan)
        $grafikBulanan = Cache::remember('dashboard_chart_month_v1', 600, function () {
            $tahunIni = date('Y');
            $aktivitas = PengajuanLayanan::selectRaw('MONTH(created_at) as bulan, COUNT(id) as total')
                ->whereYear('created_at', $tahunIni)
                ->groupBy('bulan')
                ->pluck('total', 'bulan')
                ->toArray();

            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $data[] = $aktivitas[$i] ?? 0;
            }
            return $data;
        });

        // Cache Grafik Status
        $grafikStatus = Cache::remember('dashboard_chart_status_v1', 600, function () {
            return PengajuanLayanan::select('status', DB::raw('count(id) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        });

        // Data Terbaru (Jangan di-cache terlalu lama, atau tidak usah di-cache agar realtime)
        // Kita limit kolom yang diambil (Select Specific Columns)
        $aktivitasTerbaru = PengajuanLayanan::with([
                'user:id,name,email', // Eager load spesifik kolom
                'layanan:id,nama'
            ])
            ->select('id', 'user_id', 'layanan_id', 'status', 'created_at')
            ->latest()
            ->take(5)
            ->get();

        $bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        return view('admin.dashboard', array_merge($stats, [
            'dataBulanan'      => $grafikBulanan,
            'bulanLabels'      => $bulanLabels,
            'statusCount'      => $grafikStatus,
            'aktivitasTerbaru' => $aktivitasTerbaru
        ]));
    }
}