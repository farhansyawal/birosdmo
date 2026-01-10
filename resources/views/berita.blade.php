@extends('layouts.app')

@section('title', 'Portal Berita')

@section('content')
    {{-- LIBRARY --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/scrollreveal"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; scroll-behavior: smooth; }

        /* 1. INTERACTIVE CARD */
        .news-card {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            transform-style: preserve-3d;
        }
        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        .news-card .overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
            opacity: 0.6;
            transition: opacity 0.3s;
        }
        .news-card:hover .overlay { opacity: 0.9; }
        
        /* 2. READ BUTTON ANIMATION */
        .btn-read {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }
        .news-card:hover .btn-read {
            opacity: 1;
            transform: translateY(0);
        }

        /* 3. MODAL ANIMATION */
        .modal-enter { animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .modal-leave { animation: modalOut 0.3s ease-in forwards; }
        
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes modalOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }

        /* 4. CUSTOM SCROLLBAR FOR MODAL */
        .modal-scroll::-webkit-scrollbar { width: 8px; }
        .modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        .modal-scroll::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* 5. TYPOGRAPHY CONTENT */
        .prose p { margin-bottom: 1rem; line-height: 1.7; color: #334155; }
        .prose h2, .prose h3 { font-weight: 800; color: #1e293b; margin-top: 1.5rem; margin-bottom: 0.5rem; }
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
    </style>

    {{-- HERO HIGHLIGHT (Clickable to Modal) --}}
    @if($featuredNews)
    <header class="relative w-full h-[550px] overflow-hidden group cursor-pointer" onclick="openNews({{ $featuredNews->id }})">
        <div class="absolute inset-0 bg-slate-900">
            <img src="{{ $featuredNews->gambar ? asset('storage/'.$featuredNews->gambar) : 'https://source.unsplash.com/1920x1080/?technology,city' }}" 
                 class="w-full h-full object-cover opacity-60 group-hover:scale-105 group-hover:opacity-50 transition-all duration-1000">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto h-full flex flex-col justify-end px-6 pb-16">
            <div class="reveal-hero">
                <span class="inline-block px-3 py-1 bg-rose-500 text-white text-xs font-bold uppercase tracking-wider rounded-full mb-4 shadow-lg shadow-rose-500/30">
                    Trending Now
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-4 drop-shadow-lg group-hover:text-emerald-400 transition-colors">
                    {{ $featuredNews->judul }}
                </h1>
                <div class="flex items-center text-slate-300 gap-4 text-sm font-medium">
                    <span><i class="far fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($featuredNews->tanggal)->translatedFormat('d F Y') }}</span>
                    <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                    <span class="group-hover:translate-x-2 transition-transform duration-300 flex items-center">
                        Baca Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                    </span>
                </div>
            </div>
        </div>
    </header>
    @endif

    {{-- NEWS GRID --}}
    <main class="py-20 px-6 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto">
            
            {{-- Header & Search --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6 reveal-up">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Kabar Terbaru</h2>
                    <p class="text-slate-500 mt-2">Update informasi dan kegiatan terkini.</p>
                </div>
                <form action="{{ route('berita') }}" method="GET" class="relative w-full md:w-80 group">
                    <input type="text" name="search" placeholder="Cari berita..." value="{{ request('search') }}"
                           class="w-full pl-12 pr-4 py-3 rounded-full bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm font-bold text-slate-700">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </form>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($beritas as $item)
                    <article class="news-card relative h-[400px] rounded-3xl overflow-hidden cursor-pointer group reveal-card" 
                             onclick="openNews({{ $item->id }})">
                        
                        {{-- Background Image --}}
                        <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : 'https://source.unsplash.com/800x600/?meeting,office&sig='.$item->id }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        {{-- Overlay --}}
                        <div class="overlay absolute inset-0"></div>

                        {{-- Content --}}
                        <div class="absolute inset-0 p-8 flex flex-col justify-end z-10">
                            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                <span class="text-emerald-400 text-xs font-bold uppercase tracking-wider mb-2 block">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </span>
                                <h3 class="text-2xl font-bold text-white leading-snug mb-3 line-clamp-3 group-hover:text-emerald-300 transition-colors">
                                    {{ $item->judul }}
                                </h3>
                                
                                {{-- Hidden Text (Shows on Hover) --}}
                                <p class="text-slate-300 text-sm line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-75">
                                    {{ Str::limit(strip_tags($item->isi), 100) }}
                                </p>

                                {{-- Floating Button --}}
                                <div class="btn-read mt-6">
                                    <button class="bg-white text-slate-900 px-6 py-2 rounded-full text-xs font-bold uppercase tracking-wide hover:bg-emerald-500 hover:text-white transition-colors shadow-lg">
                                        Baca Artikel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-20">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-100 rounded-full mb-4 text-slate-400">
                            <i class="far fa-newspaper text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Tidak ada berita ditemukan.</h3>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($beritas->hasPages())
                <div class="mt-16 flex justify-center reveal-up">
                    {{ $beritas->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>

    {{-- MODAL BACA BERITA (FULLSCREEN OVERLAY) --}}
    <div id="newsModal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true">
        {{-- Backdrop Blur --}}
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop" onclick="closeNews()"></div>
        
        {{-- Modal Panel --}}
        <div class="absolute inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
            <div id="modalPanel" class="pointer-events-auto bg-white w-full max-w-4xl h-[90vh] rounded-3xl shadow-2xl overflow-hidden flex flex-col relative transform scale-95 opacity-0 transition-all duration-300">
                
                {{-- Close Button (Floating) --}}
                <button onclick="closeNews()" class="absolute top-4 right-4 z-50 w-10 h-10 bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-slate-800 hover:bg-rose-500 hover:text-white transition-all shadow-lg border border-slate-100">
                    <i class="fas fa-times text-lg"></i>
                </button>

                {{-- Loading State --}}
                <div id="modalLoader" class="absolute inset-0 z-40 bg-white flex flex-col items-center justify-center">
                    <div class="w-12 h-12 border-4 border-slate-100 border-t-emerald-500 rounded-full animate-spin mb-4"></div>
                    <p class="text-slate-400 font-bold text-sm tracking-wide animate-pulse">MEMUAT KONTEN...</p>
                </div>

                {{-- Content Area --}}
                <div id="modalContent" class="flex-1 overflow-y-auto modal-scroll bg-white hidden">
                    
                    {{-- Header Image --}}
                    <div class="relative h-64 md:h-80 w-full shrink-0">
                        <img id="viewImage" src="" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
                    </div>

                    {{-- Body --}}
                    <div class="px-8 md:px-12 pb-12 -mt-20 relative z-10">
                        <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
                            {{-- Meta --}}
                            <div class="flex items-center gap-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-4">
                                <span class="text-emerald-600"><i class="fas fa-newspaper mr-1"></i> Berita</span>
                                <span id="viewDate"></span>
                                <span class="ml-auto flex items-center gap-1"><i class="fas fa-user-circle"></i> <span id="viewAuthor">Admin</span></span>
                            </div>

                            {{-- Title --}}
                            <h1 id="viewTitle" class="text-2xl md:text-4xl font-black text-slate-900 leading-tight mb-8"></h1>

                            {{-- HTML Content (Formatted) --}}
                            <div id="viewBody" class="prose prose-lg max-w-none text-slate-600">
                                {{-- Content injected via JS --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        // Scroll Reveal
        document.addEventListener('DOMContentLoaded', () => {
            ScrollReveal().reveal('.reveal-hero', { delay: 200, distance: '40px', origin: 'bottom', duration: 1000 });
            ScrollReveal().reveal('.reveal-up', { delay: 300, distance: '20px', origin: 'bottom', duration: 800 });
            ScrollReveal().reveal('.reveal-card', { interval: 100, distance: '30px', origin: 'bottom', duration: 800, scale: 0.95 });
        });

        // --- MODAL LOGIC ---
        function toggleModal(show) {
            const modal = document.getElementById('newsModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Stop scroll body
                
                // Animation In
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    panel.classList.remove('scale-95', 'opacity-0');
                    panel.classList.add('modal-enter');
                }, 10);
            } else {
                // Animation Out
                backdrop.classList.add('opacity-0');
                panel.classList.remove('modal-enter');
                panel.classList.add('modal-leave');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    panel.classList.remove('modal-leave');
                    // Reset classes for next open
                    panel.classList.add('scale-95', 'opacity-0'); 
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        function closeNews() { toggleModal(false); }

        async function openNews(id) {
            toggleModal(true);
            
            // Show Loader, Hide Content
            document.getElementById('modalLoader').classList.remove('hidden');
            document.getElementById('modalContent').classList.add('hidden');

            try {
                // Fetch JSON from Controller
                const res = await fetch(`/berita/${id}/detail`);
                if(!res.ok) throw new Error("Gagal memuat berita");
                
                const data = await res.json();

                // Populate Data
                document.getElementById('viewTitle').innerText = data.judul;
                document.getElementById('viewDate').innerText = data.tanggal;
                document.getElementById('viewAuthor').innerText = data.penulis || 'Admin';
                
                // Handle Image
                const imgEl = document.getElementById('viewImage');
                imgEl.src = data.gambar ? data.gambar : `https://source.unsplash.com/1200x600/?office,news&sig=${id}`;

                // Handle HTML Content
                document.getElementById('viewBody').innerHTML = data.isi;

                // Hide Loader, Show Content (with slight delay for smoothness)
                setTimeout(() => {
                    document.getElementById('modalLoader').classList.add('hidden');
                    document.getElementById('modalContent').classList.remove('hidden');
                    // Scroll to top of modal
                    document.getElementById('modalContent').scrollTop = 0;
                }, 300);

            } catch (error) {
                console.error(error);
                closeNews();
                // Optional: Show SweetAlert error
            }
        }
    </script>
@endsection