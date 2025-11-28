@extends('admin.layouts.app')
@section('title', 'Edit Layanan')

@section('content')
    {{-- Assets Modern (Sama seperti Index) --}}
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
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Layanan</h1>
                    <p class="text-slate-500 text-sm font-medium">Perbarui detail informasi layanan publik.</p>
                </div>
            </div>
            
            {{-- Status Badge (Visual Only) --}}
            <div class="px-4 py-2 rounded-lg {{ $layanan->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                <span class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full {{ $layanan->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></div>
                    Status Saat Ini: {{ $layanan->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                </span>
            </div>
        </div>

        {{-- 2. FORM CARD --}}
        <div class="modern-card p-8 max-w-4xl mx-auto relative overflow-hidden">
            {{-- Decoration --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>

            <form action="{{ route('admin.layanan.update', $layanan->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- PENTING: Sesuai Controller update() --}}

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    
                    {{-- LEFT SIDE: Inputs --}}
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
                                <input type="text" name="nama" value="{{ old('nama', $layanan->nama) }}"
                                    class="form-input-modern w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 transition-all @error('nama') border-rose-300 bg-rose-50 text-rose-900 focus:border-rose-500 focus:ring-rose-200 @enderror"
                                    placeholder="Contoh: Layanan Pengaduan Masyarakat" required>
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
                                    placeholder="Jelaskan tujuan dan cakupan layanan ini agar mudah dipahami pengguna..." required>{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                            </div>
                            @error('deskripsi')
                                <p class="mt-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-xs text-slate-400 text-right">Disarankan maksimal 255 karakter.</p>
                        </div>

                    </div>

                    {{-- RIGHT SIDE: Settings --}}
                    <div class="md:col-span-4 flex flex-col gap-6">
                        
                        {{-- Card Status --}}
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <h6 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Visibilitas</h6>
                            
                            <label class="relative flex items-start cursor-pointer group">
                                <div class="flex items-center h-6">
                                    {{-- Checkbox is_active (Value 1 jika dicentang) --}}
                                    <input type="checkbox" name="is_active" value="1" 
                                           class="peer sr-only"
                                           {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}>
                                    
                                    {{-- Custom Toggle UI --}}
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer 
                                                peer-checked:after:translate-x-full peer-checked:after:border-white 
                                                after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                                after:bg-white after:border-gray-300 after:border after:rounded-full 
                                                after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Publikasikan Layanan</span>
                                    <p class="text-slate-500 text-xs mt-1">Jika dimatikan, layanan ini akan disembunyikan dari user.</p>
                                </div>
                            </label>
                        </div>

                        {{-- Info Metadata --}}
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 flex-1">
                            <h6 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Metadata</h6>
                            <ul class="space-y-3">
                                <li class="text-xs flex justify-between">
                                    <span class="text-slate-500">Dibuat pada:</span>
                                    <span class="font-bold text-slate-700">{{ $layanan->created_at->format('d M Y') }}</span>
                                </li>
                                <li class="text-xs flex justify-between">
                                    <span class="text-slate-500">Terakhir update:</span>
                                    <span class="font-bold text-slate-700">{{ $layanan->updated_at->format('d M Y') }}</span>
                                </li>
                            </ul>
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
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection