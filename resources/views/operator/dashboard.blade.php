@extends('operator.layouts.app')

@section('title', 'Dashboard Operator')

@section('content')
    {{-- CUSTOM FONT & STYLE --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        
        /* Cards Hover */
        .hover-card { transition: all 0.3s ease; border: 1px solid #F1F5F9; }
        .hover-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); border-color: #E2E8F0; }
        
        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #059669 0%, #0284C7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>

    <div class="min-h-screen p-4 md:p-6">

        {{-- 1. HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 gap-4">
            <div>
                @php
                    $hour = date('H');
                    $greeting = $hour < 12 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
                @endphp
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $greeting }}, <span class="text-gradient">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-slate-500 mt-1 text-sm font-medium">
                    Posisi: <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-bold uppercase text-xs">{{ Auth::user()->position }}</span>
                </p>
            </div>
            <div class="flex items-center gap-3 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wide">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        {{-- 2. STATS CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm hover-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Masuk</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalMasuk }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-inbox text-lg"></i>
                    </div>
                </div>
                <div class="text-xs font-medium text-indigo-600 bg-indigo-50 inline-block px-2 py-1 rounded">Semua Data</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm hover-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Diproses</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalPending }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                </div>
                <div class="text-xs font-medium text-amber-600 bg-amber-50 inline-block px-2 py-1 rounded">On Progress</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm hover-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Selesai</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalSelesai }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-lg"></i>
                    </div>
                </div>
                <div class="text-xs font-medium text-emerald-600 bg-emerald-50 inline-block px-2 py-1 rounded">Finalized</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm hover-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ditolak/Revisi</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalDitolak }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-lg"></i>
                    </div>
                </div>
                <div class="text-xs font-medium text-rose-600 bg-rose-50 inline-block px-2 py-1 rounded">Attention</div>
            </div>

        </div>

        {{-- 3. CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KIRI: CHART (2/3) --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-chart-area text-emerald-500"></i> Tren Pengajuan
                    </h3>
                </div>
                <div id="chartAktivitas" class="w-full h-[300px]"></div>
            </div>

            {{-- KANAN: PRIORITAS TUGAS (1/3) --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-tasks text-amber-500"></i> Tugas Prioritas
                </h3>
                
                <div class="flex-1 overflow-y-auto custom-scroll pr-2 space-y-3 max-h-[300px]">
                    @forelse($tugasTerbaru as $item)
                        <div class="p-3 border border-slate-100 rounded-xl hover:bg-slate-50 transition group cursor-pointer">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded">
                                    #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-[10px] text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors mb-1">
                                {{ $item->user->name ?? 'Guest' }}
                            </h4>
                            <p class="text-xs text-slate-500 line-clamp-1">{{ $item->layanan->nama }}</p>
                            
                            <div class="mt-3 flex justify-end">
                                <a href="{{ route('operator.pengajuan.index') }}" class="text-xs font-bold text-emerald-600 flex items-center gap-1 hover:gap-2 transition-all">
                                    Proses Sekarang <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-40 text-slate-400 text-center">
                            <i class="fas fa-check-double text-3xl mb-2 opacity-50"></i>
                            <p class="text-xs">Tidak ada tugas tertunda.</p>
                            <p class="text-xs">Kerja bagus!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dataBulanan = @json($dataBulanan);
            const labelsBulanan = @json($bulanLabels);

            const options = {
                series: [{ name: "Dokumen Masuk", data: dataBulanan }],
                chart: { type: "area", height: 300, toolbar: { show: false }, fontFamily: "Plus Jakarta Sans, sans-serif" },
                colors: ["#10B981"], // Emerald Green
                fill: { type: "gradient", gradient: { shadeIntensity: 1, opacityFrom: 0.6, opacityTo: 0.05, stops: [0, 90, 100] } },
                stroke: { curve: "smooth", width: 3 },
                dataLabels: { enabled: false },
                xaxis: { categories: labelsBulanan, axisBorder: {show:false}, axisTicks: {show:false}, labels: {style:{colors:"#94a3b8"}} },
                yaxis: { show: false },
                grid: { borderColor: "#f1f5f9", strokeDashArray: 4 },
                tooltip: { theme: "light" }
            };

            const chart = new ApexCharts(document.querySelector("#chartAktivitas"), options);
            chart.render();
        });
    </script>
@endpush