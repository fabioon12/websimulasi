@extends('siswa.layouts.app')

@section('title', 'Belajar ' . $materi->judul . ' - CodeLab')

@section('content')
<div class="max-w-[1600px] mx-auto p-4 md:p-6" x-data="{ 
    activeTab: 'materi', 
    progres: {{ $progres ?? 0 }},
    isMenuOpen: false 
}">
    
    {{-- Breadcrumb Dinamis --}}
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
                            $prevSubId = $index > 0 ? $materi->subMateris[$index-1]->id : null;
                            $isLocked = ($index > 0 && !in_array($prevSubId, $completedSubIds ?? []) && !$isDone && !$isActive);
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
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="fas fa-play text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">Materi Ke-{{ $subMateriAktif->urutan }}</p>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">{{ $subMateriAktif->judul }}</h2>
                    </div>
                </div>

                <div class="flex bg-slate-100 p-1.5 rounded-[2rem] w-full md:w-auto overflow-x-auto no-scrollbar shadow-inner">
                    <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500'" class="flex-1 px-6 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-file-alt"></i> Bacaan
                    </button>
                    
                    {{-- TAB PDF - DISESUAIKAN DENGAN pdf_path --}}
                    @if($subMateriAktif->pdf_path)
                    <button @click="activeTab = 'pdf'" :class="activeTab === 'pdf' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500'" class="flex-1 px-6 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-file-pdf"></i> Modul PDF
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
                </div>
            </div>

            <div class="relative">
                {{-- Content: Bacaan --}}
                <div x-show="activeTab === 'materi'" x-transition:enter="transition ease-out duration-300" class="bg-white rounded-[3.5rem] border border-slate-100 shadow-sm p-8 md:p-16 min-h-[600px]">
                    <article class="max-w-4xl mx-auto">
                        <h2 class="text-4xl font-black text-slate-900 mb-10 leading-[1.1] tracking-tighter">{{ $subMateriAktif->judul }}</h2>
                        <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-medium leading-loose">
                            {!! $subMateriAktif->bacaan !!}
                        </div>
                    </article>
                </div>

                {{-- Content: PDF (MENGGUNAKAN pdf_path) --}}
                @if($subMateriAktif->pdf_path)
                <div x-show="activeTab === 'pdf'" x-cloak x-transition:enter="transition ease-out duration-300">
                    <div class="bg-white p-6 rounded-[3.5rem] border border-slate-100 shadow-sm">
                        <div class="rounded-[2.5rem] overflow-hidden bg-slate-100 border-8 border-slate-50 h-[800px]">
                            <iframe 
                                src="{{ asset('storage/' . $subMateriAktif->pdf_path) }}" 
                                class="w-full h-full" 
                                frameborder="0">
                            </iframe>
                        </div>
                        <div class="mt-6 flex justify-center">
                             <a href="{{ asset('storage/' . $subMateriAktif->pdf_path) }}" target="_blank" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-3">
                                <i class="fas fa-external-link-alt"></i> Buka Fullscreen / Download
                             </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Content: Video --}}
                @if($subMateriAktif->video_url)
                <div x-show="activeTab === 'video'" x-cloak x-transition:enter="transition ease-out duration-300" class="bg-white p-6 rounded-[3.5rem] border border-slate-100 shadow-sm">
                    <div class="aspect-video rounded-[2.5rem] overflow-hidden bg-slate-900 border-8 border-slate-50">
                        @php
                            $videoId = Str::contains($subMateriAktif->video_url, 'watch?v=') ? Str::after($subMateriAktif->video_url, 'watch?v=') : Str::afterLast($subMateriAktif->video_url, '/');
                        @endphp
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                @endif

                {{-- Content: Coding --}}
                <div x-show="activeTab === 'coding'" x-cloak x-transition:enter="transition ease-out duration-300" class="grid grid-cols-1 xl:grid-cols-12 gap-8 min-h-[700px]">
                    <aside class="xl:col-span-4 bg-white rounded-[3rem] border border-slate-100 p-8 shadow-sm">
                        <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-4">Instruksi Tugas</h4>
                        <div class="text-xs font-bold text-slate-500 leading-relaxed prose-sm">
                            {!! $subMateriAktif->instruksi_coding ?? 'Gunakan editor di samping untuk mencoba kode.' !!}
                        </div>
                    </aside>
                    <div class="xl:col-span-8">
                        <div class="bg-[#1e1e1e] rounded-[3rem] border border-slate-800 shadow-2xl overflow-hidden flex flex-col h-full">
                            <div class="px-8 py-5 bg-[#252526] border-b border-white/5 flex items-center justify-between">
                                <span class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">index.html</span>
                                <button onclick="runCode()" class="bg-emerald-500 hover:bg-emerald-400 text-white px-8 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                                    <i class="fas fa-play mr-2"></i> Run Code
                                </button>
                            </div>
                            <div class="flex-1 flex flex-col">
                                <div class="h-1/2">
                                    <textarea id="code-editor">{{ $subMateriAktif->starter_code ?? "<h1>Hello World</h1>" }}</textarea>
                                </div>
                                <div class="h-1/2 bg-white">
                                    <iframe id="result-frame" class="w-full h-full border-none"></iframe>
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
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                @else
                    <div></div>
                @endif

                <form action="{{ route('siswa.materi.complete', [$materi->id, $subMateriAktif->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-12 py-5 bg-blue-600 text-white rounded-[2rem] font-black text-[10px] uppercase hover:bg-blue-700 transition-all flex items-center gap-4">
                        {{ $nextSub ? 'Selesaikan & Lanjut' : 'Selesaikan Kursus' }} <i class="fas fa-chevron-right"></i>
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>

{{-- Codemirror & Styles --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    .CodeMirror { height: 100%; font-family: 'Fira Code', monospace; font-size: 14px; padding: 20px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
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
    });
</script>
@endsection