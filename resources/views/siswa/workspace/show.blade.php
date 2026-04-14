@extends('siswa.layouts.app')

@section('title', 'Workspace - ' . $project->judul)

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    
    {{-- Header & Breadcrumb --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <a href="{{ route('siswa.workspace.index') }}" class="group inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-2">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">{{ $project->judul }}</h1>
            <div class="flex items-center gap-4">
                <span class="px-3 py-1 bg-slate-100 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-widest">
                    {{ $project->mode }} Project
                </span>
                <span class="text-sm font-medium text-slate-400">
                    Mentor: <span class="text-slate-900 font-bold">{{ $project->guru->name }}</span>
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden md:block">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Proyek</p>
                <p class="text-sm font-black text-emerald-500 uppercase italic">Active & Running</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center animate-pulse">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
    </div>

    {{-- 1. Tombol Navigasi Utama (Shortcut) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        {{-- Card Roadmap --}}
        <a href="{{ route('siswa.milestone.index', $project->id) }}" 
           class="group p-8 bg-emerald-600 rounded-[3.5rem] text-white hover:shadow-2xl hover:shadow-emerald-200 transition-all duration-500 relative overflow-hidden">
            <div class="relative z-10">
                <i class="fas fa-flag text-4xl mb-4 opacity-30 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500"></i>
                <h3 class="font-black text-2xl uppercase tracking-tight">Project Roadmap</h3>
                <p class="text-xs opacity-80 font-medium">Lihat target pengerjaan, deadline, dan feedback mentor.</p>
                <div class="mt-6 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest bg-white/20 w-fit px-4 py-2 rounded-xl">
                    Buka Milestone <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
            {{-- Dekorasi Bulat --}}
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </a>

        {{-- Card Logbook --}}
        <a href="{{ route('siswa.logbook.index', $project->id) }}" 
           class="group p-8 bg-blue-600 rounded-[3.5rem] text-white hover:shadow-2xl hover:shadow-blue-200 transition-all duration-500 relative overflow-hidden">
            <div class="relative z-10">
                <i class="fas fa-edit text-4xl mb-4 opacity-30 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500"></i>
                <h3 class="font-black text-2xl uppercase tracking-tight">Daily Work Log</h3>
                <p class="text-xs opacity-80 font-medium">Catat kemajuan harian tim dan upload bukti pengerjaan.</p>
                <div class="mt-6 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest bg-white/20 w-fit px-4 py-2 rounded-xl">
                    Isi Logbook <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
            {{-- Dekorasi Bulat --}}
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </a>
    </div>

    {{-- 2. Detail Grid Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- Sisi Kiri: Deskripsi & Anggota --}}
        <div class="lg:col-span-8 space-y-10">
            {{-- About Project --}}
            <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
                <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-3">
                    <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
                    Tentang Proyek
                </h4>
                <p class="text-slate-600 leading-[1.8] font-medium italic">
                    "{{ $project->deskripsi }}"
                </p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Mulai</p>
                        <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Berakhir</p>
                        <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($project->tanggal_selesai)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Total Log</p>
                        <p class="text-sm font-bold text-slate-900">{{ $project->logbooks->count() }} Entry</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Anggota</p>
                        <p class="text-sm font-bold text-slate-900">{{ $project->anggota->count() }} Orang</p>
                    </div>
                </div>
            </div>

            {{-- Team Members --}}
            <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
                <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-8 flex items-center gap-3">
                    <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
                    Tim Pengembang
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($project->anggota as $member)
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=random" class="w-12 h-12 rounded-xl shadow-sm">
                        <div>
                            <p class="text-sm font-black text-slate-900 leading-tight">{{ $member->user->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                {{ $member->user_id == $project->pengaju_id ? 'Project Leader' : 'Team Member' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Mini Activity / Quick Stats --}}
        <div class="lg:col-span-4 space-y-10">
            <div class="bg-slate-900 rounded-[3rem] p-8 text-white">
                <h4 class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-6">Recent Activities</h4>
                
                <div class="space-y-6">
                    @forelse($project->logbooks->take(3) as $log)
                    <div class="flex gap-4 relative">
                        <div class="w-8 h-8 rounded-full bg-blue-600/20 flex items-center justify-center shrink-0 border border-blue-600/30">
                            <i class="fas fa-check text-[10px] text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-tight mb-1">{{ $log->judul }}</p>
                            <p class="text-[9px] text-slate-500 uppercase">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-[10px] text-slate-600 italic">Belum ada aktivitas terbaru...</p>
                    @endforelse
                </div>

                <a href="{{ route('siswa.logbook.index', $project->id) }}" class="block text-center mt-8 py-4 border border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-slate-900 transition">
                    View All Activity
                </a>
            </div>

            {{-- Info Mentor Box --}}
            <div class="bg-blue-50 rounded-[3rem] p-8 border border-blue-100">
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4">Project Mentor</p>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-xl">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">{{ $project->guru->name }}</p>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-tighter italic">"Ready to help your team"</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    body { background-color: #fcfdfe; }
</style>
@endsection