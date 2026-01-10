@extends('admin.layouts.app')
@section('title', 'Konfigurasi Form')

@section('content')
    {{-- Assets Modern (Font: Plus Jakarta Sans) --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        /* Modern Card */
        .modern-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        /* Input Styling */
        .form-input-modern:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            outline: none;
        }

        /* Item List Hover */
        .list-item-hover {
            transition: all 0.2s ease;
        }
        .list-item-hover:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            transform: translateX(4px);
        }
        
        /* Actions Visibility */
        .list-actions {
            opacity: 0;
            transition: opacity 0.2s;
        }
        .list-item-hover:hover .list-actions {
            opacity: 1;
        }
    </style>

    <div class="w-full px-6 py-8 mx-auto">

        {{-- 1. HEADER SECTION --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.layanan.index') }}"
                        class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <span
                        class="text-xs font-bold tracking-widest text-indigo-600 uppercase bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                        Form Builder
                    </span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $layanan->nama }}</h1>
                <p class="text-slate-500 mt-2 font-medium text-sm">
                    Atur kolom formulir yang perlu diisi oleh pemohon layanan ini.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-600">{{ $inputs->total() }} Field Aktif</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- 2. FORM ADD FIELD (LEFT COLUMN) --}}
            <div class="lg:col-span-1">
                <div class="modern-card p-6 sticky top-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fas fa-plus-circle text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg">Tambah Field</h3>
                    </div>

                    <form action="{{ route('admin.form-input.store', $layanan->id) }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Label --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Label Field</label>
                            <input type="text" name="label" placeholder="Contoh: Nama Lengkap" required
                                class="form-input-modern w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 transition-all">
                        </div>

                        {{-- Name (Slug) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Variable Name
                                (Slug)</label>
                            <input type="text" name="name" placeholder="nama_lengkap" required
                                class="form-input-modern w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-600 placeholder-slate-400 transition-all">
                            <p class="text-[10px] text-slate-400 mt-1">*Gunakan huruf kecil & underscore (_)</p>
                        </div>

                        {{-- Type --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tipe Input</label>
                            <div class="relative">
                                <select name="type" required
                                    class="form-input-modern w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 appearance-none cursor-pointer">
                                    <option value="text">Text (Singkat)</option>
                                    <option value="textarea">Textarea (Panjang)</option>
                                    <option value="number">Number (Angka)</option>
                                    <option value="month">Month (Bulan)</option>
                                    <option value="year">Year (Tahun)</option>
                                    <option value="email">Email</option>
                                    <option value="date">Date (Tanggal)</option>
                                    <option value="select">Select (Dropdown)</option>
                                    <option value="file">File Upload (PDF/Img)</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Options (Conditional) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Opsi (Khusus
                                Select)</label>
                            <input type="text" name="options" placeholder="Pria,Wanita (Pisahkan koma)"
                                class="form-input-modern w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 transition-all">
                        </div>

                        {{-- Required Checkbox --}}
                        <label
                            class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                            <input type="checkbox" name="is_required" value="1"
                                class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="text-sm font-bold text-slate-700">Wajib Diisi (Required)</span>
                        </label>

                        <button type="submit" class="w-full py-3 text-white font-bold rounded-xl transition-all shadow-lg 
                       !bg-slate-900 hover:bg-slate-800 hover:shadow-slate-900/40 active:scale-95 
                       flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Simpan Field
                        </button>
                    </form>
                </div>
            </div>

            {{-- 3. LIST FIELD (RIGHT COLUMN) --}}
            <div class="lg:col-span-2">
                <div class="modern-card p-0 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 text-lg">Daftar Field Form</h3>
                        <div class="text-xs font-bold text-slate-500 bg-white px-3 py-1 rounded-lg shadow-sm">
                            Urutan Otomatis
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        @forelse($inputs as $input)
                            <div
                                class="list-item-hover group flex items-center justify-between p-4 bg-white border border-slate-200 rounded-xl transition-all duration-200">

                                {{-- Field Info --}}
                                <div class="flex items-start gap-4">
                                    
                                    {{-- 🔥 QUEUE NUMBER --}}
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0 border border-slate-200 font-bold text-sm">
                                        #{{ $input->queue }}
                                    </div>

                                    <div class="flex flex-col">
                                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                            {{ $input->label }}
                                            @if($input->is_required)
                                                <span class="text-rose-500 text-xs" title="Required">*</span>
                                            @endif
                                        </h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[10px] font-mono text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">
                                                name="{{ $input->name }}"
                                            </span>
                                            @if($input->options)
                                                <span
                                                    class="text-[10px] text-slate-400 bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded border border-amber-100">
                                                    {{ Str::limit($input->options, 20) }}
                                                </span>
                                            @endif
                                            <span class="text-[10px] text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100">
                                                {{ ucfirst($input->type) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div
                                    class="flex items-center gap-2 list-actions sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                    
                                    <div
                                        class="cursor-grab w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 hover:text-slate-600 transition-all" title="Geser untuk urutkan (Coming Soon)">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>

                                    <form action="{{ route('admin.form-input.destroy', $input->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus field ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @empty
                            {{-- EMPTY STATE --}}
                            <div class="flex flex-col items-center justify-center p-12 bg-white border-2 border-dashed border-slate-200 rounded-xl text-center opacity-75">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium text-sm">Belum ada field formulir.</p>
                                <p class="text-slate-400 text-xs mt-1">Gunakan panel di sebelah kiri untuk menambahkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $inputs->links('pagination::tailwind') }}
                </div>
            </div>

        </div>
    </div>
@endsection