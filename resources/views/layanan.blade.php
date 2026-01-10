@extends('layouts.app')
@section('title', 'Katalog Layanan')

@section('content')
    <link href="xs://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        
        /* HERO & CARD STYLES (Sama seperti request sebelumnya) */
        .hero-section {
            position: relative;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: -80px; 
            padding-top: 80px; 
        }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.75)); z-index: 1; }
        .hero-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; }
        
        .service-card {
            background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 1.25rem; padding: 2rem;
            position: relative; transition: all 0.3s ease; display: flex; flex-direction: column; height: 100%;
        }
        .service-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1); border-color: #CBD5E1; }
        
        .card-accent-line { width: 50px; height: 6px; border-radius: 100px; margin-bottom: 1.5rem; background-color: var(--accent-color); }
        
        .card-big-number {
            position: absolute; top: 1.5rem; right: 1.5rem;
            font-size: 3.5rem; font-weight: 800; line-height: 1;
            color: var(--accent-color); opacity: 0.15;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .card-title {
            font-size: 1.125rem; font-weight: 700; color: #1E293B; line-height: 1.5; margin-bottom: 1rem;
            min-height: 3.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .card-desc { font-size: 0.875rem; color: #64748B; line-height: 1.6; margin-bottom: 2rem; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        
        .badge-status { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.4rem 0.8rem; border-radius: 0.5rem; }
        .badge-online { background-color: #ECFDF5; color: #059669; }
        .badge-offline { background-color: #F1F5F9; color: #64748B; }
        
        .btn-arrow { width: 3rem; height: 3rem; border-radius: 50%; background-color: #F8FAFC; display: flex; align-items: center; justify-content: center; color: #475569; transition: all 0.2s; }
        .service-card:hover .btn-arrow { background-color: var(--accent-color); color: white; transform: translateX(5px); }
    </style>

    {{-- HERO --}}
    <section class="hero-section">
        <img src="{{ asset('img/bg-hero.jpg') }}" alt="Background" class="hero-bg">
        <div class="hero-overlay"></div>
        <div class="relative z-10 text-center px-6 max-w-5xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-2 leading-tight">Layanan Pegawai ASN</h1>
            <h2 class="text-3xl md:text-5xl font-light text-slate-200 mb-8">Lingkup KLH/BPLH</h2>
            <div class="mt-8 max-w-xl mx-auto relative">
                <input type="text" id="searchInput" placeholder="Cari layanan..." class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-full py-3.5 pl-12 pr-6 text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/20 transition shadow-lg text-sm">
                <i class="fas fa-search absolute left-5 top-4 text-slate-300"></i>
            </div>
        </div>
    </section>

    {{-- EXPLANATION --}}
    <section class="bg-white py-16 px-6 border-b border-slate-100">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-slate-600 text-lg md:text-xl leading-relaxed font-medium">
                Transformasi pelayanan publik selalu diharapkan mampu membuat pelayanan publik menjadi lebih cepat, pasti, dan dengan biaya terjangkau.
            </p>
        </div>
    </section>

    {{-- GRID --}}
    <section class="py-20 px-6 md:px-12 bg-F8FAFC">
        <div class="max-w-[1400px] mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="serviceGrid">
                
                {{-- Inisialisasi Counter --}}
                @php $mainCounter = 0; @endphp

                @forelse ($layanans as $index => $layanan)
                    @php
                        $colors = ['#F97316', '#059669', '#0891B2', '#2563EB', '#7C3AED']; 
                        $color = $colors[$index % 5];
                        
                        $cleanName = trim($layanan->nama);
                        $displayNumberVisual = '';
                        $displayName = $cleanName;

                        // ===================================================
                        // LOGIKA PENOMORAN SUPER AKURAT
                        // ===================================================
                        
                        $isCustom = false;

                        // 1. Cek "8.b" (NON Binaan) - Prioritas Tertinggi
                        if (str_contains($cleanName, 'NON Binaan')) {
                            $displayNumberVisual = '8.b';
                            $mainCounter = 8; // Reset counter ke 8
                            $isCustom = true;
                        }
                        // 2. Cek "8.a" (Uji Kompetensi Binaan) - Pastikan ada kata 'Uji Kompetensi'
                        elseif (str_contains($cleanName, 'Uji Kompetensi') && str_contains($cleanName, 'Binaan')) {
                            $displayNumberVisual = '8.a';
                            $mainCounter = 8; 
                            $isCustom = true;
                        }
                        // 3. Cek "10" atau "9" (Rekomendasi JF)
                        // Gunakan ini untuk membedakan dengan "Uji Kompetensi"
                        elseif (str_contains($cleanName, 'Rekomendasi')) {
                            $mainCounter++; // Naik dari 8 ke 9 (atau bisa di-hardcode 10 jika mau)
                            $displayNumberVisual = str_pad($mainCounter, 2, '0', STR_PAD_LEFT);
                            $isCustom = true;
                        }

                        // 4. Default (Layanan Biasa)
                        if (!$isCustom) {
                            // Cek jika ada angka manual di judul database (misal: "1. Layanan X")
                            if (preg_match('/^(\d+)(?:\.([a-zA-Z]+))?\.?\s/', $cleanName, $matches)) {
                                $digit = (int)$matches[1];
                                $sub = $matches[2] ?? '';
                                $mainCounter = $digit; // Ikuti nomor database
                                $displayNumberVisual = $sub ? "$digit.$sub" : str_pad($digit, 2, '0', STR_PAD_LEFT);
                                $displayName = trim(substr($cleanName, strlen($matches[0])));
                            } else {
                                // Murni Otomatis
                                $mainCounter++;
                                $displayNumberVisual = str_pad($mainCounter, 2, '0', STR_PAD_LEFT);
                            }
                        }
                    @endphp

                    <div class="search-item" style="--accent-color: {{ $color }}">
                        <a href="{{ route('user.pages.pengajuan') }}" class="service-card block h-full">
                            <div class="card-accent-line"></div>
                            <div class="card-big-number">{{ $displayNumberVisual }}</div>
                            <h4 class="card-title service-name">
                                <span class="hidden">{{ $layanan->nama }}</span>
                                {{ $displayName }}
                            </h4>
                            <p class="card-desc">{{ Str::limit($layanan->deskripsi ?? 'Layanan administrasi resmi.', 85) }}</p>
                            <div class="card-footer">
                                @if($layanan->is_active)
                                    <span class="badge-status badge-online">ONLINE</span>
                                @else
                                    <span class="badge-status badge-offline">OFFLINE</span>
                                @endif
                                <div class="btn-arrow"><i class="fas fa-arrow-right"></i></div>
                            </div>
                        </a>
                    </div>

                @empty
                    <div class="col-span-full py-16 text-center">
                        <h3 class="text-lg font-bold text-slate-700">Belum Ada Layanan</h3>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toUpperCase();
            document.querySelectorAll('.search-item').forEach(function(card) {
                let text = card.innerText;
                card.style.display = text.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            });
        });
    </script>
@endsection