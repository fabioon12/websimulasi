@extends('siswa.layouts.app')

@section('title', 'Pusat Proyek - CodeLab')

@section('content')
{{-- Dekorasi Latar Belakang --}}
<div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
    <div class="absolute top-[20%] right-[-10%] w-[600px] h-[600px] bg-blue-50/50 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-50/50 rounded-full blur-[100px]"></div>
</div>

<div class="max-w-7xl mx-auto p-4 md:p-6 space-y-10">
    
    {{-- Glassmorphism Header --}}
    <div class="relative overflow-hidden bg-white/70 backdrop-blur-xl p-8 md:p-12 rounded-[3.5rem] border border-white shadow-2xl shadow-slate-200/50">
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-10">
            <div class="space-y-4 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-200">
                    <i class="fas fa-rocket text-xs animate-bounce"></i>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Mission Control</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight">
                    Pusat Proyek <br><span class="text-blue-600">Strategis Kamu</span>
                </h1>
                <p class="text-slate-500 font-medium max-w-md text-lg">
                    @if(request('status') == 'completed')
                        Luar biasa! Kamu telah menaklukkan <span class="text-emerald-600 font-bold">{{ $counts['completed'] }} tantangan</span> besar. 🏆
                    @else
                        Siap untuk eksekusi? Ada <span class="text-blue-600 font-bold">{{ $counts['active'] }} misi aktif</span> yang menanti sentuhanmu.
                    @endif
                </p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-4">
                <div class="group bg-white p-6 rounded-[2.5rem] text-center border border-slate-100 shadow-xl shadow-slate-200/40 hover:scale-105 transition-all duration-500">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 transition-colors">
                        <i class="fas fa-fire text-blue-600 group-hover:text-white"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total XP</p>
                    <p class="text-3xl font-black text-slate-900">{{ number_format($totalPoinSiswa) }}</p>
                </div>
                <div class="group bg-white p-6 rounded-[2.5rem] text-center border border-slate-100 shadow-xl shadow-slate-200/40 hover:scale-105 transition-all duration-500">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-indigo-600 transition-colors">
                        <i class="fas fa-trophy text-indigo-600 group-hover:text-white"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Global Rank</p>
                    <p class="text-3xl font-black text-slate-900">#{{ $rank ?? '00' }}</p>
                </div>
            </div>
        </div>
        
        <div class="absolute top-0 right-0 opacity-5 pointer-events-none">
            <i class="fas fa-project-diagram text-[20rem] -mr-20 -mt-20"></i>
        </div>
    </div>

    {{-- Modern Navigation --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="inline-flex bg-slate-100 p-1.5 rounded-[2rem] border border-slate-200/50">
            <a href="?status=active" class="px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest transition-all {{ request('status', 'active') == 'active' ? 'bg-white text-blue-600 shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                Active Misi ({{ $counts['active'] }})
            </a>
            <a href="?status=completed" class="px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest transition-all {{ request('status') == 'completed' ? 'bg-white text-emerald-600 shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">
                Archived ({{ $counts['completed'] }})
            </a>
        </div>
        
        <div class="relative group w-full md:w-80">
            <input type="text" placeholder="Cari kode proyek..." class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-3xl text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500"></i>
        </div>
    </div>

    {{-- Daftar Proyek --}}
    <div class="grid grid-cols-1 gap-8">
        @forelse($proyekAktif as $item)
        @php
            $deadline = \Carbon\Carbon::parse($item->proyek->deadline);
            $isOverdue = \Carbon\Carbon::now()->greaterThan($deadline);
            $isCompleted = ($item->progress ?? 0) >= 100;
            $isLocked = $isOverdue && !$isCompleted;
        @endphp

        <div class="group relative bg-white border border-slate-100 rounded-[3rem] p-4 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:border-blue-100 transition-all duration-500 overflow-hidden {{ $isLocked ? 'grayscale-[0.5] opacity-90' : '' }}">
            
            {{-- Badge Status --}}
            <div class="absolute top-8 right-8 z-20">
                <div class="flex items-center gap-2 px-4 py-2 bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-100">
                    @if($isLocked)
                        <i class="fas fa-lock text-rose-500 text-[10px]"></i>
                        <span class="text-[10px] font-black uppercase text-rose-600 tracking-widest">Waktu Habis</span>
                    @else
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isCompleted ? 'bg-emerald-400' : 'bg-blue-400' }} opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $isCompleted ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                        </span>
                        <span class="text-[10px] font-black uppercase text-slate-700 tracking-widest">
                            {{ $isCompleted ? 'Ready for Review' : 'In Progress' }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-10 p-4 md:p-6">
                {{-- Visual Image & Radial Progress --}}
                <div class="relative w-full lg:w-64 h-64 flex-shrink-0">
                    <div class="w-full h-full rounded-[2.5rem] overflow-hidden {{ $isLocked ? 'bg-rose-950' : 'bg-slate-900' }} relative">
                        <img src="{{ $item->proyek->cover ? asset('storage/' . $item->proyek->cover) : 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400' }}" 
                             class="w-full h-full object-cover opacity-50 group-hover:scale-110 transition duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                    </div>
                    
                    {{-- Floating Progress Overlay --}}
                    <div class="absolute -bottom-4 -right-4 bg-white p-4 rounded-[2rem] shadow-xl border border-slate-50 flex items-center gap-3">
                        <div class="relative inline-flex items-center justify-center">
                            <svg class="w-12 h-12 transform -rotate-90">
                                <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" class="text-slate-100" />
                                <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" 
                                    stroke-dasharray="{{ 2 * pi() * 20 }}" 
                                    stroke-dashoffset="{{ (2 * pi() * 20) * (1 - ($item->progress ?? 0) / 100) }}" 
                                    class="{{ $isLocked ? 'text-rose-500' : ($isCompleted ? 'text-emerald-500' : 'text-blue-600') }} transition-all duration-1000" />
                            </svg>
                            <span class="absolute text-[10px] font-black text-slate-900">{{ $item->progress }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-grow flex flex-col justify-center space-y-6">
                    <div class="space-y-2">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[9px] font-black uppercase tracking-widest {{ $isLocked ? 'text-rose-500' : 'text-blue-600' }}">{{ $item->proyek->kategori ?? 'Web Simulation' }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">ID: PRJ-{{ $item->proyek_id }}</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tighter group-hover:text-blue-600 transition-colors">
                            {{ $item->proyek->nama_proyek }}
                        </h2>
                    </div>

                    <p class="text-slate-500 leading-relaxed max-w-2xl line-clamp-2 font-medium">
                        {{ $item->proyek->deskripsi }}
                    </p>

                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-user-ninja text-slate-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Role Assigned</p>
                                <p class="text-xs font-bold text-slate-700 uppercase">{{ $item->role->nama_role }}</p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-slate-100 hidden md:block"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $isLocked ? 'bg-rose-100' : 'bg-blue-50' }} flex items-center justify-center">
                                <i class="far fa-clock {{ $isLocked ? 'text-rose-500' : 'text-blue-500' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Deadline</p>
                                <p class="text-xs font-bold {{ $isLocked ? 'text-rose-600' : 'text-slate-700' }}">{{ $deadline->format('d M, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        @if($isLocked)
                            <div class="px-8 py-4 rounded-[1.5rem] bg-slate-100 text-slate-400 font-black text-xs uppercase tracking-widest flex items-center gap-3 cursor-not-allowed">
                                <i class="fas fa-lock text-[10px]"></i> Akses Terkunci
                            </div>
                        @else
                            <a href="{{ route('siswa.proyek.pengerjaan', $item->proyek_id) }}" 
                               class="group/btn px-10 py-4 rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all flex items-center gap-3 shadow-xl 
                               {{ $isCompleted ? 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-emerald-200' : 'bg-slate-900 hover:bg-blue-600 text-white shadow-blue-200' }}">
                                {{ $isCompleted ? 'Review Hasil' : 'Buka Workspace' }}
                                <i class="fas fa-chevron-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        @endif
                        
                        <div class="flex items-center gap-2">
                            <button onclick="shareProject('{{ $item->proyek->nama_proyek }}', '{{ $item->progress }}')"
                                    class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 text-slate-400 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 flex items-center justify-center shadow-sm">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Progress Bar Bawah --}}
            <div class="absolute bottom-0 left-0 w-full h-1.5 bg-slate-50 overflow-hidden">
                <div class="h-full transition-all duration-1000 {{ $isLocked ? 'bg-rose-500' : ($isCompleted ? 'bg-emerald-500' : 'bg-blue-600') }}" 
                     style="width: {{ $item->progress }}%"></div>
            </div>
        </div>
        @empty
        <div class="py-24 text-center bg-white/50 backdrop-blur-sm rounded-[4rem] border-4 border-dashed border-slate-100">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                <i class="fas fa-satellite-dish text-slate-200 text-3xl animate-pulse"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Tidak Ada Sinyal Misi</h3>
            <p class="text-slate-400 font-medium mb-10 max-w-sm mx-auto">
                {{ request('status') == 'completed' ? 'Arsip misi kamu masih kosong. Ayo selesaikan satu proyek hari ini!' : 'Belum ada misi yang dijalankan. Pergi ke pangkalan data untuk mengambil proyek baru.' }}
            </p>
            <a href="{{ route('siswa.katalog.index') }}" class="inline-flex items-center gap-3 bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-blue-100">
                Eksplor Katalog <i class="fas fa-compass"></i>
            </a>
        </div>
        @endforelse
    </div>

    {{-- Stats Call to Action --}}
    <div class="relative rounded-[3.5rem] bg-gradient-to-br from-indigo-600 to-blue-700 p-10 md:p-16 text-white overflow-hidden mt-10">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="text-center md:text-left space-y-4">
                <h3 class="text-3xl md:text-4xl font-black tracking-tighter">Asah Skill-mu ke Level <br>Berikutnya!</h3>
                <p class="text-blue-100 font-medium opacity-80 max-w-md">Setiap proyek yang kamu selesaikan meningkatkan peluangmu untuk mendapatkan sertifikat profesional.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('siswa.katalog.index') }}" class="px-10 py-5 bg-white text-blue-600 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] hover:scale-105 transition shadow-2xl">
                    Cari Proyek Baru
                </a>
                <a href="{{ route('siswa.leaderboard') }}" class="px-10 py-5 bg-blue-500/20 backdrop-blur-md border border-white/20 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-white/10 transition">
                    Lihat Ranking
                </a>
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 text-[18rem] text-white/10 -rotate-12">
            <i class="fas fa-shield-alt"></i>
        </div>
    </div>
</div>

<style>
    * { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .group:hover .group-hover\:scale-110 { transform: scale(1.1); }
</style>

<script>
    function shareProject(projectName, progress) {
        const text = `🎯 Mission Update: Saya sedang mengerjakan "${projectName}" dan sudah mencapai progres ${progress}% di CodeLab! 🚀`;
        const url = window.location.origin + "/siswa/proyek";

        if (navigator.share) {
            navigator.share({
                title: 'CodeLab Mission Update',
                text: text,
                url: url
            }).catch(console.error);
        } else {
            const el = document.createElement('textarea');
            el.value = text + " " + url;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert("Status misi berhasil disalin ke clipboard!");
        }
    }
</script>
@endsection