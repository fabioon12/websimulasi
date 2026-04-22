@extends('siswa.layouts.app')

@section('title', 'Belajar ' . $materi->judul . ' - CodeLab')

@section('content')
<div class="max-w-[1600px] mx-auto p-4 md:p-6" x-data="{ 
    {{-- Jika tipe materi adalah kuis, otomatis buka tab kuis --}}
    activeTab: '{{ $subMateriAktif->tipe === 'kuis' ? 'kuis' : 'materi' }}', 
    progres: {{ $progres ?? 0 }},
    score: 0,
    quizFinished: false
}">
    
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-3 px-6 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
        <a href="{{ route('siswa.materi.dashboard') }}" class="hover:text-blue-600 transition flex items-center gap-2">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <i class="fas fa-chevron-right text-[7px] opacity-30"></i>
        <span class="text-slate-900 truncate">{{ $materi->judul }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- SIDEBAR AREA --}}
        <aside class="lg:col-span-3 space-y-6">
            {{-- Tombol Kembali --}}
            <a href="{{ route('siswa.materi.dashboard') }}" class="group flex items-center gap-4 p-4 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Kembali ke</span>
                    <span class="text-sm font-black text-slate-900 group-hover:text-blue-600 transition">Dashboard</span>
                </div>
            </a>

            {{-- Progres Card --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Progres Kursus</h3>
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black" x-text="progres + '%'"></span>
                    </div>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden p-0.5">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(37,99,235,0.3)]" :style="'width:' + progres + '%'"></div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Daftar Materi --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-4 shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-4 mt-2">Daftar Materi</h3>
                <div class="space-y-2">
                    @foreach($materi->subMateris as $index => $sub)
                        @php 
                            $isActive = $sub->id === $subMateriAktif->id; 
                            $isDone = in_array($sub->id, $completedSubIds ?? []);
                            $isLocked = ($index > 0 && !in_array($materi->subMateris[$index-1]->id, $completedSubIds ?? []) && !$isDone && !$isActive);
                        @endphp
                        <a @if(!$isLocked) href="{{ route('siswa.materi.learn', [$materi->id, $sub->id]) }}" @endif 
                           class="flex items-center gap-4 p-4 rounded-[1.8rem] transition-all duration-300 
                           {{ $isLocked ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-50' }} 
                           {{ $isActive ? 'bg-slate-900 text-white shadow-2xl shadow-slate-200' : 'bg-white text-slate-600' }}">
                            <div class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center text-[10px] font-black
                                {{ $isActive ? 'bg-blue-600 text-white' : ($isDone ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400') }}">
                                @if($isDone) <i class="fas fa-check"></i> @elseif($isLocked) <i class="fas fa-lock"></i> @else {{ $index + 1 }} @endif
                            </div>
                            <span class="text-[11px] font-bold leading-tight flex-1">{{ $sub->judul }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="lg:col-span-9 space-y-6">
            {{-- Content Header / Tab Switcher --}}
            <div class="bg-white p-5 rounded-[3rem] border border-slate-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-5 ml-3">
                    <div class="w-12 h-12 rounded-2xl {{ $subMateriAktif->tipe === 'kuis' ? 'bg-amber-500' : 'bg-blue-600' }} flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="fas {{ $subMateriAktif->tipe === 'kuis' ? 'fa-lightbulb' : 'fa-play' }} text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">Bab Ke-{{ $subMateriAktif->urutan }}</p>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">{{ $subMateriAktif->judul }}</h2>
                    </div>
                </div>

                <div class="flex bg-slate-100 p-1.5 rounded-[2rem] w-full md:w-auto overflow-x-auto no-scrollbar shadow-inner">
                    @if($subMateriAktif->tipe !== 'kuis')
                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500'" class="flex-1 px-6 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="fas fa-file-alt"></i> Bacaan
                        </button>
                        
                        @if($subMateriAktif->pdf_path)
                        <button @click="activeTab = 'pdf'" :class="activeTab === 'pdf' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500'" class="flex-1 px-6 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        @endif

                        @if($subMateriAktif->video_url)
                        <button @click="activeTab = 'video'" :class="activeTab === 'video' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500'" class="flex-1 px-6 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="fas fa-play-circle"></i> Video
                        </button>
                        @endif

                        <button @click="activeTab = 'coding'" :class="activeTab === 'coding' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500'" class="flex-1 px-6 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="fas fa-terminal"></i> Code Editor
                        </button>
                    @else
                        <button @click="activeTab = 'kuis'" :class="activeTab === 'kuis' ? 'bg-white text-amber-600 shadow-sm' : 'text-slate-500'" class="flex-1 px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-tasks"></i> Kerjakan Kuis
                        </button>
                    @endif
                </div>
            </div>

            <div class="relative">
                {{-- Content: Bacaan --}}
                <div x-show="activeTab === 'materi'" x-transition>
                    <div class="bg-white p-6 md:p-10 rounded-[3.5rem] border border-slate-100 shadow-sm w-full overflow-hidden">
                        <article class="max-w-4xl mx-auto w-full">
                            <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-medium leading-loose w-full break-words">
                                {!! $subMateriAktif->bacaan !!}
                            </div>
                        </article>
                    </div>
                </div>

                {{-- Content: Kuis --}}
                @if($subMateriAktif->tipe === 'kuis')
                <div x-show="activeTab === 'kuis'" x-transition>
                    <div class="bg-white p-8 md:p-12 rounded-[3.5rem] border border-slate-100 shadow-sm">
                        <div class="max-w-3xl mx-auto">
                            @if(!empty($dataKuis))
                                <form id="quizForm" class="space-y-12">
                                    @foreach($dataKuis as $index => $kuis)
                                    <div class="quiz-item" data-answer="{{ $kuis['jawaban'] }}">
                                        <div class="flex gap-6">
                                            <span class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-black text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="space-y-6 flex-1">
                                                {{-- Pertanyaan --}}
                                                <div class="space-y-4">
                                                    <h3 class="text-lg font-bold text-slate-800 leading-relaxed">{{ $kuis['pertanyaan'] }}</h3>
                                                    
                                                    {{-- TAMBAHAN: Gambar Pertanyaan --}}
                                                    @if(!empty($kuis['gambar_pertanyaan']))
                                                        <div class="mt-3">
                                                            {{-- Langsung panggil variabelnya tanpa asset() --}}
                                                            <img src="{{ $kuis['gambar_pertanyaan'] }}" 
                                                                class="max-h-60 rounded-2xl border border-slate-100 shadow-sm" 
                                                                alt="Gambar Pertanyaan">
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    @foreach(['a', 'b', 'c', 'd'] as $opsi)
                                                    <label class="relative group cursor-pointer">
                                                        <input type="radio" name="soal_{{ $index }}" value="{{ strtoupper($opsi) }}" class="peer hidden">
                                                        <div class="p-4 rounded-2xl border-2 border-slate-100 peer-checked:border-blue-600 peer-checked:bg-blue-50 group-hover:bg-slate-50 transition-all flex flex-col gap-3">
                                                            <div class="flex items-center gap-4">
                                                                <span class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[10px] font-black text-slate-400 peer-checked:bg-blue-600 peer-checked:text-white uppercase">
                                                                    {{ $opsi }}
                                                                </span>
                                                                <span class="text-sm font-bold text-slate-600">{{ $kuis['opsi_' . $opsi] }}</span>
                                                            </div>

                                                            {{-- TAMBAHAN: Gambar Opsi --}}
                                                            @if(!empty($kuis['opsi_' . $opsi . '_img']))
                                                                <div class="mt-2 w-full">
                                                                    {{-- Langsung panggil variabelnya tanpa asset() --}}
                                                                    <img src="{{ $kuis['opsi_' . $opsi . '_img'] }}" 
                                                                        class="w-full h-32 object-cover rounded-xl border border-slate-100" 
                                                                        alt="Opsi {{ $opsi }}">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <div class="pt-8 border-t border-slate-100 flex flex-col items-center">
                                        <button type="button" onclick="checkQuiz()" class="px-12 py-5 bg-slate-900 text-white rounded-3xl font-black text-[11px] uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl">
                                            Cek Jawaban & Hitung Skor
                                        </button>
                                    </div>
                                </form>

                                {{-- Quiz Result Overlay --}}
                                <div id="quizResult" class="hidden mt-10 p-8 rounded-[2.5rem] bg-slate-50 border-2 border-dashed border-slate-200 text-center">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Hasil Skor Anda</h4>
                                    <div class="text-6xl font-black text-slate-900 mb-4"><span id="displayScore">0</span>/100</div>
                                    <p id="resultMessage" class="text-sm font-bold text-slate-500 mb-6"></p>
                                    <div id="finishAction" class="hidden">
                                        <p class="text-[10px] font-black text-emerald-600 uppercase mb-4">Jawaban benar semua! Silakan lanjut.</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-20">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-question text-slate-300 text-3xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold">Data kuis belum tersedia.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Content: PDF --}}
                @if($subMateriAktif->pdf_path)
                <div x-show="activeTab === 'pdf'" x-cloak x-transition>
                    <div class="bg-white p-6 rounded-[3.5rem] border border-slate-100 shadow-sm">
                        <div class="rounded-[2.5rem] overflow-hidden bg-slate-100 border-8 border-slate-50 h-[800px]">
                            <iframe src="{{ asset('storage/' . $subMateriAktif->pdf_path) }}" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Content: Video --}}
                @if($subMateriAktif->video_url)
                    @php
                        // Regex sederhana untuk mengambil 11 karakter ID YouTube
                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $subMateriAktif->video_url, $matches);
                        $videoId = $matches[1] ?? $subMateriAktif->video_url;
                    @endphp

                    <div x-show="activeTab === 'video'" x-cloak x-transition class="bg-white p-6 rounded-[3.5rem] border border-slate-100 shadow-sm">
                        <div class="aspect-video rounded-[2.5rem] overflow-hidden bg-slate-900 border-8 border-slate-50">
                            <iframe 
                                class="w-full h-full" 
                                src="https://www.youtube.com/embed/{{ trim($videoId) }}" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                @endif

                {{-- Content: Coding --}}
                <div x-show="activeTab === 'coding'" x-cloak x-transition class="grid grid-cols-1 xl:grid-cols-12 gap-6 min-h-[700px] h-[calc(100vh-150px)]">
                    
                    <aside class="xl:col-span-3 bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm flex flex-col h-full overflow-hidden">
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                            Instruksi Tugas
                        </h4>
                        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                            <div class="text-xs font-medium text-slate-500 leading-relaxed prose prose-sm max-w-none">
                                {!! $subMateriAktif->instruksi_coding ?? 'Gunakan editor di samping untuk mencoba kode.' !!}
                            </div>
                        </div>
                    </aside>

                    <div class="xl:col-span-9 h-full">
                        <div class="bg-[#1e1e1e] rounded-[2.5rem] border border-slate-800 shadow-2xl overflow-hidden flex flex-col h-full">
                            
                            <div class="px-8 py-5 bg-[#252526] border-b border-white/5 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex gap-1.5">
                                        <div class="w-3 h-3 rounded-full bg-[#ff5f56] shadow-lg shadow-red-500/20"></div>
                                        <div class="w-3 h-3 rounded-full bg-[#ffbd2e] shadow-lg shadow-amber-500/20"></div>
                                        <div class="w-3 h-3 rounded-full bg-[#27c93f] shadow-lg shadow-emerald-500/20"></div>
                                    </div>
                                    <span class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest ml-3">playground.html</span>
                                </div>
                                <button onclick="runCode()" class="bg-emerald-500 hover:bg-emerald-400 text-white px-8 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all transform active:scale-95 flex items-center gap-2 shadow-xl shadow-emerald-500/20">
                                    <i class="fas fa-play text-[8px]"></i> RUN CODE
                                </button>
                            </div>

                            <div class="flex-1 flex flex-col lg:flex-row overflow-hidden bg-[#1e1e1e]">
                                
                                <div class="w-full lg:w-1/2 relative border-b lg:border-b-0 lg:border-r border-white/5 group bg-[#1e1e1e]">
                                    <div class="absolute top-4 right-6 text-[9px] text-white/20 font-mono z-10 uppercase tracking-widest pointer-events-none">Editor</div>
                                    
                                    <textarea id="code-editor" 
                                        class="w-full h-full bg-[#1e1e1e] focus:ring-0 border-none" 
                                        spellcheck="false"
                                        wrap="off" 
                                    >{{ $subMateriAktif->starter_code ?? "<h1>Hello World</h1>" }}</textarea>
                                </div>

                                <div class="w-full lg:w-1/2 bg-white relative h-full">
                                    <div class="absolute top-4 right-6 text-[9px] text-slate-300 font-mono z-10 uppercase tracking-widest pointer-events-none">Live Preview</div>
                                    <iframe id="result-frame" class="w-full h-full border-none bg-white"></iframe>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigasi Bawah --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-12 bg-slate-50 p-4 rounded-[2.5rem] border border-dashed border-slate-200">
                @php
                    $currentIndex = $materi->subMateris->search(fn($item) => $item->id === $subMateriAktif->id);
                    $prevSub = $materi->subMateris->get($currentIndex - 1);
                    $nextSub = $materi->subMateris->get($currentIndex + 1);
                @endphp

                @if($prevSub)
                    <a href="{{ route('siswa.materi.learn', [$materi->id, $prevSub->id]) }}" class="px-8 py-5 bg-white text-slate-900 rounded-[2rem] font-black text-[10px] uppercase border border-slate-100 hover:shadow-lg transition-all flex items-center gap-4">
                        <i class="fas fa-chevron-left"></i> Bab Sebelumnya
                    </a>
                @else
                    <div></div>
                @endif

                <form action="{{ route('siswa.materi.complete', [$materi->id, $subMateriAktif->id]) }}" method="POST" id="completeForm">
                    @csrf
                    <button type="submit" 
                        {{ $subMateriAktif->tipe === 'kuis' ? 'id=btnComplete disabled' : '' }}
                        class="px-12 py-5 {{ $subMateriAktif->tipe === 'kuis' ? 'bg-slate-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-[2rem] font-black text-[10px] uppercase transition-all flex items-center gap-4">
                        {{ $nextSub ? 'Selesaikan & Lanjut' : 'Selesaikan Kursus' }} <i class="fas fa-chevron-right"></i>
                    </button>
                    @if($subMateriAktif->tipe === 'kuis')
                    <p id="quizWarning" class="text-[9px] font-black text-slate-400 mt-2 text-center uppercase tracking-tighter">Selesaikan kuis untuk lanjut</p>
                    @endif
                </form>
            </div>
        </main>
    </div>
</div>

{{-- Styles --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<style>
    [x-cloak] { display: none !important; }
    
    /* 1. Base Text & Font */
    .prose { 
        font-family: 'Inter', sans-serif; 
        color: #334155; /* slate-700 */
        line-height: 1.8;
        text-align: justify;
        display: block;
    }

    /* 2. Heading Styling */
    .prose h1 {
        font-size: 2.25rem;
        font-weight: 800;
        color: #1e293b;
        margin-top: 2rem;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        letter-spacing: -0.02em;
        clear: both; /* Memastikan teks heading selalu di baris baru setelah gambar */
    }

    .prose p {
        margin-bottom: 1.5rem;
        clear: both; /* Mencegah teks naik ke samping gambar jika gambar full */
    }

    /* 3. List Styling (Fixing Points/Bullets) */
    .prose ul { 
        list-style-type: disc !important; 
        margin-left: 1.5rem !important; 
        margin-bottom: 1.5rem !important;
        display: block !important;
        clear: both;
    }
    .prose ol { 
        list-style-type: decimal !important; 
        margin-left: 1.5rem !important; 
        margin-bottom: 1.5rem !important;
        display: block !important;
        clear: both;
    }
    .prose li { 
        display: list-item !important; 
        margin-bottom: 0.5rem; 
    }

    /* 4. Dynamic Image Grid (Sesuai Logika Admin) */
    
    /* Default: 1 Gambar (Full Width) */
    .prose figure.attachment {
        display: block !important;
        margin: 0 auto 1.5rem auto !important;
        width: 100% !important;
        max-width: 100% !important;
        transition: transform 0.3s ease;
    }

    /* Grid: Jika terdeteksi minimal 2 gambar */
    .prose:has(figure.attachment:nth-of-type(2)) figure.attachment {
        display: inline-block !important;
        vertical-align: top;
        width: 48.5% !important;
        max-width: 48.5% !important;
        margin: 0 0.5% 15px 0.5% !important;
    }

    .prose figure.attachment img {
        width: 100%;
        height: auto;
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid #f1f5f9;
        object-fit: cover;
    }

    .prose figure.attachment img:hover {
        transform: scale(1.02);
    }

    /* Menghilangkan Metadata (Ukuran File dsb) */
    .prose .attachment__metadata { 
        display: none !important; 
    }

    /* Caption Styling */
    .prose figcaption {
        text-align: center !important;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 8px;
    }

    /* 5. CodeMirror Theme (Siswa Mode - Read Only) */
    .CodeMirror { 
        height: auto !important; 
        min-height: 300px;
        border-radius: 1.5rem; 
        font-family: 'Fira Code', monospace;
        margin-bottom: 1.5rem;
    }

    /* 6. Responsif (Mobile) */
    @media (max-width: 600px) {
        /* Di HP tetap 1 kolom walaupun ada banyak gambar */
        .prose figure.attachment,
        .prose:has(figure.attachment:nth-of-type(2)) figure.attachment {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 0 1rem 0 !important;
            display: block !important;
        }
        /* Mencegah elemen di dalam materi merusak layout card */
    .prose {
        max-width: 100% !important;
        overflow-wrap: break-word !important;
        word-wrap: break-word !important;
    }

    /* 1. Paksa Gambar agar tidak pernah keluar jalur */
    .prose img {
        max-width: 100% !important;
        height: auto !important;
        display: block;
        margin: 0 auto;
    }

    /* 2. Paksa Tabel agar bisa di-scroll ke samping jika terlalu lebar */
    .prose table {
        display: block !important;
        width: 100% !important;
        overflow-x: auto !important;
        border-collapse: collapse;
    }

    /* 3. Paksa Blok Kode agar tidak tembus */
    .prose pre {
        max-width: 100% !important;
        overflow-x: auto !important;
        white-space: pre-wrap !important; /* Membuat kode turun ke baris baru */
        word-break: break-all !important;
    }

    /* 4. Paksa elemen iframe atau video agar responsif */
    .prose iframe, .prose video {
        max-width: 100% !important;
        height: auto !important;
    }
    #code-editor {
        background-color: #1e1e1e !important;
        color: #a5b4fc !important;
        border: none !important;
        outline: none !important;
        padding: 2rem !important;
        resize: none !important;
        /* Sembunyikan scrollbar untuk Firefox */
        scrollbar-width: none; 
        /* Sembunyikan scrollbar untuk IE/Edge */
        -ms-overflow-style: none; 
    }

    /* Khusus Chrome, Safari, dan Edge: Menghilangkan scrollbar secara total */
    #code-editor::-webkit-scrollbar {
        width: 0px;
        height: 0px;
        display: none;
    }

    /* Jika Anda masih ingin ada scroll tapi warnanya gelap (tidak putih) */
    #code-editor::-webkit-scrollbar-track {
        background: #1e1e1e !important;
    }

    #code-editor::-webkit-scrollbar-thumb {
        background: #334155 !important; /* Warna abu gelap untuk pegangan scroll */
        border-radius: 10px;
    }
}

</style>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>

<script>
    // KUIS LOGIC
    function checkQuiz() {
        const form = document.getElementById('quizForm');
        const items = document.querySelectorAll('.quiz-item');
        let correctCount = 0;
        let total = items.length;

        items.forEach((item, index) => {
            const selected = form.querySelector(`input[name="soal_${index}"]:checked`);
            const correctAnswer = item.getAttribute('data-answer').toUpperCase();
            
            // 1. Ambil semua div opsi di dalam soal ini untuk reset warna
            const allOptionDivs = item.querySelectorAll('label > div');
            allOptionDivs.forEach(div => {
                div.classList.remove('border-emerald-500', 'bg-emerald-50', 'border-rose-500', 'bg-rose-50', 'border-dashed');
                div.classList.add('border-slate-100'); // Kembalikan ke border default
            });

            // 2. Logika Pengecekan
            if (selected) {
                const userValue = selected.value.toUpperCase();
                const targetDiv = selected.nextElementSibling; // div pembungkus opsi

                if (userValue === correctAnswer) {
                    // JAWABAN BENAR
                    correctCount++;
                    targetDiv.classList.remove('border-slate-100');
                    targetDiv.classList.add('border-emerald-500', 'bg-emerald-50');
                } else {
                    // JAWABAN SALAH
                    targetDiv.classList.remove('border-slate-100');
                    targetDiv.classList.add('border-rose-500', 'bg-rose-50');

                    // TAMPILKAN KUNCI JAWABAN (Cari input yang value-nya sama dengan kunci)
                    const correctInput = item.querySelector(`input[value="${correctAnswer}"]`);
                    if (correctInput) {
                        correctInput.nextElementSibling.classList.remove('border-slate-100');
                        correctInput.nextElementSibling.classList.add('border-emerald-500', 'bg-emerald-50', 'border-dashed');
                    }
                }
            } else {
                // JIKA BELUM DIJAWAB: Tetap tunjukkan mana yang benar dengan garis putus-putus
                const correctInput = item.querySelector(`input[value="${correctAnswer}"]`);
                if (correctInput) {
                    correctInput.nextElementSibling.classList.add('border-emerald-500', 'border-dashed');
                }
            }
        });

        // 3. Kalkulasi Skor & Update UI
        const score = Math.round((correctCount / total) * 100);
        document.getElementById('displayScore').innerText = score;
        document.getElementById('quizResult').classList.remove('hidden');
        
        const btnComplete = document.getElementById('btnComplete');
        const warning = document.getElementById('quizWarning');
        const resultMessage = document.getElementById('resultMessage');

        if (score >= 70) {
            resultMessage.innerText = "Luar biasa! Anda memahami materi ini dengan baik.";
            resultMessage.classList.replace('text-rose-500', 'text-emerald-600'); // Opsional: ganti warna teks
            
            if (btnComplete) {
                btnComplete.disabled = false;
                btnComplete.classList.remove('bg-slate-300', 'cursor-not-allowed', 'opacity-50');
                btnComplete.classList.add('bg-blue-600', 'hover:bg-blue-700', 'shadow-lg');
            }
            if (warning) warning.classList.add('hidden');
        } else {
            resultMessage.innerText = "Skor minimal 70 diperlukan untuk lanjut. Ayo pelajari lagi bagian yang salah!";
            resultMessage.classList.add('text-rose-500');
            
            if (btnComplete) {
                btnComplete.disabled = true;
                btnComplete.classList.add('bg-slate-300', 'cursor-not-allowed', 'opacity-50');
            }
        }

        // Scroll otomatis ke hasil agar siswa melihat skornya
        document.getElementById('quizResult').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // EDITOR LOGIC
    document.addEventListener('DOMContentLoaded', function() {
        const editorElement = document.getElementById("code-editor");
        if (editorElement) {
            const editor = CodeMirror.fromTextArea(editorElement, {
                lineNumbers: true, theme: "dracula", mode: "htmlmixed", tabSize: 2, lineWrapping: true
            });

            window.runCode = function() {
                const code = editor.getValue();
                const resultFrame = document.getElementById("result-frame").contentWindow.document;
                resultFrame.open();
                resultFrame.write(`<!DOCTYPE html><html><body style="font-family:sans-serif;padding:20px">${code}</body></html>`);
                resultFrame.close();
            }
            setTimeout(runCode, 500);
        }
    });
</script>
@endsection