@extends('user.layouts.app')
@section('title', 'Lacak Pengajuan')

@section('content')
    {{-- FONT MEWAH & MODERN --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .font-serif { font-family: 'Playfair Display', serif; }

        /* SEARCH BAR COMPACT */
        .search-wrapper {
            display: flex; align-items: center; gap: 0.5rem;
            background: #FFFFFF; border: 1px solid #E2E8F0;
            border-radius: 99px; /* Pill Shape */
            padding: 0.35rem 0.35rem 0.35rem 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease; max-width: 600px; margin: 0 auto;
        }
        .search-wrapper:focus-within {
            border-color: #94A3B8;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }
        .search-input {
            flex: 1; border: none; outline: none; background: transparent;
            font-size: 1rem; color: #334155; font-weight: 500;
        }
        .search-input::placeholder { color: #94A3B8; font-weight: 400; }
        
        .btn-search-compact {
            background: #1E293B; /* Slate-800 (Dark but soft) */
            color: white; border: none;
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-search-compact:hover {
            background: #0F172A; /* Slate-900 */
            transform: scale(1.05);
        }

        /* RESULT CARD (Tetap sama seperti sebelumnya) */
        .status-header {
            padding: 2rem; border-radius: 1.5rem 1.5rem 0 0; color: white; position: relative; overflow: hidden;
        }
        .status-header::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        }
        
        /* STATUS COLORS */
        .bg-process { background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%); }
        .bg-success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
        .bg-reject  { background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%); }
        .bg-pending { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }

        /* TIMELINE */
        .timeline-box { position: relative; padding-left: 2.5rem; margin-bottom: 2rem; }
        .timeline-box:last-child { margin-bottom: 0; }
        .timeline-line { position: absolute; left: 15px; top: 8px; bottom: -2rem; width: 2px; background: #E2E8F0; z-index: 0; }
        .timeline-box:last-child .timeline-line { display: none; }
        .timeline-marker { position: absolute; left: 0; top: 0; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10; font-size: 0.75rem; border: 4px solid #FFF; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

        /* ANIMATION */
        .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(30px); }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8">
        
        {{-- 1. TITLE SECTION --}}
        <div class="text-center mb-10 slide-up">
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Lacak Status Dokumen</h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">Pantau proses pengajuan layanan Anda secara transparan dan real-time.</p>
        </div>

        {{-- 2. SEARCH BAR (COMPACT & INLINE) --}}
        <div class="mb-16 slide-up" style="animation-delay: 0.1s;">
            <form action="{{ route('user.tracking') }}" method="GET">
                <div class="search-wrapper">
                    <i class="fas fa-search text-slate-400 text-lg ml-2"></i>
                    <input type="text" name="q" value="{{ $keyword }}" 
                           class="search-input" 
                           placeholder="Nomor Surat (Contoh: 18429/Setjen/4123)..." 
                           autocomplete="off">
                    <button type="submit" class="btn-search-compact" title="Cari Dokumen">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. RESULT AREA --}}
        @if($keyword)
            <div class="max-w-5xl mx-auto slide-up" style="animation-delay: 0.2s;">
                @if($data)
                    @php
                        // Logic Decoder Aman
                        $umum = is_string($data->data_umum) ? json_decode($data->data_umum, true) : $data->data_umum;
                        if (is_string($umum)) $umum = json_decode($umum, true);
                        if (!is_array($umum)) $umum = [];
                        
                        $nomorSurat = $umum['nomor_surat_usulan'] ?? $umum['nomor_surat'] ?? 'Tanpa Nomor';
                        $unitKerja = $umum['unit_kerja'] ?? $data->user->unit_kerja ?? '-';

                        // Warna Header Berdasarkan Status
                        $headerClass = 'bg-process'; // Default Indigo
                        $iconStatus = 'fa-sync-alt fa-spin';
                        $statusLabel = 'Sedang Diproses';

                        if($data->status == 'diterima') {
                            $headerClass = 'bg-success'; $iconStatus = 'fa-check-circle'; $statusLabel = 'Selesai';
                        } elseif($data->status == 'pending') {
                            $headerClass = 'bg-pending'; $iconStatus = 'fa-paper-plane'; $statusLabel = 'Menunggu Antrian';
                        } elseif($timeline['is_rejected']) {
                            $headerClass = 'bg-reject'; $iconStatus = 'fa-exclamation-triangle'; 
                            $statusLabel = $timeline['reject_type'] == 'ditolak' ? 'Pengajuan Ditolak' : 'Perlu Revisi';
                        }
                    @endphp

                    {{-- MAIN CARD --}}
                    <div class="bg-white rounded-[1.5rem] shadow-xl border border-slate-200 overflow-hidden">
                        
                        {{-- A. STATUS HEADER BANNER --}}
                        <div class="status-header {{ $headerClass }}">
                            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 relative z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-white text-2xl border border-white/30">
                                        <i class="fas {{ $iconStatus }}"></i>
                                    </div>
                                    <div>
                                        <p class="text-white/80 text-xs font-bold uppercase tracking-wider mb-1">Status Terkini</p>
                                        <h2 class="text-2xl md:text-3xl font-serif font-bold text-white">{{ $statusLabel }}</h2>
                                    </div>
                                </div>
                                <div class="text-left md:text-right">
                                    <p class="text-white/80 text-xs font-bold uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                                    <p class="text-white font-bold font-mono">{{ $data->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row">
                            {{-- B. DETAIL INFORMATION (Left) --}}
                            <div class="lg:w-1/3 bg-slate-50 border-r border-slate-200 p-8">
                                <h4 class="text-slate-900 font-bold text-lg mb-6 flex items-center gap-2">
                                    <i class="fas fa-file-alt text-slate-400"></i> Detail Dokumen
                                </h4>
                                
                                <div class="space-y-6">
                                    <div class="group">
                                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Nomor Surat</p>
                                        <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                                            <span class="font-mono font-bold text-indigo-600 text-sm break-all">{{ $nomorSurat }}</span>
                                            <button onclick="copyToClipboard('{{ $nomorSurat }}')" class="text-slate-400 hover:text-slate-700" title="Salin"><i class="far fa-copy"></i></button>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Jenis Layanan</p>
                                        <p class="font-bold text-slate-700 text-sm leading-relaxed">{{ $data->layanan->nama ?? '-' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">Pengusul</p>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-xs font-bold">{{ substr($data->user->name, 0, 1) }}</div>
                                            <div>
                                                <p class="font-bold text-slate-700 text-sm">{{ $data->user->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $unitKerja }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- C. TIMELINE & HISTORY (Right) --}}
                            <div class="lg:w-2/3 p-8 bg-white">
                                <h4 class="text-slate-900 font-bold text-lg mb-6 flex items-center gap-2">
                                    <i class="fas fa-history text-slate-400"></i> Riwayat Proses
                                </h4>

                                {{-- Alert if Rejected/Revisi --}}
                                @if($timeline['is_rejected'])
                                    <div class="mb-8 bg-rose-50 border border-rose-100 rounded-xl p-5">
                                        <h5 class="text-rose-800 font-bold text-sm mb-2 flex items-center gap-2">
                                            <i class="fas fa-comment-alt"></i> Catatan Verifikator:
                                        </h5>
                                        <p class="text-rose-700 text-sm italic">"{{ $timeline['alasan_penolakan'] ?? '-' }}"</p>
                                        
                                        @if($timeline['reject_type'] == 'perlu_revisi')
                                            <div class="mt-4 text-right">
                                                <a href="{{ route('user.pages.riwayat-pengajuan') }}" class="text-xs font-bold text-white bg-rose-600 px-4 py-2 rounded-lg hover:bg-rose-700 transition shadow-lg shadow-rose-200">
                                                    Perbaiki Dokumen &rarr;
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="pl-2">
                                    @foreach($timeline['steps'] as $step)
                                        @php
                                            $state = 'bg-slate-100 text-slate-300 border-slate-200'; // Default Pending
                                            $textState = 'text-slate-400';
                                            $descState = 'text-slate-300';
                                            
                                            if($step['status'] == 'completed') {
                                                $state = 'bg-emerald-100 text-emerald-600 border-emerald-200';
                                                $textState = 'text-slate-800'; $descState = 'text-slate-500';
                                            }
                                            if($step['status'] == 'current') {
                                                $state = 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-200';
                                                $textState = 'text-indigo-700'; $descState = 'text-slate-600';
                                            }
                                        @endphp

                                        <div class="timeline-box">
                                            <div class="timeline-line"></div>
                                            <div class="timeline-marker {{ $state }}">
                                                <i class="fas {{ $step['icon'] }} text-xs"></i>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                                                <div>
                                                    <h5 class="font-bold text-sm {{ $textState }}">{{ $step['label'] }}</h5>
                                                    <p class="text-xs {{ $descState }} mt-1 leading-relaxed max-w-sm">{{ $step['desc'] }}</p>
                                                </div>
                                                @if($step['status'] != 'pending')
                                                    <span class="text-[10px] font-mono font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100 mt-2 sm:mt-0 w-fit">
                                                        {{ $step['date'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- NOT FOUND --}}
                    <div class="bg-white rounded-[2rem] shadow-xl p-12 text-center border border-slate-200 max-w-2xl mx-auto fade-in-up">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                            <i class="fas fa-search-minus text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-serif font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-slate-500 mb-8 px-8">Maaf, kami tidak menemukan pengajuan dengan kata kunci <br> <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded">"{{ $keyword }}"</span></p>
                        <a href="{{ route('user.tracking') }}" class="text-indigo-600 font-bold hover:text-indigo-800 transition">Reset Pencarian</a>
                    </div>
                @endif
            @else
                {{-- INITIAL STATE (Kosong tapi estetik) --}}
                <div class="text-center py-20 opacity-30 fade-in-up">
                    <i class="fas fa-search-location text-8xl text-slate-300 mb-4 block"></i>
                    <span class="text-slate-400 font-serif text-xl italic">Silakan masukkan nomor surat untuk melacak.</span>
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            alert('Nomor Surat disalin!');
        }
    </script>
@endsection