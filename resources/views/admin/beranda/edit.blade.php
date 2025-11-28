@extends('admin.layouts.app')
@section('title', 'Edit Beranda')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .upload-zone {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='12' ry='12' stroke='%23CBD5E1FF' stroke-width='2' stroke-dasharray='8%2c 8' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            transition: all 0.2s ease;
        }

        .upload-zone:hover {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='12' ry='12' stroke='%2394A3B8FF' stroke-width='2' stroke-dasharray='8%2c 8' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            background-color: #F8FAFC;
        }
    </style>

    <div class="bg-slate-50 min-h-screen pb-20">

        {{-- HAPUS 'sticky', 'top-2', dan 'z-20' agar header diam dan tidak melayang --}}
        <div class="mb-6">
            <div
                class="bg-white border border-slate-200 shadow-soft-xl rounded-2xl px-6 py-4 flex justify-between items-center">

                {{-- Bagian Kiri: Ikon & Judul --}}
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        KLH
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 leading-tight">Beranda</h1>
                        <p class="text-xs text-slate-500 font-medium">Site Configuration</p>
                    </div>
                </div>

                {{-- Bagian Kanan: Tombol Aksi --}}
                <div class="flex gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-4 py-2 text-xs font-bold text-slate-500 uppercase transition-all ease-in bg-transparent border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-slate-700">
                        Batalkan
                    </a>
                    <button type="submit" form="main-form"
                        class="px-6 py-2 text-xs font-bold text-slate-500 uppercase transition-all ease-in bg-transparent border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-slate-700">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-10">
            <form id="main-form" action="{{ route('admin.beranda.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-12">
                @csrf

                <div class="md:grid md:grid-cols-12 md:gap-10">
                    <div class="md:col-span-4 space-y-2">
                        <h3 class="text-lg font-semibold text-slate-900">Hero Section</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Area ini adalah "wajah" website Anda. Gunakan copywriting yang kuat untuk menarik perhatian
                            dalam 3 detik pertama.
                        </p>
                    </div>
                    <div class="md:col-span-8">
                        <div
                            class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-100 p-6 space-y-6">
                            <div class="grid grid-cols-1 gap-6">
                                <div class="group">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Utama</label>
                                    <input type="text" name="hero_title" value="{{ $data->hero_title }}"
                                        class="input input-bordered w-full bg-slate-50 border-slate-200 focus:bg-white focus:border-slate-500 focus:ring-0 rounded-xl transition-all font-medium text-slate-800 placeholder:text-slate-400"
                                        placeholder="Contoh: Digital Transformation Agency" />
                                </div>
                                <div class="group">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sub Judul</label>
                                    <input type="text" name="hero_subtitle" value="{{ $data->hero_subtitle }}"
                                        class="input input-bordered w-full bg-slate-50 border-slate-200 focus:bg-white focus:border-slate-500 focus:ring-0 rounded-xl transition-all text-slate-600" />
                                </div>
                                <div class="group">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Big Text
                                        (Highlight)</label>
                                    <div class="relative">
                                        <input type="text" name="hero_bigtext" value="{{ $data->hero_bigtext }}"
                                            class="input input-bordered w-full bg-slate-50 border-slate-200 focus:bg-white focus:border-slate-500 focus:ring-0 rounded-xl transition-all pl-4 pr-20 font-bold tracking-wide" />
                                        <span
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 border border-slate-200 px-2 py-1 rounded">BOLD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200/60">

                <div class="md:grid md:grid-cols-12 md:gap-10">
                    <div class="md:col-span-4 space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Slider Manager</h3>
                            <p class="text-sm text-slate-500 leading-relaxed mt-2">
                                Atur gambar latar belakang. Pengunjung lebih suka gambar visual daripada teks panjang.
                            </p>
                        </div>
                        <button type="button" id="addSliderBtn"
                            class="btn btn-outline btn-sm w-full border-dashed border-slate-300 hover:border-slate-900 hover:bg-slate-50 text-slate-600 normal-case gap-2 h-12 rounded-xl group transition-all">
                            <span
                                class="w-6 h-6 rounded-full bg-slate-100 group-hover:bg-slate-900 group-hover:text-white flex items-center justify-center transition-colors">+</span>
                            Tambah Slide Baru
                        </button>
                    </div>
                    <div class="md:col-span-8">
                        <div id="sliderList" class="space-y-3">
                            @php $sliders = $data->slider_items ?? []; @endphp
                            @foreach ($sliders as $sl)
                                <div
                                    class="slider-card bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex gap-4 items-center group hover:border-slate-300 transition-all cursor-move">
                                    <div class="text-slate-300 cursor-grab hover:text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 8h16M4 16h16" />
                                        </svg>
                                    </div>
                                    <div
                                        class="w-20 h-16 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0 relative border border-slate-100">
                                        <img src="{{ asset('storage/' . $sl['image']) }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 grid grid-cols-2 gap-3">
                                        <div class="relative">
                                            <input type="file" name="slider_files[]"
                                                class="absolute inset-0 opacity-0 cursor-pointer z-10 w-full"
                                                onchange="previewSlider(this)" />
                                            <div
                                                class="input input-sm input-bordered w-full rounded-lg bg-slate-50 flex items-center text-xs text-slate-500">
                                                Ganti Gambar...</div>
                                            <input type="hidden" name="slider_images[]" value="{{ $sl['image'] }}">
                                        </div>
                                        <input type="text" name="slider_links[]" value="{{ $sl['link'] ?? '' }}"
                                            placeholder="Link URL (Opsional)"
                                            class="input input-sm input-bordered w-full rounded-lg focus:ring-0 text-sm">
                                    </div>
                                    <button type="button"
                                        class="remove-btn btn btn-sm btn-square btn-ghost text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        @if(empty($sliders))
                            <div id="emptySliderState"
                                class="text-center py-12 bg-white border border-dashed border-slate-200 rounded-xl">
                                <p class="text-sm text-slate-400">Belum ada slide. Tambahkan gambar di panel kiri.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="border-slate-200/60">

                <div class="md:grid md:grid-cols-12 md:gap-10">
                    <div class="md:col-span-4 space-y-2">
                        <h3 class="text-lg font-semibold text-slate-900"></h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Lapor, Layanan Pengaduan, dan Survey Kepuasan Layanan
                        </p>
                    </div>
                    <div class="md:col-span-8">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            @foreach ([1, 2, 3] as $num)
                                <div class="relative group">
                                    <input type="file" name="item_image_{{ $num }}" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer"
                                        onchange="previewFile(event, 'item{{ $num }}Preview')" />

                                    <div
                                        class="upload-zone h-48 w-full rounded-2xl flex flex-col items-center justify-center text-center p-4 bg-slate-50 overflow-hidden relative border border-transparent">
                                        <img id="item{{ $num }}Preview"
                                            src="{{ $data->{'item_image_' . $num} ? asset('storage/' . $data->{'item_image_' . $num}) : '' }}"
                                            class="{{ $data->{'item_image_' . $num} ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover z-10" />

                                        <div class="z-0 space-y-2 group-hover:scale-105 transition-transform duration-300">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center mx-auto text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-5 4h1m-1-4h1m-1 0a2 2 0 11-4 0m0 0a2 2 0 01-4 0m4 0V4" />
                                                </svg>
                                            </div>
                                            <p class="text-xs font-semibold text-slate-500">Gambar {{ $num }}</p>
                                            <p class="text-[10px] text-slate-400">Klik untuk upload</p>
                                        </div>

                                        <div
                                            class="absolute inset-0 bg-black/40 z-20 hidden group-hover:flex items-center justify-center text-white text-xs font-medium backdrop-blur-[2px] transition-all">
                                            Ubah Gambar
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200/60">

                <div class="md:grid md:grid-cols-12 md:gap-10">
                    <div class="md:col-span-4 space-y-2">
                        <h3 class="text-lg font-semibold text-slate-900">Tentang Biro</h3>
                    </div>
                    <div class="md:col-span-8">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Section</label>
                                <input type="text" name="about_title" value="{{ $data->about_title }}"
                                    class="input input-bordered w-full bg-slate-50 border-slate-200 focus:bg-white focus:border-slate-500 rounded-xl" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                                <textarea name="about_description" rows="5"
                                    class="textarea textarea-bordered w-full bg-slate-50 border-slate-200 focus:bg-white focus:border-slate-500 rounded-xl leading-relaxed">{{ $data->about_description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:grid md:grid-cols-12 md:gap-10">
                    <div class="md:col-span-4 space-y-2">
                        <h3 class="text-lg font-semibold text-slate-900">Survey Banner</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Banner horizontal penuh.</p>
                    </div>
                    <div class="md:col-span-8">
                        <div class="relative group w-full h-56 rounded-2xl overflow-hidden border border-slate-200">
                            <input type="file" name="survey_image" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer"
                                onchange="previewFile(event, 'surveyPreview')" />
                            <img id="surveyPreview"
                                src="{{ $data->survey_image ? asset('storage/' . $data->survey_image) : '' }}"
                                class="{{ $data->survey_image ? '' : 'hidden' }} w-full h-full object-cover z-10 absolute inset-0" />

                            <div
                                class="w-full h-full bg-slate-50 flex flex-col items-center justify-center z-0 upload-zone">
                                <div class="bg-white p-3 rounded-full shadow-md mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-600">Klik untuk upload banner</span>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Preview logic for standard inputs and upload zones
        function previewFile(e, id) {
            const file = e.target.files[0];
            if (!file) return;
            const img = document.getElementById(id);
            img.src = URL.createObjectURL(file);
            img.classList.remove('hidden');
        }

        // Preview logic for slider rows
        function previewSlider(input) {
            const file = input.files[0];
            if (!file) return;
            const img = input.closest('.slider-card').querySelector('img');
            img.src = URL.createObjectURL(file);
        }

        // Sortable JS Init
        new Sortable(document.getElementById("sliderList"), {
            animation: 200,
            ghostClass: 'bg-slate-50',
            handle: '.slider-card' // Entire card is draggable, or change to handle icon
        });

        // Add Slider Row
        document.getElementById("addSliderBtn").addEventListener("click", () => {
            const container = document.getElementById("sliderList");
            const div = document.createElement("div");
            div.className = "slider-card bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex gap-4 items-center group hover:border-slate-300 transition-all cursor-move animate-fadeIn";
            div.innerHTML = `
                        <div class="text-slate-300 cursor-grab hover:text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                        </div>
                        <div class="w-20 h-16 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0 relative border border-slate-100 flex items-center justify-center">
                            <img class="w-full h-full object-cover hidden">
                            <span class="text-[10px] text-slate-400">Preview</span>
                        </div>
                        <div class="flex-1 grid grid-cols-2 gap-3">
                            <div class="relative">
                                <input type="file" name="slider_files[]" class="absolute inset-0 opacity-0 cursor-pointer z-10 w-full" onchange="previewSlider(this)" required />
                                <div class="input input-sm input-bordered w-full rounded-lg bg-slate-50 flex items-center text-xs text-slate-500 border-dashed border-slate-300">Pilih File...</div>
                                <input type="hidden" name="slider_images[]" value="" />
                            </div>
                            <input type="text" name="slider_links[]" placeholder="Link URL" class="input input-sm input-bordered w-full rounded-lg focus:ring-0 text-sm">
                        </div>
                        <button type="button" class="remove-btn btn btn-sm btn-square btn-ghost text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    `;
            container.appendChild(div);
            const emptyState = document.getElementById('emptySliderState');
            if (emptyState) emptyState.style.display = 'none';
        });

        document.getElementById("sliderList").addEventListener("click", e => {
            if (e.target.closest(".remove-btn")) {
                e.target.closest(".slider-card").remove();
            }
        });
    </script>
@endsection