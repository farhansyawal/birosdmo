@extends('user.layouts.app')
@section('title', 'Riwayat Pengajuan')

@section('content')
    {{-- Font Inter (Standar UI Modern) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        /* Status Badge Styles */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .badge-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
        }

        /* Variants */
        .badge-warning {
            background: #FFFBEB;
            color: #B45309;
            border-color: #FEF3C7;
        }

        .badge-warning .badge-dot {
            background: #F59E0B;
        }

        .badge-success {
            background: #ECFDF5;
            color: #047857;
            border-color: #D1FAE5;
        }

        .badge-success .badge-dot {
            background: #10B981;
        }

        .badge-danger {
            background: #FEF2F2;
            color: #B91C1C;
            border-color: #FEE2E2;
        }

        .badge-danger .badge-dot {
            background: #EF4444;
        }

        .badge-neutral {
            background: #F1F5F9;
            color: #475569;
            border-color: #E2E8F0;
        }

        .badge-neutral .badge-dot {
            background: #94A3B8;
        }

        /* Custom Scroll for Table */
        .custom-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>

    <div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            {{-- 1. HEADER & QUICK STATS --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Pengajuan</h1>
                    <p class="text-slate-500 text-sm mt-1">Kelola dan pantau status dokumen Anda.</p>
                </div>
                <div class="flex gap-3">
                    <div class="bg-white border border-slate-200 px-4 py-2 rounded-lg shadow-sm">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total</span>
                        <div class="text-lg font-bold text-slate-800 leading-none mt-1">{{ $riwayat->total() }}</div>
                    </div>
                    <a href="{{ route('user.pages.pengajuan') }}"
                        class="bg-slate-900 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg font-medium text-sm shadow-lg shadow-slate-200 transition-all flex items-center gap-2 h-full">
                        <i class="fas fa-plus text-xs"></i> Buat Baru
                    </a>
                </div>
            </div>

            {{-- 2. MODERN TABLE CARD --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-100/50 overflow-hidden">

                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50">
                                <th
                                    class="py-4 pl-6 pr-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">
                                    #</th>
                                <th class="py-4 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Informasi Dokumen</th>
                                <th class="py-4 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis
                                    Layanan</th>
                                <th
                                    class="py-4 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                    Status</th>
                                <th
                                    class="py-4 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">
                                    Tanggal</th>
                                <th class="py-4 pl-4 pr-6 text-right w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($riwayat as $item)
                                @php
                                    // Parse Data
                                    $dataLayanan = json_decode($item->data_layanan ?? '{}', true);
                                    $namaPegawai = $dataLayanan['nama_lengkap_pegawai_yang_diusulkan'] ?? $item->user->name ?? '-';

                                    // Status Logic
                                    $statusClass = 'badge-neutral';
                                    if (in_array($item->status, ['pending', 'Belum Lengkap']))
                                        $statusClass = 'badge-warning';
                                    elseif (in_array($item->status, ['diterima', 'Sudah Diverifikasi']))
                                        $statusClass = 'badge-success';
                                    elseif ($item->status == 'ditolak')
                                        $statusClass = 'badge-danger';
                                @endphp

                                <tr class="group hover:bg-slate-50 transition-colors duration-200 cursor-pointer"
                                    onclick="openDetail({{ $item->id }})">

                                    {{-- No --}}
                                    <td class="py-4 pl-6 pr-4 text-slate-400 font-mono text-xs">
                                        {{ $loop->iteration + $riwayat->firstItem() - 1 }}
                                    </td>

                                    {{-- Informasi Dokumen (Nama + No Surat) --}}
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            {{-- Avatar Initials --}}
                                            <div
                                                class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-100">
                                                {{ substr($namaPegawai, 0, 2) }}
                                            </div>
                                            <div>
                                                <p
                                                    class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                                    {{ $namaPegawai }}
                                                </p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span
                                                        class="text-[11px] font-medium text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">
                                                        {{ $item->nomor_surat ?? 'DRAFT' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Layanan --}}
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <i class="fas fa-folder text-slate-300"></i>
                                            <span
                                                class="text-sm font-medium">{{ $item->layanan->nama ?? 'Layanan Dihapus' }}</span>
                                        </div>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="py-4 px-4 text-center">
                                        <span class="badge {{ $statusClass }}">
                                            <span class="badge-dot"></span>
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="py-4 px-4 text-right">
                                        <p class="text-sm font-bold text-slate-700">{{ $item->created_at->format('d M Y') }}</p>
                                        <p class="text-xs text-slate-400">{{ $item->created_at->format('H:i') }} WIB</p>
                                    </td>

                                    {{-- Arrow Action --}}
                                    <td class="py-4 pl-4 pr-6 text-right">
                                        <button class="text-slate-300 hover:text-indigo-600 transition-colors">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div
                                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                            <i class="fas fa-inbox text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada riwayat pengajuan.</p>
                                        <a href="{{ route('user.pages.pengajuan') }}"
                                            class="text-indigo-600 text-sm font-bold hover:underline mt-2 inline-block">Buat
                                            Pengajuan Baru</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($riwayat->hasPages())
                    <div class="bg-white border-t border-slate-200 px-6 py-4">
                        {{ $riwayat->links('pagination::tailwind') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- 3. MODERN DETAIL MODAL --}}
    <div id="detailModal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">

        {{-- Backdrop dengan Blur --}}
        <div id="modalBackdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0"
            onclick="closeDetail()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                {{-- Modal Panel --}}
                <div id="modalPanel"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 border border-slate-100">

                    {{-- A. Modal Header --}}
                    <div
                        class="bg-white px-6 py-5 border-b border-slate-100 flex justify-between items-center sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500">
                                <i class="fas fa-file-lines"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 leading-tight">Detail Pengajuan</h3>
                                <p class="text-xs text-slate-500 mt-0.5 font-medium">ID Transaksi: <span id="modalIdDisplay"
                                        class="font-mono text-indigo-600">Loading...</span></p>
                            </div>
                        </div>
                        <button type="button" onclick="closeDetail()"
                            class="group bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl p-2 transition-all duration-200">
                            <i class="fas fa-times text-lg group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>

                    {{-- B. Modal Body (Tempat Inject HTML dari Controller) --}}
                    <div class="px-8 py-8 max-h-[70vh] overflow-y-auto custom-scroll bg-slate-50/50" id="modalBody">
                        {{-- Default Loading State --}}
                        <div class="flex flex-col items-center justify-center py-12">
                            <div
                                class="w-12 h-12 border-4 border-slate-200 border-t-indigo-600 rounded-full animate-spin mb-4">
                            </div>
                            <p class="text-sm font-bold text-slate-500">Mengambil data...</p>
                        </div>
                    </div>

                    {{-- C. Modal Footer --}}
                    <div class="bg-white px-6 py-4 border-t border-slate-100 flex justify-end">
                        <button type="button" onclick="closeDetail()"
                            class="px-5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-50 transition-all shadow-sm">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script Tambahan untuk Animasi Modal (Update Script yang ada) --}}
    <script>
        const modal = document.getElementById('detailModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const body = document.getElementById('modalBody');
        const idDisplay = document.getElementById('modalIdDisplay');

        function openDetail(id) {
            // 1. Buka Wrapper
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Kunci scroll background

            // 2. Animasi Masuk (Delay dikit biar transisi jalan)
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);

            // 3. Reset Content & ID
            idDisplay.innerText = '#' + String(id).padStart(5, '0');
            body.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-10 h-10 border-4 border-slate-200 border-t-indigo-600 rounded-full animate-spin mb-3"></div>
                    <p class="text-sm font-bold text-slate-500">Memuat data...</p>
                </div>`;

            // 4. Fetch Data
            fetch(`/user/pengajuan/${id}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network error');
                    return res.text();
                })
                .then(html => {
                    body.innerHTML = html;
                })
                .catch(err => {
                    body.innerHTML = `
                        <div class="text-center py-10 bg-rose-50 rounded-2xl border border-rose-100">
                            <i class="fas fa-exclamation-circle text-3xl text-rose-400 mb-2"></i>
                            <p class="text-slate-800 font-bold">Gagal memuat data</p>
                            <p class="text-xs text-slate-500">Silakan coba lagi nanti.</p>
                        </div>`;
                });
        }

        function closeDetail() {
            // 1. Animasi Keluar
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

            // 2. Sembunyikan Wrapper setelah animasi selesai
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Buka kunci scroll
            }, 300);
        }
    </script>
@endsection