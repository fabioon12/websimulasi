@extends('guru.layouts.app')

@section('title', 'Review Tugas - Mentor Dashboard')


<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    /* ============================================================
       BASE CONTAINER (Modal & Instruksi)
       ============================================================ */
    .instruction-content, 
    .trix-content, 
    #noteContent {
        display: flex !important;
        flex-wrap: wrap !important; /* Kunci agar bisa berjajar */
        gap: 10px !important; /* Jarak antar gambar */
        line-height: 1.8;
    }

    /* Pastikan teks (p, h1, li) tetap mengambil lebar penuh (100%) */
    .instruction-content p, .instruction-content h1, .instruction-content ul, .instruction-content ol,
    .trix-content p, .trix-content h1, .trix-content ul, .trix-content ol,
    #noteContent p, #noteContent h1, #noteContent ul, #noteContent ol {
        flex: 0 0 100% !important; /* Paksa teks memenuhi satu baris */
        font-size: 0.95rem !important;
        margin-bottom: 0.5rem;
    }

    /* --- HEADING 1 STYLE --- */
    .instruction-content h1, .trix-content h1, #noteContent h1 {
        font-size: 1.75rem !important;
        font-weight: 900 !important;
        color: #0f172a !important;
        text-transform: uppercase;
        margin-top: 1rem !important;
    }

    /* --- LIST STYLE (BULLET & NUMBERING) --- */
    .instruction-content ul, .trix-content ul, #noteContent ul { list-style-type: disc !important; padding-left: 2rem !important; }
    .instruction-content ol, .trix-content ol, #noteContent ol { list-style-type: decimal !important; padding-left: 2rem !important; }
    .instruction-content li, .trix-content li, #noteContent li { display: list-item !important; margin-bottom: 0.3rem; }

    /* ============================================================
       GRID 2 KOLOM (GAMBAR)
       ============================================================ */
    /* Targetkan elemen attachment Trix */
    .instruction-content figure, 
    .instruction-content attachment,
    .trix-content figure, 
    .trix-content attachment,
    #noteContent figure,
    #noteContent attachment {
        /* KUNCI: Lebar diatur agar 2 gambar muat dalam 1 baris (100% / 2 - gap) */
        flex: 0 0 calc(50% - 10px) !important; 
        max-width: calc(50% - 10px) !important;
        margin: 0 !important;
        display: block !important;
    }

    /* Pastikan gambar di dalamnya tidak terpotong */
    .instruction-content figure img, 
    .trix-content figure img,
    #noteContent figure img {
        width: 100% !important;
        height: auto !important; /* Gambar tampil penuh/utuh */
        border-radius: 1.5rem !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        object-fit: contain !important;
    }

    /* ============================================================
       RESPONSIVE & CLEANUP
       ============================================================ */
    @media (max-width: 640px) {
        .instruction-content figure, .trix-content figure, #noteContent figure {
            flex: 0 0 100% !important; /* Balik ke 1 kolom di HP */
            max-width: 100% !important;
        }
    }

    /* Sembunyikan caption & metadata agar grid tidak rusak */
    figcaption, .attachment__metadata, .attachment__progress {
        display: none !important;
    }
</style>

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10 pb-24 space-y-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div class="flex items-center gap-6">
            <a href="{{ route('guru.proyek.dashboard') }}" 
               class="w-14 h-14 bg-white border border-slate-100 rounded-3xl flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-xl transition-all group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <div class="flex items-center gap-2 text-emerald-600 font-black text-[10px] uppercase tracking-[0.3em] mb-1">
                    <span class="w-8 h-[2px] bg-emerald-600"></span> Quality Control
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter italic uppercase leading-none">
                    Review Tugas <span class="text-emerald-600">Siswa</span> 🔍
                </h1>
            </div>
        </div>

        <div class="bg-white border border-slate-100 px-6 py-4 rounded-[2rem] shadow-sm flex items-center gap-4">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <div class="flex flex-col">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Waiting Review</span>
                <span class="text-sm font-black text-slate-900">{{ $tugasMasuk->count() }} Antrean</span>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-50">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Siswa</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Misi / Proyek</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Submission</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tugasMasuk as $tugas)
                    <tr class="hover:bg-emerald-50/30 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($tugas->user->name) }}&background=10b981&color=fff" 
                                     class="w-12 h-12 rounded-2xl shadow-sm" alt="avatar">
                                <div>
                                    <p class="font-black text-slate-900 group-hover:text-emerald-600 transition">{{ $tugas->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-black">UID: #{{ $tugas->user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-block px-2 py-0.5 bg-emerald-50 rounded text-[9px] font-black text-emerald-600 uppercase mb-1">{{ $tugas->proyek->nama_proyek }}</span>
                            <p class="text-sm font-bold text-slate-800">{{ $tugas->roadmap->judul_tugas }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-2">
                                <a href="{{ $tugas->link_repo }}" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 hover:underline text-[10px] font-black uppercase tracking-widest">
                                    <i class="fab fa-github text-base"></i> Open Repo
                                </a>
                                @if($tugas->catatan_siswa)
                                {{-- Gunakan ID unik untuk mengambil konten via JS --}}
                                <div id="raw-content-{{ $tugas->id }}" class="hidden">{!! $tugas->catatan_siswa !!}</div>
                                <button onclick="viewNote({{ $tugas->id }})" class="inline-flex items-center gap-2 text-slate-400 hover:text-blue-600 text-[10px] font-black uppercase tracking-widest transition">
                                    <i class="fas fa-comment-alt-lines"></i> Lihat Catatan & Screenshot
                                </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <button onclick="openModal({{ $tugas->id }}, '{{ $tugas->user->name }}', {{ $tugas->roadmap->poin }})" 
                                    class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-slate-200">
                                Beri Nilai
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <p class="text-slate-400 font-black uppercase text-xs italic">Semua tugas sudah bersih! ☕</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL LIHAT CATATAN --}}
<div id="modalNote" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
    <div class="bg-white w-full max-w-4xl rounded-[3rem] p-10 shadow-2xl relative border border-white/20 animate-in zoom-in duration-300">
        <button onclick="closeNote()" class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h2 class="text-2xl font-black text-slate-900 mb-6 italic uppercase tracking-tighter">Catatan Pekerjaan Siswa</h2>
        
        <div id="noteContent" class="trix-content instruction-content prose prose-slate max-w-none text-slate-600 font-medium bg-slate-50 p-8 rounded-3xl border border-slate-100 max-h-[65vh] overflow-y-auto">
            {{-- Konten Trix --}}
        </div>
    </div>
</div>

{{-- MODAL PENILAIAN --}}
<div id="modalReview" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
    <div class="bg-white w-full max-w-lg rounded-[3rem] p-10 shadow-2xl relative animate-in zoom-in duration-300">
        <button onclick="closeModal()" class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h2 class="text-3xl font-black text-slate-900 mb-1 italic uppercase tracking-tighter">Review Misi</h2>
        <p id="modalStudentName" class="text-emerald-600 font-black text-[10px] mb-8 uppercase tracking-[0.2em]"></p>

        <form id="formReview" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Status</label>
                    <select name="status" class="w-full mt-2 bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold">
                        <option value="diterima">✅ TERIMA (ACC)</option>
                        <option value="ditolak">❌ TOLAK (REVISI)</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Reward Poin</label>
                    <input type="number" name="poin_didapat" id="modalPoin" class="w-full mt-2 bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Feedback Mentor</label>
                <textarea name="feedback_guru" rows="4" placeholder="Saran perbaikan..." class="w-full mt-2 bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-medium resize-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 py-5 bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 shadow-xl shadow-emerald-200">Kirim Penilaian</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan Catatan/Screenshot Siswa
    function viewNote(id) {
        // Ambil konten dari div hidden berdasarkan ID
        const content = document.getElementById('raw-content-' + id).innerHTML;
        const modal = document.getElementById('modalNote');
        const container = document.getElementById('noteContent');
        
        container.innerHTML = content;
        modal.classList.remove('hidden');
    }

    function closeNote() {
        document.getElementById('modalNote').classList.add('hidden');
    }

    // Fungsi Modal Penilaian
    function openModal(id, name, maxPoin) {
        document.getElementById('modalReview').classList.remove('hidden');
        document.getElementById('modalStudentName').innerText = "Target Siswa: " + name;
        document.getElementById('modalPoin').value = maxPoin;
        // Sesuaikan route dengan yang ada di web.php
        document.getElementById('formReview').action = "/guru/proyek/review-tugas/" + id;
    }

    function closeModal() {
        document.getElementById('modalReview').classList.add('hidden');
    }

    // Tutup modal jika klik di luar box
    window.onclick = function(event) {
        if (event.target.id == 'modalReview') closeModal();
        if (event.target.id == 'modalNote') closeNote();
    }
</script>
@endsection