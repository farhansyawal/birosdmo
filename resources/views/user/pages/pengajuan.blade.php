@extends('user.layouts.app')
@section('title', 'Pengajuan Layanan')

@section('content')
    {{-- 1. FONTS & MODERN CSS --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        /* Layout & Transitions */
        .animate-up {
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .layer {
            display: none;
        }

        .layer.active {
            display: block;
            animation: slideUp 0.4s ease-out;
        }

        /* Modern Input Style (Apple/Linear Style) */
        .modern-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.5rem;
            display: block;
        }

        .modern-input {
            width: 100%;
            background-color: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .modern-input:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            /* Indigo 500 */
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.2);
            outline: none;
        }

        /* Service Card Selection */
        .service-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .service-card:hover:not(.disabled) {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        .service-card.selected {
            background: #4f46e5;
            /* Indigo 600 */
            border-color: #4f46e5;
            color: white;
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.4);
        }

        .service-card.selected h4,
        .service-card.selected p,
        .service-card.selected i {
            color: white !important;
        }

        /* Vertical Stepper */
        .step-item {
            position: relative;
            padding-left: 2.5rem;
            padding-bottom: 2.5rem;
            border-left: 2px solid #e2e8f0;
        }

        .step-item:last-child {
            border-left: transparent;
            padding-bottom: 0;
        }

        .step-dot {
            position: absolute;
            left: -0.6rem;
            top: 0;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #cbd5e1;
            transition: all 0.3s;
        }

        .step-item.active .step-dot {
            background: #4f46e5;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }

        .step-item.completed .step-dot {
            background: #10b981;
            border-color: #10b981;
        }
    </style>

    <div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="w-full max-w-7xl">
            {{-- MAIN CONTAINER (SPLIT LAYOUT) --}}
            <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/60 overflow-hidden flex flex-col lg:flex-row min-h-[750px] border border-slate-100">
                
                {{-- LEFT SIDEBAR (Sticky Info & Progress) --}}
                <div class="lg:w-1/3 bg-slate-50/80 backdrop-blur-sm p-10 flex flex-col justify-between border-r border-slate-200 relative overflow-hidden">
                    {{-- Decorative Blobs --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-100/50 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-100/50 rounded-full blur-3xl -ml-32 -mb-32 pointer-events-none"></div>

                    <div class="relative z-10">
                        {{-- Logo / Title --}}
                        <div class="flex items-center gap-4 mb-12">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Portal Layanan</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Pengajuan Baru</p>
                            </div>
                        </div>

                        {{-- Vertical Stepper --}}
                        <div class="space-y-1 pl-2">
                            <div class="step-item active" id="step-nav-1">
                                <div class="step-circle">1</div>
                                <h4 class="text-sm font-bold text-slate-800">Administrasi Surat</h4>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Informasi dasar surat pengantar.</p>
                            </div>
                            <div class="step-item" id="step-nav-2">
                                <div class="step-circle">2</div>
                                <h4 class="text-sm font-bold text-slate-400">Pilih Layanan</h4>
                                <p class="text-xs text-slate-400 mt-1 font-medium">Tentukan jenis layanan yang dituju.</p>
                            </div>
                            <div class="step-item" id="step-nav-3">
                                <div class="step-circle">3</div>
                                <h4 class="text-sm font-bold text-slate-400">Lengkapi Berkas</h4>
                                <p class="text-xs text-slate-400 mt-1 font-medium">Upload dokumen persyaratan.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Help Box --}}
                    <div class="relative z-10 mt-10">
                        <div class="p-5 bg-white/60 backdrop-blur rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow cursor-help">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0"><i class="fas fa-headset"></i></div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Butuh Bantuan?</p>
                                <span class="text-sm font-bold text-indigo-600">Hubungi Helpdesk &rarr;</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT CONTENT (Dynamic Forms) --}}
                <div class="lg:w-2/3 p-8 md:p-12 relative bg-white overflow-y-auto custom-scroll max-h-[750px]">
                    
                    {{-- LAYER 1: DATA UMUM --}}
                    <div id="layer1" class="layer active">
                        <div class="max-w-2xl mx-auto">
                            <div class="mb-10">
                                <span class="inline-block py-1 px-3 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mb-3 border border-slate-200">Langkah 1 dari 3</span>
                                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Mulai Pengajuan</h3>
                                <p class="text-slate-500 font-medium">Silakan lengkapi data surat pengantar dari unit kerja Anda.</p>
                            </div>
                            
                            <form id="formUmum" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="col-span-1">
                                        <label class="modern-label">Nomor Surat <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nomor_surat_usulan" class="modern-input" placeholder="Contoh: 800/123/Setjen" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="modern-label">Tanggal Surat <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tanggal_surat_usulan" class="modern-input" required>
                                    </div>
                                    <div class="col-span-full">
                                        <label class="modern-label">Perihal Surat <span class="text-rose-500">*</span></label>
                                        <input type="text" name="perihal_surat_usulan" class="modern-input" placeholder="Contoh: Usulan Kenaikan Pangkat..." required>
                                    </div>
                                    <div class="col-span-full">
                                        <label class="modern-label">Unit Kerja Pengusul <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <select id="unitKerjaSelect" name="unit_kerja" class="modern-input appearance-none cursor-pointer" required>
                                                <option value="" disabled selected>Memuat data...</option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><i class="fas fa-chevron-down"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-10 flex justify-end">
                                    <button type="button" onclick="nextLayer(2)" class="px-8 py-4 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-slate-200 hover:shadow-indigo-200 transition-all transform active:scale-95 flex items-center gap-3 group">
                                        Selanjutnya <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- LAYER 2: PILIH LAYANAN --}}
                    <div id="layer2" class="layer">
                        <div class="max-w-4xl mx-auto">
                            <div class="mb-8 flex items-end justify-between border-b border-slate-100 pb-6">
                                <div>
                                    <span class="inline-block py-1 px-3 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mb-3 border border-slate-200">Langkah 2 dari 3</span>
                                    <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">Katalog Layanan</h3>
                                    <p class="text-slate-500 font-medium">Pilih jenis layanan yang Anda butuhkan.</p>
                                </div>
                                <div class="hidden md:block text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-200">
                                    {{ $layanans->where('is_active', 1)->count() }} Layanan Tersedia
                                </div>
                            </div>

                            {{-- Service Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-4" id="layananList">
                                @foreach ($layanans as $layanan)
                                    <div @if ($layanan->is_active) onclick="selectLayanan({{ $layanan->id }}, '{{ addslashes($layanan->nama) }}', this)" @endif 
                                         class="service-card group rounded-2xl p-5 flex items-start gap-4 {{ !$layanan->is_active ? 'opacity-50 grayscale cursor-not-allowed disabled' : '' }}">
                                        
                                        <div class="icon-box w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white group-hover:text-indigo-600 transition-colors shrink-0">
                                            <i class="fas fa-box-open text-xl"></i>
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <h4 class="font-bold text-slate-900 text-base mb-1 group-hover:text-indigo-600 transition-colors">{{ $layanan->nama }}</h4>
                                                {{-- Check Icon (Hidden default, visible if selected) --}}
                                                @if($layanan->is_active)
                                                    <div class="w-6 h-6 rounded-full border-2 border-slate-200 flex items-center justify-center group-[.selected]:bg-white group-[.selected]:border-white transition-all">
                                                        <i class="fas fa-check text-[10px] text-indigo-600 opacity-0 group-[.selected]:opacity-100 transform scale-0 group-[.selected]:scale-100 transition-all"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ $layanan->deskripsi }}</p>
                                            @unless ($layanan->is_active)
                                                <span class="mt-2 inline-block text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded">Non-Aktif</span>
                                            @endunless
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-6 border-t border-slate-100 flex justify-between items-center mt-6">
                                <button onclick="prevLayer(1)" class="text-slate-500 hover:text-slate-800 font-bold text-sm flex items-center gap-2 transition-colors px-4 py-2 rounded-lg hover:bg-slate-50">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- LAYER 3: FORM DETAIL --}}
                    <div id="layer3" class="layer">
                        <div class="max-w-2xl mx-auto">
                            <div class="mb-10">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold mb-4 border border-emerald-100 shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Langkah Terakhir
                                </div>
                                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Detail Pengajuan</h3>
                                <p class="text-slate-500 font-medium text-sm">Anda memilih layanan: <span id="layananTitle" class="text-indigo-600 font-bold underline decoration-wavy decoration-indigo-300"></span></p>
                            </div>

                            <form id="layananForm" method="POST" enctype="multipart/form-data" action="" class="space-y-6">
                                @csrf
                                <div id="formFields" class="grid grid-cols-1 gap-6">
                                    {{-- Loader Skeleton (Default State) --}}
                                    <div class="col-span-full space-y-5 animate-pulse opacity-50">
                                        <div class="space-y-2"><div class="skeleton h-3 w-32"></div><div class="skeleton h-12 w-full"></div></div>
                                        <div class="space-y-2"><div class="skeleton h-3 w-24"></div><div class="skeleton h-12 w-full"></div></div>
                                        <div class="space-y-2 pt-2"><div class="skeleton h-3 w-40"></div><div class="skeleton h-32 w-full"></div></div>
                                    </div>
                                </div>

                                <div class="pt-8 border-t border-slate-100 mt-10 flex justify-between items-center">
                                    <button type="button" onclick="prevLayer(2)" class="text-slate-500 hover:text-slate-800 font-bold text-sm flex items-center gap-2 transition-colors px-4 py-2 rounded-lg hover:bg-slate-50">
                                        <i class="fas fa-arrow-left"></i> Ubah Layanan
                                    </button>

                                    <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-xl shadow-indigo-200 transition-all transform active:scale-95 flex items-center gap-3 group">
                                        <span>Kirim Berkas</span>
                                        <i class="fas fa-paper-plane group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- === JAVASCRIPT LOGIC === --}}
    <script>
        let selectedLayananId = null;
        const formatLabel = (str) => str.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

        // --- STEPPER VISUAL ---
        function updateStepVisual(step) {
            for (let i = 1; i <= 3; i++) {
                const el = document.getElementById(`step-nav-${i}`);
                const title = el.querySelector('h4');
                const desc = el.querySelector('p');

                if (i === step) {
                    el.classList.add('active'); el.classList.remove('completed');
                    title.classList.remove('text-slate-400'); title.classList.add('text-slate-800');
                    desc.classList.remove('text-slate-400'); desc.classList.add('text-slate-500');
                } else if (i < step) {
                    el.classList.remove('active'); el.classList.add('completed');
                    title.classList.remove('text-slate-800'); title.classList.add('text-slate-400');
                    desc.classList.remove('text-slate-500'); desc.classList.add('text-slate-400');
                } else {
                    el.classList.remove('active', 'completed');
                    title.classList.remove('text-slate-800'); title.classList.add('text-slate-400');
                    desc.classList.remove('text-slate-500'); desc.classList.add('text-slate-400');
                }
            }
        }

        function nextLayer(n) {
            if (n === 2) {
                const form = document.getElementById('formUmum');
                if (!form.checkValidity()) return form.reportValidity();
            }
            switchLayer(n);
        }

        function prevLayer(n) { switchLayer(n); }

        function switchLayer(n) {
            document.querySelectorAll('.layer').forEach(el => el.classList.remove('active'));
            setTimeout(() => { document.getElementById('layer' + n).classList.add('active'); }, 50);
            updateStepVisual(n);
        }

        // --- MAIN LOGIC: SELECT LAYANAN & RENDER FIELD ---
        function selectLayanan(id, nama, element) {
            document.querySelectorAll('.service-card').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');

            selectedLayananId = id;
            document.getElementById('layananTitle').textContent = nama;
            document.getElementById('layananForm').action = `/user/pages/pengajuan/submit/${id}`;

            const container = document.getElementById('formFields');
            container.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center py-20 text-slate-400"><i class="fas fa-circle-notch fa-spin text-4xl mb-4 text-indigo-500"></i><p>Menyiapkan formulir...</p></div>`;

            setTimeout(() => nextLayer(3), 300);

            fetch(`/api/layanan/${id}/fields`)
                .then(res => res.json())
                .then(data => {
                    data.sort((a, b) => a.queue - b.queue);
                    const skipFields = ['nomor_surat_usulan', 'perihal_surat_usulan', 'tanggal_surat_usulan', 'unit_kerja'];
                    container.innerHTML = '';

                    const filteredData = data.filter(f => !skipFields.includes(f.name));

                    if (filteredData.length === 0) {
                        container.innerHTML = `<div class="col-span-full text-center py-12 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200"><p class="text-slate-500 font-medium">Tidak ada data tambahan yang perlu diisi.</p></div>`;
                        return;
                    }

                    filteredData.forEach(field => {
                        let inputHtml = '';
                        const reqAttr = field.is_required ? 'required' : '';
                        const star = field.is_required ? '<span class="text-rose-500">*</span>' : '';
                        let customId = '';

                        // --- DETEKSI NAMA FIELD (FLEXIBLE) ---
                        const fName = field.name.toLowerCase();
                        if (fName.includes('jabatan')) customId = 'id="jabatanDropdown"';
                        else if (fName.includes('pangkat') || fName.includes('golongan')) customId = 'id="pangkatDropdown"';
                        else if (fName.includes('pensiun')) customId = 'id="pensiunDropdown"'; // <--- PENSIUN
                        else if (fName.includes('kenaikanPangkat')) customId = 'id="kenaikanPangkatDropdown"'; // <--- PENSIUN

                        switch (field.type) {
                            case 'textarea':
                                inputHtml = `<textarea name="${field.name}" rows="3" class="modern-input" placeholder="..." ${reqAttr}></textarea>`;
                                break;
                            case 'select':
                                let opts = `<option value="" disabled selected>-- Pilih --</option>`;
                                if (field.options && !customId) {
                                    const arr = Array.isArray(field.options) ? field.options : field.options.split(',');
                                    arr.forEach(o => opts += `<option value="${o.trim()}">${o.trim()}</option>`);
                                }
                                inputHtml = `<div class="relative"><select name="${field.name}" ${customId} class="modern-input appearance-none cursor-pointer" ${reqAttr}>${opts}</select><i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i></div>`;
                                break;
                            case 'file':
                                inputHtml = `<div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:bg-slate-50 transition cursor-pointer group">
                                                    <input type="file" name="${field.name}" accept="${field.accept ?? '*/*'}" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" ${reqAttr}>
                                                    <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 group-hover:text-indigo-500 mb-2 transition-colors"></i>
                                                    <p class="text-sm font-bold text-slate-600">Klik untuk upload file</p>
                                                    <p class="text-xs text-slate-400 mt-1">Format PDF/JPG (Max 2MB)</p>
                                                   </div>`;
                                break;
                            default:
                                inputHtml = `<input type="${field.type}" name="${field.name}" class="modern-input" placeholder="..." ${reqAttr}>`;
                        }

                        container.innerHTML += `
                                <div class="${field.type === 'textarea' ? 'col-span-full' : ''}">
                                    <label class="modern-label">${field.label || formatLabel(field.name)} ${star}</label>
                                    ${inputHtml}
                                </div>`;
                    });

                    loadDropdownData();
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = '<p class="text-rose-500 col-span-full text-center">Gagal memuat form.</p>';
                });
        }

        // --- LOAD DROPDOWN DARI API ---
        function loadDropdownData() {
            // Helper function
            const populate = (id, url, placeholder) => {
                const el = document.getElementById(id);
                if (el) {
                    fetch(url).then(r => r.json()).then(data => {
                        el.innerHTML = `<option value="" disabled selected>-- ${placeholder} --</option>`;
                        data.forEach(item => {
                            let val = (item && item.value) ? item.value : item;
                            let lbl = (item && item.label) ? item.label : item;
                            el.innerHTML += `<option value="${val}">${lbl}</option>`;
                        });
                    }).catch(e => console.error(e));
                }
            };

            populate('jabatanDropdown', '/api/jabatan', 'Pilih Jabatan');
            populate('pangkatDropdown', '/api/pangkat', 'Pilih Pangkat');
            populate('pensiunDropdown', '/api/pensiun', 'Pilih Jenis Pensiun');
            // Menjadi ini (sesuai route web.php):
            populate('kenaikanPangkatDropdown', '/api/kenaikan-pangkat', 'Pilih Jenis Kenaikan Pangkat');
        }

        // --- INIT ---
        document.addEventListener('DOMContentLoaded', () => {
            // Load Unit Kerja (Static)
            const unitSelect = document.getElementById('unitKerjaSelect');
            if (unitSelect) {
                fetch('/api/unit-kerja').then(r => r.json()).then(data => {
                    unitSelect.innerHTML = '<option value="" disabled selected>-- Pilih Unit Kerja --</option>';
                    data.forEach(u => unitSelect.innerHTML += `<option value="${u.value}">${u.label}</option>`);
                });
            }

            // Handle Submit
            document.getElementById('layananForm').addEventListener('submit', function (e) {
                const formUmum = document.getElementById('formUmum');
                formUmum.querySelectorAll('input, select').forEach(i => {
                    if (i.name) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden'; hidden.name = i.name; hidden.value = i.value;
                        this.appendChild(hidden);
                    }
                });
            });
        });
    </script>
@endsection