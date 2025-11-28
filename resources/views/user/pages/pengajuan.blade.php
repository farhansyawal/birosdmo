@extends('user.layouts.app')
@section('title', 'Pengajuan Layanan')

@section('content')
    {{-- 1. FONTS & MODERN CSS --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        /* Layout & Transitions */
        .animate-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .layer { display: none; }
        .layer.active { display: block; animation: slideUp 0.4s ease-out; }

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
            border-color: #6366f1; /* Indigo 500 */
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
            background: #4f46e5; /* Indigo 600 */
            border-color: #4f46e5;
            color: white;
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.4);
        }
        .service-card.selected h4, 
        .service-card.selected p, 
        .service-card.selected i { color: white !important; }

        /* Vertical Stepper */
        .step-item { position: relative; padding-left: 2.5rem; padding-bottom: 2.5rem; border-left: 2px solid #e2e8f0; }
        .step-item:last-child { border-left: transparent; padding-bottom: 0; }
        
        .step-dot {
            position: absolute; left: -0.6rem; top: 0;
            width: 1.25rem; height: 1.25rem;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #cbd5e1;
            transition: all 0.3s;
        }
        .step-item.active .step-dot {
            background: #4f46e5; border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }
        .step-item.completed .step-dot {
            background: #10b981; border-color: #10b981;
        }
    </style>

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="w-full max-w-6xl">
            
            <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 overflow-hidden flex flex-col lg:flex-row min-h-[600px]">
                
                {{-- LEFT PANEL: SIDEBAR PROGRESS --}}
                <div class="lg:w-1/3 bg-slate-50 p-10 flex flex-col justify-between border-r border-slate-100">
                    <div>
                        <div class="flex items-center gap-3 mb-10">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pengajuan</h2>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Portal Layanan</p>
                            </div>
                        </div>

                        {{-- Vertical Stepper --}}
                        <div class="mt-8">
                            <div class="step-item active" id="step-nav-1">
                                <div class="step-dot"></div>
                                <h4 class="text-sm font-bold text-slate-800">Data Administrasi</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Informasi dasar surat pengajuan.</p>
                            </div>
                            <div class="step-item" id="step-nav-2">
                                <div class="step-dot"></div>
                                <h4 class="text-sm font-bold text-slate-400">Pilih Layanan</h4>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">Jenis layanan yang dibutuhkan.</p>
                            </div>
                            <div class="step-item" id="step-nav-3">
                                <div class="step-dot"></div>
                                <h4 class="text-sm font-bold text-slate-400">Kelengkapan Form</h4>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">Isi detail formulir spesifik.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto pt-10">
                        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm">
                            <p class="text-xs text-slate-500 mb-2 font-medium">Butuh bantuan?</p>
                            <a href="#" class="text-sm font-bold text-indigo-600 flex items-center gap-2 hover:underline">
                                <i class="fas fa-headset"></i> Hubungi Support
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT PANEL: FORM CONTENT --}}
                <div class="lg:w-2/3 p-8 md:p-12 relative overflow-y-auto max-h-[800px] custom-scroll">
                    
                    {{-- LAYER 1: DATA UMUM --}}
                    <div id="layer1" class="layer active">
                        <div class="mb-8">
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">Mulai Pengajuan</h3>
                            <p class="text-slate-500">Silakan isi data surat pengantar terlebih dahulu.</p>
                        </div>

                        <form id="formUmum" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="modern-label">Nomor Surat</label>
                                    <input type="text" name="nomor_surat_usulan" class="modern-input" placeholder="Nomor Surat Usulan" required>
                                </div>
                                <div>
                                    <label class="modern-label">Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat_usulan" class="modern-input" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="modern-label">Perihal</label>
                                    <input type="text" name="perihal_surat_usulan" class="modern-input" placeholder="Perihal pengajuan..." required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="modern-label">Unit Kerja</label>
                                    <div class="relative">
                                        <select id="unitKerjaSelect" name="unit_kerja" class="modern-input appearance-none cursor-pointer" required>
                                            <option value="">Memuat data...</option>
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8 flex justify-end">
                                <button type="button" onclick="nextLayer(2)" class="group bg-slate-900 hover:bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-indigo-500/30 transition-all active:scale-95 flex items-center gap-3">
                                    Lanjut Langkah 2 
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- LAYER 2: PILIH LAYANAN --}}
                    <div id="layer2" class="layer">
                        <div class="mb-8">
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">Katalog Layanan</h3>
                            <p class="text-slate-500">Pilih salah satu layanan yang tersedia di bawah ini.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pb-4" id="layananList">
                            @foreach ($layanans as $layanan)
                                <div @if ($layanan->is_active) onclick="selectLayanan({{ $layanan->id }}, '{{ addslashes($layanan->nama) }}', this)" @endif 
                                     class="service-card group {{ !$layanan->is_active ? 'opacity-50 grayscale cursor-not-allowed disabled' : '' }}">
                                    
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-white transition-colors">
                                            <i class="fas fa-box-open text-xl"></i>
                                        </div>
                                        @if($layanan->is_active)
                                            <div class="w-6 h-6 rounded-full border-2 border-slate-200 group-[.selected]:bg-white group-[.selected]:border-white flex items-center justify-center">
                                                <i class="fas fa-check text-indigo-600 opacity-0 group-[.selected]:opacity-100 text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <h4 class="font-bold text-slate-900 text-lg mb-2">{{ $layanan->nama }}</h4>
                                    <p class="text-sm text-slate-500 leading-relaxed">{{ Str::limit($layanan->deskripsi, 100) }}</p>

                                    @unless ($layanan->is_active)
                                        <div class="mt-4 inline-flex items-center gap-1 text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded">
                                            <i class="fas fa-ban"></i> Tidak Tersedia
                                        </div>
                                    @endunless
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                            <button onclick="prevLayer(1)" class="text-slate-400 hover:text-slate-800 font-bold text-sm flex items-center gap-2 transition-colors">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                        </div>
                    </div>

                    {{-- LAYER 3: FORM DETAIL --}}
                    <div id="layer3" class="layer">
                        <div class="mb-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold mb-3 border border-indigo-100">
                                <i class="fas fa-check-circle"></i> Langkah Terakhir
                            </div>
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Detail Pengajuan</h3>
                            <p class="text-slate-500 text-sm mt-1">Layanan: <span id="layananTitle" class="font-bold text-slate-800"></span></p>
                        </div>

                        <form id="layananForm" method="POST" enctype="multipart/form-data" action="" class="space-y-6">
                            @csrf
                            
                            <div id="formFields" class="grid grid-cols-1 gap-6">
                                {{-- JS will inject fields here --}}
                            </div>

                            <div class="pt-8 border-t border-slate-100 mt-8 flex justify-between items-center">
                                <button type="button" onclick="prevLayer(2)" class="text-slate-400 hover:text-slate-800 font-bold text-sm flex items-center gap-2 transition-colors">
                                    <i class="fas fa-arrow-left"></i> Ubah Layanan
                                </button>

                                <button type="submit" class="group bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-bold shadow-xl hover:shadow-indigo-500/40 transition-all active:scale-95 flex items-center gap-3">
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

    {{-- === JAVASCRIPT LOGIC === --}}
    <script>
        let selectedLayananId = null;

        // --- VISUAL STEPPER UPDATE ---
        function updateStepVisual(step) {
            // Reset styles
            for (let i = 1; i <= 3; i++) {
                const el = document.getElementById(`step-nav-${i}`);
                const title = el.querySelector('h4');
                const desc = el.querySelector('p');
                const dot = el.querySelector('.step-dot');

                if (i === step) {
                    // Active
                    el.classList.add('active');
                    el.classList.remove('completed');
                    title.classList.remove('text-slate-400'); title.classList.add('text-slate-800');
                    desc.classList.remove('text-slate-400'); desc.classList.add('text-slate-500');
                } else if (i < step) {
                    // Completed
                    el.classList.remove('active');
                    el.classList.add('completed');
                    title.classList.remove('text-slate-800'); title.classList.add('text-slate-400');
                    desc.classList.remove('text-slate-500'); desc.classList.add('text-slate-400');
                } else {
                    // Future
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

        function prevLayer(n) {
            switchLayer(n);
        }

        function switchLayer(n) {
            document.querySelectorAll('.layer').forEach(el => el.classList.remove('active'));
            
            setTimeout(() => {
                const target = document.getElementById('layer' + n);
                target.classList.add('active');
            }, 50); // Slight delay for animation reset

            updateStepVisual(n);
        }

        // --- LOGIC PILIH LAYANAN & RENDER FIELD ---
        function selectLayanan(id, nama, element) {
            // Highlight Card
            document.querySelectorAll('.service-card').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');

            selectedLayananId = id;
            document.getElementById('layananTitle').textContent = nama;
            
            const form = document.getElementById('layananForm');
            form.action = `/user/pages/pengajuan/submit/${id}`;

            const container = document.getElementById('formFields');
            container.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-slate-400">
                    <i class="fas fa-circle-notch fa-spin text-4xl mb-4 text-indigo-500"></i>
                    <p class="font-medium">Menyiapkan formulir...</p>
                </div>`;
            
            // Pindah layar dulu biar smooth
            setTimeout(() => nextLayer(3), 300);

            fetch(`/api/layanan/${id}/fields`)
                .then(res => res.json())
                .then(data => {
                    data.sort((a, b) => a.queue - b.queue);

                    const skipFields = ['nomor_surat_usulan', 'perihal_surat_usulan', 'tanggal_surat_usulan', 'unit_kerja'];
                    container.innerHTML = '';
                    
                    const filteredData = data.filter(f => !skipFields.includes(f.name));

                    if (filteredData.length === 0) {
                        container.innerHTML = `
                            <div class="col-span-full text-center py-12 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                <p class="text-slate-500 font-medium">Tidak ada data tambahan yang perlu diisi.</p>
                            </div>`;
                        return;
                    }

                    filteredData.forEach(field => {
                        let inputHtml = '';
                        const reqAttr = field.is_required ? 'required' : '';
                        const star = field.is_required ? '<span class="text-rose-500">*</span>' : '';
                        let customId = '';
                        if (field.name === 'jabatan_pegawai_yang_diusulkan') customId = 'id="jabatanDropdown"';
                        if (field.name === 'pangkat_dan_golongan') customId = 'id="pangkatDropdown"';

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
                                inputHtml = `<div class="relative"><select name="${field.name}" ${customId} class="modern-input appearance-none cursor-pointer" ${reqAttr}>${opts}</select>
                                             <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i></div>`;
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
                                <label class="modern-label">${field.label} ${star}</label>
                                ${inputHtml}
                            </div>
                        `;
                    });

                    loadDropdownData();
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = '<p class="text-rose-500 col-span-full text-center">Gagal memuat form.</p>';
                });
        }

        // --- LOAD DROPDOWN ---
        function loadDropdownData() {
            const jabSelect = document.getElementById('jabatanDropdown');
            if (jabSelect) {
                fetch('/api/jabatan').then(r => r.json()).then(data => {
                    jabSelect.innerHTML = '<option value="" disabled selected>-- Pilih Jabatan --</option>';
                    data.forEach(item => {
                        let val = (typeof item === 'object' && item !== null) ? (item.value || item.nama || JSON.stringify(item)) : item;
                        let lbl = (typeof item === 'object' && item !== null) ? (item.label || item.nama || "Pilihan") : item;
                        jabSelect.innerHTML += `<option value="${val}">${lbl}</option>`;
                    });
                });
            }
            const pangSelect = document.getElementById('pangkatDropdown');
            if (pangSelect) {
                fetch('/api/pangkat').then(r => r.json()).then(data => {
                    pangSelect.innerHTML = '<option value="" disabled selected>-- Pilih Pangkat --</option>';
                    data.forEach(item => {
                        let val = (typeof item === 'object' && item !== null) ? (item.value || item.nama || JSON.stringify(item)) : item;
                        let lbl = (typeof item === 'object' && item !== null) ? (item.label || item.nama || "Pilihan") : item;
                        pangSelect.innerHTML += `<option value="${val}">${lbl}</option>`;
                    });
                });
            }
        }

        // --- INIT UNIT KERJA ---
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/api/unit-kerja').then(r => r.json()).then(data => {
                const s = document.getElementById('unitKerjaSelect');
                s.innerHTML = '<option value="" disabled selected>-- Pilih Unit Kerja --</option>';
                data.forEach(u => s.innerHTML += `<option value="${u.value}">${u.label}</option>`);
            });
        });

        // --- SUBMIT ---
        document.getElementById('layananForm').addEventListener('submit', function(e) {
            const formUmum = document.getElementById('formUmum');
            const inputs = formUmum.querySelectorAll('input, select');
            inputs.forEach(i => {
                if(i.name) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = i.name;
                    hidden.value = i.value;
                    this.appendChild(hidden);
                }
            });
        });
    </script>
@endsection