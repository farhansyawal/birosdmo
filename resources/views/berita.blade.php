@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-16 px-6">

    <!-- SECTION: Berita Terbaru -->
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 border-l-4 border-blue-600 pl-3">
            Berita Terbaru
        </h2>

        <!-- Grid utama -->
        <div class="grid md:grid-cols-3 gap-6">
            
            <!-- KOTAK BIRU -->
            <div class="bg-blue-700 text-white rounded-2xl p-8 flex flex-col justify-between shadow-lg hover:shadow-2xl transition-transform transform hover:-translate-y-1">
                <div class="text-center flex flex-col items-center justify-center space-y-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 100-4H5a2 2 0 100 4m14 0v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6" />
                        </svg>
                    </div>
                    <p class="text-lg leading-relaxed font-medium">
                        Informasi terkini tentang apa yang terjadi di Indonesia dan menyangkut Biro SDM & Organisasi.
                    </p>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('berita') }}" 
                       class="inline-block px-6 py-2 bg-white text-blue-700 font-semibold rounded-full shadow hover:bg-blue-100 transition">
                        INDEKS BERITA
                    </a>
                </div>
            </div>

            <!-- KARTU BERITA 1 -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-transform transform hover:-translate-y-1">
                <img src="{{ asset('images/berita1.jpg') }}" alt="Berita 1" class="w-full h-56 object-cover">
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-2 hover:text-blue-600 transition">
                        Peningkatan Kapasitas Kebijakan Pengangkatan PPPK Teknis
                    </h3>
                    <p class="text-gray-600 text-sm mb-3">
                        Biro SDM dan Organisasi mengadakan pelatihan peningkatan kapasitas terkait pengangkatan PPPK Teknis dan Paruh Waktu.
                    </p>
                    <a href="#" class="text-blue-600 font-semibold inline-block hover:underline">
                        Selengkapnya →
                    </a>
                </div>
            </div>

            <!-- KARTU BERITA 2 -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-transform transform hover:-translate-y-1">
                <img src="{{ asset('images/berita2.jpg') }}" alt="Berita 2" class="w-full h-56 object-cover">
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-2 hover:text-blue-600 transition">
                        Workshop Digitalisasi Data ASN untuk Efisiensi Pengelolaan SDM
                    </h3>
                    <p class="text-gray-600 text-sm mb-3">
                        Biro SDM & Organisasi menyelenggarakan workshop untuk memperkuat pengelolaan data ASN secara efisien dan transparan.
                    </p>
                    <a href="#" class="text-blue-600 font-semibold inline-block hover:underline">
                        Selengkapnya →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
