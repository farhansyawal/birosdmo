@extends('operator.layouts.app')
@section('title', 'Daftar Pengajuan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Import Font Inter & Plus Jakarta --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* BASE STYLES */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F1F5F9;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* CUSTOM SCROLLBAR */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Table Optimization */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .modern-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 1rem;
            font-weight: 700;
            text-align: left;
        }

        .modern-table td {
            background: white;
            padding: 1.25rem 1rem;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }

        .modern-table tr td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }

        .modern-table tr td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .modern-table tr {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .modern-table tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            z-index: 10;
            position: relative;
        }

        /* BADGES */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: capitalize;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
        }

        /* CHECKBOX CARD (INTERACTIVE) */
        .check-card {
            position: relative;
            transition: all 0.2s ease;
            border: 2px solid #E2E8F0;
            background-color: #fff;
            border-radius: 1rem;
            overflow: hidden;
        }

        .check-card:hover {
            border-color: #94A3B8;
            transform: translateY(-1px);
        }

        .check-card.selected {
            border-color: #10B981;
            background-color: #F0FDF4;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
        }

        .check-card .icon-box {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* MODAL BACKDROP */
        .modal-backdrop {
            background-color: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
        }

        /* Z-INDEX FIXES */
        div:where(.swal2-container) {
            z-index: 999999 !important;
        }
    </style>

    <div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8">

        {{-- HEADER SECTION --}}
        <div class="max-w-7xl mx-auto mb-10">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div>
                    <h1 class="text-3xl font-heading font-extrabold text-slate-900 tracking-tight">Daftar Pengajuan</h1>
                    <div class="flex items-center gap-2 mt-2 text-sm text-slate-500 font-medium">
                        <span>Panel Kontrol:</span>
                        <span
                            class="bg-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wide border border-indigo-200">
                            {{ Auth::user()->position ?? 'Operator' }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('operator.pengajuan.export') }}"
                        class="bg-white border border-slate-200 text-slate-600 hover:text-emerald-600 hover:border-emerald-200 px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm hover:shadow transition flex items-center gap-2">
                        <i class="fas fa-file-excel text-emerald-500"></i> Export Data
                    </a>
                </div>
            </div>
        </div>

        {{-- TABLE CONTENT --}}
        <div class="max-w-7xl mx-auto overflow-hidden">
            <div class="overflow-x-auto pb-4">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Identitas Pengusul</th>
                            <th width="25%">Layanan</th>
                            <th width="15%" class="text-center">Status Dokumen</th>
                            <th width="15%" class="text-center">Validasi</th>
                            <th width="10%" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $item)
                            @php
                                // Logic Styling
                                $stClass = 'bg-slate-100 text-slate-500 border border-slate-200';
                                $dotColor = 'bg-slate-400';

                                if ($item->status == 'pending') {
                                    $stClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                                    $dotColor = 'bg-amber-500';
                                } elseif (in_array($item->status, ['menunggu_koordinator', 'menunggu_verifikator', 'menunggu_kabiro'])) {
                                    $stClass = 'bg-indigo-50 text-indigo-700 border border-indigo-200';
                                    $dotColor = 'bg-indigo-500';
                                } elseif ($item->status == 'diterima') {
                                    $stClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                                    $dotColor = 'bg-emerald-500';
                                } elseif ($item->status == 'ditolak') {
                                    $stClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                                    $dotColor = 'bg-rose-500';
                                } elseif ($item->status == 'perlu_revisi') {
                                    $stClass = 'bg-orange-50 text-orange-700 border border-orange-200';
                                    $dotColor = 'bg-orange-500';
                                }

                                // Logic Permissions
                                $userPos = Auth::user()->position;
                                $canAction = false;
                                if ($userPos == 'tu' && $item->status == 'pending')
                                    $canAction = true;
                                if ($userPos == 'koordinator' && $item->status == 'menunggu_koordinator')
                                    $canAction = true;
                                if ($userPos == 'verifikator' && $item->status == 'menunggu_verifikator')
                                    $canAction = true;
                                if ($userPos == 'kepala_biro' && $item->status == 'menunggu_kabiro')
                                    $canAction = true;

                                // Logic Checklist
                                $verifikasi = $item->verifikasi ? json_decode($item->verifikasi, true) : null;
                                $lengkapSemua = $verifikasi ? !in_array('Belum Lengkap', array_values($verifikasi)) && !in_array(false, array_values($verifikasi)) : false;
                                $adaVerifikasi = $verifikasi !== null;
                            @endphp

                            <tr onclick="openDetail({{ $item->id }})">
                                <td class="text-center font-bold text-slate-400 text-xs">
                                    {{ $loop->iteration + $pengajuans->firstItem() - 1 }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 flex items-center justify-center text-sm font-bold border border-slate-300 shadow-inner">
                                            {{ substr($item->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ $item->user->name ?? '-' }}</div>
                                            <div
                                                class="text-xs text-slate-500 font-mono mt-0.5 bg-slate-100 px-1.5 py-0.5 rounded inline-block">
                                                {{ $item->user->nip ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                        <span
                                            class="text-sm font-semibold text-slate-700 truncate max-w-[200px]">{{ $item->layanan->nama ?? '-' }}</span>
                                    </div>
                                    <div class="text-xs text-slate-400 pl-3.5">{{ $item->created_at->format('d M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge {{ $stClass }}">
                                        <span class="dot {{ $dotColor }}"></span>
                                        {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($adaVerifikasi && $lengkapSemua)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                            <i class="fas fa-check-double"></i> Lengkap
                                        </span>
                                    @elseif ($adaVerifikasi && !$lengkapSemua)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100">
                                            <i class="fas fa-exclamation-triangle"></i> Belum
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs font-medium italic">- Belum dicek -</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="openDetail({{ $item->id }})"
                                            class="group w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 transition shadow-sm flex items-center justify-center">
                                            <i class="fas fa-eye group-hover:scale-110 transition-transform"></i>
                                        </button>

                                        @if($canAction)
                                            <button onclick="openVerifikasi({{ $item->id }})"
                                                class="group w-9 h-9 rounded-xl bg-indigo-600 border border-indigo-600 text-white hover:bg-indigo-700 transition shadow-md hover:shadow-indigo-200 flex items-center justify-center relative overflow-hidden">
                                                <div
                                                    class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform">
                                                </div>
                                                <i class="fas fa-pen-nib relative z-10"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-50">
                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-folder-open text-3xl text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada data pengajuan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengajuans->hasPages())
                <div class="mt-6 px-1">{{ $pengajuans->links('pagination::tailwind') }}</div>
            @endif
        </div>
    </div>

    {{-- 1. MODAL DETAIL (VIEW ONLY) --}}
    <div id="detailModal" class="fixed inset-0 z-[9990] hidden" role="dialog">
        <div class="fixed inset-0 modal-backdrop transition-opacity" onclick="closeDetail()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="modal-panel relative w-full max-w-4xl transform rounded-2xl bg-white text-left shadow-2xl transition-all scale-95 opacity-0 duration-300">

                    <div
                        class="bg-slate-50/80 backdrop-blur px-8 py-5 border-b border-slate-100 flex justify-between items-center rounded-t-2xl sticky top-0 z-20">
                        <div>
                            <h3 class="font-heading font-bold text-xl text-slate-800">Detail Pengajuan</h3>
                            <p class="text-xs text-slate-500 font-mono mt-1">ID: <span id="modalIdDisplay"
                                    class="text-indigo-600 font-bold">#...</span></p>
                        </div>
                        <button onclick="closeDetail()"
                            class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 flex items-center justify-center transition shadow-sm"><i
                                class="fas fa-times"></i></button>
                    </div>

                    <div id="detailContent" class="p-8 max-h-[75vh] overflow-y-auto custom-scroll bg-white"></div>

                    <div class="px-8 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex justify-end">
                        <button onclick="closeDetail()"
                            class="px-6 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-400 transition shadow-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MODAL VERIFIKASI (SPLIT VIEW) --}}
    <div id="verifikasiModal" class="fixed inset-0 z-[9999] hidden" role="dialog">
        <div class="fixed inset-0 modal-backdrop transition-opacity" onclick="closeVerifikasi()"></div>

        <div class="fixed inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
            <div
                class="modal-panel bg-white w-full max-w-[85rem] h-[85vh] rounded-3xl shadow-2xl overflow-hidden flex flex-col border border-slate-200 transform transition-all scale-95 opacity-0">

                {{-- Header --}}
                <div class="bg-white px-8 py-5 border-b border-slate-200 flex justify-between items-center z-20 shrink-0">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                            <i class="fas fa-tasks text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-extrabold text-xl text-slate-900">Proses Verifikasi</h3>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 mt-0.5">
                                <span
                                    class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded uppercase tracking-wide border border-slate-200">
                                    {{ Auth::user()->position }} Panel
                                </span>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeVerifikasi()"
                        class="w-10 h-10 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-800 transition flex items-center justify-center border border-transparent hover:border-slate-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Split Body --}}
                <div class="flex-1 flex overflow-hidden relative">

                    {{-- KIRI: KONTEN UTAMA --}}
                    <div class="flex-1 overflow-y-auto custom-scroll p-8 bg-slate-50/50">
                        <div id="verifikasiContent" class="max-w-4xl mx-auto">
                            Loading...
                        </div>
                    </div>

                    {{-- KANAN: SIDEBAR AKSI --}}
                    <div
                        class="w-[360px] bg-white border-l border-slate-200 flex flex-col shrink-0 z-10 shadow-[-5px_0_20px_-5px_rgba(0,0,0,0.05)]">

                        <div class="p-6 flex-1 overflow-y-auto custom-scroll">
                            <div class="mb-6">
                                <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Keputusan
                                    Akhir</h4>

                                <div class="space-y-5">
                                    <div>
                                        <label class="text-xs font-bold text-slate-700 mb-2 block uppercase">Tindakan
                                            Lanjutan</label>
                                        <div class="relative">
                                            <select id="statusSelect" onchange="toggleCatatan()"
                                                class="w-full ... (class lama) ...">
                                                <option value="">-- Pilih Keputusan --</option>

                                                @if(Auth::user()->position == 'tu')
                                                    {{-- TU Mengirim ke Koordinator --}}
                                                    <option value="menunggu_koordinator">
                                                        Disposisi ke Koordinator ({{ $koordinator->name ?? 'Tanpa Nama' }})
                                                    </option>

                                                    {{-- TU Mengirim ke Kabiro --}}
                                                    <option value="menunggu_kabiro" class="text-purple-600">
                                                        Disposisi ke Kepala Biro ({{ $kabiro->name ?? 'Tanpa Nama' }})
                                                    </option>

                                                    <option value="ditolak" class="text-red-600">Tolak Final</option>
                                                @endif

                                                @if(Auth::user()->position == 'koordinator')
                                                    {{-- Koordinator mengirim ke Verifikator --}}
                                                    <option value="menunggu_verifikator">
                                                        Teruskan ke Verifikator ({{ $verifikator->name ?? 'Tanpa Nama' }})
                                                    </option>

                                                    <option value="ditolak" class="text-red-600">Tolak Final</option>
                                                @endif

                                                @if(Auth::user()->position == 'kepala_biro')
                                                    {{-- Kabiro mengirim ke Verifikator --}}
                                                    <option value="menunggu_verifikator" class="text-blue-600">
                                                        Kirim ke Verifikator ({{ $verifikator->name ?? 'Tanpa Nama' }})
                                                    </option>

                                                    <option value="ditolak" class="text-red-600">Tolak Final</option>
                                                @endif

                                                @if(Auth::user()->position == 'verifikator')
                                                    <option value="diterima" class="text-emerald-600">SETUJUI</option>
                                                    <option value="perlu_revisi" class="text-orange-600">Minta Revisi User
                                                    </option>
                                                    <option value="ditolak" class="text-red-600">Tolak Final</option>
                                                @endif
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                                <i class="fas fa-chevron-down text-xs"></i></div>
                                        </div>
                                    </div>

                                    <div id="catatanArea" class="hidden transition-all duration-300">
                                        <label class="text-xs font-bold text-slate-700 mb-2 block uppercase">Catatan /
                                            Alasan <span class="text-red-500">*</span></label>
                                        <textarea id="adminCatatan" rows="6"
                                            class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl p-4 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm placeholder:text-slate-400 resize-none"
                                            placeholder="Tulis alasan revisi atau penolakan secara jelas disini..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                            <button onclick="saveVerifikasi()"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl shadow-lg shadow-slate-300 transition transform active:scale-[0.98] flex items-center justify-center gap-3">
                                <i class="fas fa-paper-plane"></i> <span>Simpan Keputusan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        let currentVerifikasiId = null;
        const toLabel = (str) => str.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

        // --- HELPER RENDERING ---
        const renderRow = (label, content) => `
                <div class="flex flex-col sm:flex-row sm:justify-between py-3 border-b border-slate-50 last:border-0 gap-2">
                    <span class="text-xs font-bold text-slate-400 uppercase sm:w-1/3 pt-1 tracking-wide">${label}</span>
                    <span class="text-sm font-semibold text-slate-800 sm:w-2/3 sm:text-right break-words leading-relaxed">${content || '-'}</span>
                </div>`;

        const formatDisplay = (k, v) => {
            if (!v) return '<div class="h-24 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 text-xs gap-2"><i class="fas fa-file-slash text-xl opacity-50"></i><span>Tidak ada file</span></div>';

            const val = (typeof v === 'object' && v !== null && v.value) ? v.value : v.toString().trim();
            const realPath = val.startsWith('http') ? val : `/storage/${val}`;

            if (val.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                return `<a href="${realPath}" target="_blank" class="group relative block w-full max-w-[200px] h-32 rounded-xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                                <img src="${realPath}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center backdrop-blur-[2px]">
                                    <span class="bg-white/20 border border-white/50 text-white px-3 py-1 rounded-full text-xs font-bold backdrop-blur">Lihat Gambar</span>
                                </div>
                            </a>`;
            }
            if (val.match(/\.(pdf)$/i)) {
                return `<a href="${realPath}" target="_blank" class="flex items-center gap-4 p-4 rounded-xl bg-white border border-slate-200 hover:border-indigo-300 hover:shadow-md transition group w-full">
                                <div class="w-12 h-12 bg-rose-50 border border-rose-100 rounded-xl flex items-center justify-center text-rose-500 text-2xl shadow-sm group-hover:scale-110 transition-transform"><i class="fas fa-file-pdf"></i></div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Dokumen PDF</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Klik untuk melihat file</p>
                                </div>
                            </a>`;
            }
            if (val.includes('http')) {
                return `<a href="${val}" target="_blank" class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 hover:underline font-bold text-sm bg-indigo-50 px-4 py-3 rounded-xl border border-indigo-100 transition"><i class="fas fa-external-link-alt"></i> Buka Tautan</a>`;
            }
            return `<span class="font-bold text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg text-sm">${val}</span>`;
        };

        // --- MODAL CONTROLS ---
        function toggleModal(id, show) {
            const el = document.getElementById(id);
            const panel = el.querySelector('.modal-panel');
            if (show) {
                el.classList.remove('hidden'); document.body.style.overflow = 'hidden';
                setTimeout(() => { panel.classList.remove('scale-95', 'opacity-0'); panel.classList.add('scale-100', 'opacity-100'); }, 10);
            } else {
                panel.classList.remove('scale-100', 'opacity-100'); panel.classList.add('scale-95', 'opacity-0');
                setTimeout(() => { el.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
            }
        }
        function closeDetail() { toggleModal('detailModal', false); }
        function closeVerifikasi() { toggleModal('verifikasiModal', false); }

        // --- OPEN DETAIL (VIEW ONLY) ---
        async function openDetail(id) {
            document.getElementById('modalIdDisplay').innerText = '#' + String(id).padStart(5, '0');
            toggleModal('detailModal', true);
            const content = document.getElementById('detailContent');
            content.innerHTML = '<div class="flex flex-col items-center justify-center py-20"><div class="w-12 h-12 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div><p class="text-slate-400 font-bold text-sm">Mengambil data...</p></div>';

            try {
                const res = await fetch(`/operator/api/pengajuan/${id}`);
                const data = await res.json();

                let htmlUmum = Object.entries(data.data_umum || {}).map(([k, v]) => renderRow(toLabel(k), v)).join('');
                let htmlLayanan = Object.entries(data.data_layanan || {}).map(([k, v]) => `
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-3 tracking-wide">${k.replace(/_/g, ' ')}</p>
                            <div>${formatDisplay(k, v)}</div>
                        </div>`).join('');

                content.innerHTML = `
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                                    <h4 class="text-xs font-extrabold text-indigo-500 uppercase tracking-widest mb-6 border-b border-slate-100 pb-3 flex items-center gap-2">
                                        <i class="fas fa-address-card"></i> Identitas
                                    </h4>
                                    ${htmlUmum}
                                </div>
                                ${data.catatan ? `<div class="bg-amber-50 border border-amber-200 p-5 rounded-xl"><h5 class="text-xs font-bold text-amber-600 uppercase mb-2">Catatan Sebelumnya</h5><p class="text-sm text-amber-800 font-medium leading-relaxed">"${data.catatan}"</p></div>` : ''}
                            </div>
                            <div class="lg:col-span-2">
                                <h4 class="text-xs font-extrabold text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2 px-1">
                                    <i class="fas fa-folder-open"></i> Kelengkapan Berkas
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    ${htmlLayanan}
                                </div>
                            </div>
                        </div>`;
            } catch (e) { content.innerHTML = '<p class="text-rose-500 text-center font-bold py-10">Gagal memuat data.</p>'; }
        }

        // --- OPEN VERIFIKASI (ACTION) ---
        async function openVerifikasi(id) {
            currentVerifikasiId = id;
            toggleModal('verifikasiModal', true);
            const content = document.getElementById('verifikasiContent');
            content.innerHTML = '<div class="flex flex-col items-center justify-center py-32 opacity-50"><div class="w-12 h-12 border-4 border-slate-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div><p class="text-slate-500 font-bold text-sm">Menyiapkan lembar kerja...</p></div>';

            try {
                const res = await fetch(`/operator/api/pengajuan/${id}?t=${Date.now()}`);
                if (!res.ok) throw new Error("Gagal mengambil data");
                const dataRes = await res.json();

                // Set Form Status
                document.getElementById('statusSelect').value = dataRes.status;
                document.getElementById('adminCatatan').value = dataRes.catatan || '';
                toggleCatatan();

                const saved = dataRes.verifikasi_saved || {};
                const files = dataRes.data_layanan || {};
                const dataUmum = dataRes.data_umum || {};
                const userPos = '{{ Auth::user()->position }}';
                const namaLayanan = dataRes.layanan ? dataRes.layanan.nama : (dataRes.layanan_nama || 'Layanan');
                let suratKey = Object.keys(files).find(k => k.toLowerCase().includes('surat')) || Object.keys(files)[0];

                let contentHtml = '';

                // === 1. TAMPILAN TU & KOORDINATOR (Preview) ===
                if (userPos === 'tu' || userPos === 'koordinator') {
                    contentHtml = `
                            <div class="bg-indigo-50/50 p-6 rounded-2xl border border-indigo-100 mb-8 flex items-start gap-6">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-indigo-600 shrink-0 border border-indigo-50"><i class="fas fa-envelope-open-text text-3xl"></i></div>
                                <div>
                                    <h5 class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">Pengajuan Masuk</h5>
                                    <h2 class="text-2xl font-extrabold text-slate-800 leading-tight mb-2">${namaLayanan}</h2>
                                    <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-slate-600">
                                        <span class="font-medium"><i class="fas fa-hashtag text-slate-400 mr-1"></i> ${dataUmum['Nomor Surat'] || dataUmum['nomor_surat'] || '-'}</span>
                                        <span class="font-medium"><i class="fas fa-calendar text-slate-400 mr-1"></i> ${dataUmum['Tanggal Surat'] || '-'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-6">
                                <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-paperclip text-slate-400"></i> Berkas Surat Pengantar</h4>
                                ${formatDisplay('Surat Pengantar', files[suratKey])}
                            </div>
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-user-circle text-slate-400"></i> Data Pengusul</h4>
                                <div class="space-y-1">
                                    ${renderRow('Asal Instansi', dataUmum['Unit Kerja'] || dataUmum['unit_kerja'] || dataRes.user.unit_kerja || '-')}
                                    ${renderRow('Perihal', dataUmum['Perihal'] || dataUmum['perihal'])}
                                </div>
                            </div>`;
                }

                // === 2. TAMPILAN KEPALA BIRO (Ringkasan) ===
                else if (userPos === 'kepala_biro') {
                    contentHtml = `
                            <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                                <div class="w-20 h-20 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-6 text-purple-600 text-3xl shadow-sm"><i class="fas fa-signature"></i></div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Persetujuan Layanan</h3>
                                <p class="text-slate-500 mb-8">${namaLayanan}</p>
                                <div class="text-left bg-slate-50 rounded-xl p-6 border border-slate-100 space-y-3">
                                    ${renderRow('Nomor Surat', dataUmum['Nomor Surat'] || dataUmum['nomor_surat'])}
                                    ${renderRow('Perihal', dataUmum['Perihal'] || dataUmum['perihal'])}
                                </div>
                                <div class="mt-8 text-left">
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-4">Berkas Lampiran</p>
                                    <div class="flex flex-wrap gap-3">${Object.entries(files).map(([k, v]) => formatDisplay(k, v)).join('')}</div>
                                </div>
                            </div>`;
                }

                // === 3. TAMPILAN VERIFIKATOR (Checklist) - DEFAULT ELSE ===
                else {
                    if (Object.keys(files).length === 0) {
                        contentHtml = '<div class="text-center py-12 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200"><p class="text-slate-400 font-bold">Tidak ada berkas yang perlu diperiksa.</p></div>';
                    } else {
                        contentHtml = `
                                <div class="mb-8">
                                    <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Mode Checklist</span>
                                    <h2 class="text-2xl font-bold text-slate-800 mt-3">${namaLayanan}</h2>
                                    <p class="text-slate-500 text-sm mt-1">Silakan periksa kelengkapan dan validitas berkas di bawah ini.</p>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    ${Object.entries(files).map(([k, v]) => {
                            const isChecked = saved[k] === 'Lengkap';
                            const val = (typeof v === 'object' && v?.value) ? v.value : v;
                            const lbl = (typeof v === 'object' && v?.label) ? v.label : k.replace(/_/g, ' ');
                            return `
                                        <div class="check-card p-5 flex flex-col md:flex-row gap-6 cursor-pointer group select-none ${isChecked ? 'selected' : ''}" onclick="toggleCheck(this, 'chk_${k}')">
                                            <div class="w-full md:w-48 shrink-0 flex flex-col justify-center">
                                                ${formatDisplay(k, val)}
                                            </div>
                                            <div class="flex-1 flex flex-col justify-between py-1">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h5 class="text-sm font-bold text-slate-700 uppercase tracking-wide group-hover:text-indigo-600 transition-colors">${lbl}</h5>
                                                    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all ${isChecked ? 'bg-emerald-500 border-emerald-500 scale-110' : 'border-slate-300 bg-white'} icon-box shadow-sm">
                                                        <i class="fas fa-check text-white text-sm ${isChecked ? 'opacity-100' : 'opacity-0'} transition-opacity duration-200"></i>
                                                        <input type="checkbox" id="chk_${k}" class="hidden" ${isChecked ? 'checked' : ''}>
                                                    </div>
                                                </div>
                                                <div class="mt-auto pt-2">
                                                    <span class="text-[10px] font-bold uppercase tracking-wider ${isChecked ? 'text-emerald-600' : 'text-slate-300'} transition-colors status-text">
                                                        ${isChecked ? 'Valid (Lengkap)' : 'Belum Dicek'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>`;
                        }).join('')}
                                </div>`;
                    }
                }
                content.innerHTML = contentHtml;
            } catch (e) {
                console.error(e);
                content.innerHTML = '<div class="text-center py-10 text-rose-500 font-bold">Gagal memuat data.</div>';
            }
        }

        // --- CHECKBOX TOGGLE ---
        function toggleCheck(card, inputId) {
            const input = document.getElementById(inputId);
            input.checked = !input.checked;
            const iconBox = card.querySelector('.icon-box');
            const icon = iconBox.querySelector('i');
            const statusText = card.querySelector('.status-text');

            if (input.checked) {
                card.classList.add('selected');
                iconBox.classList.remove('border-slate-300', 'bg-white');
                iconBox.classList.add('bg-emerald-500', 'border-emerald-500', 'scale-110');
                icon.classList.remove('opacity-0'); icon.classList.add('opacity-100');
                statusText.innerText = 'Valid (Lengkap)';
                statusText.classList.remove('text-slate-300'); statusText.classList.add('text-emerald-600');
            } else {
                card.classList.remove('selected');
                iconBox.classList.add('border-slate-300', 'bg-white');
                iconBox.classList.remove('bg-emerald-500', 'border-emerald-500', 'scale-110');
                icon.classList.add('opacity-0'); icon.classList.remove('opacity-100');
                statusText.innerText = 'Belum Dicek';
                statusText.classList.add('text-slate-300'); statusText.classList.remove('text-emerald-600');
            }
        }

        // --- CATATAN TOGGLE ---
        function toggleCatatan() {
            const val = document.getElementById('statusSelect').value;
            const area = document.getElementById('catatanArea');
            if (val === 'perlu_revisi' || val === 'ditolak') {
                area.classList.remove('hidden');
                setTimeout(() => document.getElementById('adminCatatan').focus(), 100);
            } else {
                area.classList.add('hidden');
                document.getElementById('adminCatatan').value = '';
            }
        }

        // --- SAVE DATA ---
        async function saveVerifikasi() {
            const status = document.getElementById('statusSelect').value;
            const catatan = document.getElementById('adminCatatan').value;

            if (!status) { Swal.fire('Peringatan', 'Harap pilih keputusan tindakan!', 'warning'); return; }
            if ((status === 'perlu_revisi' || status === 'ditolak') && !catatan.trim()) { Swal.fire('Peringatan', 'Wajib mengisi catatan untuk revisi/penolakan!', 'warning'); return; }

            const checkboxes = document.querySelectorAll('#verifikasiContent input[type="checkbox"]');
            const hasil = {};
            if (checkboxes.length > 0) {
                checkboxes.forEach(c => { hasil[c.id.replace('chk_', '')] = c.checked ? 'Lengkap' : 'Belum Lengkap'; });
            }

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                // 1. Simpan Checklist (Jika Verifikator)
                if (checkboxes.length > 0) {
                    await fetch(`/operator/api/pengajuan/${currentVerifikasiId}/verifikasi`, {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ hasil })
                    });
                }
                // 2. Simpan Status
                const res = await fetch(`/operator/api/pengajuan/${currentVerifikasiId}/status`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ status, catatan })
                });

                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Keputusan Disimpan', text: 'Status pengajuan telah diperbarui.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
                } else {
                    Swal.fire('Gagal', 'Terjadi kesalahan sistem.', 'error');
                }
            } catch (e) { Swal.fire('Error', 'Koneksi terputus.', 'error'); }
        }
    </script>
@endsection