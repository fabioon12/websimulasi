@extends('siswa.layouts.app')

@section('title', 'Workspace - ' . $partisipasi->proyek->nama_proyek)

<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    /* ============================================================
       1. BASE & TYPOGRAPHY (UNTUK SEMUA AREA)
       ============================================================ */
    .instruction-content, .prose-invert {
        line-height: 1.8;
        font-size: 0; /* Menghilangkan gap spasi antar gambar grid */
    }

    /* Mengembalikan font-size teks agar tidak hilang */
    .instruction-content p, .instruction-content span, .instruction-content li,
    .prose-invert p, .prose-invert span, .prose-invert li {
        font-size: 0.875rem !important; /* text-sm */
        color: inherit;
    }

    /* --- HEADING 1 STYLE --- */
    .instruction-content h1, trix-editor h1, .prose-invert h1 {
        display: block !important;
        width: 100% !important;
        clear: both; /* Agar tidak sejajar dengan gambar */
        font-weight: 800 !important;
        text-transform: uppercase;
        margin-top: 1.5rem !important;
        margin-bottom: 1rem !important;
        line-height: 1.2 !important;
        letter-spacing: -0.025em;
    }
    /* Warna H1: Gelap untuk box putih, Biru untuk box gelap */
    .instruction-content h1 { color: #0f172a !important; font-size: 1.75rem !important; }
    trix-editor h1, .prose-invert h1 { color: #60a5fa !important; font-size: 1.5rem !important; }

    /* --- LIST STYLE (BULLET & NUMBERING) --- */
    .instruction-content ul, .prose-invert ul, trix-editor ul { 
        list-style-type: disc !important; 
        padding-left: 1.5rem !important; 
    }
    .instruction-content ol, .prose-invert ol, trix-editor ol { 
        list-style-type: decimal !important; 
        padding-left: 1.5rem !important; 
    }
    .instruction-content ul, .instruction-content ol,
    .prose-invert ul, .prose-invert ol,
    trix-editor ul, trix-editor ol {
        display: block !important;
        width: 100% !important;
        clear: both;
        margin-top: 0.5rem !important;
        margin-bottom: 1rem !important;
    }
    .instruction-content li, .prose-invert li, trix-editor li {
        margin-bottom: 0.25rem !important;
        display: list-item !important; /* Memastikan peluru muncul */
    }

    /* ============================================================
       2. GRID 2 KOLOM (GAMBAR FULL / TIDAK TERPOTONG)
       ============================================================ */
    .instruction-content figure, 
    .instruction-content attachment,
    trix-editor figure.attachment,
    .prose-invert figure {
        display: inline-block !important;
        width: 49% !important; /* 2 Kolom presisi */
        margin: 0.5% !important;
        vertical-align: top !important;
        padding: 0 !important;
    }

    .instruction-content figure img, 
    trix-editor figure.attachment img,
    .prose-invert figure img {
        width: 100% !important;
        height: auto !important; /* Gambar tampil utuh (TIDAK TERPOTONG) */
        display: block !important;
        border-radius: 1.5rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin: 0 !important;
    }

    /* ============================================================
       3. TRIX EDITOR DARK MODE (HASIL PEKERJAAN)
       ============================================================ */
    trix-editor {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 1.5rem !important;
        color: #f8fafc !important;
        min-height: 200px !important;
        padding: 1.25rem !important;
        outline: none !important;
    }

    /* Toolbar Trix agar terlihat di Background Gelap */
    trix-toolbar .trix-button-row { 
        background: rgba(255, 255, 255, 0.03) !important; 
        border-radius: 1rem !important; 
        border: none !important; 
        margin-bottom: 0.5rem !important; 
    }
    trix-toolbar .trix-button { filter: invert(1) brightness(2); opacity: 0.6; }
    trix-toolbar .trix-button--active { background: #3b82f6 !important; filter: none !important; opacity: 1 !important; }

    /* Responsive untuk HP */
    @media (max-width: 640px) {
        .instruction-content figure, trix-editor figure.attachment, .prose-invert figure {
            width: 100% !important;
            margin: 0.5rem 0 !important;
            display: block !important;
        }
    }

    /* Hilangkan Elemen Sampah Trix */
    figcaption, .attachment__metadata, .attachment__progress { display: none !important; }
</style>

@section('content')
@php
    $deadline = \Carbon\Carbon::parse($partisipasi->proyek->deadline);
    $isOverdue = \Carbon\Carbon::now()->greaterThan($deadline);
    $isCompleted = $partisipasi->progress >= 100;
@endphp

<div class="max-w-[1400px] mx-auto pb-20 px-4 md:px-0">
    
    {{-- Header Navigation --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div class="flex items-center gap-5">
            <a href="{{ route('siswa.proyek.index') }}" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 hover:text-slate-900 shadow-sm border border-slate-100 transition">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $partisipasi->proyek->nama_proyek }}</h1>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">
                    Role: <span class="text-blue-600">{{ $partisipasi->role->nama_role }}</span>
                </p>
            </div>
        </div>
        
        <div class="bg-white px-8 py-4 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] font-black {{ $isOverdue && !$isCompleted ? 'text-rose-500' : 'text-slate-400' }} uppercase tracking-widest">
                    {{ $isOverdue && !$isCompleted ? 'Waktu Berakhir' : 'Progress Proyek' }}
                </p>
                <p class="text-xl font-black {{ $isOverdue && !$isCompleted ? 'text-rose-600' : 'text-slate-900' }}">
                    {{ $isOverdue && !$isCompleted ? $deadline->format('d M Y') : $partisipasi->progress . '%' }}
                </p>
            </div>
            <div class="w-16 h-16 {{ $isOverdue && !$isCompleted ? 'bg-rose-600' : 'bg-slate-900' }} rounded-2xl flex items-center justify-center text-white font-black text-xs transition-colors">
                @if($isOverdue && !$isCompleted)
                    <i class="fas fa-lock"></i>
                @else
                    {{ $tugasDikumpulkan->where('status', 'diterima')->count() }}/{{ $roadmaps->count() }}
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- SISI KIRI: TIMELINE TUGAS --}}
        <div class="lg:col-span-4 space-y-4">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                <span class="w-2 h-2 bg-blue-600 rounded-full"></span> Roadmap Tugas
            </h3>

            <div class="space-y-4 relative">
                <div class="absolute left-8 top-10 bottom-10 w-0.5 border-l-2 border-dashed border-slate-200 z-0"></div>

                @foreach($roadmaps as $task)
                @php $pengumpulan = $tugasDikumpulkan->get($task->id); @endphp

                <button onclick="showTaskDetail({{ $task->id }})" 
                        class="task-card w-full text-left relative z-10 group bg-white p-5 rounded-[2rem] border-2 transition-all duration-300 
                        {{ $pengumpulan && $pengumpulan->status == 'diterima' ? 'border-emerald-100 shadow-emerald-100/50' : 'border-transparent hover:border-blue-100 shadow-sm' }}
                        {{ $pengumpulan && $pengumpulan->status == 'ditolak' ? 'border-rose-100 shadow-rose-100/50' : '' }}"
                        id="btn-task-{{ $task->id }}">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center font-black text-xs transition-colors
                            {{ $pengumpulan && $pengumpulan->status == 'diterima' ? 'bg-emerald-500 text-white' : 
                               ($pengumpulan && $pengumpulan->status == 'ditolak' ? 'bg-rose-500 text-white' : 
                               ($pengumpulan && $pengumpulan->status == 'pending' ? 'bg-amber-400 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-blue-600 group-hover:text-white')) }}">
                            @if($pengumpulan && $pengumpulan->status == 'diterima')
                                <i class="fas fa-check"></i>
                            @elseif($pengumpulan && $pengumpulan->status == 'ditolak')
                                <i class="fas fa-times"></i>
                            @elseif($pengumpulan && $pengumpulan->status == 'pending')
                                <i class="fas fa-clock"></i>
                            @else
                                {{ $task->urutan }}
                            @endif
                        </div>

                        <div class="flex-grow">
                            <h4 class="text-sm font-black text-slate-900 leading-tight uppercase tracking-tight">{{ $task->judul_tugas }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">{{ $task->poin }} Points</p>
                        </div>
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        {{-- SISI KANAN: DETAIL & UPLOAD --}}
        <div class="lg:col-span-8">
            <div id="welcome-screen" class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[3.5rem] p-20 text-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i class="fas fa-terminal text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase italic">Pilih Tugas Untuk Memulai</h3>
            </div>

            @foreach($roadmaps as $task)
            <div id="task-detail-{{ $task->id }}" class="task-detail-content hidden space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                
                {{-- Instruksi Tugas --}}
                <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm space-y-6">
                    <span class="px-5 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest">
                        Instruksi Step {{ $task->urutan }}
                    </span>
                    <h2 class="text-3xl font-black text-slate-900">{{ $task->judul_tugas }}</h2>
                    <div class="prose prose-slate max-w-none text-slate-600 font-medium instruction-content overflow-hidden">
                        {!! $task->instruksi !!}
                    </div>
                </div>

                {{-- Hasil Pekerjaan (Logic Lock Berdasarkan Deadline) --}}
                <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl">
                    <h3 class="text-xl font-black mb-6 italic uppercase tracking-tight text-blue-400">Hasil Pekerjaan</h3>
                    
                    @php $p = $tugasDikumpulkan->get($task->id); @endphp

                    {{-- 1. ALERT BOX: FEEDBACK MENTOR (Selalu Tampil Jika Ada) --}}
                    @if($p && $p->feedback_guru)
                    <div class="mb-8 p-6 rounded-[2rem] border {{ $p->status == 'ditolak' ? 'bg-rose-500/10 border-rose-500/20 text-rose-200' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-200' }}">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 {{ $p->status == 'ditolak' ? 'bg-rose-500' : 'bg-emerald-500' }} text-white shadow-lg">
                                <i class="fas {{ $p->status == 'ditolak' ? 'fa-exclamation-triangle' : 'fa-comment-dots' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black uppercase tracking-[0.2em] {{ $p->status == 'ditolak' ? 'text-rose-400' : 'text-emerald-400' }}">Catatan Mentor</h4>
                                <p class="text-sm font-medium mt-1 leading-relaxed italic">"{{ $p->feedback_guru }}"</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- 2. LOGIC TAMPILAN FORM / STATUS --}}
                    @if($p && $p->status == 'diterima')
                        {{-- Tugas Selesai --}}
                        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-[2rem] p-8 text-center text-emerald-500">
                            <i class="fas fa-check-circle text-4xl mb-4"></i>
                            <h4 class="text-lg font-black uppercase tracking-widest">Tugas Selesai & Terverifikasi!</h4>
                        </div>

                    @elseif($isOverdue && !$isCompleted)
                        {{-- Batas Waktu Habis & Belum 100% --}}
                        <div class="bg-rose-500/10 border border-rose-500/20 rounded-[3rem] p-10 text-center">
                            <div class="w-20 h-20 bg-rose-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-rose-900/20">
                                <i class="fas fa-calendar-times text-3xl"></i>
                            </div>
                            <h4 class="text-xl font-black text-rose-400 uppercase tracking-tight">Batas Waktu Habis</h4>
                            <p class="text-rose-200/60 mt-2 text-sm max-w-sm mx-auto font-medium">
                                Misi ini telah dikunci pada <b>{{ $deadline->format('d M Y, H:i') }}</b>. Akses pengumpulan ditutup.
                            </p>
                            
                            @if($p)
                                <div class="mt-8 pt-8 border-t border-white/5 text-left opacity-40 select-none">
                                    <p class="text-[10px] font-black text-slate-500 uppercase mb-4 italic">Arsip Pengiriman Terakhir (Read-Only):</p>
                                    <p class="text-xs text-blue-400 truncate mb-2"><i class="fab fa-github mr-2"></i>{{ $p->link_repo }}</p>
                                    <div class="prose prose-invert prose-sm line-clamp-3">{!! $p->catatan_siswa !!}</div>
                                </div>
                            @endif
                        </div>

                    @else
                        {{-- Form Normal (Masih ada waktu atau sudah selesai 100% jadi bisa review) --}}
                        <form action="{{ route('siswa.tugas.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="roadmap_id" value="{{ $task->id }}">
                            <input type="hidden" name="proyek_id" value="{{ $partisipasi->proyek_id }}">
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Link GitHub / Repository</label>
                                <input type="url" name="link_repo" required placeholder="https://github.com/..." 
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm outline-none focus:border-blue-500 transition" 
                                    value="{{ $p ? $p->link_repo : '' }}">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Catatan & Screenshot (Paste Langsung)</label>
                                <input id="catatan_{{ $task->id }}" type="hidden" name="catatan_siswa" value="{{ $p ? $p->catatan_siswa : '' }}">
                                <trix-editor input="catatan_{{ $task->id }}" placeholder="Tulis instruksi atau lampirkan gambar..."></trix-editor>
                            </div>

                            <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-900/20 group">
                                <span class="group-hover:scale-110 transition-transform inline-block">
                                    {{ $p ? 'Kirim Revisi Pekerjaan' : 'Kirim Pekerjaan Sekarang' }}
                                </span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script>
    // 1. Trix Configuration (Heading 1)
    document.addEventListener("trix-before-initialize", () => {
        Trix.config.blockAttributes.heading1 = {
            tagName: "h1",
            terminal: true,
            breakOnReturn: true,
            group: false
        };
    });

    // 2. Logic Navigasi Detail Tugas
    function showTaskDetail(taskId) {
        document.getElementById('welcome-screen').classList.add('hidden');
        document.querySelectorAll('.task-detail-content').forEach(el => el.classList.add('hidden'));
        
        const target = document.getElementById('task-detail-' + taskId);
        if(target) target.classList.remove('hidden');
        
        document.querySelectorAll('.task-card').forEach(el => {
            el.classList.remove('border-blue-500', 'bg-blue-50/30', 'ring-4', 'ring-blue-500/10');
        });
        
        const activeBtn = document.getElementById('btn-task-' + taskId);
        if(activeBtn) activeBtn.classList.add('border-blue-500', 'bg-blue-50/30', 'ring-4', 'ring-blue-500/10');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // 3. Trix Attachment Logic (Upload)
    (function() {
        var HOST = "{{ route('siswa.trix.upload') }}"; 

        document.addEventListener("trix-attachment-add", function(event) {
            if (event.attachment.file) {
                const file = event.attachment.file;
                const formData = new FormData();
                formData.append("file", file);
                formData.append("_token", "{{ csrf_token() }}");

                const xhr = new XMLHttpRequest();
                xhr.open("POST", HOST, true);
                xhr.upload.onprogress = (e) => event.attachment.setUploadProgress(e.loaded / e.total * 100);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const data = JSON.parse(xhr.responseText);
                        return event.attachment.setAttributes({ url: data.url, href: data.url });
                    }
                    event.attachment.remove();
                };
                xhr.send(formData);
            }
        });
    })();
</script>
@endsection