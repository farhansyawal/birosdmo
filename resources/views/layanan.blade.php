@extends('layouts.app')
@section('title', 'Layanan')
@section('content')
<!-- HERO SECTION (100% sama konsep seperti beranda, menyatu dengan navbar) -->
<section class="relative w-full h-[380px]">
    <!-- Background image -->
    <img src="{{ asset('img/bg-hero.jpg') }}" alt="Background"
        class="absolute top-0 left-0 w-full h-full object-cover brightness-75">

    <!-- Overlay hitam -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- Teks di tengah -->
    <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white px-4">
        <h1 class="text-5xl md:text-6xl font-semibold mb-3">LAYANAN PEGAWAI ASN</h1>
        <h1 class="text-2xl md:text-6xl font-semibold font-light">Lingkup KLH / BPLH</h1>
    </div>
</section>

<!-- PENJELASAN SETELAH HERO -->
<section class="bg-white py-12 px-6 md:px-20 text-center">
    <p class="text-gray-800 text-lg md:text-xl leading-relaxed max-w-4xl mx-auto">
        Transformasi pelayanan publik selalu diharapkan mampu membuat pelayanan publik menjadi lebih cepat,
        pasti, dan dengan biaya terjangkau. Pemerintah telah menerbitkan
        UU No. 37 Tahun 2008 tentang Ombudsman RI
        yang mendorong seluruh instansi pemerintah memperbaiki kinerja pelayanan publiknya.
    </p>
</section>


@endsection