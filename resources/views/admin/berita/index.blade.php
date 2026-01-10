@extends('admin.layouts.app')
@section('title', 'Manajemen Berita')

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

        /* TABLE STYLES (Sama dengan Dashboard) */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            width: 100%;
        }

        .modern-table thead th {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748B;
            padding: 0 1.5rem;
            text-align: left;
            letter-spacing: 0.05em;
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
            border-color: #6366F1;
        }

        /* MODAL & FORM */
        .modal-backdrop {
            background-color: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border: 1px solid #E2E8F0;
            border-radius: 0.75rem;
            transition: all 0.2s;
            background-color: #F8FAFC;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366F1;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>

    <div class="min-h-screen py-8 px-4">

        {{-- HEADER --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Berita</h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">Publikasikan informasi terbaru untuk portal.</p>
            </div>

            <button onclick="openModal('create')"
                class="group bg-slate-900 hover:bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-slate-200 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-plus"></i> Buat Berita
            </button>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto pb-10 custom-scroll">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th width="15%">Thumbnail</th>
                        <th width="35%">Judul Berita</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Preview Konten</th>
                        <th width="10%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritas as $item)
                        <tr class="group">
                            <td class="w-32">
                                <div class="w-24 h-16 rounded-lg overflow-hidden border border-slate-200 shadow-sm relative">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div
                                            class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300 text-xs font-bold">
                                            No IMG</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div
                                    class="text-sm font-bold text-slate-800 line-clamp-2 leading-relaxed group-hover:text-indigo-600 transition-colors">
                                    {{ $item->judul }}
                                </div>
                            </td>
                            <td>
                                <div
                                    class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 w-fit">
                                    <i class="far fa-calendar-alt text-indigo-400"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                    {{ Str::limit(strip_tags($item->isi), 90) }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="openModal('edit', {{ $item->id }})"
                                        class="w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition flex items-center justify-center shadow-sm"
                                        title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $item->id }})"
                                        class="w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 transition flex items-center justify-center shadow-sm"
                                        title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="py-16 text-center text-slate-400 italic bg-slate-50/50 rounded-xl border-2 border-dashed border-slate-200">
                                Belum ada berita yang dipublikasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $beritas->links('pagination::tailwind') }}</div>
    </div>

    {{-- MODAL FORM BERITA (CREATE / EDIT) --}}
    <div id="beritaModal" class="fixed inset-0 z-[999] hidden" role="dialog">
        <div class="fixed inset-0 modal-backdrop transition-opacity" onclick="closeModal()"></div>
        <input type="hidden" id="update_route" value="{{ route('admin.berita.update', ':id') }}">
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col border border-slate-200 transform transition-all scale-95 opacity-0"
                id="modalPanel">

                {{-- Header --}}
                <div class="bg-white px-8 py-5 border-b border-slate-100 flex justify-between items-center z-20 shrink-0">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                            <i class="fas fa-pen-nib text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-900 tracking-tight" id="modalTitle">Tambah Berita
                            </h3>
                            <p class="text-xs text-slate-500 font-medium">Kelola konten publikasi.</p>
                        </div>
                    </div>
                    <button onclick="closeModal()"
                        class="w-9 h-9 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center transition"><i
                            class="fas fa-times"></i></button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto custom-scroll p-8 bg-white">
                    <form id="beritaForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="methodField"></div> {{-- Injection untuk Method PUT --}}

                        <div class="space-y-6">
                            {{-- Judul --}}
                            <div>
                                <label class="form-label">Judul Berita <span class="text-rose-500">*</span></label>
                                <input type="text" name="judul" id="inputJudul" class="form-input"
                                    placeholder="Masukkan judul berita..." required>
                            </div>

                            {{-- Tanggal & Gambar --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="form-label">Tanggal Publikasi <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal" id="inputTanggal" class="form-input" required>
                                </div>

                                <div>
                                    <label class="form-label">Gambar Cover</label>
                                    <div class="relative border border-slate-200 bg-slate-50 rounded-xl p-1 flex items-center gap-3 cursor-pointer hover:bg-slate-100 transition group"
                                        onclick="document.getElementById('inputGambar').click()">
                                        <input type="file" name="gambar" id="inputGambar" class="hidden" accept="image/*"
                                            onchange="previewImage(event)">

                                        <div
                                            class="w-12 h-12 rounded-lg bg-slate-200 overflow-hidden shrink-0 border border-slate-300 relative">
                                            <img id="previewImg" class="w-full h-full object-cover hidden">
                                            <div id="iconPlaceholder"
                                                class="absolute inset-0 flex items-center justify-center text-slate-400"><i
                                                    class="fas fa-image"></i></div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-bold text-slate-700">Upload Gambar</p>
                                            <p class="text-[10px] text-slate-500">Max 2MB (JPG/PNG)</p>
                                        </div>
                                        <div class="pr-3 text-slate-400 group-hover:text-indigo-500"><i
                                                class="fas fa-cloud-upload-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Isi Berita --}}
                            <div>
                                <label class="form-label">Isi Konten <span class="text-rose-500">*</span></label>
                                <textarea name="isi" id="inputIsi" rows="8" class="form-input resize-none leading-relaxed"
                                    placeholder="Tulis konten berita di sini..." required></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 px-8 py-5 border-t border-slate-200 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-bold text-sm hover:bg-white transition">Batal</button>
                    <button type="button" onclick="submitForm()"
                        class="px-8 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition active:scale-95 flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        // --- MODAL ANIMATION ---
        function toggleModal(show) {
            const modal = document.getElementById('beritaModal');
            const panel = document.getElementById('modalPanel');
            if (show) {
                modal.classList.remove('hidden'); document.body.style.overflow = 'hidden';
                setTimeout(() => { panel.classList.remove('scale-95', 'opacity-0'); panel.classList.add('scale-100', 'opacity-100'); }, 10);
            } else {
                panel.classList.remove('scale-100', 'opacity-100'); panel.classList.add('scale-95', 'opacity-0');
                setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
            }
        }
        function closeModal() { toggleModal(false); }

        // --- LOGIC BUKA MODAL (CREATE / EDIT) ---
        async function openModal(mode, id = null) {
            const form = document.getElementById('beritaForm');
            const title = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');
            const previewImg = document.getElementById('previewImg');
            const iconPlaceholder = document.getElementById('iconPlaceholder');

            // Reset UI
            form.reset();
            methodField.innerHTML = '';
            previewImg.src = '';
            previewImg.classList.add('hidden');
            iconPlaceholder.classList.remove('hidden');

            if (mode === 'create') {
                title.innerText = 'Tambah Berita Baru';
                // Pastikan route store benar
                form.action = "{{ route('admin.berita.store') }}";
                document.getElementById('inputTanggal').valueAsDate = new Date();
                toggleModal(true);
            }
            else if (mode === 'edit') {
                title.innerText = 'Edit Berita';

                // --- PERBAIKAN LOGIC ROUTE UPDATE ---
                // Ambil URL template
                let rawRoute = document.getElementById('update_route').value;
                // Decode agar %3Aid kembali menjadi :id (jika ter-encode oleh browser/laravel)
                let cleanRoute = decodeURIComponent(rawRoute);
                // Replace placeholder :id dengan ID data
                form.action = cleanRoute.replace(':id', id);
                // ------------------------------------

                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // Fetch Data Berita
                try {
                    const res = await fetch(`/admin/berita/${id}/json`);
                    if (!res.ok) throw new Error("Gagal ambil data");
                    const data = await res.json();

                    document.getElementById('inputJudul').value = data.judul;
                    document.getElementById('inputTanggal').value = data.tanggal;
                    document.getElementById('inputIsi').value = data.isi;

                    if (data.gambar_url) {
                        previewImg.src = data.gambar_url;
                        previewImg.classList.remove('hidden');
                        iconPlaceholder.classList.add('hidden');
                    }
                    toggleModal(true);
                } catch (e) {
                    console.error(e); // Debugging
                    Swal.fire('Error', 'Gagal memuat data berita.', 'error');
                }
            }
        }

        // --- HELPER FUNCTIONS ---
        function submitForm() {
            const form = document.getElementById('beritaForm');
            if (form.checkValidity()) {
                form.submit();
            } else {
                form.reportValidity();
            }
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('previewImg');
                const icon = document.getElementById('iconPlaceholder');
                output.src = reader.result;
                output.classList.remove('hidden');
                icon.classList.add('hidden');
            };
            if (event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Berita?', text: "Tindakan ini tidak dapat dibatalkan.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }

        // --- TOAST NOTIFICATION ---
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => { toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer; } });
                @if(session('success')) Toast.fire({ icon: 'success', title: "{{ session('success') }}" }); @endif
    </script>
@endsection