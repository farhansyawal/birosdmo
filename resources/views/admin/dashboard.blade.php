@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="bg-gray-50/60 min-h-screen p-6">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 flex items-center">
                <i class="fa-solid fa-gauge-high text-blue-600"></i> Dashboard Admin
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Selamat datang kembali,
                <span class="font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
            </p>
        </div>
    </div>

    {{-- STATISTIC CARDS (Compact Style) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Total Pengguna</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalUser }}</h3>
                </div>
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Total Pengajuan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalPengajuan }}</h3>
                </div>
                <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                    <i class="fa-solid fa-file-lines text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Layanan Aktif</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalLayanan }}</h3>
                </div>
                <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                    <i class="fa-solid fa-layer-group text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Admin Aktif</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalAdmin }}</h3>
                </div>
                <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                    <i class="fa-solid fa-user-shield text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-blue-500"></i> Aktivitas Pengajuan Bulanan
            </h2>
            <div id="chartAktivitas" class="h-[280px]"></div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-green-500"></i> Distribusi Status Pengajuan
            </h2>
            <div id="chartDistribusi" class="h-[280px]"></div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="mt-8 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i> Aktivitas Terbaru
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <t  ad class="bg-gray-100 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Pengguna</th>
                        <th class="px-4 py-3">Layanan</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aktivitasTerbaru as $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->layanan->nama ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                @if($item->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($item->status == 'diterima') bg-green-100 text-green-700
                                @elseif($item->status == 'ditolak') bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada aktivitas terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {

        // === Area Chart (Aktivitas Bulanan) ===
        new ApexCharts(document.querySelector("#chartAktivitas"), {
            chart: {
                type: "area",
                height: 280,
                toolbar: { show: false },
                fontFamily: "Inter, sans-serif"
            },
            series: [{
                name: "Pengajuan",
                data: @json($dataBulanan)
            }],
            xaxis: {
                categories: @json($bulanLabels),
                labels: { style: { colors: "#94a3b8" } }
            },
            yaxis: { labels: { style: { colors: "#94a3b8" } } },
            colors: ["#3b82f6"],
            fill: {
                gradient: { shadeIntensity: 0.4, opacityFrom: 0.5, opacityTo: 0.15 }
            },
            stroke: { curve: "smooth", width: 2.5 },
            grid: { borderColor: "#f1f5f9" },
            tooltip: { theme: "light" }
        }).render();

        // === Donut Chart (Distribusi Status Pengajuan) ===
        const statusCount = @json($statusCount);

        // Urutan status yang diinginkan
        const statusOrder = ["pending", "ditolak", "ms", "tms", "diterima"];

        // Warna untuk masing-masing status (urutan sesuai atas)
        const statusColors = ["#facc15", "#ef4444", "#9ca3af", "#fb923c", "#22c55e"];

        // Data series & label disusun ulang sesuai urutan di atas
        const seriesData = statusOrder.map(s => statusCount[s] || 0);
        const labelData = statusOrder.map(s => s.charAt(0).toUpperCase() + s.slice(1));

        new ApexCharts(document.querySelector("#chartDistribusi"), {
            chart: {
                type: "donut",
                height: 280,
                fontFamily: "Inter, sans-serif"
            },
            series: seriesData,
            labels: labelData,
            colors: statusColors,
            legend: {
                position: "bottom",
                labels: { colors: "#475569" }
            },
            dataLabels: {
                enabled: true,
                style: { colors: ["#fff"] }
            }
        }).render();
    });
</script>
@endsection