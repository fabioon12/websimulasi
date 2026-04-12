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
                        {{-- Data langsung dimasukkan ke value input hidden agar terbaca Trix --}}
                        <input id="bacaan_trix" type="hidden" name="bacaan" value="{{ $subMateri->bacaan }}">
                        <trix-editor input="bacaan_trix" class="prose prose-slate max-w-none min-h-[400px] p-8 focus:outline-none bg-white"></trix-editor>
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
    function editMateriHandler() {
        return {
            tab: '{{ $subMateri->tipe == 'kuis' ? 'kuis' : 'info' }}',
            tipeMateri: '{{ $subMateri->tipe }}',
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
                judul: @json($subMateri->judul),
                kategori: @json($subMateri->kategori),
                urutan: {{ $subMateri->urutan }},
                video: @json($subMateri->video_url),
                pdfName: '{{ $subMateri->pdf_path ? "PDF Terupload (Klik ganti)" : "" }}',
                
                
                kuis: {!! json_encode($subMateri->kuis->map(function($q) {
                    return [
                        'pertanyaan' => $q->pertanyaan,
                        'gambar_pertanyaan' => $q->gambar_pertanyaan,
                        'point' => $q->point,
                        'opsi_a' => $q->opsi_a,
                        'opsi_a_img' => $q->opsi_a_img,
                        'opsi_b' => $q->opsi_b,
                        'opsi_b_img' => $q->opsi_b_img,
                        'opsi_c' => $q->opsi_c,
                        'opsi_c_img' => $q->opsi_c_img,
                        'opsi_d' => $q->opsi_d,
                        'opsi_d_img' => $q->opsi_d_img,
                        'jawaban' => $q->jawaban,
                    ];
                })->toArray()) !!}
            },

            async uploadMedia(event, targetKey) {
                const file = event.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('image', file);

                try {
                    // Buat route khusus untuk handle upload image kuis temporary atau permanen
                    const res = await fetch('{{ route("guru.submateri.uploadImage") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await res.json();
                    if(data.url) {
                        this.tempKuis[targetKey] = data.url;
                    }
                } catch (e) {
                    alert('Gagal upload gambar');
                }
            },

            tambahSoal() {
                if(!this.tempKuis.pertanyaan) return alert('Isi pertanyaan!');
                this.form.kuis.push({...this.tempKuis});
                // Reset temp form
                this.tempKuis = { 
                    pertanyaan: '', gambar_pertanyaan: null, point: 10,
                    opsi_a: '', opsi_a_img: null, opsi_b: '', opsi_b_img: null,
                    opsi_c: '', opsi_c_img: null, opsi_d: '', opsi_d_img: null,
                    jawaban: 'a' 
                };
            },
            async updateData() {
                // Tampilkan loading sederhana jika perlu
                const btnSimpan = event.target;
                btnSimpan.disabled = true;
                btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

                const formData = new FormData();
                
                // Laravel mengidentifikasi ini sebagai PUT melalui spoofing method
                formData.append('_method', 'PUT');
                formData.append('judul', this.form.judul);
                formData.append('kategori', this.form.kategori);
                formData.append('urutan', this.form.urutan);
                formData.append('tipe', this.tipeMateri);

                if (this.tipeMateri === 'kuis') {
                    formData.append('kuis_data', JSON.stringify(this.form.kuis));
                } else {
                    // Ambil value terbaru dari Trix Editor
                    const bacaanValue = document.getElementById('bacaan_trix').value;
                    const instruksiValue = document.getElementById('instruksi_trix').value;
                    
                    formData.append('bacaan', bacaanValue);
                    formData.append('instruksi_coding', instruksiValue);
                    formData.append('starter_code', window.editorGuru.getValue());
                    formData.append('video_url', this.form.video);
                    
                    if (this.$refs.fileInput.files[0]) {
                        formData.append('pdf_file', this.$refs.fileInput.files[0]);
                    }
                }

                try {
                    const res = await fetch('{{ route('guru.submateri.update', $subMateri->id) }}', {
                        method: 'POST', // Tetap POST karena kita pakai _method PUT di dalam FormData
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json' // Memberitahu Laravel untuk merespons dengan JSON, bukan redirect
                        },
                        body: formData
                    });

                    // Cek jika response bukan 200-299
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Terjadi kesalahan pada server');
                    }

                    const data = await res.json();
                    
                    if (data.status === 'success') {
                        alert('Berhasil diperbarui!');
                        window.location.href = '{{ route("guru.submateri.dashboard", $materi_id) }}';
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                } catch (e) {
                    console.error("Update Error:", e);
                    alert('Gagal menyimpan: ' + e.message);
                } finally {
                    btnSimpan.disabled = false;
                    btnSimpan.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Perubahan';
                }
            }
        }
    }
</script>

<style>
    /* Styling agar Trix Editor terlihat rapi dan menyatu */
    trix-toolbar { border-bottom: 1px solid #f1f5f9 !important; background: #f8fafc !important; border-radius: 2rem 2rem 0 0 !important; padding: 10px !important; }
    trix-editor { border: none !important; min-height: 400px !important; font-family: 'Inter', sans-serif !important; padding: 2rem !important; }
    .CodeMirror { height: 450px !important; font-size: 14px !important; border-radius: 0 0 1.8rem 1.8rem; }
    [x-cloak] { display: none !important; }
</style>
@endsection