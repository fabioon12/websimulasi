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
        {{-- Header & Tab Navigation --}}
        @include('guru.submateri.partialscreate.header')
        {{-- SISI KIRI: FORM INPUT --}}
        @include('guru.submateri.partialscreate.kiri')
        {{-- SISI KANAN: SIDEBAR ANTREAN --}}
        @include('guru.submateri.partialscreate.kanan')
    </div>
</div>

{{-- Script Tambahan untuk Trix Editor & CodeMirror --}}
@include('guru.submateri.partialscreate.script')
@endsection