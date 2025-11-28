@extends('layouts.app')

@section('title', 'Peraturan / Kebijakan')

@section('content')
{{-- === JUMBOTRON === --}}
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

{{-- === ISI HALAMAN === --}}
<section class="container mx-auto px-6 py-10">
    <h2 class="font-bold text-lg mb-4 uppercase text-gray-800">Peraturan :</h2>
    <ol class="list-decimal pl-6 space-y-3 text-blue-800">
        <li>
            <a href="#" class="hover:underline">
                Peraturan Menteri LH/Kepala BPLH No. 1 Tahun 2024 tentang Organisasi dan Tata Kerja KLH/BPLH
            </a>
        </li>
        <li>
            <a href="#" class="hover:underline">
                Peraturan Presiden RI No. 11 Tahun 2024 tentang Perubahan atas Perpres No. 98 Tahun 2020 tentang Gaji dan Tunjangan PPPK
            </a>
        </li>
        <li>
            <a href="#" class="hover:underline">
                Peraturan Pemerintah RI No. 49 Tahun 2018 tentang Manajemen PPPK
            </a>
        </li>
        <li>
            <a href="#" class="hover:underline">
                Surat Menteri LH/Kepala BPLH No. 60 Tahun 2025 tentang Penguatan Pengisian JF Binaan KLH/BPLH Lingkup Pemda Provinsi/Kabupaten/Kota
            </a>
        </li>
        <li>
            <a href="#" class="hover:underline">
                Keputusan Menteri LH/Kepala BPLH No. 128 Tahun 2025 tentang Pelimpahan Kewenangan dalam Pelaksanaan Administrasi Kepegawaian di KLH/BPLH
            </a>
        </li>
        <li>
            <a href="#" class="hover:underline">
                Surat Edaran No. 3 Tahun 2025 tentang Ketentuan Usulan Kenaikan Pangkat dan Proses Administrasi JF di Lingkungan KLH/BPLH
            </a>
        </li>
        <li>
            <a href="#" class="hover:underline">
                Surat Edaran No. 1 Tahun 2025 tentang Pedoman Tata Naskah Dinas Lingkup KLH/BPLH
            </a>
        </li>
        <li>
            <a href="#" class="hover:underline">
                Undang-Undang RI No. 20 Tahun 2023 tentang Aparatur Sipil Negara
            </a>
        </li>
    </ol>
</section>
@endsection