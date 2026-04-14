@extends('siswa.layouts.app')

@section('title', 'Katalog Proyek - CodeLab')

@section('content')
<div class="space-y-12 pb-20">
    {{-- Header Section dengan Glass Background --}}
    <div class="relative p-8 md:p-12 rounded-[3rem] overflow-hidden bg-slate-900 shadow-2xl shadow-blue-900/20">
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-blue-600 rounded-full blur-[120px] opacity-20"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-indigo-500 rounded-full blur-[120px] opacity-20"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="space-y-4">
                <nav class="flex items-center gap-2 text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">
                    <a href="/dashboard" class="hover:text-white transition">Dashboard</a>
                    <i class="fas fa-circle text-[4px] text-slate-600"></i>
                    <span class="text-slate-400">Katalog Proyek</span>
                </nav>
                <h1 class="text-4xl md:text-5xl font-black text-white leading-tight">
                    Bangun Portofolio <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Level Industri.</span>
                </h1>
                <p class="text-slate-400 max-w-xl text-lg font-medium leading-relaxed">
                    Selesaikan simulasi proyek nyata, kumpulkan poin, dan bersiaplah untuk karir profesionalmu.
                </p>
            </div>
            
            {{-- Filter & Search Box --}}
            <form action="{{ route('siswa.katalog.index') }}" method="GET" class="bg-white/5 backdrop-blur-xl p-3 rounded-[2rem] border border-white/10 flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <div class="relative group">
                    <select name="difficulty" onchange="this.form.submit()" class="appearance-none bg-white/10 border border-white/10 pl-5 pr-12 py-4 rounded-2xl text-sm font-bold text-white focus:bg-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition w-full sm:w-48 cursor-pointer">
                        <option value="Semua Tingkat" class="bg-slate-900">Semua Tingkat</option>
                        <option value="mudah" {{ request('difficulty') == 'mudah' ? 'selected' : '' }} class="bg-slate-900">Easy</option>
                        <option value="menengah" {{ request('difficulty') == 'menengah' ? 'selected' : '' }} class="bg-slate-900">Medium</option>
                        <option value="sulit" {{ request('difficulty') == 'sulit' ? 'selected' : '' }} class="bg-slate-900">Hardcore</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-blue-400"></i>
                </div>
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tantangan..." class="bg-white/10 border border-white/10 px-6 py-4 rounded-2xl text-sm font-medium text-white placeholder:text-slate-500 focus:bg-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition w-full sm:w-64">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-500 w-10 h-10 rounded-xl flex items-center justify-center text-white transition shadow-lg">
                        <i class="fas fa-search text-xs"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Grid Katalog --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($proyeks as $proyek)
            @php
                $diffConfig = [
                    'mudah' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-500', 'border' => 'border-emerald-500/20', 'dot' => 'bg-emerald-500'],
                    'menengah' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20', 'dot' => 'bg-amber-500'],
                    'sulit' => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-500', 'border' => 'border-rose-500/20', 'dot' => 'bg-rose-500'],
                ][$proyek->kesulitan] ?? ['bg' => 'bg-slate-500/10', 'text' => 'text-slate-500', 'border' => 'border-slate-500/20', 'dot' => 'bg-slate-500'];

                $timeStatus = $proyek->time_status == 'urgent' ? 'bg-rose-500 text-white animate-pulse' : 'bg-slate-100 text-slate-600';
            @endphp

            <div class="group relative flex flex-col">
                {{-- Card Background & Hover Effect --}}
                <div class="absolute inset-0 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-[3rem] translate-y-4 opacity-0 group-hover:opacity-100 group-hover:translate-y-6 transition-all duration-500 blur-2xl -z-10"></div>
                
                <div class="bg-white rounded-[3rem] overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col h-full transform transition-all duration-500 group-hover:-translate-y-2">
                    
                    {{-- Visual Header --}}
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $proyek->cover ? asset('storage/' . $proyek->cover) : 'https://images.unsplash.com/photo-1557821552-17105176677c?q=80&w=800' }}" 
                             class="w-full h-full object-cover transition duration-1000 group-hover:scale-110">
                        
                        {{-- Badges on Image --}}
                        <div class="absolute top-6 left-6 flex flex-col gap-2">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">
                                {{ $proyek->mode }}
                            </span>
                            <span class="px-4 py-2 {{ $timeStatus }} rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">
                                <i class="far fa-clock mr-1"></i> {{ $proyek->time_label }}
                            </span>
                        </div>

                        {{-- Category/Roles Pills --}}
                        <div class="absolute bottom-6 left-6 right-6 flex flex-wrap gap-2">
                            @foreach($proyek->roles->take(2) as $role)
                                <span class="px-3 py-1.5 bg-slate-900/60 backdrop-blur-xl border border-white/20 text-white text-[9px] font-bold rounded-xl uppercase">
                                    {{ $role->nama_role }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-8 flex-grow flex flex-col">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex items-center gap-1.5 {{ $diffConfig['bg'] }} {{ $diffConfig['text'] }} px-3 py-1 rounded-full border {{ $diffConfig['border'] }}">
                                <div class="w-1.5 h-1.5 rounded-full {{ $diffConfig['dot'] }}"></div>
                                <span class="text-[10px] font-black uppercase tracking-wider">{{ $proyek->kesulitan }}</span>
                            </div>
                        </div>

                        <h3 class="text-2xl font-black text-slate-900 mb-4 group-hover:text-blue-600 transition-colors duration-300">
                            {{ $proyek->nama_proyek }}
                        </h3>

                        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-3 font-medium">
                            {{ $proyek->deskripsi }}
                        </p>

                        {{-- Mentor & Action --}}
                        <div class="mt-auto space-y-6">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-3xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center border-2 border-white shadow-sm">
                                        <i class="fas fa-user-tie text-slate-600 text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Lead Mentor</span>
                                        <span class="text-xs font-bold text-slate-700 truncate w-32">{{ $proyek->guru->name ?? 'CodeLab Mentor' }}</span>
                                    </div>
                                </div>
                                <div class="flex -space-x-2">
                                    <div class="w-7 h-7 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center text-[8px] font-bold text-blue-600">
                                        +{{ rand(10, 50) }}
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('siswa.katalog.show', $proyek->id) }}" class="flex items-center justify-center gap-3 w-full py-5 bg-slate-900 text-white rounded-[1.5rem] font-black text-sm hover:bg-blue-600 transition-all duration-300 shadow-xl shadow-slate-900/10">
                                Ambil Tantangan <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State dengan Ilustrasi Minimalis --}}
            <div class="col-span-full py-32 flex flex-col items-center">
                <div class="w-32 h-32 bg-slate-50 rounded-[3rem] flex items-center justify-center mb-6">
                    <i class="fas fa-ghost text-4xl text-slate-200"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Proyek tidak ditemukan</h3>
                <p class="text-slate-400 font-medium">Coba gunakan kata kunci lain atau ubah filter kesulitan.</p>
            </div>
        @endforelse
    </div>

    {{-- Custom Pagination --}}
    <div class="mt-20 flex justify-center">
        {{ $proyeks->appends(request()->query())->links() }}
    </div>

    {{-- Floating CTA Section --}}
    <div class="relative mt-20 p-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-[3.5rem] shadow-2xl shadow-blue-500/20">
        <div class="bg-slate-900 rounded-[3.4rem] p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-10 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 blur-[100px]"></div>
            
            <div class="relative z-10 text-center md:text-left">
                <span class="px-4 py-2 bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-[0.3em] rounded-full">Community Lab</span>
                <h2 class="text-3xl md:text-4xl font-black text-white mt-4 mb-4 leading-tight">Punya Ide Proyek <br> Sendiri?</h2>
                <p class="text-slate-400 font-medium max-w-sm">
                    Ajukan proposalmu dan ajak teman-temanmu berkolaborasi dalam satu tim impian.
                </p>
            </div>
            
            <a href="{{ route('siswa.proposal.create') }}" class="inline-block relative z-10 bg-white text-slate-900 px-10 py-5 rounded-[1.5rem] font-black text-sm hover:scale-105 hover:bg-blue-50 transition-all duration-300 shadow-2xl">
                Ajukan Sekarang <i class="fas fa-paper-plane ml-2"></i>
            </a>
        </div>
    </div>
</div>

<style>
    /* Custom style untuk line-clamp jika tidak pakai tailwind plugin */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endsection