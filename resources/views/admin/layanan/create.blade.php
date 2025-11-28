@extends('admin.layouts.app')
@section('title', 'Tambah Layanan Baru')

@section('content')
    {{-- Assets Modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        /* Card Modern Styling */
        .modern-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 1rem;
        }

        /* Custom Input Focus */
        .form-input-modern:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            outline: none;
        }
    </style>

    <div class="w-full px-6 py-8 mx-auto">

        {{-- 1. HEADER NAVIGATION --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.layanan.index') }}" 
                   class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all shadow-sm group">
                    <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Layanan</h1>
                    <p class="text-slate-500 text-sm font-medium">Buat layanan publik baru untuk ditampilkan ke pengguna.</p>
                </div>
            </div>
            
            {{-- Breadcrumb Badge --}}
            <div class="px-4 py-2 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                <span class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Form Pembuatan
                </span>
            </div>
        </div>

        {{-- 2. FORM CARD --}}
        <div class="modern-card p-8 max-w-4xl mx-auto relative overflow-hidden">
            {{-- Decoration --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>

            <form action="{{ route('admin.layanan.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    
                    {{-- LEFT SIDE: Inputs Utama --}}
                    <div class="md:col-span-8 space-y-6">
                        
                        {{-- Input Nama --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Nama Layanan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-heading text-slate-400"></i>
                                </div>
                                <input type="text" name="nama" value="{{ old('nama') }}"
                                    class="form-input-modern w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 transition-all @error('nama') border-rose-300 bg-rose-50 text-rose-900 focus:border-rose-500 focus:ring-rose-200 @enderror"
                                    placeholder="Contoh: Layanan Legalisir Ijazah" required>
                            </div>
                            @error('nama')
                                <p class="mt-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Input Deskripsi --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Deskripsi Singkat <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea name="deskripsi" rows="5"
                                    class="form-input-modern w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 transition-all leading-relaxed @error('deskripsi') border-rose-300 bg-rose-50 @enderror"
                                    placeholder="Jelaskan tujuan dan cakupan layanan ini agar mudah dipahami pengguna..." required>{{ old('deskripsi') }}</textarea>
                            </div>
                            @error('deskripsi')
                                <p class="mt-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-xs text-slate-400 text-right">Disarankan maksimal 255 karakter.</p>
                        </div>

                    </div>

                    {{-- RIGHT SIDE: Settings / Status --}}
                    <div class="md:col-span-4 flex flex-col gap-6">
                        
                        {{-- Card Status --}}
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <h6 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Status Awal</h6>
                            
                            <label class="relative flex items-start cursor-pointer group">
                                <div class="flex items-center h-6">
                                    {{-- Checkbox is_active --}}
                                    <input type="checkbox" name="is_active" value="1" 
                                           class="peer sr-only" checked> {{-- Default Checked saat Create --}}
                                    
                                    {{-- Custom Toggle UI --}}
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer 
                                                peer-checked:after:translate-x-full peer-checked:after:border-white 
                                                after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                                after:bg-white after:border-gray-300 after:border after:rounded-full 
                                                after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Langsung Publikasikan</span>
                                    <p class="text-slate-500 text-xs mt-1">Layanan akan langsung terlihat oleh user setelah disimpan.</p>
                                </div>
                            </label>
                        </div>

                        {{-- Info Card --}}
                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                                <div>
                                    <h6 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-1">Tips Admin</h6>
                                    <p class="text-xs text-blue-600/80 leading-relaxed">
                                        Setelah layanan dibuat, Anda dapat menambahkan konfigurasi <b>Form Input</b> melalui tombol setting di halaman daftar layanan.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.layanan.index') }}" 
                       class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-slate-900 rounded-xl hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                        <i class="fas fa-save mr-2"></i> Simpan Layanan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection