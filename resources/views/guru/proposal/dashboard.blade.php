@extends('guru.layouts.app')

@section('title', 'Workspace Monitoring - Guru Panel')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    {{-- Header & Hero Section --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900 rounded-[3rem] p-10 mb-12 shadow-2xl shadow-emerald-900/20">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-center md:text-left">
                <span class="inline-block px-4 py-1.5 bg-emerald-500/20 border border-emerald-500/30 rounded-full text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                    Guru Monitoring System
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">
                    Project <span class="text-emerald-400">Monitoring.</span>
                </h1>
                <p class="text-slate-300 font-medium max-w-md">Pantau progres, validasi milestone, dan bimbing siswa secara real-time dalam satu dashboard terintegrasi.</p>
            </div>

            <div class="flex gap-4">
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 text-center min-w-[140px]">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Proyek Aktif</p>
                    <h3 class="text-3xl font-black text-white">{{ $activeProjects->total() }}</h3>
                </div>
                <div class="bg-emerald-500 p-6 rounded-[2rem] text-center min-w-[140px] shadow-lg shadow-emerald-500/40">
                    <p class="text-emerald-100 text-[10px] font-black uppercase tracking-widest mb-1">Rerata Progres</p>
                    <h3 class="text-3xl font-black text-white">{{ $averageProgress }}%</h3>
                </div>
            </div>
        </div>
        
        {{-- Decorative Elements --}}
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Control Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 px-4">
        <div class="flex items-center gap-4">
            <div class="relative group">
                <input type="text" placeholder="Cari proyek siswa..." 
                    class="bg-white border-2 border-slate-100 shadow-sm rounded-2xl py-3.5 pl-12 pr-6 text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all w-full md:w-80">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 bg-white px-5 py-3.5 rounded-2xl shadow-sm border-2 border-slate-100 hover:border-emerald-500 hover:text-emerald-600 transition-all font-bold text-sm text-slate-600">
                <i class="fas fa-sort-amount-down"></i> Urutkan
            </button>
        </div>
    </div>

    {{-- Project Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($activeProjects as $project)
            @php
                // Hitung Progres
                $totalMs = $project->milestones->count();
                $doneMs = $project->milestones->where('status_review', 'disetujui')->where('is_completed', true)->count();
                $perProjectProgress = $totalMs > 0 ? round(($doneMs / $totalMs) * 100) : 0;

                // Hitung Sisa Waktu
                $end = \Carbon\Carbon::parse($project->tanggal_selesai);
                $daysLeft = ceil(now()->diffInDays($end, false));
                
                // Hitung Total Durasi Proyek
                $start = \Carbon\Carbon::parse($project->tanggal_mulai);
                $totalDuration = $start->diffInDays($end);
            @endphp

            <div class="group relative bg-white rounded-[3rem] border-2 border-slate-100 p-8 hover:border-emerald-500 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 flex flex-col">
                
                {{-- 1. Card Top: Thumbnail & Status Badge --}}
                <div class="flex justify-between items-start mb-6">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 overflow-hidden shadow-inner group-hover:scale-105 transition-transform duration-500">
                        <img src="{{ $project->thumbnail ? asset('storage/' . $project->thumbnail) : 'https://ui-avatars.com/api/?name='.urlencode($project->judul).'&background=random' }}" 
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="px-4 py-1.5 {{ $daysLeft <= 3 ? 'bg-rose-50 text-rose-600' : 'bg-slate-900 text-white' }} rounded-full text-[9px] font-black uppercase tracking-wider">
                            {{ $daysLeft < 0 ? 'Waktu Habis' : $daysLeft . ' Hari Lagi' }}
                        </span>
                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black">
                            {{ $perProjectProgress }}% Done
                        </span>
                    </div>
                </div>

                {{-- 2. Project Info --}}
                <div class="mb-6">
                    <h3 class="text-xl font-black text-slate-900 group-hover:text-emerald-600 transition-colors mb-2 line-clamp-2 min-h-[3.5rem]">
                        {{ $project->judul }}
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center text-[10px] text-white font-black shadow-sm">
                            {{ strtoupper(substr($project->pengaju->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Ketua Kelompok</p>
                            <p class="text-xs font-bold text-slate-700 leading-none">{{ $project->pengaju->name }}</p>
                        </div>
                    </div>
                </div>

                {{-- 3. Milestone Visual Tracker --}}
                <div class="mb-8 p-5 bg-slate-50 rounded-[2rem] border border-slate-100">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Milestone Tracker</span>
                        <span class="text-[10px] font-black text-slate-700">{{ $doneMs }}/{{ $totalMs }}</span>
                    </div>
                    <div class="w-full h-3 bg-white rounded-full overflow-hidden p-0.5 border border-slate-200">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000 shadow-[0_0_12px_rgba(16,185,129,0.4)]" 
                            style="width: {{ $perProjectProgress }}%"></div>
                    </div>
                </div>

                {{-- 4. Stats Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="text-center p-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Durasi</p>
                        <p class="text-xs font-black text-slate-700">{{ $totalDuration }} Hari</p>
                    </div>
                    <div class="border-l border-slate-100 text-center p-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Logbook</p>
                        <p class="text-xs font-black text-slate-700">{{ $project->logbooks->count() }} Entri</p>
                    </div>
                </div>

                {{-- 5. Actions (Push to Bottom) --}}
                <div class="mt-auto flex gap-3">
                    <a href="{{ route('guru.milestone.index', ['project_id' => $project->id]) }}" 
                    class="flex-1 flex items-center justify-center gap-2 bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-slate-200 active:scale-95">
                        <i class="fas fa-tasks"></i> Review
                    </a>
                    <a href="{{ route('guru.monitoring.logbook', $project->id) }}" 
                    class="w-14 flex items-center justify-center bg-white border-2 border-slate-100 text-slate-400 py-4 rounded-2xl hover:border-emerald-500 hover:text-emerald-600 transition-all active:scale-95 shadow-sm">
                        <i class="fas fa-book"></i>
                    </a>
                </div>

                {{-- Visual Accent on Hover --}}
                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-1/3 h-1 bg-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 group-hover:w-1/2"></div>
            </div>
        @empty
            {{-- Empty State tetap sama --}}
        @endforelse
    </div>

    {{-- Custom Pagination --}}
    <div class="mt-12 px-4">
        {{ $activeProjects->links() }}
    </div>
</div>

<style>
    /* Menghilangkan scrollbar default di tabel jika diperlukan */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endsection