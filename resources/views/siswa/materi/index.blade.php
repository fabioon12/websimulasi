@extends('siswa.layouts.app') 

@section('title', 'Dashboard Belajar - CodeLab')

@section('content')
{{-- Animated Background Elements --}}
<div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
    <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-200/30 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-[10%] left-[-5%] w-[400px] h-[400px] bg-indigo-200/30 rounded-full blur-[100px]"></div>
</div>

<div class="max-w-7xl mx-auto p-6 space-y-12">
    {{-- Hero Section: Glassmorphism with XP Stats --}}
    <div class="relative overflow-hidden bg-slate-950 rounded-[3.5rem] p-8 md:p-14 text-white shadow-2xl shadow-blue-900/20">
        {{-- Decorative Glow --}}
        <div class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-600/40 via-transparent to-transparent"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-10">
            <div class="text-center lg:text-left space-y-6">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                    <i class="fas fa-bolt text-yellow-400 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-100">Ready to Level Up?</span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black leading-tight tracking-tighter">
                    Lanjut Belajar,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">
                        {{ explode(' ', auth()->user()->name)[0] }}!
                    </span>
                </h1>
                
                <p class="text-blue-100/70 font-medium max-w-md text-lg">
                    Selesaikan tantangan hari ini dan kumpulkan poin untuk mendaki Hall of Fame.
                </p>
                
                <div class="flex flex-wrap gap-8 justify-center lg:justify-start pt-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600/20 flex items-center justify-center border border-blue-500/30">
                            <i class="fas fa-book-open text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black">{{ $materiAktif->count() }}</p>
                            <p class="text-[9px] font-bold uppercase opacity-50 tracking-widest">Kelas Aktif</p>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-yellow-500/20 flex items-center justify-center border border-yellow-500/30">
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                        <div>
                            {{-- Gunakan variabel total poin dari controller sebelumnya jika tersedia --}}
                            <p class="text-2xl font-black">{{ number_format($myTotalPoin ?? 0) }}</p>
                            <p class="text-[9px] font-bold uppercase opacity-50 tracking-widest">Total XP</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Floating Astronaut Icon --}}
            <div class="relative hidden lg:block">
                <div class="w-64 h-64 bg-blue-500/10 rounded-full flex items-center justify-center border border-white/5 animate-float">
                    <i class="fas fa-user-astronaut text-[8rem] text-blue-400 drop-shadow-[0_0_30px_rgba(59,130,246,0.5)]"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Lanjutkan Belajar (Horizontal Scroll on Mobile) --}}
    <section class="space-y-8">
        <div class="flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Terakhir Dipelajari</h2>
            </div>
            <a href="{{ route('siswa.materi.dashboard') }}" class="group flex items-center gap-2 text-blue-600 text-[10px] font-black uppercase tracking-widest">
                <span>Eksplor Semua</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($materiAktif as $item)
                @php
                    $total = $item->sub_materis_count ?? 0;
                    $selesai = $item->progress_count ?? 0;
                    $persen = $total > 0 ? round(($selesai / $total) * 100) : 0;
                    $isFinished = $persen >= 100;
                @endphp

                <div class="group relative bg-white rounded-[2.5rem] p-4 shadow-xl shadow-slate-200/60 border border-slate-100 hover:border-blue-200 transition-all duration-500 hover:-translate-y-2">
                    <div class="relative h-48 rounded-[2rem] overflow-hidden bg-slate-900 shadow-inner">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/'.$item->thumbnail) }}" class="w-full h-full object-cover opacity-80 group-hover:scale-110 group-hover:opacity-100 transition duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-800 to-slate-950">
                                <i class="fas fa-code text-white/10 text-6xl"></i>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 right-4">
                            <div class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-[10px] text-white font-bold">
                                {{ $item->kategori }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 px-2 space-y-4">
                        <h3 class="text-xl font-black text-slate-900 line-clamp-1">{{ $item->judul }}</h3>
                        
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mastery</span>
                                <span class="text-[10px] font-black text-blue-600">{{ $persen }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(37,99,235,0.4)]" 
                                     style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                        
                        <a href="{{ route('siswa.materi.learn', $item->id) }}" 
                           class="w-full py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 transition-all
                           {{ $isFinished ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-950 text-white hover:bg-blue-600' }}">
                            {{ $isFinished ? 'Review Modul' : 'Lanjutkan' }}
                            <i class="fas {{ $isFinished ? 'fa-redo' : 'fa-play-circle' }}"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-slate-300"></i>
                    </div>
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada kelas yang dimulai</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Section: Eksplorasi with Modern Cards --}}
    <section class="space-y-8 pt-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Katalog Materi</h2>
            
            <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                <a href="{{ route('siswa.materi.dashboard') }}" 
                   class="px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all
                   {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-white text-slate-400 border border-slate-200 hover:border-blue-400' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('siswa.materi.dashboard', ['category' => $cat]) }}" 
                       class="px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap
                       {{ request('category') == $cat ? 'bg-blue-600 text-white' : 'bg-white text-slate-400 border border-slate-200 hover:border-blue-400' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($materis as $materi)
                @php $isEnrolled = in_array($materi->id, $materiDiambilIds); @endphp
                
                <div class="bg-white rounded-[2rem] border border-slate-100 p-3 hover:shadow-2xl hover:shadow-slate-200 transition-all duration-300 group">
                    <div class="h-32 bg-slate-100 rounded-[1.5rem] mb-4 overflow-hidden relative">
                        @if($materi->thumbnail)
                            <img src="{{ asset('storage/'.$materi->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-50">
                                <i class="fas fa-laptop-code text-slate-200 text-3xl"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    
                    <div class="px-2">
                        <span class="text-[8px] font-black uppercase text-blue-600 tracking-tighter">{{ $materi->kategori }}</span>
                        <h4 class="text-sm font-black text-slate-800 leading-tight mt-1 line-clamp-2 h-10 group-hover:text-blue-600 transition-colors">
                            {{ $materi->judul }}
                        </h4>
                        
                        <div class="mt-4 flex items-center justify-between text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                            <span class="flex items-center gap-1">
                                <i class="far fa-clock"></i>
                                {{ $materi->sub_materis_count }} Modul
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-fire text-orange-500"></i>
                                Expert
                            </span>
                        </div>

                        <div class="mt-4">
                            @if($isEnrolled)
                                <a href="{{ route('siswa.materi.learn', $materi->id) }}" 
                                   class="w-full py-2.5 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-blue-600 hover:text-white transition-all">
                                    Mulai Belajar
                                </a>
                            @else
                                <form action="{{ route('siswa.materi.enroll', $materi->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-blue-600 transition-all active:scale-95">
                                        Ambil Kelas
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-15px) scale(1.05); }
    }
    .animate-float { animation: float 5s ease-in-out infinite; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Smooth transitions */
    * { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
</style>
@endsection