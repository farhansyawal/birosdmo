@extends('user.layouts.app')
@section('title', 'Riwayat Pengajuan')

@section('content')
    {{-- FONTS & ASSETS --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
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

        /* Status Badge */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        /* Custom Scroll */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Pengajuan</h1>
                <p class="text-slate-500 mt-2 text-sm font-medium">Pantau status dokumen dan lakukan perbaikan jika
                    diperlukan.</p>
            </div>
            <a href="{{ route('user.pages.pengajuan') }}"
                class="group flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-200 transition-all active:scale-95">
                <i class="fas fa-plus"></i> Buat Pengajuan Baru
            </a>
        </div>

        {{-- TABLE CONTAINER --}}
        <div class="overflow-x-auto pb-4">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Informasi Dokumen</th>
                        <th width="25%">Layanan</th>
                        <th width="20%">Status</th>
                        <th width="15%" class="text-right">Tanggal</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600">
                    @forelse ($riwayat as $item)
                        @php
                            // 1. Decode JSON
                            $dL = json_decode($item->data_layanan ?? '{}', true);
                            $dU = json_decode($item->data_umum ?? '{}', true);

                            // 2. AMBIL NOMOR SURAT DARI KEY YANG BENAR (nomor_surat_usulan)
                            $nomorSurat = $dU['nomor_surat_usulan'] ?? '-';

                            // 3. Siapkan Data untuk Modal (Tanpa Request AJAX lagi)
                            $jsonData = [
                                'id' => $item->id,
                                'nomor' => $nomorSurat,
                                'status' => $item->status,
                                'layanan' => $item->layanan->nama ?? 'Layanan Dihapus',
                                'tanggal' => $item->created_at->translatedFormat('d F Y H:i'),
                                'data_umum' => $dU,
                                'data_layanan' => $dL,
                                'catatan' => $item->catatan,
                            ];

                            // Config warna status
                            $statusConfig = [
                                'pending' => [
                                    'bg' => 'bg-amber-50 text-amber-600',
                                    'dot' => 'bg-amber-500',
                                    'label' => 'Menunggu Verifikasi',
                                ],
                                'menunggu_koordinator' => [
                                    'bg' => 'bg-amber-50 text-amber-600',
                                    'dot' => 'bg-amber-500',
                                    'label' => 'Proses Koordinator',
                                ],
                                'menunggu_verifikator' => [
                                    'bg' => 'bg-blue-50 text-blue-600',
                                    'dot' => 'bg-blue-500',
                                    'label' => 'Proses Verifikator',
                                ],
                                'diterima' => [
                                    'bg' => 'bg-emerald-50 text-emerald-600',
                                    'dot' => 'bg-emerald-500',
                                    'label' => 'Selesai / Diterima',
                                ],
                                'ditolak' => [
                                    'bg' => 'bg-rose-50 text-rose-600',
                                    'dot' => 'bg-rose-500',
                                    'label' => 'Ditolak',
                                ],
                                'perlu_revisi' => [
                                    'bg' => 'bg-orange-50 text-orange-600',
                                    'dot' => 'bg-orange-500',
                                    'label' => 'Perlu Revisi',
                                ],
                            ];
                            $conf = $statusConfig[$item->status] ?? [
                                'bg' => 'bg-slate-100',
                                'dot' => 'bg-slate-400',
                                'label' => $item->status,
                            ];
                        @endphp

                        <tr onclick='openDetailLocal(@json($jsonData))'>
                            <td class="text-center font-bold text-slate-400">
                                {{ $loop->iteration + $riwayat->firstItem() - 1 }}
                            </td>
                            <td>
                                {{-- Nama Pegawai (Asumsi ada di data_layanan, jika tidak pakai Auth user) --}}
                                <div class="font-bold text-slate-800">
                                    {{ $dL['nama_lengkap_pegawai_yang_diusulkan'] ?? Auth::user()->name }}</div>

                                {{-- TAMPILKAN NOMOR SURAT YANG SUDAH DIAMBIL --}}
                                <div
                                    class="text-xs text-slate-400 mt-1 font-mono bg-slate-100 inline-block px-2 py-0.5 rounded">
                                    {{ Str::limit($nomorSurat, 30) }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <span class="font-medium truncate">{{ $item->layanan->nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $conf['bg'] }}">
                                    <span class="badge-dot {{ $conf['dot'] }}"></span> {{ $conf['label'] }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="font-bold text-slate-700">{{ $item->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="text-right pr-4">
                                @if ($item->status == 'perlu_revisi')
                                    <button onclick="event.stopPropagation(); openEditAjax({{ $item->id }})"
                                        class="px-3 py-1.5 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold hover:bg-orange-200 transition border border-orange-200 animate-pulse">
                                        <i class="fas fa-pencil-alt"></i> Revisi
                                    </button>
                                @else
                                    <i class="fas fa-chevron-right text-slate-300"></i>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-20">
                                <div class="opacity-50">
                                    <i class="far fa-folder-open text-4xl text-slate-300 mb-3"></i>
                                    <p class="text-slate-500 font-medium">Belum ada riwayat pengajuan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($riwayat->hasPages())
            <div class="mt-6">
                {{ $riwayat->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    {{-- MODAL DETAIL (INSTANT VIEW) --}}
    <div id="detailModal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div id="detailBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0"
            onclick="closeModal('detailModal')"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div id="detailPanel"
                    class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all opacity-0 scale-95 translate-y-4">

                    {{-- Modal Header --}}
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Detail Pengajuan</h3>
                            <p class="text-xs text-slate-500 mt-0.5">ID: <span id="viewId"
                                    class="font-mono font-bold text-indigo-600"></span></p>
                        </div>
                        <button onclick="closeModal('detailModal')"
                            class="w-8 h-8 rounded-full bg-white text-slate-400 hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center transition shadow-sm border border-slate-200"><i
                                class="fas fa-times"></i></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 max-h-[70vh] overflow-y-auto custom-scroll">
                        {{-- Status Alert --}}
                        <div id="viewStatusContainer" class="mb-6"></div>

                        {{-- Catatan Revisi (Jika Ada) --}}
                        <div id="viewCatatanBox" class="hidden mb-6 bg-rose-50 border border-rose-100 rounded-xl p-4">
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center text-rose-500 shrink-0">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-rose-700">Catatan Revisi</h5>
                                    <p id="viewCatatanText" class="text-sm text-rose-600 mt-1 leading-relaxed"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Data Sections --}}
                        <div class="space-y-6">
                            <div>
                                <h4
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-b border-slate-100 pb-2">
                                    Informasi Umum</h4>
                                <div id="viewDataUmum" class="grid grid-cols-1 gap-y-3"></div>
                            </div>
                            <div>
                                <h4
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-b border-slate-100 pb-2">
                                    Berkas & Kelengkapan</h4>
                                <div id="viewDataLayanan" class="space-y-3"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end">
                        <button onclick="closeModal('detailModal')"
                            class="px-5 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-sm font-bold hover:bg-slate-50 shadow-sm transition">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT (AJAX LOADED) --}}
    <div id="editModal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true">
        <div id="editBackdrop" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity opacity-0"
            onclick="closeModal('editModal')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div id="editPanel"
                    class="relative w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all opacity-0 scale-95 translate-y-4">
                    <form id="editForm" onsubmit="submitEdit(event)" enctype="multipart/form-data">
                        <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Perbaikan Dokumen</h3>
                                    <p class="text-xs text-orange-600 font-medium">Mohon lengkapi sesuai catatan revisi.
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="closeModal('editModal')"
                                class="w-8 h-8 rounded-full bg-white/50 hover:bg-white text-slate-400 hover:text-rose-500 transition"><i
                                    class="fas fa-times"></i></button>
                        </div>

                        <div id="editBody" class="p-8 max-h-[65vh] overflow-y-auto custom-scroll">
                            {{-- Loader --}}
                            <div id="editLoader" class="flex flex-col items-center justify-center py-12">
                                <div
                                    class="w-10 h-10 border-4 border-slate-200 border-t-orange-500 rounded-full animate-spin mb-3">
                                </div>
                                <p class="text-sm font-bold text-slate-400">Mengambil data terbaru...</p>
                            </div>

                            {{-- Content --}}
                            <div id="editContent" class="hidden space-y-6">
                                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg">
                                    <h5 class="text-xs font-bold text-rose-800 uppercase mb-1">Alasan Penolakan:</h5>
                                    <p id="editCatatanDisplay" class="text-sm text-rose-700"></p>
                                </div>
                                <div id="editFieldsContainer" class="space-y-4"></div>
                                <input type="hidden" name="submission_id" id="editSubmissionId">
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" onclick="closeModal('editModal')"
                                class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 font-bold text-sm hover:bg-slate-50">Batal</button>
                            <button type="submit" id="btnSubmitEdit"
                                class="px-5 py-2.5 rounded-xl bg-orange-600 text-white font-bold text-sm hover:bg-orange-700 shadow-lg shadow-orange-200 flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Kirim Perbaikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // --- UTILS ---
        const formatLabel = (str) => str.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

        // --- MODAL SYSTEM (Copy from your existing code) ---
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                document.querySelector(`#${id} div[id$="Backdrop"]`).classList.remove('opacity-0');
                const panel = document.querySelector(`#${id} div[id$="Panel"]`);
                panel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'scale-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const backdrop = modal.querySelector('div[id$="Backdrop"]');
            const panel = modal.querySelector('div[id$="Panel"]');
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // --- HELPER UNTUK FETCH DATA MASTER (Pangkat, Jabatan, Unit Kerja) ---
        async function getMasterData(type) {
            try {
                const res = await fetch(`/api/${type}`);
                return await res.json();
            } catch (e) {
                console.error(`Gagal load ${type}`, e);
                return [];
            }
        }

        // --- OPEN EDIT MODAL (LOGIC BARU) ---
        async function openEditAjax(id) {
            // Reset & Show Loading
            document.getElementById('editSubmissionId').value = id;
            document.getElementById('editLoader').classList.remove('hidden');
            document.getElementById('editContent').classList.add('hidden');
            document.getElementById('editFieldsContainer').innerHTML = ''; // Bersihkan container
            openModal('editModal');

            try {
                // 1. Ambil Data Pengajuan (Existing Values)
                const resSubmission = await fetch(`/user/pengajuan/${id}`);
                if (!resSubmission.ok) throw new Error("Gagal load data pengajuan");
                const submission = await resSubmission.json();

                // Set Catatan
                document.getElementById('editCatatanDisplay').innerText = submission.catatan || '-';

                // Gabungkan data value (Umum + Layanan) untuk pencarian mudah
                // Note: submission.data_layanan dari controller mungkin formatnya {key: {type:..., value:...}} 
                // Kita perlu value aslinya.
                let existingValues = {
                    ...submission.data_umum
                };

                // Normalisasi data layanan (jika formatnya object kompleks dari controller)
                if (submission.data_layanan) {
                    Object.entries(submission.data_layanan).forEach(([k, v]) => {
                        existingValues[k] = (typeof v === 'object' && v !== null && v.value !== undefined) ? v
                            .value : v;
                    });
                }

                // 2. Ambil Definisi Field dari API Layanan (Agar tipe input sesuai: Select, Date, dll)
                // Jika layanan_id ada di response controller, gunakan itu.
                const layananId = submission.layanan_id;
                const resFields = await fetch(`/api/layanan/${layananId}/fields`);
                const fields = await resFields.json();

                // Sort field sesuai urutan
                fields.sort((a, b) => a.queue - b.queue);

                const container = document.getElementById('editFieldsContainer');

                // 3. Render Field Satu per Satu
                // Kita gunakan for...of agar bisa await fetch data dropdown
                for (const field of fields) {
                    // Skip field read-only tertentu jika perlu
                    if (['nomor_surat_usulan', 'perihal_surat_usulan', 'tanggal_surat_usulan'].includes(field.name)) {
                        // Opsional: Tetap render tapi readonly, atau skip jika masuk Data Umum
                        // Mari kita render sebagai input readonly untuk konteks
                        // continue; 
                    }

                    const value = existingValues[field.name] || '';
                    const isErr = !value && field.is_required; // Logic error sederhana
                    const errorClass = isErr ? 'border-orange-300 bg-orange-50' : 'border-slate-200';

                    let inputHtml = '';

                    // --- BUILDER INPUT BERDASARKAN TIPE ---

                    // Tipe A: SELECT (Dropdown)
                    if (field.type === 'select') {
                        let optionsHtml = `<option value="" disabled>-- Pilih --</option>`;

                        // Cek apakah ini dropdown API (Pangkat/Jabatan) atau Manual
                        let optionsData = [];

                        if (field.name === 'pangkat_dan_golongan') {
                            optionsData = await getMasterData('pangkat'); // Fetch API Pangkat
                        } else if (field.name === 'jabatan_pegawai_yang_diusulkan') {
                            optionsData = await getMasterData('jabatan'); // Fetch API Jabatan
                        } else if (field.options) {
                            // Dropdown statis dari database fields
                            optionsData = Array.isArray(field.options) ? field.options : field.options.split(',');
                        }

                        // Render Options
                        optionsData.forEach(opt => {
                            // Handle format data yang berbeda (String vs Object)
                            let optVal = (typeof opt === 'object') ? (opt.value || opt.nama) : opt;
                            let optLbl = (typeof opt === 'object') ? (opt.label || opt.nama) : opt;
                            // Trim whitespace agar matching value akurat
                            optVal = typeof optVal === 'string' ? optVal.trim() : optVal;

                            // Cek Selected (Logic matching value)
                            // Value dari DB mungkin URL file (jika salah tipe) atau string biasa
                            const isSelected = String(value).trim() === String(optVal) ? 'selected' : '';

                            optionsHtml += `<option value="${optVal}" ${isSelected}>${optLbl}</option>`;
                        });

                        inputHtml = `
                        <div class="relative">
                            <select name="${field.name}" class="w-full px-3 py-2.5 border ${errorClass} rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none bg-white">
                                ${optionsHtml}
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>`;
                    }

                    // Tipe B: FILE
                    else if (field.type === 'file') {
                        // Cek apakah value adalah URL (file sudah ada)
                        const hasFile = value && typeof value === 'string' && (value.includes('uploads') || value
                            .includes('storage'));

                        inputHtml = `
                        <div class="bg-slate-50 p-4 rounded-xl border ${hasFile ? 'border-emerald-200 bg-emerald-50/30' : errorClass}">
                            ${hasFile ? 
                                `<div class="mb-3 text-xs flex items-center gap-2 text-emerald-600 font-bold bg-white p-2 rounded border border-emerald-100">
                                        <i class="fas fa-check-circle"></i> File Tersimpan 
                                        <a href="${value.includes('http') ? value : '/storage/'+value}" target="_blank" class="underline ml-auto">Lihat</a>
                                     </div>` : 
                                `<div class="mb-2 text-xs text-orange-600 font-bold flex gap-1"><i class="fas fa-exclamation-circle"></i> File Wajib Diupload Ulang</div>`
                            }
                            <input type="file" name="${field.name}" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                            <p class="text-[10px] text-slate-400 mt-1">Format: PDF/JPG Max 2MB.</p>
                        </div>`;
                    }

                    // Tipe C: DATE
                    else if (field.type === 'date') {
                        inputHtml =
                            `<input type="date" name="${field.name}" value="${value}" class="w-full px-3 py-2.5 border ${errorClass} rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">`;
                    }

                    // Tipe D: TEXTAREA
                    else if (field.type === 'textarea') {
                        inputHtml =
                            `<textarea name="${field.name}" rows="3" class="w-full px-3 py-2.5 border ${errorClass} rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">${value}</textarea>`;
                    }

                    // Default: TEXT
                    else {
                        inputHtml =
                            `<input type="text" name="${field.name}" value="${value}" class="w-full px-3 py-2.5 border ${errorClass} rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">`;
                    }

                    // Append ke Container
                    container.innerHTML += `
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 flex justify-between">
                            ${field.label} 
                            ${isErr ? '<span class="text-orange-500 text-[10px] italic">Perlu diperbaiki</span>' : ''}
                        </label>
                        ${inputHtml}
                    </div>
                `;
                }

                // Show Content
                document.getElementById('editLoader').classList.add('hidden');
                document.getElementById('editContent').classList.remove('hidden');

            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Gagal memuat formulir perbaikan.', 'error');
                closeModal('editModal');
            }
        }

        // --- SUBMIT EDIT (Sama seperti sebelumnya) ---
        async function submitEdit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitEdit');
            const form = document.getElementById('editForm');
            const id = document.getElementById('editSubmissionId').value;
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

            try {
                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                const res = await fetch(`/user/pengajuan/${id}/update`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const result = await res.json();
                if (!res.ok) throw new Error(result.message || 'Gagal update.');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data revisi dikirim.',
                    timer: 1500,
                    showConfirmButton: false
                });
                setTimeout(() => location.reload(), 1500);

            } catch (err) {
                Swal.fire('Gagal', err.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    </script>
@endpush
