@extends('siswa.layouts.app')

@section('title', 'Project Workspace - CodeLab')

@section('content')
{{-- Inisialisasi Alpine.js dengan deteksi Tab dari URL --}}
<div class="max-w-7xl mx-auto px-6 py-10" 
     x-data="{ tab: '{{ request()->has('rejected_page') ? 'rejected' : (request()->tab == 'rejected' ? 'rejected' : 'approved') }}' }">
    
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
        <div class="space-y-2">
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-4">
                <span class="opacity-50">Siswa</span>
                <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
                <span>Workspace</span>
            </nav>
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">Project<span class="text-blue-600">.</span>Center</h1>
            <p class="text-slate-500 font-medium text-lg">Kelola ekosistem proyek dan pantau hasil kurasi mentor.</p>
        </div>

        {{-- Switcher Tab Modern --}}
        <div class="bg-slate-100/80 backdrop-blur-md p-1.5 rounded-[2rem] border border-slate-200/50 flex shadow-inner">
            <button @click="tab = 'approved'" 
                :class="tab === 'approved' ? 'bg-white text-slate-900 shadow-xl scale-105' : 'text-slate-400 hover:text-slate-600'"
                class="px-10 py-3.5 rounded-[1.8rem] text-[11px] font-black uppercase tracking-widest transition-all duration-500 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full" :class="tab === 'approved' ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300'"></div>
                Approved
            </button>
            <button @click="tab = 'rejected'" 
                :class="tab === 'rejected' ? 'bg-white text-slate-900 shadow-xl scale-105' : 'text-slate-400 hover:text-slate-600'"
                class="px-10 py-3.5 rounded-[1.8rem] text-[11px] font-black uppercase tracking-widest transition-all duration-500 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full" :class="tab === 'rejected' ? 'bg-rose-500 animate-pulse' : 'bg-slate-300'"></div>
                Rejected
            </button>
        </div>
    </div>

    {{-- Pendataan / Stats Bento Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-16">
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500">
                <i class="fas fa-layer-group"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Submissions</p>
            <h4 class="text-3xl font-black text-slate-900">{{ $approvedProjects->total() + $rejectedProjects->total() }}</h4>
        </div>

        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-500">
                <i class="fas fa-check-double"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Approved</p>
            <h4 class="text-3xl font-black text-slate-900">{{ $approvedProjects->total() }}</h4>
        </div>

        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-500">
                <i class="fas fa-ban"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rejected</p>
            <h4 class="text-3xl font-black text-slate-900">{{ $rejectedProjects->total() }}</h4>
        </div>

        <div class="bg-slate-900 p-8 rounded-[3rem] shadow-2xl shadow-blue-200 flex flex-col justify-between overflow-hidden relative group">
            <div class="relative z-10 text-white">
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Active Now</p>
                <h4 class="text-2xl font-black leading-tight">{{ $approvedProjects->total() }} Projects</h4>
            </div>
            <i class="fas fa-rocket absolute -bottom-4 -right-4 text-8xl text-white/5 group-hover:text-blue-500/20 transition-all duration-700"></i>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="relative min-h-[400px]">
        
        {{-- TAB: APPROVED --}}
        <div x-show="tab === 'approved'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                @forelse($approvedProjects as $proyek)
                <div class="group bg-white rounded-[4rem] p-5 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-700 relative">
                    
                {{-- Thumbnail & Deadline --}}
                    <div class="relative h-60 w-full rounded-[3rem] overflow-hidden mb-8 shadow-inner">
                        <img src="{{ $proyek->thumbnail ? asset('storage/'.$proyek->thumbnail) : 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?q=80&w=800' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        
                        @php
                            $start = \Carbon\Carbon::parse($proyek->tanggal_mulai);
                            $end = \Carbon\Carbon::parse($proyek->tanggal_selesai);
                            $diffInDays = now()->diffInDays($end, false);
                        @endphp
                        <div class="absolute top-6 left-6">
                            <div class="backdrop-blur-xl {{ $diffInDays <= 7 ? 'bg-rose-500/80' : 'bg-white/20' }} px-5 py-2 rounded-2xl border border-white/30 text-white">
                                <p class="text-[10px] font-black uppercase tracking-widest leading-none mb-1">Deadline</p>
                                <p class="font-black text-sm leading-none">{{ $diffInDays < 0 ? 'Overdue' : ceil($diffInDays) . ' Days' }}</p>
                            </div>
                        </div>

                        <div class="absolute bottom-6 left-6">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md border border-white/30 rounded-lg text-[9px] font-black text-white uppercase tracking-widest">
                                {{ $proyek->mode }} Project
                            </span>
                        </div>
                    </div>

                    <div class="px-4 pb-4">
                        <div class="flex items-center gap-3 mb-4">
                            <p class="text-[11px] font-black text-blue-600 uppercase tracking-widest">{{ $proyek->guru->name }}</p>
                        </div>
                        
                        <h3 class="text-2xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors leading-tight">
                            {{ $proyek->judul }}
                        </h3>

                        {{-- DESKRIPSI PROYEK --}}
                        <p class="text-sm text-slate-500 line-clamp-2 mb-6 font-medium leading-relaxed">
                            {{ $proyek->deskripsi }}
                        </p>
                        
                        {{-- Progress bar & Timeline --}}
                        <div class="space-y-3 mb-8">
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <span>Timeline Progres</span>
                                <span class="{{ $diffInDays <= 7 ? 'text-rose-500' : 'text-emerald-500' }}">Due {{ $end->format('d M') }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                @php
                                    $totalDays = $start->diffInDays($end) ?: 1;
                                    $passedDays = $start->diffInDays(now());
                                    $percentage = min(100, max(0, ($passedDays / $totalDays) * 100));
                                @endphp
                                <div class="h-full {{ $diffInDays <= 7 ? 'bg-rose-500' : 'bg-blue-600' }} rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                            <div class="flex -space-x-3">
                                @foreach($proyek->anggota->take(3) as $member)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=random" class="w-10 h-10 rounded-2xl border-4 border-white shadow-sm hover:z-10 transition-all" title="{{ $member->user->name }}">
                                @endforeach
                            </div>
                           {{-- GRUP TOMBOL AKSES --}}
                            <div class="flex items-center gap-2">
                                {{-- Tombol Langsung ke Logbook --}}
                                <a href="{{ route('siswa.logbook.index', $proyek->id) }}" 
                                class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                                title="Daily Logbook">
                                    <i class="fas fa-book text-xs"></i>
                                </a>

                                {{-- Tombol Langsung ke Milestone --}}
                                <a href="{{ route('siswa.milestone.index', $proyek->id) }}" 
                                class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm" 
                                title="Project Roadmap">
                                    <i class="fas fa-flag text-xs"></i>
                                </a>

                                {{-- Tombol Workspace Utama (Yang sudah ada) --}}
                                <a href="{{ route('siswa.workspace.show', $proyek->id) }}" 
                                class="w-12 h-12 bg-slate-900 text-white rounded-[1.2rem] flex items-center justify-center hover:bg-blue-600 hover:-rotate-12 transition-all shadow-xl shadow-slate-200">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                
                <div class="col-span-full py-32 text-center">
                    <h5 class="text-xl font-black text-slate-300 uppercase tracking-[0.3em]">Belum Ada Proyek Aktif</h5>
                </div>
                @endforelse
                <a href="{{ route('siswa.proposal.create') }}" 
                    class="group border-4 border-dashed border-slate-200 rounded-[4rem] p-8 flex flex-col items-center justify-center text-center gap-4 hover:border-blue-500 hover:bg-blue-50/50 transition-all duration-500 min-h-[400px]">
                        <div class="w-20 h-20 bg-slate-100 rounded-[2rem] flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white group-hover:rotate-90 transition-all duration-700 shadow-sm">
                            <i class="fas fa-plus text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-slate-900 mb-1">Ajukan Proyek Baru</h4>
                            <p class="text-sm text-slate-500 font-medium px-6">Punya ide kreatif? Kirim proposalmu sekarang untuk ditinjau mentor.</p>
                        </div>
                        <div class="mt-4 px-6 py-2 bg-white border border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-blue-600 group-hover:border-blue-200 transition-all">
                            Get Started
                        </div>
                    </a>
            </div>
            
            <div class="mt-12">
                {{ $approvedProjects->appends(['tab' => 'approved'])->links() }}
            </div>
        </div>

        {{-- TAB: REJECTED --}}
        <div x-show="tab === 'rejected'" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-x-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($rejectedProjects as $proyek)
                <div class="bg-white rounded-[3.5rem] p-10 border border-rose-100 shadow-sm relative group hover:border-rose-300 transition-all duration-500">
                    <div class="bg-rose-50 w-16 h-16 rounded-2xl flex items-center justify-center text-rose-500 mb-8">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 mb-2 leading-tight">{{ $proyek->judul }}</h3>
                    
                    {{-- Deskripsi pada proyek ditolak --}}
                    <p class="text-xs text-slate-400 line-clamp-2 mb-6 font-medium">
                        {{ $proyek->deskripsi }}
                    </p>

                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 mb-8">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Mentor Feedback:</p>
                        <p class="text-sm font-medium text-rose-600 italic leading-relaxed">"{{ $proyek->alasan_penolakan ?? 'Perlu revisi pada bagian detail teknologi.' }}"</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black text-rose-400 uppercase">Draft Rejected</span>
                        <a href="{{ route('siswa.proposal.create') }}" class="px-6 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase hover:bg-rose-600 transition-all">Revisi</a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center text-slate-400 uppercase tracking-widest font-black text-xs">Bersih! Tidak ada proposal ditolak.</div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $rejectedProjects->appends(['tab' => 'rejected'])->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    body { background-color: #f8fafc; }
</style>
@endsection