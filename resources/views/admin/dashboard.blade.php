@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    @push('head')
        {{-- 1. PRECONNECT FONTS & LOAD ICONS --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        {{-- Tambahkan FontAwesome agar ikon muncul --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
            
            .hover-card { transition: all 0.3s ease; }
            .hover-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
            
            .text-gradient { 
                background: linear-gradient(135deg, #4F46E5 0%, #06B6D4 100%); 
                -webkit-background-clip: text; 
                -webkit-text-fill-color: transparent; 
            }
            
            /* Skeleton Animation */
            .skeleton {
                background: #e2e8f0;
                background: linear-gradient(110deg, #e2e8f0 8%, #f1f5f9 18%, #e2e8f0 33%);
                background-size: 200% 100%;
                animation: 1.5s shine linear infinite;
            }
            @keyframes shine { to { background-position-x: -200%; } }
        </style>
    @endpush

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">

        {{-- 2. HEADER SECTION --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
            <div>
                @php
                    $h = date('H');
                    $greet = $h < 12 ? 'Selamat Pagi' : ($h < 15 ? 'Selamat Siang' : ($h < 18 ? 'Selamat Sore' : 'Selamat Malam'));
                @endphp
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $greet }}, <span class="text-gradient">{{ Auth::user()->name ?? 'Admin' }}</span>
                </h1>
                <p class="text-slate-500 mt-2 text-sm font-medium">Berikut adalah ringkasan aktivitas sistem hari ini.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-white border border-slate-200 px-5 py-2.5 rounded-xl shadow-sm">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-sm font-bold text-slate-700 uppercase tracking-wide">
                    {{ date('d F Y') }}
                </span>
            </div>
        </div>

        {{-- 3. STATISTIC CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card Total Pengguna --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pengguna</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalUser) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            {{-- Card Pengajuan --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pengajuan Masuk</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalPengajuan) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                </div>
            </div>

            {{-- Card Layanan --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Layanan Aktif</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalLayanan) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
            </div>

            {{-- Card Admin --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Administrator</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalAdmin) }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Tren Pengajuan <span class="text-xs font-normal text-slate-500 ml-2">({{ date('Y') }})</span></h2>
                
                {{-- Container Chart --}}
                <div id="chartAktivitas" class="w-full h-[320px] relative">
                    {{-- Skeleton akan dihapus oleh JS --}}
                    <div class="skeleton w-full h-full rounded-xl absolute inset-0 z-10"></div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Status Dokumen</h2>
                
                {{-- Container Chart --}}
                <div id="chartDistribusi" class="w-full h-[320px] relative">
                    <div class="skeleton w-full h-full rounded-xl absolute inset-0 z-10"></div>
                </div>
            </div>
        </div>

        {{-- 5. RECENT ACTIVITY TABLE --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-base">Aktivitas Terbaru</h3>
                <span class="text-xs font-medium text-slate-500">5 Transaksi Terakhir</span>
            </div>
            
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-bold text-slate-400 uppercase bg-white border-b border-slate-100">
                            <th class="px-6 py-4">Pengguna</th>
                            <th class="px-6 py-4">Layanan</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-50">
                        @php
                            $statusMap = [
                                'pending' => ['bg-yellow-100', 'text-yellow-700', 'Pending'],
                                'diterima' => ['bg-emerald-100', 'text-emerald-700', 'Selesai'],
                                'ditolak' => ['bg-rose-100', 'text-rose-700', 'Ditolak'],
                                'menunggu_koordinator' => ['bg-blue-100', 'text-blue-700', 'Proses'],
                                'menunggu_verifikator' => ['bg-indigo-100', 'text-indigo-700', 'Verif'],
                                'perlu_revisi' => ['bg-orange-100', 'text-orange-700', 'Revisi'],
                            ];
                            $defaultStatus = ['bg-slate-100', 'text-slate-600', '-'];
                        @endphp

                        @forelse ($aktivitasTerbaru as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                                            {{ substr($item->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-sm">{{ Str::limit($item->user->name ?? 'Guest', 20) }}</div>
                                            <div class="text-xs text-slate-400">{{ Str::limit($item->user->email ?? '-', 25) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium">{{ Str::limit($item->layanan->nama ?? '-', 35) }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ $item->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php $s = $statusMap[$item->status] ?? $defaultStatus; @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $s[0] }} {{ $s[1] }}">
                                        {{ $s[2] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm italic">
                                    Belum ada data aktivitas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts" defer></script>
    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            const dataBulanan = @json($dataBulanan);
            const labelsBulanan = @json($bulanLabels);
            const statusData = @json($statusCount);

            setTimeout(() => {
                const commonConfig = {
                    fontFamily: "Plus Jakarta Sans, sans-serif",
                    animations: { enabled: false },
                    toolbar: { show: false }
                };

                // FIX: Bersihkan Skeleton dulu sebelum render chart
                const chartAreaEl = document.querySelector("#chartAktivitas");
                chartAreaEl.innerHTML = ""; // Hapus div .skeleton

                new ApexCharts(chartAreaEl, {
                    ...commonConfig,
                    series: [{ name: "Data", data: dataBulanan }],
                    chart: { type: "area", height: 320, ...commonConfig },
                    colors: ["#4F46E5"],
                    fill: { type: "gradient", gradient: { opacityFrom: 0.5, opacityTo: 0.1 } },
                    stroke: { curve: "smooth", width: 2 },
                    dataLabels: { enabled: false },
                    xaxis: { categories: labelsBulanan, labels: { style: { fontSize: '11px', colors: '#64748b' } } },
                    yaxis: { labels: { style: { colors: '#64748b' } } },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                }).render();

                // FIX: Bersihkan Skeleton dulu sebelum render chart
                const chartDonutEl = document.querySelector("#chartDistribusi");
                chartDonutEl.innerHTML = ""; // Hapus div .skeleton

                const sMap = {
                    "pending": "Pending", "diterima": "Selesai", "ditolak": "Ditolak", 
                    "menunggu_koordinator": "Proses", "perlu_revisi": "Revisi"
                };
                const cMap = ["#FBBF24", "#10B981", "#EF4444", "#3B82F6", "#F97316"];
                
                const series = []; const labels = []; const colors = [];
                Object.keys(sMap).forEach((key, index) => {
                    if(statusData[key] > 0) {
                        series.push(statusData[key]);
                        labels.push(sMap[key]);
                        colors.push(cMap[index]);
                    }
                });

                if(series.length === 0) { series.push(1); labels.push('Belum ada data'); colors.push('#cbd5e1'); }

                new ApexCharts(chartDonutEl, {
                    ...commonConfig,
                    series: series,
                    labels: labels,
                    chart: { type: "donut", height: 320, ...commonConfig },
                    colors: colors,
                    legend: { position: 'bottom', fontSize: '13px', markers: {radius: 12} },
                    plotOptions: { pie: { donut: { size: '65%' } } },
                    dataLabels: { enabled: false },
                    stroke: { width: 0 }
                }).render();

            }, 300);
        });
    </script>
@endpush