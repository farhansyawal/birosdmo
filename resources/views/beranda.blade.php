@extends('layouts.app')

@section('content')

    {{-- LIBRARY & CUSTOM FONTS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Font Pairing Premium */
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFFFF;
            color: #334155;
            overflow-x: hidden;
        }

        .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* BRAND COLORS */
        .text-teal-brand {
            color: #0F766E;
        }

        .text-orange-brand {
            color: #F97316;
        }

        .bg-gradient-brand {
            background: linear-gradient(135deg, #0F766E 0%, #0284C7 100%);
        }

        /* TYPEWRITER CURSOR STYLE */
        .typed-cursor {
            color: #F97316;
            /* Orange Brand */
            font-size: 1em;
            font-weight: 300;
        }

        /* UI ENHANCEMENTS */
        .hover-lift-glow {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift-glow:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(15, 118, 110, 0.15);
        }

        .img-zoom {
            overflow: hidden;
        }

        .img-zoom img {
            transition: transform 0.8s ease-in-out;
        }

        .group:hover .img-zoom img {
            transform: scale(1.06);
        }

        /* WAVE DIVIDER SVG */
        .wave-divider {
            position: absolute;
            bottom: -1px;
            /* Fix gap garis tipis */
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
            z-index: 20;
        }

        .wave-divider svg {
            position: relative;
            display: block;
            width: calc(138% + 1.3px);
            height: 80px;
        }

        @media (min-width: 768px) {
            .wave-divider svg {
                height: 120px;
            }
        }
    </style>

    @php
        use App\Models\BerandaSetting;
        $s = BerandaSetting::first();
        $heroSliders = $s->slider_items ?? [];
        $heroTitleText = $s->hero_bigtext ?? 'ZONA INTEGRITAS'; // Teks untuk Typewriter

        $images = [];
        if ($s) {
            if ($s->item_image_1)
                $images[] = ['image' => $s->item_image_1, 'url' => 'https://www.lapor.go.id/', 'label' => 'Layanan Digital', 'desc' => 'Platform terintegrasi untuk administrasi kepegawaian ASN.'];
            if ($s->item_image_2)
                $images[] = ['image' => $s->item_image_2, 'url' => '#', 'label' => 'Pusat Pengaduan', 'desc' => 'Kanal resmi penyampaian aspirasi dan laporan pelanggaran.'];
            if ($s->item_image_3)
                $images[] = ['image' => $s->item_image_3, 'url' => '#', 'label' => 'Portal Data SDM', 'desc' => 'Transparansi data statistik organisasi dan aparatur.'];
        }
        $dummyImg = 'https://source.unsplash.com/random/1200x600?office,modern';
    @endphp

    {{-- ========================================================= --}}
    {{-- ==================== 1. HERO SECTION ==================== --}}
    {{-- ========================================================= --}}
    <section class="relative w-full h-[85vh] min-h-[600px] overflow-hidden bg-teal-900">

        {{-- Swiper Slider --}}
        <div class="swiper heroSwiper w-full h-full z-0 absolute inset-0">
            <div class="swiper-wrapper">
                @if(is_array($heroSliders) && count($heroSliders) > 0)
                    @foreach($heroSliders as $sl)
                        <div class="swiper-slide w-full h-full relative">
                            {{-- Overlay Gradient: Teal ke Biru Gelap --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-teal-950/95 via-teal-900/70 to-transparent z-10">
                            </div>
                            <img src="{{ asset('storage/' . ($sl['image'] ?? '')) }}"
                                class="w-full h-full object-cover mix-blend-overlay opacity-60" alt="Hero">
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide w-full h-full relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-teal-950/95 via-teal-900/70 to-transparent z-10">
                        </div>
                        <img src="{{ asset('img/bg-hero.jpg') }}"
                            class="w-full h-full object-cover mix-blend-overlay opacity-60">
                    </div>
                @endif
            </div>
        </div>

        {{-- Hero Content --}}
        <div class="absolute inset-0 z-20 flex items-center px-6 md:px-16 container mx-auto">
            <div class="max-w-4xl pt-16" data-aos="fade-right" data-aos-duration="1000">

                {{-- Badge Organik --}}
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-8 shadow-lg">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                    </span>
                    <span class="text-white text-xs font-bold tracking-widest uppercase">Kementerian Lingkungan Hidup</span>
                </div>

                {{-- TYPEWRITER HEADLINE --}}
                <div class="min-h-[80px] md:min-h-[100px] mb-6 flex items-center">
                    <h1 class="font-heading font-bold text-5xl md:text-7xl text-white leading-tight drop-shadow-xl">
                        <span id="typed-hero"></span>
                    </h1>
                </div>

                <p
                    class="text-teal-50 text-lg md:text-2xl font-light mb-12 max-w-2xl leading-relaxed border-l-4 border-orange-brand pl-6">
                    {{ $s->hero_subtitle ?? 'Mewujudkan Biro Sumber Daya Manusia dan Organisasi yang Bersih, Efektif, dan Melayani.' }}
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="#info"
                        class="group relative px-8 py-4 bg-white text-teal-900 font-bold rounded-xl overflow-hidden transition-all hover:shadow-xl hover:scale-105">
                        <span class="relative z-10 flex items-center gap-2">
                            Jelajahi Layanan
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 transition-transform group-hover:translate-x-1 text-orange-brand" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        {{-- WAVE DIVIDER (Transisi Halus ke Putih) --}}
        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    fill="#FFFFFF"></path>
            </svg>
        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- =========== 2. INFO CARDS (LANDSCAPE FLOATING) ========== --}}
    {{-- ========================================================= --}}
    <section id="info" class="py-24 bg-white relative z-10">
        <div class="container mx-auto px-6 md:px-16">

            {{-- Section Header --}}
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-orange-brand font-bold tracking-widest text-sm uppercase mb-3 block">Akses Cepat</span>
                <h2 class="font-heading text-4xl md:text-5xl font-bold text-teal-900 mb-4">
                    Layanan & <span class="text-gradient-brand">Informasi Publik</span>
                </h2>
                <div class="w-24 h-1.5 bg-gradient-brand mx-auto rounded-full opacity-50"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @foreach ($images as $index => $item)
                    @php $imgSrc = !empty($item['image']) ? asset('storage/' . $item['image']) : $dummyImg; @endphp

                    <a href="{{ $item['url'] }}"
                        class="group block bg-white rounded-[2rem] overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover-lift-glow border border-slate-100"
                        data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">

                        {{-- LANDSCAPE IMAGE (16:9) --}}
                        <div class="img-zoom relative w-full aspect-[16/9]">
                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover" alt="{{ $item['label'] }}">
                            {{-- Overlay Halus saat Hover --}}
                            <div class="absolute inset-0 bg-teal-900/0 group-hover:bg-teal-900/10 transition-all duration-500">
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-8 relative">
                            <h3
                                class="font-heading text-2xl font-bold text-teal-900 mb-3 group-hover:text-teal-700 transition-colors">
                                {{ $item['label'] ?? 'Informasi' }}
                            </h3>
                            <p class="text-slate-500 text-lg mb-8 leading-relaxed">
                                {{ $item['desc'] }}
                            </p>

                            <div class="flex justify-between items-center">
                                <span
                                    class="text-sm font-bold text-teal-700 uppercase tracking-wider group-hover:text-orange-brand transition-colors">Akses
                                    Sekarang</span>
                                <div
                                    class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center text-teal-700 group-hover:bg-orange-brand group-hover:text-white transition-all duration-300 transform group-hover:rotate-45 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- ================= 3. TENTANG BIRO (MODERN) ============== --}}
    {{-- ========================================================= --}}
    <section class="py-24 bg-teal-50 relative overflow-hidden">
        {{-- Background Blob Decoration --}}
        <div
            class="absolute top-0 right-0 -mr-32 -mt-32 w-[600px] h-[600px] bg-teal-200/40 rounded-full blur-3xl mix-blend-multiply">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-32 -mb-32 w-[500px] h-[500px] bg-blue-200/40 rounded-full blur-3xl mix-blend-multiply">
        </div>

        <div class="container mx-auto px-6 md:px-16 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-20">

                {{-- Text Content --}}
                <div class="lg:w-1/2" data-aos="fade-right">
                    <h4 class="text-teal-700 font-bold uppercase tracking-widest text-sm mb-4">Profil Biro</h4>
                    <h2 class="font-heading text-4xl md:text-5xl font-bold text-teal-900 mb-8 leading-tight">
                        {{ $s->about_title ?? 'Profesionalisme & Integritas dalam Pelayanan' }}
                    </h2>
                    <div class="prose prose-lg text-slate-600 mb-10 leading-relaxed">
                        <p>
                            {{ $s->about_description ?? 'Kami berkomitmen membangun tata kelola SDM yang adaptif terhadap teknologi. Mengutamakan transparansi dan akuntabilitas untuk mendukung kinerja Kementerian Lingkungan Hidup.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="pl-5 border-l-4 border-teal-700 bg-white/50 py-2 rounded-r-lg">
                            <h5 class="font-heading font-bold text-teal-900 text-xl mb-1">Adaptif</h5>
                            <p class="text-sm text-slate-500">Inovasi berkelanjutan.</p>
                        </div>
                        <div class="pl-5 border-l-4 border-blue-600 bg-white/50 py-2 rounded-r-lg">
                            <h5 class="font-heading font-bold text-teal-900 text-xl mb-1">Kolaboratif</h5>
                            <p class="text-sm text-slate-500">Sinergi antar unit.</p>
                        </div>
                    </div>
                </div>

                {{-- Image Stack --}}
                <div class="lg:w-1/2 relative" data-aos="fade-left">

                    {{-- Wrapper dengan aspect ratio agar GAMBAR TIDAK GEPENG --}}
                    <div
                        class="relative z-20 rounded-[2.5rem] overflow-hidden shadow-2xl border-[6px] border-white aspect-[4/3]">

                        <img src="{{ asset('img/corporate2.jpg') }}" class="w-full h-full object-cover object-center" />

                    </div>

                    {{-- Decorative Accent --}}
                    <div
                        class="absolute top-8 left-8 w-full h-full rounded-[2.5rem] border-2 border-orange-brand/30 z-10 pointer-events-none">
                    </div>

                </div>

            </div>
        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- ================= 4. BERITA (CLEAN LIST) ================ --}}
    {{-- ========================================================= --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6 md:px-16">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-4">
                <div>
                    <h2 class="font-heading text-4xl font-bold text-teal-900 mb-2">Kabar Terbaru</h2>
                    <p class="text-slate-500 text-lg">Informasi dan kegiatan terkini biro.</p>
                </div>
                <a href="{{ route('berita') }}"
                    class="group inline-flex items-center gap-2 font-bold text-teal-700 border-b-2 border-transparent hover:border-orange-brand transition-all pb-1">
                    Lihat Semua Arsip <span
                        class="text-orange-brand group-hover:translate-x-1 transition-transform">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                @forelse($featuredNews as $item)
                    @php
                        // Parsing tanggal untuk memudahkan format
                        $date = \Carbon\Carbon::parse($item->tanggal);
                    @endphp

                    <article class="group cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <a href="{{ route('berita.show', $item->id) }}" class="block">
                            <div
                                class="img-zoom rounded-2xl overflow-hidden mb-6 relative aspect-[4/3] shadow-md border border-slate-100">
                                {{-- Logic Gambar: Cek apakah ada gambar di DB, jika tidak pakai placeholder --}}
                                <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://source.unsplash.com/random/800x600?office&sig=' . $item->id }}"
                                    alt="{{ $item->judul }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur px-4 py-2 rounded-lg shadow-sm">
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">
                                        {{ $date->translatedFormat('M Y') }}
                                    </p>
                                    <p class="text-2xl font-heading font-bold text-teal-900 leading-none">
                                        {{ $date->format('d') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mb-3">
                                <span class="h-px w-6 bg-orange-brand"></span>
                                {{-- Jika Anda punya kategori, bisa dipanggil disini. Jika tidak, hardcode 'Berita' --}}
                                <span class="text-xs font-bold text-orange-brand uppercase tracking-wider">Berita</span>
                            </div>

                            <h3
                                class="font-heading font-bold text-xl text-teal-900 mb-4 leading-snug group-hover:text-teal-700 transition-colors">
                                {{ Str::limit($item->judul, 60) }}
                            </h3>

                            <div class="text-slate-500 line-clamp-2 mb-6 text-sm">
                                {{-- strip_tags digunakan untuk membuang tag HTML dari summernote sebelum dipotong --}}
                                {{ Str::limit(strip_tags($item->isi), 100) }}
                            </div>

                            <span
                                class="text-sm font-bold text-teal-700 flex items-center gap-2 group-hover:gap-3 transition-all">
                                Baca Selengkapnya <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </a>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-10">
                        <p class="text-slate-500">Belum ada berita terbaru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- ============== 5. LACAK PERMOHONAN =============== --}}
    {{-- ========================================================= --}}
    <section class="py-24 bg-white relative overflow-hidden" data-aos="fade-up">
        <div class="container mx-auto px-6 md:px-16 relative z-10">

            {{-- Section Header --}}
            <div class="text-center mb-16">
                <span class="text-orange-brand font-bold tracking-widest text-sm uppercase mb-3 block">Transparansi
                    Layanan</span>
                <h2 class="font-heading text-4xl md:text-5xl font-bold text-teal-900 mb-4">
                    Pantau Status <span
                        class="text-teal-700/80 bg-clip-text text-transparent bg-gradient-to-r from-teal-700 to-blue-600">Permohonan
                        Surat</span>
                </h2>
                <div class="w-24 h-1.5 bg-gradient-brand mx-auto rounded-full opacity-50"></div>
                <p class="text-slate-500 mt-4 max-w-2xl mx-auto text-lg">
                    Cek perkembangan dokumen yang Anda ajukan dengan mudah.
                </p>
            </div>

            {{-- Call-to-Action (CTA) Link --}}
            <div class="text-center" data-aos="zoom-in" data-aos-delay="300">
                <p class="text-2xl font-medium text-slate-700 mb-6">
                    Ingin tahu status permohonan Anda?
                </p>

                {{-- Tombol Link (Sesuai Permintaan Anda) --}}
                <a href="#" class="group relative inline-flex items-center gap-3 px-10 py-5 
              bg-white text-orange-600 border-2 border-orange-600 
              font-bold text-xl rounded-full overflow-hidden transition-all duration-300 
              hover:shadow-2xl hover:bg-orange-600 hover:text-white hover:border-orange-600
              transform hover:scale-105 shadow-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    Pantau Permohonan Saya, Klik di Sini
                </a>
                {{-- Anda harus mendaftarkan rute 'lacak.page' di web.php --}}
            </div>

        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- ============== 6. INDEKS KEPUASAN (MURNI) =============== --}}
    {{-- ========================================================= --}}
    <section class="py-24 px-4 bg-teal-50/50 border-t border-teal-100">
        <div class="container mx-auto" data-aos="zoom-in" data-aos-duration="1000">
            @if($s && $s->survey_image)
                {{-- Container Gambar Murni dengan Shadow Premium --}}
                <div
                    class="w-full rounded-[2.5rem] overflow-hidden shadow-2xl border-[6px] border-white hover:shadow-[0_30px_60px_rgba(15,118,110,0.15)] transition-all duration-500">
                    <img src="{{ asset('storage/' . $s->survey_image) }}" class="w-full h-auto object-cover block"
                        alt="Indeks Kepuasan Masyarakat">
                </div>
            @else
                <div
                    class="w-full h-72 bg-teal-100/50 border-2 border-dashed border-teal-200 rounded-3xl flex items-center justify-center text-teal-400 font-medium">
                    Banner Survei Belum Diupload
                </div>
            @endif
        </div>
    </section>


    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    {{-- Add Typed.js --}}
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>

    <script>
        AOS.init({ once: true, offset: 100, duration: 800, easing: 'ease-out-cubic' });

        var swiper = new Swiper(".heroSwiper", {
            loop: true,
            effect: "fade",
            fadeEffect: { crossFade: true },
            speed: 2000,
            autoplay: { delay: 6000, disableOnInteraction: false },
        });

        // TYPEWRITER EFFECT (Hanya untuk judul utama)
        var typed = new Typed('#typed-hero', {
            // Mengambil teks dari PHP Variable
            strings: ["{{ $heroTitleText }}"],
            typeSpeed: 90,  // Kecepatan ketik (makin besar makin lambat)
            startDelay: 500,
            showCursor: true,
            cursorChar: '_', // Kursor garis bawah (retro style)
            autoInsertCss: true,
        });
    </script>

@endsection