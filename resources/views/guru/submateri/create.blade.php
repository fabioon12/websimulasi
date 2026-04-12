@extends('guru.layouts.app')

@section('title', 'Studio Kurikulum - CodeLab')

@section('content')
{{-- Trix Editor Assets --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<div class="max-w-[1400px] mx-auto p-4 md:p-6" x-data="{ 
   tab: 'info',
    tipeMateri: 'materi',
    materiId: {{ $materi_id }},

    tempKuis: {
        pertanyaan: '',
        gambar_pertanyaan: null, 
        point: 10,
        opsi_a: '', opsi_a_img: null,
        opsi_b: '', opsi_b_img: null,
        opsi_c: '', opsi_c_img: null,
        opsi_d: '', opsi_d_img: null,
        jawaban: 'a'
    },

    form: {
        judul: '',
        kategori: '', 
        urutan: {{ $nextUrutan }},
        bacaan: '',
        video: '',
        pdfFile: null, 
        pdfName: '',
        instruksi: '',
        kode: '<html>\n<body>\n\n</body>\n</html>',
        kuis: []
    },

    listMateri: [],

    async uploadGambarKuis(file, targetKey) {
        if (!file) return;

        let formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch('{{ route('guru.submateri.uploadImage') }}', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            if (!response.ok) throw new Error('Upload gagal');

            const data = await response.json();
            this.tempKuis[targetKey] = data.url; 
            
            alert('Gambar berhasil diunggah!');
        } catch (error) {
            console.error(error);
            alert('Gagal mengunggah gambar.');
        }
    },

    tambahSoal() {
        if(this.tempKuis.pertanyaan === '') return alert('Pertanyaan kuis harus diisi!');
        
        // Push copy dari tempKuis ke array kuis
        this.form.kuis.push({...this.tempKuis});
        
        // RESET: Pastikan semua key dikembalikan ke default
        this.tempKuis = {
            pertanyaan: '',
            gambar_pertanyaan: null,
            point: 10,
            opsi_a: '', opsi_a_img: null,
            opsi_b: '', opsi_b_img: null,
            opsi_c: '', opsi_c_img: null,
            opsi_d: '', opsi_d_img: null,
            jawaban: 'a'
        };

        // Reset input file secara manual jika diperlukan lewat ID/Ref
        alert('Soal berhasil ditambahkan ke list kuis!');
    },

    tambahKeAntrean() {
       // 1. Cek Judul. Jika Kuis, kita bisa beri judul default kalau user malas isi
        if (this.form.judul === '') {
            if (this.tipeMateri === 'kuis') {
                this.form.judul = 'Kuis: ' + (this.form.kategori || 'Evaluasi');
            } else {
                return alert('Judul materi minimal harus diisi!');
            }
        }
        
        // 2. Logika validasi berdasarkan tipe
        if(this.tipeMateri === 'materi') {
            if(this.form.kategori === '') return alert('Kategori harus diisi!');
            this.form.kode = window.editorGuru.getValue();
            this.form.bacaan = document.getElementById('bacaan_trix').value;
            this.form.instruksi = document.getElementById('instruksi_trix').value;
        } else {
            // Validasi khusus kuis
            if(this.form.kuis.length === 0) {
                return alert('Kamu belum menambahkan soal ke antrean kuis! Klik Simpan Soal dulu.');
            }
            this.form.kategori = this.form.kategori || 'Evaluasi';
        }

        // 3. Simpan ke antrean
        this.listMateri.push({
            ...this.form,
            tipe: this.tipeMateri,
            kuis: [...this.form.kuis]
        });
        this.resetForm();

        // Setel ulang untuk inputan selanjutnya
        this.form.kategori = kategoriSekarang;
        this.form.urutan = urutanBerikutnya;
        this.tipeMateri = 'materi';
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
            kode: '<html>\n<body>\n\n</body>\n</html>',
            kuis: []
        };
        
        if (this.$refs.trixEditor) this.$refs.trixEditor.editor.loadHTML('');
        if (document.getElementById('instruksi_trix')) document.getElementById('instruksi_trix').value = '';
        
        window.editorGuru.setValue('<html>\n<body>\n\n</body>\n</html>');
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
        formData.append(`materi[${i}][tipe]`, m.tipe);
        
        if(m.tipe === 'materi') {
            formData.append(`materi[${i}][bacaan]`, m.bacaan);
            formData.append(`materi[${i}][video]`, m.video);
            formData.append(`materi[${i}][instruksi]`, m.instruksi);
            formData.append(`materi[${i}][kode]`, m.kode);
            // File PDF dikirim sebagai file asli, bukan string
            if(m.pdfFile) {
                formData.append(`materi[${i}][pdf_file]`, m.pdfFile);
            }
        } else {
            // Kuis dikirim sebagai string JSON (Berisi URL gambar yang sudah diupload sebelumnya)
            formData.append(`materi[${i}][kuis_data]`, JSON.stringify(m.kuis));
        }
    });

    try {
        // Tampilkan loading jika perlu
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
            alert('Berhasil! Semua materi telah disimpan.');
            window.location.href = '{{ route("guru.submateri.dashboard", $materi_id) }}';
        } else {
            alert('Gagal: ' + result.message);
        }
    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan koneksi saat menyimpan.');
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
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Studio Materi</h1>
                <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-widest">Materi Induk: <span class="text-blue-600">{{ $materi->judul }}</span></p>
            </div>
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
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- SISI KIRI: FORM INPUT --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-[3.5rem] p-2 border border-slate-100 shadow-sm overflow-hidden">
                
                {{-- Tab Navigation --}}
                <div class="flex bg-slate-50 p-2 gap-2 rounded-[3rem]">
                    <template x-if="tipeMateri === 'materi'">
                        <div class="flex flex-1 gap-2">
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
                    </template>

                    {{-- Tab Kuis selalu muncul sebagai opsi terakhir, atau menjadi satu-satunya jika tipe kuis --}}
                </div>
                <div class="p-8">
                    {{-- Tab 1: Info --}}
                    <div x-show="tab === 'info' && tipeMateri === 'materi'" x-transition class="space-y-6">
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
                                <trix-editor input="bacaan_trix" x-ref="trixEditor" 
                                    class="prose prose-slate max-w-none min-h-[400px] focus:outline-none">
                                </trix-editor>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Media --}}
                    <div x-show="tab === 'media' && tipeMateri === 'materi'" x-transition class="space-y-8">
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
                    <div x-show="tab === 'coding' && tipeMateri === 'materi'" x-transition class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Instruksi Lab (Mendukung Format Kode)</label>
                            <div class="bg-slate-50 rounded-[2.5rem] overflow-hidden border border-transparent focus-within:border-emerald-500 transition-all">
                                {{-- Tambahkan ID unik untuk input instruksi --}}
                                <input id="instruksi_trix" type="hidden" name="instruksi_coding" x-model="form.instruksi">
                                <trix-editor input="instruksi_trix" 
                                    class="prose prose-emerald max-w-none min-h-[200px] focus:outline-none">
                                </trix-editor>
                            </div>
                        </div>
                        
                        <div class="bg-[#1e1e1e] rounded-[2.5rem] overflow-hidden border border-slate-800">
                            <div class="px-8 py-4 bg-[#252526] text-[10px] font-black text-slate-500 uppercase tracking-widest">Editor Kode Awal</div>
                            <textarea id="editor-final"></textarea>
                        </div>
                    </div>


                {{-- Tab 4: Hanya Kuis --}}
                <div x-show="tab === 'kuis'" x-transition class="space-y-6">
                    
                    {{-- Header: Poin & Gambar Utama Soal --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Poin Soal</label>
                            <input type="number" x-model="tempKuis.point" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold text-slate-700 shadow-sm" placeholder="Contoh: 10">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Gambar Pertanyaan (Opsional)</label>
                            <input type="file" @change="uploadGambarKuis($event.target.files[0], 'gambar_pertanyaan')" class="w-full px-4 py-3 bg-white border-none rounded-2xl text-xs text-slate-500 shadow-sm">
                            
                            <template x-if="tempKuis.gambar_pertanyaan">
                                <div class="mt-2 relative inline-block">
                                    <img :src="tempKuis.gambar_pertanyaan" class="h-20 rounded-lg border-2 border-white shadow-sm">
                                    <button @click="tempKuis.gambar_pertanyaan = null" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-5 h-5 text-[10px]"><i class="fas fa-times"></i></button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Form Input Soal --}}
                    <div class="bg-amber-50/50 border border-amber-100 rounded-[2.5rem] p-8 space-y-6">
                        {{-- Input Pertanyaan --}}
                        <textarea x-model="tempKuis.pertanyaan" placeholder="Pertanyaan..." class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold text-slate-700 min-h-[100px] shadow-sm"></textarea>
                        
                        {{-- Input Opsi A-D dengan Gambar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_a" placeholder="Teks Opsi A" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_a_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_a_img">
                                        <img :src="tempKuis.opsi_a_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_b" placeholder="Teks Opsi B" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_b_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_b_img">
                                        <img :src="tempKuis.opsi_b_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_c" placeholder="Teks Opsi C" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_c_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_c_img">
                                        <img :src="tempKuis.opsi_c_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_d" placeholder="Teks Opsi D" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_d_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_d_img">
                                        <img :src="tempKuis.opsi_d_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Form: Jawaban & Simpan --}}
                        <div class="flex items-center justify-between pt-4 border-t border-amber-100">
                            <select x-model="tempKuis.jawaban" class="px-6 py-3 bg-white border-none rounded-xl font-black text-blue-600 text-xs shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="a">JAWABAN A</option>
                                <option value="b">JAWABAN B</option>
                                <option value="c">JAWABAN C</option>
                                <option value="d">JAWABAN D</option>
                            </select>
                            <button type="button" @click="tambahSoal()" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-amber-200">
                                Simpan Soal
                            </button>
                        </div>
                    </div>

                    {{-- Preview List Soal --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Daftar Soal Antrean</label>
                        <template x-for="(soal, i) in form.kuis" :key="i">
                            <div class="flex items-center gap-4 p-4 bg-white border border-slate-100 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs" x-text="i+1"></div>
                                <div class="flex-1 flex flex-col">
                                    <span class="font-bold text-slate-700 truncate" x-text="soal.pertanyaan"></span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase" x-text="'Poin: ' + soal.point"></span>
                                </div>
                                {{-- Preview kecil jika ada gambar pertanyaan --}}
                                <template x-if="soal.gambar_pertanyaan">
                                    <img :src="soal.gambar_pertanyaan" class="w-8 h-8 rounded bg-slate-100 object-cover">
                                </template>
                                <button @click="form.kuis.splice(i, 1)" class="w-8 h-8 text-rose-500 hover:bg-rose-50 rounded-full transition-colors">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </template>
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
    trix-editor ul {
        list-style-type: disc !important;
        margin-left: 1.5rem !important;
        padding-left: 0.5rem !important;
    }

    trix-editor ol {
        list-style-type: decimal !important;
        margin-left: 1.5rem !important;
        padding-left: 0.5rem !important;
    }

    trix-editor li {
        display: list-item !important;
        margin-bottom: 0.25rem !important;
    }

    /* Styling tambahan agar toolbar tombol aktif terlihat jelas */
    trix-toolbar .trix-button--active { 
        background-color: #eff6ff !important; 
        color: #2563eb !important; 
    }
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