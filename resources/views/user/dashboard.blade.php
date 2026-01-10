@extends('user.layouts.app')
@section('title', 'Dashboard')

@section('content')
    {{-- Fonts & Libraries --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        
        /* Interactive Cards */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; overflow: hidden;
            background: #fff; border: 1px solid #F1F5F9;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #CBD5E1;
        }
        
        /* Soft Gradients for Icons */
        .bg-gradient-primary { background: linear-gradient(135deg, #6366F1 0%, #818CF8 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #10B981 0%, #34D399 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #EF4444 0%, #F87171 100%); }

        /* Welcome Animation */
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        
        {{-- 1. HEADER SECTION --}}
        <div class="flex flex-col md:flex-row justify-between items-start mb-10 gap-6 fade-in-up">
            <div>
                <div class="flex items-center gap-2 mb-1 text-sm font-bold text-indigo-600 uppercase tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $user->unit_kerja ?? 'Unit Kerja Tidak Diketahui' }}
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-500">{{ explode(' ', $user->name)[0] }}</span>
                </h1>
                <p class="text-slate-500 mt-2 font-medium">Pantau status pengajuan layanan Anda secara real-time di sini.</p>
            </div>
            
            <a href="{{ route('user.pages.pengajuan') }}" class="group relative px-6 py-3 rounded-xl bg-slate-900 text-white font-bold shadow-lg hover:bg-indigo-600 transition-all active:scale-95 flex items-center gap-3 overflow-hidden">
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                <i class="fas fa-plus relative z-10"></i> 
                <span class="relative z-10">Buat Pengajuan Baru</span>
            </a>
        </div>

        {{-- 2. STATS GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 fade-in-up" style="animation-delay: 0.1s;">
            {{-- Card Total --}}
            <div class="stat-card rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Pengajuan</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-primary flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fas fa-folder-open text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 text-xs font-bold text-slate-400">
                    Semua Riwayat
                </div>
            </div>

            {{-- Card Proses --}}
            <div class="stat-card rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sedang Proses</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $stats['proses'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-warning flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <i class="fas fa-sync-alt fa-spin text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 text-xs font-bold text-amber-600">
                    Menunggu Verifikasi
                </div>
            </div>

            {{-- Card Selesai --}}
            <div class="stat-card rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Diterima</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $stats['selesai'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-success flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 text-xs font-bold text-emerald-600">
                    Selesai Diproses
                </div>
            </div>

            {{-- Card Revisi --}}
            <div class="stat-card rounded-2xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Perlu Tindakan</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $stats['revisi'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-danger flex items-center justify-center text-white shadow-lg shadow-rose-200">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 text-xs font-bold text-rose-600">
                    Revisi / Ditolak
                </div>
            </div>
        </div>

        {{-- 3. MAIN CONTENT (Split 2/3 and 1/3) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 fade-in-up" style="animation-delay: 0.2s;">
            
            {{-- LEFT: TABEL AKTIVITAS (LEBIH LUAS 2/3) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden h-full">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-history text-slate-400"></i> Pengajuan Terakhir
                        </h4>
                        <a href="{{ route('user.pages.riwayat-pengajuan') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition">Lihat Semua &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                                <tr>
                                    <th class="px-6 py-4 border-b border-slate-100">Layanan</th>
                                    <th class="px-6 py-4 border-b border-slate-100 text-center">Status</th>
                                    <th class="px-6 py-4 border-b border-slate-100 text-right">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($recentActivities as $item)
                                    <tr class="hover:bg-slate-50 transition group cursor-pointer" onclick="window.location='{{ route('user.pages.riwayat-pengajuan') }}'">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ substr($item->layanan->nama ?? 'L', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition line-clamp-1">{{ $item->layanan->nama ?? 'Layanan Dihapus' }}</p>
                                                    <p class="text-xs text-slate-400 font-mono">{{ $item->nomor_surat ?? 'Draft' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $badges = [
                                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    'diterima' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                    'perlu_revisi' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                    'menunggu_koordinator' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'menunggu_verifikator' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                ];
                                                $badgeClass = $badges[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badgeClass }}">
                                                {{ str_replace('_', ' ', $item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="text-xs font-bold text-slate-600">{{ $item->created_at->format('d M Y') }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center opacity-50">
                                                <i class="far fa-folder-open text-3xl text-slate-300 mb-2"></i>
                                                <p class="text-sm text-slate-500">Belum ada riwayat pengajuan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RIGHT: STATUS & QUICK LINKS (1/3) --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- 1. Grafik Status --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                        <i class="fas fa-chart-pie text-indigo-500"></i> Statistik Status
                    </h4>
                    {{-- Container Chart --}}
                    <div id="chartStatus" class="w-full flex justify-center py-2"></div>
                </div>

                {{-- 2. Menu Pintasan (Pelengkap) --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                        Aksi Cepat
                    </h4>
                    <div class="space-y-3">
                        <a href="{{ route('user.pages.riwayat-pengajuan') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-white border border-transparent hover:border-indigo-100 hover:shadow-sm transition group">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 group-hover:text-indigo-600 transition">
                                <i class="fas fa-list"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-indigo-700">Lihat Semua Riwayat</span>
                            <i class="fas fa-chevron-right ml-auto text-xs text-slate-300 group-hover:text-indigo-400"></i>
                        </a>

                        <a href="#" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-white border border-transparent hover:border-indigo-100 hover:shadow-sm transition group">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 group-hover:text-indigo-600 transition">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-indigo-700">Pengaturan Profil</span>
                            <i class="fas fa-chevron-right ml-auto text-xs text-slate-300 group-hover:text-indigo-400"></i>
                        </a>

                        <a href="https://wa.me/628123456789" target="_blank" class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50/50 hover:bg-emerald-50 border border-transparent hover:border-emerald-200 hover:shadow-sm transition group">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-emerald-500">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-emerald-700">Hubungi Helpdesk</span>
                            <i class="fas fa-external-link-alt ml-auto text-xs text-emerald-300 group-hover:text-emerald-500"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- CHART SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data untuk chart
            const series = [{{ $stats['proses'] }}, {{ $stats['selesai'] }}, {{ $stats['revisi'] }}];
            const labels = ['Proses', 'Selesai', 'Revisi'];
            
            // Cek jika data kosong semua
            const totalData = series.reduce((a, b) => a + b, 0);

            var options = {
                series: totalData > 0 ? series : [1], // Placeholder jika kosong
                labels: totalData > 0 ? labels : ['Belum ada data'],
                chart: { type: 'donut', height: 250, fontFamily: 'Plus Jakarta Sans, sans-serif' },
                colors: totalData > 0 ? ['#F59E0B', '#10B981', '#EF4444'] : ['#E2E8F0'],
                plotOptions: { 
                    pie: { 
                        donut: { 
                            size: '70%', 
                            labels: { 
                                show: true, 
                                name: { fontSize: '10px', color: '#64748B' },
                                value: { fontSize: '18px', fontWeight: 'bold', color: '#1E293B', offsetY: 0 },
                                total: { 
                                    show: true, 
                                    label: 'Total', 
                                    fontSize: '10px', 
                                    color: '#64748B',
                                    formatter: () => totalData 
                                } 
                            } 
                        } 
                    } 
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                legend: { position: 'bottom', fontSize: '11px', markers: { radius: 12 } },
                tooltip: { enabled: totalData > 0 }
            };

            var chart = new ApexCharts(document.querySelector("#chartStatus"), options);
            chart.render();
        });
    </script>
@endsection