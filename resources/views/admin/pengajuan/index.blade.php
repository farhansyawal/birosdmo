@extends('admin.layouts.app')
@section('title', 'Monitoring Pengajuan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Font & Library --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }

        /* TABLE STYLES */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0 0.6rem;
            width: 100%;
        }

        .modern-table thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748B;
            padding: 0 1.5rem;
            text-align: left;
        }

        .modern-table tbody tr {
            background-color: #ffffff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .modern-table tbody tr td {
            padding: 1rem 1.5rem;
            border-top: 1px solid #F1F5F9;
            border-bottom: 1px solid #F1F5F9;
        }

        .modern-table tbody tr td:first-child {
            border-left: 1px solid #F1F5F9;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }

        .modern-table tbody tr td:last-child {
            border-right: 1px solid #F1F5F9;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .modern-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -5px rgba(0, 0, 0, 0.1);
            border-color: #0F766E;
        }

        /* BADGE */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .dot {
            width: 0.4rem;
            height: 0.4rem;
            border-radius: 50%;
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
    </style>

    <div class="min-h-screen py-8 px-4">

        {{-- HEADER --}}
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Monitoring Pengajuan</h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">Control Panel Admin: Pantau dan kelola seluruh data.</p>
            </div>
            <button onclick="location.reload()"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>

        {{-- TABLE LIST --}}
        <div class="overflow-x-auto pb-10 custom-scroll">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="25%">Pengusul</th>
                        <th width="25%">Layanan</th>
                        <th width="20%" class="text-center">Status</th>
                        <th width="15%" class="text-right">Tanggal</th>
                        <th width="10%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $item)
                        @php
                            // KONFIGURASI STATUS & LABEL LENGKAP
                            $statusMap = [
                                'pending' => [
                                    'label' => 'Pending (TU)',
                                    'class' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'dot' => 'bg-amber-500'
                                ],
                                'menunggu_koordinator' => [
                                    'label' => 'Menunggu Koordinator',
                                    'class' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'dot' => 'bg-blue-500'
                                ],
                                'menunggu_verifikator' => [
                                    'label' => 'Menunggu Verifikator',
                                    'class' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'dot' => 'bg-indigo-500'
                                ],
                                'diterima' => [
                                    'label' => 'Diterima (Selesai)',
                                    'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'dot' => 'bg-emerald-500'
                                ],
                                'ditolak' => [
                                    'label' => 'Ditolak',
                                    'class' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'dot' => 'bg-rose-500'
                                ],
                                'perlu_revisi' => [
                                    'label' => 'Perlu Revisi',
                                    'class' => 'bg-orange-50 text-orange-700 border-orange-100',
                                    'dot' => 'bg-orange-500'
                                ]
                            ];

                            // Fallback jika status tidak dikenal
                            $conf = $statusMap[$item->status] ?? [
                                'label' => $item->status,
                                'class' => 'bg-slate-100 text-slate-500 border-slate-200',
                                'dot' => 'bg-slate-400'
                            ];
                        @endphp
                        <tr class="group">
                            <td class="text-center font-mono text-xs text-indigo-600 font-bold bg-indigo-50/50 rounded-lg p-1">
                                #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-slate-800 text-white flex items-center justify-center text-xs font-bold shadow-md">
                                        {{ substr($item->user->name ?? 'U', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">{{ $item->user->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $item->user->nip ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-folder text-emerald-500"></i>
                                    <span class="text-sm font-semibold text-slate-700">{{ $item->layanan->nama ?? '-' }}</span>
                                </div>
                                <div class="text-xs text-slate-400 mt-1 pl-5">{{ $item->user->unit_kerja ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="status-badge {{ $conf['class'] }}">
                                    <span class="dot {{ $conf['dot'] }}"></span>
                                    {{-- Gunakan Label dari Map, bukan str_replace --}}
                                    {{ $conf['label'] }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="text-sm font-bold text-slate-700">{{ $item->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="openAdminDetail({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition flex items-center justify-center shadow-sm"
                                        title="Control Panel">
                                        <i class="fas fa-sliders-h"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white transition flex items-center justify-center shadow-sm"
                                        title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('admin.pengajuan.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-500">Data kosong.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pengajuans->links('pagination::tailwind') }}</div>
    </div>

    {{-- MODAL ADMIN (NEW CONCEPT: DOCUMENT & INSPECTOR) --}}
    <div id="adminModal" class="fixed inset-0 z-[999] hidden" role="dialog">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-[2px] transition-opacity" onclick="closeAdminDetail()">
        </div>

        <div class="fixed inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
            <div class="bg-white w-full max-w-6xl h-[85vh] rounded-2xl shadow-2xl overflow-hidden flex flex-col border border-slate-200 transform transition-all scale-95 opacity-0"
                id="modalPanel">

                {{-- 1. CLEAN HEADER --}}
                <div class="bg-white px-6 py-4 border-b border-slate-200 flex justify-between items-center z-20 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900 tracking-tight">Admin Control</h3>
                            <div class="flex items-center gap-2 text-xs font-mono text-slate-500">
                                <span>ID:</span>
                                <span id="modalIdDisplay"
                                    class="bg-slate-100 px-2 py-0.5 rounded text-slate-700 font-bold">#LOADING</span>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeAdminDetail()"
                        class="w-8 h-8 rounded-full bg-white hover:bg-slate-100 text-slate-400 hover:text-rose-500 transition flex items-center justify-center border border-transparent hover:border-slate-200">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                {{-- 2. SPLIT BODY --}}
                <div class="flex-1 flex overflow-hidden relative">

                    {{-- KIRI: AREA DATA (SCROLLABLE) --}}
                    <div class="flex-1 overflow-y-auto custom-scroll bg-white p-8">
                        <div id="detailContent" class="max-w-3xl mx-auto space-y-8">
                            {{-- Loader Placeholder --}}
                            <div class="flex flex-col items-center justify-center h-64 text-slate-300 animate-pulse">
                                <i class="fas fa-circle-notch fa-spin text-4xl mb-3"></i>
                                <p class="text-sm font-medium text-slate-400">Mengambil data pengajuan...</p>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: INSPECTOR PANEL (FIXED SIDEBAR) --}}
                    <div
                        class="w-[320px] bg-slate-50 border-l border-slate-200 flex flex-col shrink-0 z-10 shadow-[-10px_0_20px_-10px_rgba(0,0,0,0.05)]">

                        <div class="p-6 flex-1 overflow-y-auto custom-scroll">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Intervensi Status
                            </h4>

                            <div class="space-y-6">
                                {{-- Status Control --}}
                                <div>
                                    <label class="text-xs font-bold text-slate-700 mb-2 block">Status Dokumen</label>
                                    <div class="relative">
                                        <select id="adminStatusSelect"
                                            class="w-full appearance-none bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                                            <option value="pending">Pending (TU)</option>
                                            <option value="menunggu_koordinator">Menunggu Koordinator</option>
                                            <option value="menunggu_verifikator">Menunggu Verifikator</option>
                                            <option value="diterima">Diterima (Selesai)</option>
                                            <option value="ditolak">Ditolak</option>
                                            <option value="perlu_revisi">Perlu Revisi</option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- Catatan Control --}}
                                <div>
                                    <label class="text-xs font-bold text-slate-700 mb-2 block">Catatan Admin</label>
                                    <textarea id="adminCatatan" rows="6"
                                        class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm placeholder:text-slate-400 resize-none"
                                        placeholder="Tulis alasan perubahan status secara paksa di sini..."></textarea>
                                    <p class="text-[10px] text-slate-400 mt-2 italic">*Catatan ini akan terlihat oleh user.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Action --}}
                        <div class="p-6 border-t border-slate-200 bg-white">
                            <button onclick="forceUpdate()"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-slate-200 transition transform active:scale-[0.98] flex items-center justify-center gap-2 group">
                                <span>Simpan Perubahan</span>
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT (Updated Logic) --}}
    <script>
        let currentId = null;

        // 1. HELPERS UNTUK TAMPILAN DATA
        const renderRow = (label, value) => `
                                    <div class="flex flex-col sm:flex-row justify-between py-4 border-b border-slate-100 last:border-0 gap-2">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider sm:w-1/3 pt-1">${label}</span>
                                        <span class="text-sm font-semibold text-slate-800 sm:w-2/3 text-left sm:text-right break-words leading-relaxed">${value ?? '-'}</span>
                                    </div>
                                `;

        const formatDisplay = (k, v) => {
            if (!v) return '<span class="text-slate-300 text-xs italic">Tidak ada file</span>';
            const val = v.toString().trim();

            // LOGIC FIX URL:
            // Controller mengirim Full URL (http://...).
            // Jika sudah ada http, JANGAN tambah /storage/ lagi.
            // Jika belum ada (path relative), tambah /storage/.
            const fileUrl = val.includes('http') ? val : `/storage/${val}`;

            // Image Preview
            if (val.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                return `
                        <div class="mt-2 flex justify-end">
                            <a href="${fileUrl}" target="_blank" class="group relative block w-full max-w-[200px] rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                <img src="${fileUrl}" class="w-full h-32 object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full backdrop-blur">Lihat</span>
                                </div>
                            </a>
                        </div>`;
            }
            // PDF Preview Link
            if (val.match(/\.(pdf)$/i)) {
                return `
                        <div class="mt-2 flex justify-end">
                            <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-3 p-3 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 hover:bg-rose-100 transition w-full max-w-[240px]">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-rose-500 text-xl shadow-sm"><i class="fas fa-file-pdf"></i></div>
                                <div class="text-left">
                                    <p class="text-xs font-bold">Dokumen PDF</p>
                                    <p class="text-[10px] opacity-70">Klik untuk membuka</p>
                                </div>
                            </a>
                        </div>`;
            }
            // Link Text (Jika isinya teks url biasa)
            if (val.includes('http')) return `<a href="${val}" target="_blank" class="text-indigo-600 hover:underline font-bold text-sm break-all">${val}</a>`;

            return `<span class="text-slate-700 font-medium text-sm">${val}</span>`;
        };

        // 2. MODAL LOGIC
        function toggleModal(show) {
            const modal = document.getElementById('adminModal');
            const panel = document.getElementById('modalPanel');
            if (show) {
                modal.classList.remove('hidden'); document.body.style.overflow = 'hidden';
                setTimeout(() => { panel.classList.remove('scale-95', 'opacity-0'); panel.classList.add('scale-100', 'opacity-100'); }, 10);
            } else {
                panel.classList.remove('scale-100', 'opacity-100'); panel.classList.add('scale-95', 'opacity-0');
                setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
            }
        }
        function closeAdminDetail() { toggleModal(false); }

        // 3. LOAD DATA
        async function openAdminDetail(id) {
            currentId = id;
            document.getElementById('modalIdDisplay').innerText = '#' + String(id).padStart(5, '0');
            toggleModal(true);

            const content = document.getElementById('detailContent');

            try {
                const res = await fetch(`/admin/pengajuan/${id}`);
                const data = await res.json();

                // Set Form Values
                document.getElementById('adminStatusSelect').value = data.status;
                document.getElementById('adminCatatan').value = data.catatan || '';

                // Render Content
                let html = `
                                            <div class="space-y-8">
                                                <div class="bg-white">
                                                    <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                                                        <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                                                        Data Pemohon
                                                    </h4>
                                                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                                                        ${Object.entries(data.data_umum || {}).map(([k, v]) => renderRow(k, v)).join('')}
                                                    </div>
                                                </div>

                                                <div class="bg-white">
                                                    <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                                                        <span class="w-1 h-5 bg-emerald-500 rounded-full"></span>
                                                        Berkas Layanan
                                                    </h4>
                                                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 divide-y divide-slate-200/50">
                                                        ${Object.entries(data.data_layanan || {}).map(([k, v]) => `
                                                            <div class="py-4 first:pt-0 last:pb-0">
                                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">${k.replace(/_/g, ' ')}</p>
                                                                <div class="pl-2">${formatDisplay(k, v.value || v)}</div>
                                                            </div>
                                                        `).join('')}
                                                    </div>
                                                </div>

                                                ${data.catatan ? `
                                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                                                    <h5 class="text-xs font-bold text-amber-700 uppercase mb-2 flex items-center gap-2"><i class="fas fa-sticky-note"></i> Catatan Terakhir</h5>
                                                    <p class="text-sm text-amber-900 italic">"${data.catatan}"</p>
                                                </div>` : ''}
                                            </div>
                                        `;
                content.innerHTML = html;

            } catch (e) { content.innerHTML = '<div class="text-center py-20 text-rose-500 font-bold">Gagal memuat data. Coba refresh.</div>'; }
        }

        // 4. FORCE UPDATE ACTION
        async function forceUpdate() {
            // Pake SweetAlert Confirm
            const result = await Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Anda akan mengubah status dokumen ini secara paksa sebagai Admin.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            });

            if (!result.isConfirmed) return;

            const status = document.getElementById('adminStatusSelect').value;
            const catatan = document.getElementById('adminCatatan').value;
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const res = await fetch(`/admin/pengajuan/${currentId}/update`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ status, catatan })
                });
                const json = await res.json();

                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Status telah diperbarui.', timer: 1500, showConfirmButton: false });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: json.message });
                }
            } catch (e) { Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi terputus.' }); }
        }
        // Tampilkan Flash Message dari Laravel jika ada (misal setelah delete)
        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif


        // 5. destroy
        function confirmDelete(id) {
            Swal.fire({
                title: "Hapus Data?",
                text: "Data yang dihapus tidak dapat dikembalikan.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e11d48",
                cancelButtonColor: "#94a3b8",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
                customClass: { popup: "rounded-xl" }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    document.getElementById("delete-form-" + id).submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "{{ session('success') }}",
                timer: 1800,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "{{ session('error') }}",
            });
        @endif
    </script>
@endsection