<!-- @extends('layouts.app')

@section('content')
{{-- ===================== SECTION KONTAK ===================== --}}
<section class="min-h-screen bg-gray-50 py-16 px-6">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-800">Hubungi Kami</h1>
            <p class="text-gray-600 mt-3 max-w-2xl mx-auto">
                Jangan ragu untuk menghubungi kami melalui form atau informasi berikut.
            </p>
        </div>

        {{-- GRID 2 KOLOM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            {{-- INFO KONTAK --}}
            <div class="space-y-4 text-gray-700">
                <p class="flex items-start">
                    <i class="fa-solid fa-building text-blue-600 w-7 mt-1"></i>
                    <span>
                        <strong>Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup</strong><br>
                        Biro Sumber Daya Manusia dan Organisasi
                    </span>
                </p>
                <p class="flex items-start">
                    <i class="fa-solid fa-location-dot text-blue-600 w-6 mt-1"></i>
                    <span>
                        <strong>Gedung C Lantai 2, Jalan D.I. Panjaitan, Kav 24,<br>
                        Kebon Nanas, Jakarta Timur 13410</strong>
                    </span>
                </p>
                <p class="flex items-start">
                    <i class="fa-solid fa-envelope text-blue-600 w-6 mt-1"></i>
                    <span>info@menlhk.go.id</span>
                </p>
            </div>

            {{-- FORM KONTAK --}}
            <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-200">
                {{-- Success Message --}}
                @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('kontak.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="font-semibold text-gray-700">Nama</label>
                        <input type="text" name="nama" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Masukkan nama" required>
                    </div>
                    <div>
                        <label class="font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Masukkan email" required>
                    </div>
                    <div>
                        <label class="font-semibold text-gray-700">Pesan</label>
                        <textarea name="pesan" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Tulis pesan..." required></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold shadow">
                        Kirim Pesan
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

{{-- ===================== SECTION MAPS FULL SCREEN ===================== --}}
<section class="w-full h-screen m-0 p-0">
    <div class="w-full h-full">
        <iframe
            src="https://www.google.com/maps/d/embed?mid=1aEiXhCzNWwiBUpSVKYw_5qRpaDE0xy8&ehbc=2E312F"
            width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>
@endse

{{-- ===================== ANIMASI SCROLL (REVEAL) ===================== --}}
@push('scripts')
<style>
    .reveal {
        opacity-0;
        transform: translateY(40px)
        transiti
    }ic