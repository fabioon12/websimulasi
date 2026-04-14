@extends('siswa.layouts.app')

@section('title', 'Project Roadmap - ' . $project->judul)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10" x-data="{ showAddModal: false }">
    
    {{-- Header Section --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <a href="{{ route('siswa.workspace.show', $project->id) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 mb-4 hover:gap-4 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Workspace
            </a>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Project<span class="text-blue-600">Roadmap.</span></h1>
            <p class="text-slate-500 font-medium italic">"Setiap langkah besar dimulai dari target yang jelas."</p>
        </div>

        {{-- Tombol Tambah (Hanya untuk Ketua/Pengaju) --}}
        @if(auth()->id() == $project->pengaju_id)
        <button @click="showAddModal = true" class="px-8 py-4 bg-blue-600 text-white rounded-[2rem] text-[11px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-2xl shadow-blue-200">
            <i class="fas fa-flag mr-2"></i> Tambah Target Baru
        </button>
        @endif
    </div>

    {{-- Roadmap Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($project->milestones as $milestone)
            <div class="bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm relative flex flex-col h-full group hover:shadow-xl transition-all duration-500">
                
                {{-- Status Header --}}
                <div class="flex items-center justify-between mb-6">
                    <span class="text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest
                        {{ $milestone->status_review === 'disetujui' ? 'bg-emerald-100 text-emerald-600' : 
                           ($milestone->status_review === 'revisi' ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-500') }}">
                        {{ $milestone->status_review }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $milestone->deadline->format('d M Y') }}
                    </span>
                </div>

                <h3 class="text-xl font-black text-slate-900 mb-4 leading-tight">{{ $milestone->nama_milestone }}</h3>

                {{-- Feedback Section --}}
                <div class="flex-grow">
                    @if($milestone->feedback_guru)
                        <div class="p-5 bg-slate-50 rounded-[2rem] border border-slate-100 relative">
                            <i class="fas fa-quote-left absolute top-4 right-4 text-slate-200"></i>
                            <p class="text-[9px] font-black text-blue-600 uppercase mb-2">Mentor Feedback:</p>
                            <p class="text-xs text-slate-600 italic leading-relaxed">"{{ $milestone->feedback_guru }}"</p>
                        </div>
                    @else
                        <div class="py-6 border-2 border-dashed border-slate-50 rounded-[2rem] flex items-center justify-center text-slate-300">
                            <p class="text-[10px] font-black uppercase tracking-widest">Belum ada feedback</p>
                        </div>
                    @endif
                </div>

                {{-- Action Footer --}}
                <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between">
                    @if(!$milestone->is_completed && auth()->id() == $project->pengaju_id)
                        <form action="{{ route('siswa.milestone.complete', $milestone->id) }}" method="POST" class="w-full">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg">
                                Tandai Selesai
                            </button>
                        </form>
                    @elseif($milestone->is_completed)
                        <div class="flex items-center gap-2 text-emerald-500 font-black text-[10px] uppercase">
                            <i class="fas fa-check-circle text-lg"></i>
                            Laporan Terkirim
                        </div>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="col-span-full py-20 bg-slate-50 rounded-[4rem] border-4 border-dashed border-white text-center">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i class="fas fa-map-signs text-3xl text-slate-200"></i>
                </div>
                <p class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Belum ada roadmap yang dibuat</p>
                @if(auth()->id() == $project->pengaju_id)
                    <button @click="showAddModal = true" class="mt-4 text-blue-600 font-black text-[10px] uppercase tracking-widest">Buat Sekarang &rarr;</button>
                @endif
            </div>
        @endforelse
    </div>

    {{-- MODAL SECTION --}}
    <div 
        x-show="showAddModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Modal Content --}}
        <div 
            @click.away="showAddModal = false" 
            class="bg-white w-full max-w-lg rounded-[3.5rem] p-10 shadow-2xl relative"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        >
            <button @click="showAddModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <div class="mb-8">
                <h3 class="text-2xl font-black text-slate-900 mb-1">Tambah Target.</h3>
                <p class="text-xs font-medium text-slate-400 tracking-tight">Apa milestone besar tim kamu berikutnya?</p>
            </div>
            
            <form action="{{ route('siswa.milestone.store', $project->id) }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Milestone</label>
                    <input type="text" name="nama_milestone" required placeholder="Contoh: Perancangan Database" 
                           class="w-full px-8 py-5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition outline-none">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Target Selesai (Deadline)</label>
                    <input type="date" name="deadline" required 
                           class="w-full px-8 py-5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition outline-none">
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-200">
                        Buat Roadmap <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection