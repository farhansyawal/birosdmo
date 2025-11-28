<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Layanan;
use App\Models\PengajuanLayanan;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Ambil total data
        $totalUser = User::count();
        $totalLayanan = Layanan::count();
        $totalPengajuan = PengajuanLayanan::count();
        $totalAdmin = User::where('role', 'admin')->count();

        // Ambil data pengajuan per bulan (untuk grafik)
        $aktivitas = PengajuanLayanan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Pastikan semua bulan ada
        $bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $dataBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulanan[] = $aktivitas[$i] ?? 0;
        }

        // Data distribusi status
        $statusCount = PengajuanLayanan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Ambil aktivitas terbaru
        $aktivitasTerbaru = PengajuanLayanan::with(['user', 'layanan'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUser',
            'totalLayanan',
            'totalPengajuan',
            'totalAdmin',
            'dataBulanan',
            'bulanLabels',
            'statusCount',
            'aktivitasTerbaru'
        ));
    }
}
