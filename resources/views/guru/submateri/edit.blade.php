@extends('guru.layouts.app')

@section('title', 'Edit Materi - CodeLab')

@section('content')
{{-- Trix Editor Assets --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<div class="max-w-6xl mx-auto p-4 md:p-6" x-data="editMateriHandler()" x-init="initEditor()" x-cloak>
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 px-4">
        <div class="flex items-center gap-5">
            <a href="{{ route('guru.submateri.dashboard', $materi_id) }}" class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Edit Materi</h1>
                <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-widest">
                    Update Konten: <span class="text-blue-600">{{ $subMateri->judul }}</span>
                </p>
            </div>
        </div>

         <div class="flex justify-center mb-8">
            <div class="flex bg-slate-100 p-1.5 rounded-[2rem] border border-slate-200 shadow-inner">
                <button @click="tipeMateri = 'materi'; tab = 'info'" 
                    :class="tipeMateri === 'materi' ? 'bg-white shadow-md text-blue-600' : 'text-slate-400'"
                    class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-book-open"></i> Materi Lengkap
                </button>
                <button @click="tipeMateri = 'kuis'; tab = 'kuis'" 
                    :class="tipeMateri === 'kuis' ? 'bg-amber-500 shadow-md text-white' : 'text-slate-400'"
                    class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-tasks"></i> Hanya Kuis
                </button>
            </div>
        </div>

        <button @click="updateData()" class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition shadow-xl active:scale-95">
            <i class="fas fa-save mr-2"></i> Simpan Perubahan
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        
        {{-- Navigation Tabs --}}
        <div class="flex bg-slate-50/50 p-2 gap-2 border-b border-slate-100">
            <template x-if="tipeMateri !== 'kuis'">
                <div class="flex flex-1 gap-2">
                    <button @click="tab = 'info'" 
                        :class="tab === 'info' ? 'bg-white shadow-md text-blue-600' : 'text-slate-500 hover:bg-white/50'" 
                        class="flex-1 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-[0.1em] transition-all flex items-center justify-center gap-2.5">
                        <i class="fas fa-file-alt"></i> Teks & Bacaan
                    </button>
                    <button @click="tab = 'media'" 
                        :class="tab === 'media' ? 'bg-white shadow-md text-rose-600' : 'text-slate-500 hover:bg-white/50'" 
                        class="flex-1 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-[0.1em] transition-all flex items-center justify-center gap-2.5">
                        <i class="fas fa-play-circle"></i> Video & PDF
                    </button>
                    <button @click="tab = 'coding'" 
                        :class="tab === 'coding' ? 'bg-white shadow-md text-emerald-600' : 'text-slate-500 hover:bg-white/50'" 
                        class="flex-1 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-[0.1em] transition-all flex items-center justify-center gap-2.5">
                        <i class="fas fa-code"></i> Coding Lab
                    </button>
                </div>
            </template>
            
            <template x-if="tipeMateri === 'kuis'">
                <button class="flex-1 py-4 bg-white shadow-sm text-amber-600 rounded-2xl text-[11px] font-black uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fas fa-tasks"></i> Editor Soal Kuis
                </button>
            </template>
        </div>

        <div class="p-8 md:p-12">
            {{-- Metadata --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12 pb-10 border-b border-slate-100">
                <div class="md:col-span-6 space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Judul Sub Materi</label>
                    <input type="text" x-model="form.judul" class="w-full px-6 py-4 bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 rounded-2xl font-bold text-slate-800 transition-all">
                </div>
                <div class="md:col-span-4 space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Kategori</label>
                    <input type="text" x-model="form.kategori" class="w-full px-6 py-4 bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 rounded-2xl font-bold text-slate-800 transition-all">
                </div>
                <div class="md:col-span-2 space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] text-center block">Urutan</label>
                    <input type="number" x-model="form.urutan" class="w-full px-4 py-4 bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 rounded-2xl font-black text-slate-800 text-center transition-all">
                </div>
            </div>

            {{-- 1. TAB INFO: Teks Bacaan --}}
            <div x-show="tab === 'info' && tipeMateri !== 'kuis'">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-6 h-1 bg-blue-500 rounded-full"></span> Konten Bacaan Utama
                    </label>
                    <div class="bg-white rounded-[2rem] border-2 border-slate-100 overflow-hidden shadow-sm">
                        <input id="bacaan_trix" type="hidden" name="bacaan" value="{{ $subMateri->bacaan }}">
                        <trix-editor 
                            x-init="$el.addEventListener('trix-attachment-add', (e) => handleTrixUpload(e))"
                            input="bacaan_trix" 
                            class="prose prose-slate max-w-none min-h-[400px] p-8 focus:outline-none bg-white">
                        </trix-editor>
                    </div>
                </div>
            </div>

            {{-- 2. TAB MEDIA: Youtube & PDF --}}
            <div x-show="tab === 'media' && tipeMateri !== 'kuis'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">ID Video Youtube</label>
                        <div class="relative group">
                            <i class="fab fa-youtube absolute left-6 top-1/2 -translate-y-1/2 text-rose-500 text-xl"></i>
                            <input type="text" x-model="form.video" placeholder="Contoh: dQw4w9WgXcQ" class="w-full pl-16 pr-6 py-5 bg-slate-50 border-none focus:ring-2 focus:ring-rose-500 rounded-[1.5rem] font-bold text-slate-700 transition-all">
                        </div>
                        <p class="text-[10px] text-slate-400 italic mt-2">*ID Video saat ini: <span class="text-slate-800 font-bold" x-text="form.video || '-'"></span></p>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Dokumen PDF</label>
                        <div @click="$refs.fileInput.click()" class="border-2 border-dashed border-slate-200 rounded-[1.5rem] p-8 flex flex-col items-center justify-center bg-slate-50 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all group text-center">
                            <input type="file" x-ref="fileInput" class="hidden" @change="form.pdfName = $event.target.files[0].name">
                            <i class="fas fa-file-pdf text-2xl mb-3 text-slate-400 group-hover:text-blue-500"></i>
                            <span class="text-[11px] font-black text-slate-500 uppercase" x-text="form.pdfName || 'Klik untuk upload PDF Baru'"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. TAB CODING LAB: Hanya di sini Code Editor muncul --}}
            <div x-show="tab === 'coding' && tipeMateri !== 'kuis'" class="space-y-10">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-6 h-1 bg-emerald-500 rounded-full"></span> Instruksi Lab
                    </label>
                    <div class="bg-white rounded-[2rem] border-2 border-slate-100 overflow-hidden shadow-sm">
                        <input id="instruksi_trix" type="hidden" name="instruksi" value="{{ $subMateri->instruksi_coding }}">
                        <trix-editor input="instruksi_trix" class="prose max-w-none min-h-[150px] p-8 focus:outline-none"></trix-editor>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Starter Code (Editor View)</label>
                    <div class="rounded-[2rem] overflow-hidden border-4 border-slate-900 shadow-2xl">
                        <div class="bg-[#1e1e1e] px-6 py-3 flex items-center justify-between border-b border-white/5">
                            <div class="flex gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div><div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div><div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div></div>
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">index.html</span>
                        </div>
                        {{-- ID unik untuk CodeMirror --}}
                        <textarea id="editor-edit-mode"></textarea>
                    </div>
                </div>
            </div>

            {{-- 4. TAB KUIS --}}
            <div x-show="tipeMateri === 'kuis'" class="space-y-12">
                {{-- Form Tambah Pertanyaan --}}
                <div class="bg-amber-50/50 rounded-[2.5rem] p-10 border border-amber-100/50">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-8">
                                <label class="text-[10px] font-black text-amber-600 uppercase tracking-widest ml-4 mb-2 block">Pertanyaan</label>
                                <textarea x-model="tempKuis.pertanyaan" placeholder="Tuliskan pertanyaan..." class="w-full px-8 py-6 bg-white border-none rounded-3xl font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-amber-400 min-h-[120px] outline-none"></textarea>
                            </div>
                            <div class="md:col-span-4 space-y-4">
                                <label class="text-[10px] font-black text-amber-600 uppercase tracking-widest ml-2 block">Media & Nilai</label>
                                {{-- Input Gambar Pertanyaan --}}
                                <div class="bg-white p-4 rounded-3xl shadow-sm">
                                    <input type="file" @change="uploadMedia($event, 'gambar_pertanyaan')" class="text-[10px] w-full">
                                    <template x-if="tempKuis.gambar_pertanyaan">
                                        <img :src="tempKuis.gambar_pertanyaan" class="mt-2 h-20 w-full object-cover rounded-xl">
                                    </template>
                                </div>
                                {{-- Input Poin --}}
                                <input type="number" x-model="tempKuis.point" placeholder="Poin Soal" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-black text-slate-700 shadow-sm focus:ring-2 focus:ring-amber-400">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <template x-for="opt in ['a','b','c','d']" :key="opt">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-4 bg-white p-3 rounded-[1.5rem] border border-slate-100 shadow-sm">
                                        <span class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center font-black text-slate-400 uppercase text-xs" x-text="opt"></span>
                                        <input type="text" x-model="tempKuis['opsi_' + opt]" class="flex-1 bg-transparent border-none font-bold text-slate-600 focus:ring-0 text-sm" :placeholder="'Teks Opsi ' + opt.toUpperCase()">
                                    </div>
                                    {{-- Input Gambar Opsi (Opsional) --}}
                                    <div class="px-2">
                                        <input type="file" @change="uploadMedia($event, 'opsi_' + opt + '_img')" class="text-[9px] w-full text-slate-400">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 mt-4 border-t border-amber-100/50">
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kunci Jawaban:</span>
                                <div class="flex bg-white p-1 rounded-2xl gap-1 shadow-sm">
                                    <template x-for="opt in ['a','b','c','d']">
                                        <button @click="tempKuis.jawaban = opt" 
                                            :class="tempKuis.jawaban === opt ? 'bg-amber-500 text-white' : 'text-slate-400 hover:bg-slate-50'" 
                                            class="w-10 py-2 rounded-xl font-black text-xs uppercase transition-all" x-text="opt"></button>
                                    </template>
                                </div>
                            </div>
                            <button @click="tambahSoal()" class="px-10 py-4 bg-amber-600 text-white rounded-2xl font-black text-[10px] uppercase hover:bg-slate-900 transition-all shadow-lg">Simpan Soal ke Daftar</button>
                        </div>
                    </div>
                </div>

                {{-- Daftar Pertanyaan Terpilih --}}
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Daftar Soal Saat Ini (<span x-text="form.kuis.length"></span>)</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <template x-for="(soal, i) in form.kuis" :key="i">
                            <div class="bg-white border border-slate-100 p-6 rounded-[2rem] flex items-center gap-6 group hover:shadow-md transition-all">
                                <div class="w-14 h-14 rounded-[1.25rem] bg-slate-50 flex flex-col items-center justify-center font-black text-slate-300">
                                    <span class="text-[8px] uppercase">No</span><span class="text-xl" x-text="i+1"></span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <template x-if="soal.gambar_pertanyaan">
                                            <img :src="soal.gambar_pertanyaan" class="w-12 h-12 rounded-lg object-cover border border-slate-100">
                                        </template>
                                        <p class="font-bold text-slate-700" x-text="soal.pertanyaan"></p>
                                    </div>
                                    <div class="flex items-center gap-4 text-[9px] font-black uppercase mt-2">
                                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-md" x-text="'Kunci: ' + soal.jawaban"></span>
                                        <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-md" x-text="soal.point + ' Poin'"></span>
                                    </div>
                                </div>
                                <button @click="form.kuis.splice(i, 1)" class="w-10 h-10 text-rose-500 opacity-0 group-hover:opacity-100 transition-all hover:bg-rose-50 rounded-xl">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CodeMirror & Mode --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>

<script>
    // 1. Konfigurasi Heading (Tetap sama)
    document.addEventListener("trix-before-initialize", () => {
        Trix.config.blockAttributes.heading1 = {
            tagName: "h1",
            terminal: true,
            breakOnReturn: true,
            group: false
        };
    });

    // 2. Fungsi Global Upload (Agar bisa dipanggil x-init)
    async function handleTrixUpload(event) {
        if (!event.attachment.file) return;

        const formData = new FormData();
        formData.append("image", event.attachment.file);

        try {
            const response = await fetch('{{ route("guru.submateri.uploadImage") }}', {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                event.attachment.setAttributes({
                    url: data.url,
                    href: data.url
                });
            } else {
                event.attachment.remove();
                alert("Upload gagal");
            }
        } catch (e) {
            event.attachment.remove();
            console.error("Upload Error:", e);
        }
    }

    // 3. Handler Alpine.js
    function editMateriHandler() {
        return {
            // ... (Kode Alpine Anda yang sebelumnya) ...
            tab: '{{ $subMateri->tipe == 'kuis' ? 'kuis' : 'info' }}',
            tipeMateri: '{{ $subMateri->tipe }}',
            form: {
                judul: @json($subMateri->judul),
                kategori: @json($subMateri->kategori),
                urutan: {{ $subMateri->urutan }},
                video: @json($subMateri->video_url),
                kuis: {!! json_encode($subMateri->kuis) !!}
            },

            async updateData() {
                // Gunakan ID button yang benar atau event.target
                const btnSimpan = document.getElementById('btn-simpan-materi') || event.currentTarget;
                btnSimpan.disabled = true;
                btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

                try {
                    const formData = new FormData();
                    formData.append('_method', 'PUT'); // Spoofing PUT
                    formData.append('judul', this.form.judul);
                    formData.append('kategori', this.form.kategori);
                    formData.append('urutan', this.form.urutan);
                    formData.append('tipe', this.tipeMateri);

                    // Ambil value terbaru dari input hidden Trix
                    const inputBacaan = document.getElementById('bacaan_trix');
                    formData.append('bacaan', inputBacaan ? inputBacaan.value : '');

                    if (this.tipeMateri === 'kuis') {
                        formData.append('kuis_data', JSON.stringify(this.form.kuis));
                    } else {
                        formData.append('video_url', this.form.video || '');
                        // ... Tambahkan instruksi_coding dll jika ada
                    }

                    const res = await fetch('{{ route('guru.submateri.update', $subMateri->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await res.json();
                    if (res.ok && data.status === 'success') {
                        alert('Berhasil diperbarui!');
                        window.location.href = '{{ route("guru.submateri.dashboard", $materi_id) }}';
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan');
                    }
                } catch (e) {
                    alert(e.message);
                } finally {
                    btnSimpan.disabled = false;
                    btnSimpan.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Perubahan';
                }
            }
        }
    }
</script>

<style>
    /* 1. Base Trix Styling (Editor Utama) */
    trix-editor, .trix-content {
        min-height: 450px !important;
        padding: 2rem !important;
        background-color: transparent !important;
        border: none !important;
        color: #475569;
        font-weight: 500;
        outline: none !important;
        line-height: 1.6;
        display: block !important; 
    }
    
    /* 2. Heading & Paragraph */
    trix-editor h1, .trix-content h1 {
        font-size: 1.875rem !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
        color: #1e293b !important;
        display: block !important;
        margin-bottom: 1rem !important;
        margin-top: 1rem !important;
        clear: both; 
    }

    trix-editor p, .trix-content p {
        display: block !important;
        margin-bottom: 1rem !important;
        clear: both;
    }

    /* 3. List Styling */
    trix-editor ul, .trix-content ul { 
        list-style-type: disc !important; 
        margin-left: 1.5rem !important; 
        display: block !important;
        clear: both;
    }
    trix-editor ol, .trix-content ol { 
        list-style-type: decimal !important; 
        margin-left: 1.5rem !important; 
        display: block !important;
        clear: both;
    }
    trix-editor li, .trix-content li { 
        display: list-item !important; 
        margin-bottom: 0.25rem !important; 
    }

    /* 4. Container & Toolbar (Amber Style) */
    .trix-container {
        border: 2px solid #f1f5f9; 
        border-radius: 2rem;
        overflow: hidden;
        background: white;
        transition: all 0.3s ease;
    }

    trix-toolbar { 
        border-bottom: 1px solid #f1f5f9 !important; 
        padding: 15px 20px !important; 
        background: #fffbeb !important; /* amber-50 */
        position: sticky; 
        top: 0; 
        z-index: 50;
    }

    trix-toolbar .trix-button--active,
    trix-toolbar .trix-button.trix-active { 
        color: #fbbf24 !important; /* Amber */
        background: white !important; 
        border-radius: 8px !important; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* 5. Image Grid Styling (Dynamic 1 or 2 Columns) */
    
    /* Default: Jika hanya 1 gambar = Full Width */
    trix-editor figure.attachment, 
    .trix-content figure.attachment {
        display: block !important;
        margin: 0 auto 20px auto !important;
        width: 100% !important;
        max-width: 100% !important;
        transition: all 0.3s ease;
    }

    /* Grid: Jika terdeteksi minimal 2 gambar = 2 Kolom */
    trix-editor:has(figure.attachment:nth-of-type(2)) figure.attachment,
    .trix-content:has(figure.attachment:nth-of-type(2)) figure.attachment {
        display: inline-block !important;
        vertical-align: top;
        width: 48.5% !important; 
        max-width: 48.5% !important;
        margin: 0 0.5% 15px 0.5% !important;
    }

    trix-editor figure.attachment img,
    .trix-content figure.attachment img {
        width: 100% !important;
        border-radius: 1rem;
        height: auto !important;
        border: 1px solid #f1f5f9;
        object-fit: cover;
    }

    /* Metadata & Caption */
    trix-editor .attachment__metadata { 
        display: none !important; 
    }

    trix-editor figcaption, .trix-content figcaption {
        text-align: center !important;
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 5px;
    }

    /* 6. CodeMirror Dracula (Opsional untuk mode code) */
    .CodeMirror { 
        height: 450px !important; 
        font-size: 14px !important; 
        border-radius: 1.8rem; 
        padding: 15px;
    }

    /* 7. Responsif (Mobile) */
    @media (max-width: 768px) {
        /* Tetap 2 kolom di tablet jika ada 2 gambar */
        trix-editor:has(figure.attachment:nth-of-type(2)) figure.attachment {
            width: 48% !important;
        }
    }

    @media (max-width: 480px) {
        /* Paksa 1 kolom di HP walaupun ada banyak gambar */
        trix-editor figure.attachment,
        trix-editor:has(figure.attachment:nth-of-type(2)) figure.attachment {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 0 15px 0 !important;
            display: block !important;
        }
    }

    [x-cloak] { display: none !important; }
</style>
@endsection