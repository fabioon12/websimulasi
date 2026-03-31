@extends('guru.layouts.app')

@section('title', 'Studio Kurikulum - CodeLab')

@section('content')
{{-- Trix Editor Assets --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<div class="max-w-[1400px] mx-auto p-4 md:p-6" x-data="{ 
    tab: 'info',
    materiId: {{ $materi_id }},

    form: {
        judul: '',
        kategori: '', 
        urutan: {{ $nextUrutan }},
        bacaan: '',
        video: '',
        pdfFile: null, 
        pdfName: '',
        instruksi: '',
        kode: '<html>\n<body>\n\n</body>\n</html>'
    },

    listMateri: [],

    tambahKeAntrean() {
        if(this.form.judul === '') return alert('Judul materi minimal harus diisi!');
        if(this.form.kategori === '') return alert('Kategori harus diisi!');
        
        // Ambil kode terbaru dari CodeMirror
        this.form.kode = window.editorGuru.getValue();

        // Ambil konten terbaru dari Trix Editor
        this.form.bacaan = document.getElementById('bacaan_trix').value;

        // Masukkan data ke dalam list antrean
        this.listMateri.push({
            judul: this.form.judul,
            kategori: this.form.kategori,
            urutan: this.form.urutan,
            bacaan: this.form.bacaan,
            video: this.form.video,
            pdfFile: this.form.pdfFile, 
            pdfName: this.form.pdfName,
            instruksi: this.form.instruksi,
            kode: this.form.kode
        });
        
        let kategoriSekarang = this.form.kategori;
        let urutanBerikutnya = parseInt(this.form.urutan) + 1;
        
        this.resetForm();
        
        this.form.kategori = kategoriSekarang;
        this.form.urutan = urutanBerikutnya;
        
        this.tab = 'info';
        alert('Berhasil ditambah ke antrean!');
    },

    resetForm() {
        this.form = {
            judul: '',
            kategori: '',
            urutan: 1,
            bacaan: '',
            video: '',
            pdfFile: null,
            pdfName: '',
            instruksi: '',
            kode: '<html>\n<body>\n\n</body>\n</html>'
        };
        
        // Reset Trix Editor secara visual
        if (this.$refs.trixEditor) {
            this.$refs.trixEditor.editor.loadHTML('');
        }

        window.editorGuru.setValue(this.form.kode);
        if(this.$refs.fileInput) this.$refs.fileInput.value = '';
    },

    hapusDariAntrean(index) {
        if(confirm('Hapus materi ini dari antrean?')) {
            this.listMateri.splice(index, 1);
        }
    },

    async simpanKeDatabase() {
        if(this.listMateri.length === 0) return alert('Antrean materi masih kosong!');

        let formData = new FormData();
        formData.append('materi_id', this.materiId);
        
        this.listMateri.forEach((m, i) => {
            formData.append(`materi[${i}][judul]`, m.judul);
            formData.append(`materi[${i}][kategori]`, m.kategori);
            formData.append(`materi[${i}][urutan]`, m.urutan);
            formData.append(`materi[${i}][bacaan]`, m.bacaan);
            formData.append(`materi[${i}][video]`, m.video);
            formData.append(`materi[${i}][instruksi]`, m.instruksi);
            formData.append(`materi[${i}][kode]`, m.kode);
            if(m.pdfFile) formData.append(`materi[${i}][pdf_file]`, m.pdfFile);
        });

        try {
            const response = await fetch('{{ route('guru.submateri.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();
            if(result.status === 'success') {
                alert(result.message);
                window.location.href = '{{ route("guru.submateri.dashboard", $materi_id) }}';
            } else {
                alert('Gagal menyimpan: ' + result.message);
            }
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan sistem saat menyimpan ke database.');
        }
    }
}">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 px-4">
        <div class="flex items-center gap-5">
            <a href="{{ route('guru.submateri.dashboard', $materi_id) }}" class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Studio Kurikulum</h1>
                <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-widest">Materi Induk: <span class="text-blue-600">{{ $materi->judul }}</span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- SISI KIRI: FORM INPUT --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-[3.5rem] p-2 border border-slate-100 shadow-sm overflow-hidden">
                
                {{-- Tab Navigation --}}
                <div class="flex bg-slate-50 p-2 gap-2 rounded-[3rem]">
                    <button @click="tab = 'info'" :class="tab === 'info' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="flex-1 py-4 rounded-[2rem] text-[10px] font-black uppercase tracking-widest transition flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i> 1. Info & Teks
                    </button>
                    <button @click="tab = 'media'" :class="tab === 'media' ? 'bg-white shadow-sm text-rose-600' : 'text-slate-400'" class="flex-1 py-4 rounded-[2rem] text-[10px] font-black uppercase tracking-widest transition flex items-center justify-center gap-2">
                        <i class="fas fa-play-circle"></i> 2. Video & PDF
                    </button>
                    <button @click="tab = 'coding'" :class="tab === 'coding' ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-400'" class="flex-1 py-4 rounded-[2rem] text-[10px] font-black uppercase tracking-widest transition flex items-center justify-center gap-2">
                        <i class="fas fa-code"></i> 3. Coding Lab
                    </button>
                </div>

                <div class="p-8">
                    {{-- Tab 1: Info --}}
                    <div x-show="tab === 'info'" x-transition class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-6 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Judul Sub Materi</label>
                                <input type="text" x-model="form.judul" placeholder="Contoh: Pengenalan HTML" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-4 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Kategori</label>
                                <input type="text" x-model="form.kategori" placeholder="Contoh: Dasar" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Urutan</label>
                                <input type="number" x-model="form.urutan" class="w-full px-4 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700 text-center focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        {{-- RICH TEXT EDITOR DENGAN JUSTIFY & IMAGE --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Isi Materi (Media & Teks Rapi)</label>
                            <div class="bg-slate-50 rounded-[2.5rem] overflow-hidden border border-transparent focus-within:border-blue-500 transition-all">
                                <input id="bacaan_trix" type="hidden" name="bacaan" x-model="form.bacaan">
                                <trix-editor input="bacaan_trix" x-ref="trixEditor" placeholder="Tuliskan materi, tambahkan gambar, atau buat rata kanan-kiri di sini..." class="prose prose-slate max-w-none min-h-[400px]"></trix-editor>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Media --}}
                    <div x-show="tab === 'media'" x-transition class="space-y-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">ID Video Youtube</label>
                            <div class="relative">
                                <i class="fab fa-youtube absolute left-6 top-5 text-rose-500 text-xl"></i>
                                <input type="text" x-model="form.video" placeholder="Hanya ID-nya saja, contoh: dQw4w9WgXcQ" class="w-full pl-16 pr-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">File Modul (PDF)</label>
                            <div class="relative border-2 border-dashed rounded-[2.5rem] p-12 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer"
                                :class="form.pdfName ? 'border-emerald-400 bg-emerald-50/30' : 'border-slate-100 bg-slate-50 hover:border-blue-400'"
                                @click="$refs.fileInput.click()">
                                <input type="file" x-ref="fileInput" class="hidden" accept="application/pdf" 
                                    @change="if($event.target.files[0]) { form.pdfFile = $event.target.files[0]; form.pdfName = form.pdfFile.name; }">
                                
                                <template x-if="!form.pdfName">
                                    <div class="text-center text-slate-400">
                                        <i class="fas fa-file-pdf text-4xl mb-3"></i>
                                        <p class="text-[10px] font-black uppercase tracking-widest">Klik untuk pilih PDF</p>
                                    </div>
                                </template>

                                <template x-if="form.pdfName">
                                    <div class="flex items-center gap-4 bg-white p-5 rounded-3xl shadow-sm border border-emerald-100">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white"><i class="fas fa-check"></i></div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-xs font-black text-slate-700 truncate max-w-[200px]" x-text="form.pdfName"></span>
                                            <span class="text-[9px] font-bold text-emerald-500 uppercase">File Siap</span>
                                        </div>
                                        <button type="button" @click.stop="form.pdfFile = null; form.pdfName = ''; $refs.fileInput.value = ''" class="ml-4 text-slate-300 hover:text-rose-500 transition">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Coding --}}
                    <div x-show="tab === 'coding'" x-transition class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Instruksi Lab</label>
                            <textarea x-model="form.instruksi" rows="3" placeholder="Apa tugas kodingnya?" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>
                        <div class="bg-[#1e1e1e] rounded-[2.5rem] overflow-hidden border border-slate-800">
                            <div class="px-8 py-4 bg-[#252526] text-[10px] font-black text-slate-500 uppercase tracking-widest">Editor Kode Awal</div>
                            <textarea id="editor-final"></textarea>
                        </div>
                    </div>

                    {{-- Tombol Tambah Ke Antrean --}}
                    <div class="mt-10 pt-8 border-t border-slate-50 flex justify-end">
                        <button type="button" @click="tambahKeAntrean()" class="px-12 py-5 bg-blue-600 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-blue-100 active:scale-95 transition flex items-center gap-4">
                            Tambah Ke Antrean <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: SIDEBAR ANTREAN --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-slate-900 rounded-[3rem] p-8 shadow-2xl sticky top-6">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Antrean Materi</h3>
                        <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest">Siap diproses</p>
                    </div>
                    <span class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-blue-400 font-black text-sm" x-text="listMateri.length"></span>
                </div>

                <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="(m, index) in listMateri" :key="index">
                        <div class="bg-white/5 border border-white/10 p-5 rounded-3xl flex items-center gap-4 group hover:bg-white/10 transition">
                            <div class="w-10 h-10 rounded-2xl bg-blue-600 flex items-center justify-center text-[10px] font-black text-white shadow-lg" x-text="m.urutan"></div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-100 truncate" x-text="m.judul"></h4>
                                <span class="text-[8px] font-black text-blue-400 uppercase tracking-widest" x-text="m.kategori"></span>
                            </div>
                            <button @click="hapusDariAntrean(index)" class="text-slate-600 hover:text-rose-500 transition opacity-0 group-hover:opacity-100">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </template>

                    <template x-if="listMateri.length === 0">
                        <div class="text-center py-20 bg-white/5 rounded-[2.5rem] border border-dashed border-white/10">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Belum ada antrean</p>
                        </div>
                    </template>
                </div>

                <div class="mt-8 pt-8 border-t border-white/10">
                    <button @click="simpanKeDatabase()" class="w-full py-5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-900/40 transition active:scale-95">
                        Simpan & Publikasikan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CodeMirror & Styling --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Integrasi CodeMirror (Tetap Sama)
        window.editorGuru = CodeMirror.fromTextArea(document.getElementById("editor-final"), {
            lineNumbers: true,
            theme: "dracula",
            mode: "xml",
            tabSize: 2,
            lineWrapping: true
        });
        window.editorGuru.setValue("<html>\n<body>\n\n</body>\n</html>");

        // 2. Tambahkan Konfigurasi Justify (Rata Kanan-Kiri) ke Trix
        Trix.config.textAttributes.justify = {
            tagName: "div",
            style: { textAlign: "justify" },
            inheritable: true
        };

        // Tambahkan Tombol Justify ke Toolbar Trix secara otomatis
        addEventListener("trix-initialize", function(event) {
            const extraActions = `
                <button type="button" class="trix-button" data-trix-attribute="justify" title="Justify">
                    <i class="fas fa-align-justify"></i>
                </button>
            `;
            const toolbar = event.target.toolbarElement;
            const textGroup = toolbar.querySelector(".trix-button-group--text-tools");
            textGroup.insertAdjacentHTML("beforeend", extraActions);
        });

        // 3. Logika Upload Gambar Trix
        addEventListener("trix-attachment-add", function(event) {
            if (event.attachment.file) {
                uploadTrixImage(event.attachment);
            }
        });

        async function uploadTrixImage(attachment) {
            const file = attachment.file;
            const formData = new FormData();
            formData.append("image", file);

            try {
                const response = await fetch('{{ route('guru.submateri.uploadImage') }}', {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: formData
                });
                
                if (!response.ok) throw new Error("Upload Gagal");
                
                const data = await response.json();
                attachment.setAttributes({
                    url: data.url,
                    href: data.url
                });
            } catch (error) {
                console.error(error);
                alert("Gagal mengupload gambar ke server.");
            }
        }
    });
</script>

<style>
    /* Styling Custom Trix Editor */
    trix-editor {
        border: none !important;
        background-color: transparent !important;
        padding: 2rem !important;
        min-height: 450px !important;
        font-weight: 500;
        color: #475569;
        outline: none !important;
    }
    trix-toolbar { 
        border-bottom: 1px solid #f1f5f9 !important; 
        padding: 15px 20px !important; 
        background: white;
        position: sticky;
        top: 0;
        z-index: 30;
    }
    trix-toolbar .trix-button { border: none !important; background: transparent !important; }
    trix-toolbar .trix-button:hover { color: #2563eb !important; }
    trix-toolbar .trix-button--active { color: #2563eb !important; background: #eff6ff !important; border-radius: 8px; }
    
    /* Gambar di dalam editor */
    trix-editor attachment-figure { margin: 20px 0; border-radius: 1.5rem; overflow: hidden; border: 4px solid #f8fafc; }

    .CodeMirror { height: 250px; font-family: 'Fira Code', monospace; font-size: 13px; padding: 15px; background: transparent !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>
@endsection