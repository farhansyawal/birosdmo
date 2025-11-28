@extends('admin.layouts.app')
@section('title', 'Manajemen Layanan')

@section('content')
    {{-- Assets Modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Tippy.js for Tooltips --}}
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css" />
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        /* Abstract Background Decoration */
        .decoration-blob {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
        }
        
        /* Card Modern Styling */
        .modern-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.08);
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    {{-- Background Blobs --}}
    <div class="fixed top-0 right-0 w-96 h-96 bg-blue-200 rounded-full decoration-blob -mr-20 -mt-20"></div>
    <div class="fixed bottom-0 left-0 w-80 h-80 bg-purple-200 rounded-full decoration-blob -ml-20 -mb-20"></div>

    <div class="relative w-full px-6 py-8 mx-auto z-10">

        {{-- 1. HEADER & ACTION BAR --}}
        <div class="flex flex-col lg:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <span class="text-xs font-bold tracking-widest text-indigo-600 uppercase bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                    Management Console
                </span>
                <h1 class="text-4xl font-extrabold text-slate-900 mt-4 tracking-tight">Pusat Layanan</h1>
                <p class="text-slate-500 mt-2 font-medium max-w-xl text-sm leading-relaxed">
                    Atur katalog layanan publik Anda. Pastikan setiap layanan memiliki deskripsi yang jelas.
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Total Count Badge --}}
                <div class="bg-white px-4 py-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-600">{{ count($layanan) }}</div>
                    </div>
                    <span class="text-xs font-bold text-slate-600">Total Item</span>
                </div>

                {{-- Add Button --}}
                <a href="{{ route('admin.layanan.create') }}" 
                   class="group relative inline-flex h-12 items-center justify-center overflow-hidden rounded-2xl bg-slate-900 px-8 font-medium text-white transition-all duration-300 hover:bg-indigo-600 hover:w-48 w-12 lg:w-auto shadow-lg hover:shadow-indigo-500/30">
                    <div class="absolute inset-0 flex h-full w-full justify-center [transform:skew(-12deg)_translateX(-100%)] group-hover:duration-1000 group-hover:[transform:skew(-12deg)_translateX(100%)]">
                        <div class="relative h-full w-8 bg-white/20"></div>
                    </div>
                    <span class="lg:hidden"><i class="fas fa-plus"></i></span>
                    <span class="hidden lg:flex items-center gap-2 relative">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                </a>
            </div>
        </div>

        {{-- 2. SEARCH & FILTER BAR --}}
        <div class="bg-white/70 backdrop-blur-md border border-white/50 rounded-2xl p-2 mb-8 flex flex-col sm:flex-row gap-3 shadow-sm">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchInput" placeholder="Cari nama layanan atau deskripsi..." 
                       class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium">
            </div>
            <div class="flex gap-2">
                <div class="px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div> {{ $layanan->where('is_active', 1)->count() }} Aktif
                </div>
                <div class="px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-slate-300"></div> {{ $layanan->where('is_active', 0)->count() }} Off
                </div>
            </div>
        </div>

        {{-- 3. LIST CARD CONTAINER --}}
        <div class="space-y-4">
            
            {{-- HEADERS --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                <div class="col-span-1 text-center">#</div>
                <div class="col-span-5">Informasi Layanan</div>
                <div class="col-span-2 text-center">Status</div>
                <div class="col-span-2 text-center">Tanggal</div>
                <div class="col-span-2 text-center">Aksi</div>
            </div>

            {{-- DATA ROWS --}}
            @forelse ($layanan as $index => $item)
                <div class="modern-card rounded-2xl p-4 md:p-0 search-item">
                    <div class="md:grid md:grid-cols-12 md:gap-4 md:items-center">
                        
                        {{-- 1. Index --}}
                        <div class="hidden md:block col-span-1 text-center font-bold text-slate-300 text-lg">
                            {{ $index + 1 }}
                        </div>

                        {{-- 2. Info Layanan --}}
                        <div class="col-span-12 md:col-span-5 py-2 md:py-4 px-2">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm group">
                                    <i class="fas fa-layer-group text-xl group-hover:scale-110 transition-transform"></i>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-slate-800 mb-1 item-name">{{ $item->nama }}</h4>
                                    <p class="text-xs text-slate-500 line-clamp-2 font-medium leading-relaxed">{{ $item->deskripsi }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Status Toggle (FIXED HTML STRUCTURE) --}}
                        <div class="col-span-6 md:col-span-2 flex items-center justify-start md:justify-center py-2 md:py-0 px-2 md:px-0 mt-3 md:mt-0">
                            <label class="relative inline-flex items-center cursor-pointer" data-tippy-content="Klik untuk ubah status">
                                <input type="checkbox" 
                                       onchange="toggleLayanan(this, '{{ $item->id }}')"
                                       {{ $item->is_active ? 'checked' : '' }} 
                                       class="sr-only peer">
                                
                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300/30 rounded-full peer 
                                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                            after:bg-white after:border-gray-300 after:border after:rounded-full 
                                            after:h-6 after:w-6 after:transition-all after:duration-300 
                                            peer-checked:bg-indigo-600 shadow-inner"></div>
                            </label>
                        </div>

                        {{-- 4. Tanggal Update --}}
                        <div class="col-span-6 md:col-span-2 text-left md:text-center py-2 md:py-0 px-2 md:px-0 mt-3 md:mt-0">
                            <div class="inline-flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Terakhir Update</span>
                                <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md mt-1">
                                    {{ $item->updated_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        {{-- 5. Actions --}}
                        <div class="col-span-12 md:col-span-2 flex items-center justify-end md:justify-center gap-2 py-4 md:py-0 px-4 md:px-0 border-t md:border-t-0 border-slate-100 mt-3 md:mt-0">
                            
                            <a href="{{ route('admin.form-input.index', $item->id) }}" 
                               class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all shadow-sm hover:shadow-indigo-200"
                               data-tippy-content="Config Form">
                                <i class="fas fa-sliders-h"></i>
                            </a>

                            <a href="{{ route('admin.layanan.edit', $item->id) }}" 
                               class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all shadow-sm hover:shadow-blue-200"
                               data-tippy-content="Edit Info">
                                <i class="fas fa-pen"></i>
                            </a>

                            <button onclick="hapusLayanan('{{ $item->id }}')" 
                                    class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm hover:shadow-rose-200"
                                    data-tippy-content="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-20 bg-white/60 backdrop-blur rounded-3xl border border-dashed border-slate-300">
                    <div class="w-24 h-24 bg-gradient-to-tr from-slate-100 to-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-slate-100">
                        <i class="fas fa-inbox text-slate-300 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800">Belum Ada Layanan</h3>
                    <p class="text-slate-500 mt-2 text-sm max-w-xs mx-auto">Mulai tambahkan layanan publik untuk ditampilkan kepada pengguna.</p>
                    <a href="{{ route('admin.layanan.create') }}" class="mt-6 inline-block text-indigo-600 font-bold text-sm hover:underline decoration-2 underline-offset-4">
                        + Buat Layanan Pertama
                    </a>
                </div>
            @endforelse

        </div>
    </div>

    @push('scripts')
        {{-- Load Libraries --}}
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>

        <script>
            // Init Tippy
            tippy('[data-tippy-content]', {
                animation: 'scale',
                theme: 'light',
                content: (reference) => reference.getAttribute('data-tippy-content'),
            });

            // Real-time Search Filter
            document.getElementById('searchInput').addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let cards = document.querySelectorAll('.search-item');
                
                cards.forEach(card => {
                    let text = card.querySelector('.item-name').innerText.toLowerCase();
                    if(text.includes(filter)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Logic Hapus
            function hapusLayanan(id) {
                if (typeof confirmAction === 'function') {
                    confirmAction("Hapus layanan ini secara permanen?", () => executeDelete(id));
                } else {
                    if(confirm("Yakin hapus?")) executeDelete(id);
                }
            }

            function executeDelete(id) {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch(`/admin/layanan/${id}/delete`, {
                    method: "DELETE", headers: { "X-CSRF-TOKEN": token, "Accept": "application/json" }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        showToast('success', res.message);
                        setTimeout(() => location.reload(), 800);
                    } else {
                        showToast('error', res.message);
                    }
                })
                .catch(() => showToast('error', "Gagal menghapus data!"));
            }

            // Logic Toggle (FIXED)
            function toggleLayanan(checkbox, id) {
                // Simpan state awal untuk antisipasi error
                const previousState = !checkbox.checked;
                const token = document.querySelector('meta[name="csrf-token"]')?.content;

                fetch(`/admin/layanan/${id}/toggle`, {
                    method: "POST", headers: { "X-CSRF-TOKEN": token, "Accept": "application/json" }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        // SUKSES: Tidak perlu ubah visual (sudah otomatis berubah saat klik)
                        // Cukup tampilkan notifikasi
                        showToast('success', res.message);
                    } else {
                        // GAGAL: Kembalikan ke posisi semula
                        checkbox.checked = previousState;
                        showToast('error', res.message);
                    }
                })
                .catch(() => {
                    // ERROR: Kembalikan ke posisi semula
                    checkbox.checked = previousState;
                    showToast('error', 'Terjadi kesalahan koneksi');
                });
            }
        </script>
    @endpush
@endsection