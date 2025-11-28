@extends('operator.layouts.app')
@section('title', 'Daftar Pengajuan')

@section('content')
    {{-- 1. FONTS & STYLES --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }

        /* Floating Rows Table Style */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            /* Jarak antar baris */
            width: 100%;
        }

        .modern-table thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94A3B8;
            padding: 0 1.5rem;
            text-align: left;
        }

        .modern-table tbody tr {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modern-table tbody tr td {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #F1F5F9;
            border-bottom: 1px solid #F1F5F9;
        }

        /* Rounded corners for the row */
        .modern-table tbody tr td:first-child {
            border-left: 1px solid #F1F5F9;
            border-top-left-radius: 1rem;
            border-bottom-left-radius: 1rem;
        }

        .modern-table tbody tr td:last-child {
            border-right: 1px solid #F1F5F9;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }

        /* Hover Effect */
        .modern-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.05);
            border-color: #6366F1;
        }

        /* Status Dot Styles */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
        }

        /* Modal Transitions */
        .modal-backdrop {
            backdrop-filter: blur(4px);
            background-color: rgba(15, 23, 42, 0.4);
        }

        .modal-panel {
            transition: all 0.3s ease-out;
        }

        /* Custom Scroll */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }
    </style>

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">

        {{-- 2. HEADER & STATS OVERVIEW --}}
        <div class="mb-10">
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-6">
                {{-- Kiri: Judul --}}
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Pengajuan</h1>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Kelola verifikasi dan validasi berkas layanan.</p>
                </div>

                {{-- Kanan: Action --}}
                <div>
                    <a href="{{ route('operator.pengajuan.export') }}"
                        class="group inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-emerald-200 transition-all active:scale-95">
                        <i class="fas fa-file-excel"></i> Export Data
                    </a>
                </div>
            </div>

            {{-- Stats Cards (Ringkasan Cepat) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Masuk</span>
                    <span class="text-2xl font-extrabold text-slate-800 mt-1">{{ $pengajuans->total() }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col">
                    <span class="text-xs font-bold text-amber-500 uppercase tracking-wider">Pending</span>
                    <span
                        class="text-2xl font-extrabold text-slate-800 mt-1">{{ $pengajuans->where('status', 'pending')->count() }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col">
                    <span class="text-xs font-bold text-blue-500 uppercase tracking-wider">Verifikasi</span>
                    <span
                        class="text-2xl font-extrabold text-slate-800 mt-1">{{ $pengajuans->where('status', 'Sudah Diverifikasi')->count() }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Selesai</span>
                    <span
                        class="text-2xl font-extrabold text-slate-800 mt-1">{{ $pengajuans->where('status', 'diterima')->count() }}</span>
                </div>
            </div>
        </div>

        {{-- 3. MODERN FLOATING LIST --}}
        <div class="overflow-x-auto custom-scroll pb-10">
            <table class="modern-table" id="flowbiteTable">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">Identitas Pengusul</th>
                        <th width="25%">Layanan</th>
                        <th width="15%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Verifikasi</th>
                        <th width="10%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuans as $item)
                        @php
                            // Logic Status Warna
                            $stClass = 'bg-slate-100 text-slate-500';
                            $dotColor = 'bg-slate-400';
                            if (in_array($item->status, ['pending'])) {
                                $stClass = 'bg-amber-50 text-amber-600';
                                $dotColor = 'bg-amber-500';
                            } elseif (in_array($item->status, ['diterima', 'Sudah Diverifikasi'])) {
                                $stClass = 'bg-emerald-50 text-emerald-600';
                                $dotColor = 'bg-emerald-500';
                            } elseif ($item->status == 'ditolak') {
                                $stClass = 'bg-rose-50 text-rose-600';
                                $dotColor = 'bg-rose-500';
                            }

                            // Logic Verifikasi
                            $verifikasi = $item->verifikasi ? json_decode($item->verifikasi, true) : null;
                            $lengkapSemua = $verifikasi ? !in_array(false, $verifikasi) : false;
                            $adaVerifikasi = $verifikasi !== null;
                        @endphp

                        <tr class="group">
                            {{-- 1. No --}}
                            <td class="text-slate-400 font-bold text-xs text-center">
                                {{ $loop->iteration + $pengajuans->firstItem() - 1 }}
                            </td>

                            {{-- 2. Identitas --}}
                            <td>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-100 shadow-sm">
                                        {{ substr($item->user->name ?? 'U', 0, 2) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $item->user->name ?? '-' }}</span>
                                        <span
                                            class="text-xs text-slate-500 font-mono mt-0.5">{{ $item->user->nip ?? 'NIP Tidak Ada' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Layanan --}}
                            <td>
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-semibold text-slate-700">{{ $item->layanan->nama ?? '-' }}</span>
                                    <div class="flex items-center gap-2 text-xs text-slate-400">
                                        <i class="far fa-calendar-alt"></i> {{ $item->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </td>

                            {{-- 4. Status Utama --}}
                            <td class="text-center">
                                <span class="status-badge {{ $stClass }}">
                                    <span class="dot {{ $dotColor }}"></span> {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            {{-- 5. Status Verifikasi --}}
                            <td class="text-center">
                                @if ($adaVerifikasi && $lengkapSemua)
                                    <span class="text-xs font-bold text-emerald-600 flex items-center justify-center gap-1"><i
                                            class="fas fa-check-double"></i> Lengkap</span>
                                @elseif ($adaVerifikasi && !$lengkapSemua)
                                    <span class="text-xs font-bold text-amber-600 flex items-center justify-center gap-1"><i
                                            class="fas fa-exclamation-circle"></i> Belum</span>
                                @else
                                    <span class="text-xs font-bold text-slate-400 flex items-center justify-center gap-1"><i
                                            class="fas fa-minus"></i> Pending</span>
                                @endif
                            </td>

                            {{-- 6. Aksi --}}
                            <td class="text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button onclick="openDetail({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all shadow-sm flex items-center justify-center"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openVerifikasi({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition-all shadow-sm flex items-center justify-center"
                                        title="Verifikasi Berkas">
                                        <i class="fas fa-check-square"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center bg-transparent shadow-none">
                                <div class="flex flex-col items-center justify-center opacity-50">
                                    <div class="w-20 h-20 bg-slate-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-4xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-600 font-bold text-lg">Belum ada pengajuan</p>
                                    <p class="text-slate-400 text-sm">Data pengajuan baru akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pengajuans->hasPages())
            <div class="mt-4">
                {{ $pengajuans->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    {{-- 4. MODAL DETAIL (Premium Glass) --}}
    <div id="detailModal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 modal-backdrop transition-opacity opacity-0" id="detailBackdrop" onclick="closeDetail()">
        </div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div id="detailCard"
                    class="modal-panel relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl opacity-0 scale-95 translate-y-4">

                    {{-- Header --}}
                    <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900">Detail Pengajuan</h3>
                            <p class="text-sm text-slate-500 mt-1">ID Transaksi: <span id="modalIdDisplay"
                                    class="font-mono text-indigo-600 font-bold">...</span></p>
                        </div>
                        <button onclick="closeDetail()" class="text-slate-400 hover:text-rose-500 transition-colors p-2">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div id="detailContent" class="p-8 max-h-[70vh] overflow-y-auto custom-scroll"></div>

                    {{-- Footer --}}
                    <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex justify-end">
                        <button onclick="closeDetail()"
                            class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-100 transition shadow-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. MODAL VERIFIKASI --}}
    <div id="verifikasiModal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 modal-backdrop transition-opacity opacity-0" id="verifBackdrop"
            onclick="closeVerifikasi()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div id="verifCard"
                    class="modal-panel relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl opacity-0 scale-95 translate-y-4">

                    <div class="bg-slate-900 px-8 py-6 flex justify-between items-center text-white">
                        <div>
                            <h3 class="text-xl font-bold">Verifikasi Berkas</h3>
                            <p class="text-slate-400 text-xs mt-1">Pastikan semua berkas valid sebelum menyetujui.</p>
                        </div>
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-lg"></i>
                        </div>
                    </div>

                    <div id="verifikasiContent" class="p-8 max-h-[60vh] overflow-y-auto custom-scroll"></div>

                    <div
                        class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-xs font-bold text-slate-500 uppercase">Set Status:</span>
                            <select id="statusSelect"
                                class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2">
                                <option value="pending">Pending</option>
                                <option value="ms">Memenuhi Syarat (MS)</option>
                                <option value="tms">Tidak Memenuhi Syarat (TMS)</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        <div class="flex gap-3 w-full sm:w-auto justify-end">
                            <button onclick="closeVerifikasi()"
                                class="px-5 py-2 bg-white border border-slate-300 text-slate-600 font-bold rounded-lg hover:bg-slate-50 text-sm">Batal</button>
                            <button onclick="saveVerifikasi()"
                                class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-lg shadow-emerald-200 transition active:scale-95 text-sm flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT LOGIC --}}
    <script>
        let currentVerifikasiId = null;
        let currentPengajuanId = null;
        let cachePengajuan = {};

        // Helper Utils
        function renderRow(label, value) {
            return `<div class="flex flex-col sm:flex-row sm:justify-between py-3 border-b border-slate-100 last:border-0"><span class="text-xs font-bold uppercase text-slate-400 tracking-wider">${label}</span><span class="text-sm font-semibold text-slate-800 text-right break-words max-w-md mt-1 sm:mt-0">${value ?? '-'}</span></div>`;
        }
        function toLabel(name) { return name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()); }
        function formatUpload(name, value) {
            if (!value) return `<span class="text-slate-300 italic text-xs">Kosong</span>`;
            const val = value.toString().trim();
            const btnClass = "inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold hover:bg-indigo-100 transition-colors";
            if (val.includes('drive.google.com')) return `<a href="${val}" target="_blank" class="${btnClass}"><i class="fab fa-google-drive"></i> Drive</a>`;
            if (name.match(/upload|file/i) || val.match(/\.(pdf|jpg|png)$/i)) return `<a href="/storage/${val}" target="_blank" class="${btnClass}"><i class="fas fa-file-download"></i> File</a>`;
            return `<span class="font-medium text-slate-700">${val}</span>`;
        }

        // Modal Animations
        function toggleModal(modalId, cardId, backdropId, show) {
            const modal = document.getElementById(modalId);
            const card = document.getElementById(cardId);
            const backdrop = document.getElementById(backdropId);

            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    card.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
                    card.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                }, 10);
            } else {
                backdrop.classList.add('opacity-0');
                card.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                card.classList.add('opacity-0', 'scale-95', 'translate-y-4');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        // --- DETAIL ---
        async function openDetail(id) {
            currentPengajuanId = id;
            document.getElementById('modalIdDisplay').innerText = '#' + String(id).padStart(5, '0');
            toggleModal('detailModal', 'detailCard', 'detailBackdrop', true);

            const content = document.getElementById('detailContent');
            content.innerHTML = `<div class="flex justify-center py-12"><i class="fas fa-circle-notch fa-spin text-3xl text-indigo-500"></i></div>`;

            // Reset Cache
            delete cachePengajuan[id];

            try {
                const res = await fetch(`/operator/api/pengajuan/${id}?_=${Date.now()}`);
                if (!res.ok) throw new Error('Gagal');
                const data = await res.json();

                const info = data.data_umum || {};
                const layanan = data.data_layanan || {};

                content.innerHTML = `
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2"><i class="fas fa-info-circle mr-1"></i> Info Umum</h4>
                                ${Object.entries(info).map(([k, v]) => renderRow(k, v)).join('')}
                            </div>
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2"><i class="fas fa-folder-open mr-1"></i> Data Layanan</h4>
                                ${Object.entries(layanan).map(([k, v]) => renderRow(toLabel(k), formatUpload(k, v))).join('')}
                            </div>
                        </div>`;
            } catch (err) {
                content.innerHTML = `<p class="text-center text-rose-500 font-bold py-10">Gagal memuat data.</p>`;
            }
        }

        function closeDetail() { toggleModal('detailModal', 'detailCard', 'detailBackdrop', false); }

        // --- VERIFIKASI ---
        async function openVerifikasi(id) {
            currentVerifikasiId = id;
            toggleModal('verifikasiModal', 'verifCard', 'verifBackdrop', true);
            const content = document.getElementById('verifikasiContent');
            content.innerHTML = `<div class="flex justify-center py-12"><i class="fas fa-circle-notch fa-spin text-3xl text-emerald-500"></i></div>`;

            try {
                const [dataRes, verifyRes] = await Promise.all([
                    fetch(`/operator/api/pengajuan/${id}?_=${Date.now()}`).then(r => r.json()),
                    fetch(`/operator/api/pengajuan/${id}/verifikasi`).then(r => r.json())
                ]);

                const saved = verifyRes.hasil || {};
                const dataLayanan = dataRes.data_layanan || {};
                document.getElementById('statusSelect').value = dataRes.status;

                if (Object.keys(dataLayanan).length === 0) {
                    content.innerHTML = `<div class="text-center text-slate-400 py-8 font-medium">Tidak ada data untuk diverifikasi.</div>`;
                    return;
                }

                content.innerHTML = `
                        <div class="space-y-3">
                            ${Object.entries(dataLayanan).map(([key, value]) => {
                    const checked = saved[key] === 'Lengkap' ? 'checked' : '';
                    return `
                                <label class="flex items-center justify-between bg-slate-50 border border-slate-200 p-4 rounded-xl cursor-pointer hover:border-emerald-400 transition-colors group">
                                    <div class="flex-1 mr-4">
                                        <p class="text-sm font-bold text-slate-800 mb-1">${toLabel(key)}</p>
                                        <div class="text-xs">${formatUpload(key, value)}</div>
                                    </div>
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="chk_${key}" class="w-6 h-6 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500 cursor-pointer" ${checked}>
                                    </div>
                                </label>`;
                }).join('')}
                        </div>`;

            } catch (err) {
                content.innerHTML = `<p class="text-center text-rose-500 font-bold py-10">Gagal memuat data.</p>`;
            }
        }

        function closeVerifikasi() { toggleModal('verifikasiModal', 'verifCard', 'verifBackdrop', false); }

        async function saveVerifikasi() {
            const checkboxes = document.querySelectorAll('#verifikasiContent input[type="checkbox"]');
            const hasil = {};
            checkboxes.forEach(chk => {
                const name = chk.id.replace('chk_', '');
                hasil[name] = chk.checked ? 'Lengkap' : 'Belum Lengkap';
            });
            const status = document.getElementById('statusSelect').value;

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]').content;

                // Simpan Checklist
                await fetch(`/operator/api/pengajuan/${currentVerifikasiId}/verifikasi`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ hasil })
                });

                // Simpan Status
                const res = await fetch(`/operator/api/pengajuan/${currentVerifikasiId}/status`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ status })
                });

                const result = await res.json();
                if (res.ok && result.success) {
                    showToast('success', 'Data berhasil disimpan!');
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast('error', 'Gagal menyimpan.');
                }
            } catch (err) {
                showToast('error', 'Terjadi kesalahan.');
            }
        }
    </script>
@endsection