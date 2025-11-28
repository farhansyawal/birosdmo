@extends('layouts.app')

@section('content')

    @php
        use App\Models\BerandaSetting;
        $s = BerandaSetting::first();

        // HERO SLIDER
        $heroSliders = $s?->slider_items ? json_decode($s->slider_items, true) : [];

        // INFO IMAGES (3 gambar)
        $infoImages = $s?->info_images ? json_decode($s->info_images, true) : [];
    @endphp


    {{-- ========================================================= --}}
    {{-- ======================= HERO SECTION ===================== --}}
    {{-- ========================================================= --}}

    <section class="relative w-full h-[450px] mt-[48px] rounded-lg overflow-hidden shadow">

        {{-- SWIPER --}}
        <div class="swiper heroSwiper w-full h-full z-0">
            <div class="swiper-wrapper">
                @if(count($heroSliders) > 0)
                    @foreach($heroSliders as $sl)
                        <div class="swiper-slide w-full h-full relative">
                            <img src="{{ asset('storage/' . $sl['image']) }}" class="w-full h-full object-cover" alt="slider">
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide w-full h-full relative">
                        <img src="{{ asset('img/bg-hero.jpg') }}" class="w-full h-full object-cover brightness-75">
                    </div>
                @endif
            </div>

            {{-- Swiper Navigations --}}
            <div class="swiper-pagination z-20"></div>
        </div>

        {{-- OVERLAY --}}
        <div class="absolute inset-0 bg-black/40 z-10"></div>

        {{-- TEXT --}}
        <div class="absolute inset-0 flex flex-col justify-center z-[15] px-6 md:px-24">
            <h1 class="text-white text-3xl md:text-5xl font-light mb-2">
                {{ $s->hero_title ?? 'Selamat datang di' }}
            </h1>

            <h1 class="text-red-600 font-extrabold text-5xl md:text-7xl">
                {{ $s->hero_bigtext ?? 'ZONA INTEGRITAS' }}
            </h1>

            <h2 class="text-white text-2xl md:text-4xl mt-2">
                {{ $s->hero_subtitle ?? 'Biro Sumber Daya Manusia dan Organisasi' }}
            </h2>
        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- =================== 3 GAMBAR BIASA (INFO) ================ --}}
    {{-- ========================================================= --}}

    @php
        $images = [];

        if ($s) {
            if ($s->item_image_1)
                $images[] = ['image' => $s->item_image_1, 'url' => '#'];
            if ($s->item_image_2)
                $images[] = ['image' => $s->item_image_2, 'url' => '#'];
            if ($s->item_image_3)
                $images[] = ['image' => $s->item_image_3, 'url' => '#'];
        }
    @endphp

    <section class="container mx-auto my-10 flex flex-wrap justify-center items-center gap-6 px-4">
        @if(count($images) > 0)
            @foreach ($images as $item)
                <a href="{{ $item['url'] }}" class="transform hover:scale-105 transition duration-300">
                    <img src="{{ asset('storage/' . $item['image']) }}" class="h-20 md:h-24 object-contain" alt="Info Image">
                </a>
            @endforeach
        @else
            {{-- Default images --}}
            <a href="https://www.lapor.go.id/" class="transform hover:scale-105 transition duration-300">
                <img src="https://lh3.googleusercontent.com/sitesv/AAzXCkf5rBcge_ctkF40UY9JT1K9GJKI3w-qYdVUZ4RLCqndDiANXeecGvorKbZ1R3uzdBd_KJ0u2phaFVXcgE-w2Lo2hurpTkQYHn6DJG0HWkK3uQ93Ig0yOtMKwFm1CS0PygHhJ_uHXGoFipaHJkX0T_a37dFXjlpjPQakHGX2m6JsLqXwqbUA298M_AAGU9ism4rahDt3M3vG6dV5aAzofgjUqCzJuZmU7xZMpI0=w1280"
                    class="h-20 md:h-24" alt="Lapor!">
            </a>
            <a href="#" class="transform hover:scale-105 transition duration-300">
                <img src="https://lh3.googleusercontent.com/sitesv/AAzXCkfNmiq48LSAPbOjVrVftx0Mo3EEXaNa-9KsurXSKjCqu0MGOhlisPiXL6ggJ4qe8Rp_Fy_vmZ4sft5rpiw3idaCtEkCY4s3XeysK_hDow44HkDSv_VeiY_KmDLRjCcJpOfVGL2_A4uPxtR93m6iYTZzCDLIvureXceKvpdmdSNKcP9FNBmHDEiQ_TedByZ1NSjhWqrwPcHC1MKK6Mih4DQIGvSaapy_fGr_sFk=w1280"
                    class="h-20 md:h-24" alt="Layanan">
            </a>
            <a href="#" class="transform hover:scale-105 transition duration-300">
                <img src="https://lh3.googleusercontent.com/sitesv/AAzXCkdNusGLHvxBi3m0q-nRjCjLI3b6SESk9xQjZs_Uanpv26uyENGJLZBRoHDKaB1JH8S2zWYZsG-yW9w5IQP4uK9Cc5ZOqwnWQvLor52JN3a9mRR1PjalYDz6261i_pHH6vXKXKbWG9FdLh7y3gHfyPQ9Cw7lTYrgHtD-_EoHy2UxgehGOT2z-7dh4PfJt0U3M0zIn5_E_x1uzRE3P7BCeuMfWSozYtZBQEqcoaw=w1280"
                    class="h-20 md:h-24" alt="Survey">
            </a>
        @endif
    </section>


    {{-- ========================================================= --}}
    {{-- ================== TENTANG BIRO SECTION ================= --}}
    {{-- ========================================================= --}}

    <section class="bg-blue-100 py-10 px-4 text-center">
        <h2 class="text-xl md:text-2xl text-blue-800 font-semibold mb-3">
            {{ $s->about_title ?? 'Tentang Biro SDM dan Organisasi' }}
        </h2>

        <p class="text-sm md:text-base text-gray-700 max-w-2xl mx-auto leading-relaxed">
            {{ $s->about_description ??
        'Melaksanakan koordinasi dan penyiapan bahan pembinaan dan pengelolaan administrasi sumber daya manusia,
                                                                perencanaan dan pengembangan sumber daya manusia, serta pengelolaan organisasi dan tata laksana.' }}
        </p>
    </section>

    {{-- ========================================================= --}}
    {{-- ================== BERITA TERKINI ================= --}}
    {{-- ========================================================= --}}

    <section class="container mx-auto my-10 px-4">
        <h2 class="text-xl md:text-2xl text-blue-800 font-semibold mb-6 text-center">
            Berita Terkini
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{--CARD BERITA 1--}}
            <div class="bg-white shadow rounded overflow-hidden hover:shadow-lg transition">
                <img src="https://lh3.googleusercontent.com/sitesv/AAzXCkcOc…R-32aiE9acA-E9Ndyei4IPw4SVU3oiTkNmHXwF3lub-=w1280"
                    alt="Berita 1">
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">Judul Berita Pertama </h3>
                    <p class="text-gray-600 text-sm">
                        Peningkatan Kapasitas tentang Prosedur Kebijakan Pengangkatan PPPK Teknis dan PPPK Paruh Waktu di
                        Lingkup Biro SDM dan Organisasi KLH/BPLH
                    </p>
                    <a href="#" class="text-blue-600 font-semibold text-sm mt-2 block">Baca Selengkapnya -> </a>
                </div>
            </div>

            {{--CARD BERITA 2--}}
            <div class="bg-white shadow rounded overflow-hidden hover:shadow-lg transition">
                <img src="https://lh3.googleusercontent.com/sitesv/AAzXCkcOc…R-32aiE9acA-E9Ndyei4IPw4SVU3oiTkNmHXwF3lub-=w1280"
                    alt="Berita 1">
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">Judul Berita Kedua </h3>
                    <p class="text-gray-600 text-sm">
                        Peningkatan Kapasitas tentang Prosedur Kebijakan Pengangkatan PPPK Teknis dan PPPK Paruh Waktu di
                        Lingkup Biro SDM dan Organisasi KLH/BPLH
                    </p>
                    <a href="#" class="text-blue-600 font-semibold text-sm mt-2 block">Baca Selengkapnya -> </a>
                </div>
            </div>

            {{--CARD BERITA 3--}}
            <div class="bg-white shadow rounded overflow-hidden hover:shadow-lg transition">
                <img src=https://lh3.googleusercontent.com/sitesv/AAzXCkcOc…R-32aiE9acA-E9Ndyei4IPw4SVU3oiTkNmHXwF3lub-=w1280
                    alt="Berita 1">
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">Judul Berita ketiga </h3>
                    <p class="text-gray-600 text-sm">
                        Peningkatan Kapasitas tentang Prosedur Kebijakan Pengangkatan PPPK Teknis dan PPPK Paruh Waktu di
                        Lingkup Biro SDM dan Organisasi KLH/BPLH
                    </p>
                    <a href="#" class="text-blue-600 font-semibold text-sm mt-2 block">Baca Selengkapnya -> </a>
                </div>
            </div>

        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- ================== INDEKS KEPUASAN ======================= --}}
    {{-- ========================================================= --}}

    <section class="container mx-auto my-10 flex justify-center px-4">
        <img src="{{ $s && $s->survey_image ? asset('storage/' . $s->survey_image) :
        'https://lh3.googleusercontent.com/sitesv/AAzXCkcTan4NYsM0ot4XySzcMq4WcgVNthhgSMUO4ZB9k2G9WRMz01A8C8p0vvml4uBfD5a_vBWFxP_IwoyoniSV-bOmPVsXB4T1qcLotlxShuuLNetyCbx2Ng5PYTogML-_QThaMAcwe_UPMRLN8eLv2tnyRfYHlgnkL6Eqq26CQhOHBnyEiQ-Bm335hXatc9VVML2bX1wwEuujfBT0TR-rpF6KdyEY_vTA3_Mj=w1280' }}"
            class="w-full md:w-2/3 rounded shadow-md" alt="Indeks Kepuasan Masyarakat">
    </section>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".heroSwiper", {
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            effect: "fade",
            fadeEffect: { crossFade: true },
        });
    </script>


@endsection