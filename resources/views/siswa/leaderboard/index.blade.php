@extends('siswa.layouts.app')

@section('title', 'Hall of Fame - Blueprint Leaderboard')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen text-slate-900">
    
    {{-- Header & User Rank Highlight --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-12 bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100">
        <div>
            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-1 rounded-full text-xs font-bold mb-3 border border-blue-100">
                <i class="fas fa-trophy"></i>
                <span>Point Based Ranking</span>
            </div>
            <h1 class="text-4xl font-black text-slate-950 tracking-tighter leading-none">Hall of Fame 🏆</h1>
            <p class="text-slate-500 font-medium mt-3 text-lg max-w-md">Kumpulkan poin dari setiap tugas yang diterima mentor dan jadilah yang terbaik.</p>
        </div>
        
        {{-- My Status Card --}}
        <div class="bg-slate-950 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-slate-300 flex flex-col sm:flex-row items-center gap-6 min-w-[340px] relative overflow-hidden group w-full lg:w-auto">
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-600 rounded-full opacity-20 blur-3xl group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="flex items-center gap-6 w-full">
                <div class="relative z-10 w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-blue-900/50 border border-blue-500/50">
                    <i class="fas fa-user-astronaut"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-blue-200 text-[10px] font-black uppercase tracking-[0.2em]">Peringkat Anda</p>
                    <h3 class="text-3xl font-black tracking-tight mt-1">#{{ $myRank }} <span class="text-sm font-medium opacity-70">Global</span></h3>
                    <div class="flex items-center gap-2 mt-2 bg-white/10 px-3 py-1 rounded-full border border-white/10 w-fit">
                        <span class="text-[10px] font-black uppercase tracking-widest text-blue-100">{{ $user->class }}</span>
                        <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-blue-100">{{ $user->major }}</span>
                    </div>
                </div>
                <div class="relative z-10 ml-auto text-right self-start">
                    <p class="text-blue-200 text-[10px] font-black uppercase tracking-[0.2em]">Total Poin</p>
                    <h3 class="text-2xl font-black text-blue-400 mt-1">{{ number_format($myTotalPoin) }} <span class="text-xs uppercase">Poin</span></h3>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="relative z-10 w-full sm:w-auto border-t sm:border-t-0 sm:border-l border-white/10 pt-4 sm:pt-0 sm:pl-6 flex flex-row sm:flex-col gap-2">
                <button onclick="shareRanking()" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-share-alt"></i> Share
                </button>
                <a href="https://wa.me/?text={{ urlencode('🔥 Keren! Saya berada di peringkat #'.$myRank.' dengan total '.$myTotalPoin.' Poin di Hall of Fame CodeLab! Cek peringkatmu di: '.Request::url()) }}" 
                   target="_blank"
                   class="bg-white/10 hover:bg-white/20 text-white p-2 rounded-xl transition-all flex items-center justify-center">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Modern Podium Section (Top 3) --}}
    @if($leaderboard->onFirstPage())
    <div class="mb-16 mt-8 p-6 bg-white rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden">
        {{-- Decorative background --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-50 rounded-full blur-3xl -mr-48 -mt-48 opacity-50"></div>
        
        <div class="absolute top-6 left-8 flex items-center gap-3 z-10">
            <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em]">Tier 1 - Elite Students</h2>
        </div>

        <div class="flex flex-col md:flex-row items-end justify-center gap-6 lg:gap-8 pt-16 relative z-10">
            
            {{-- Juara 2 --}}
            @if(isset($leaderboard[1]))
            <div class="w-full md:w-64 order-2 md:order-1 group flex flex-col items-center">
                <div class="relative mb-6 transform group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center text-3xl font-black text-slate-400 shadow-xl border-4 border-slate-100 relative z-10 overflow-hidden">
                        {{ substr($leaderboard[1]->name, 0, 2) }}
                    </div>
                    <div class="absolute -top-4 -right-4 w-12 h-12 bg-slate-300 text-slate-900 rounded-full flex items-center justify-center border-4 border-white font-black italic shadow-lg z-20 text-lg">2</div>
                </div>
                <div class="text-center mb-4">
                    <h3 class="font-black text-slate-950 text-lg line-clamp-1 leading-none">{{ $leaderboard[1]->name }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2 bg-slate-100 px-3 py-1 rounded-full inline-block">
                        {{ $leaderboard[1]->class }} {{ $leaderboard[1]->major }}
                    </p>
                </div>
                <div class="w-full h-32 bg-gradient-to-b from-slate-100 to-white rounded-t-[2rem] border-t-2 border-slate-200 flex flex-col items-center justify-center relative overflow-hidden">
                    <span class="text-3xl font-black text-blue-700">{{ number_format($leaderboard[1]->total_poin ?? 0) }}</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Poin Poin</span>
                </div>
            </div>
            @endif

            {{-- Juara 1 --}}
            @if(isset($leaderboard[0]))
            <div class="w-full md:w-72 order-1 md:order-2 group flex flex-col items-center transform scale-105 z-20 -mb-2">
                <div class="absolute -top-12 left-1/2 -translate-x-1/2 text-5xl animate-bounce z-30">👑</div>
                <div class="relative mb-6 transform group-hover:-translate-y-3 transition-transform duration-300">
                    <div class="w-32 h-32 bg-slate-950 rounded-[2.5rem] flex items-center justify-center text-5xl font-black text-white shadow-2xl border-4 border-blue-600 relative z-10 overflow-hidden shadow-blue-300">
                        {{ substr($leaderboard[0]->name, 0, 2) }}
                        <div class="absolute -inset-full top-0 block h-full w-1/2 -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
                    </div>
                    <div class="absolute -top-4 -right-4 w-14 h-14 bg-amber-400 text-slate-950 rounded-full flex items-center justify-center border-4 border-slate-950 font-black italic text-2xl shadow-lg z-20">1</div>
                </div>
                <div class="text-center mb-5">
                    <h3 class="font-black text-slate-950 text-2xl line-clamp-1 tracking-tight">{{ $leaderboard[0]->name }}</h3>
                    <p class="text-[11px] text-blue-700 font-bold uppercase tracking-widest mt-2 bg-blue-50 px-4 py-1.5 rounded-full inline-block border border-blue-100">
                        {{ $leaderboard[0]->class }} {{ $leaderboard[0]->major }}
                    </p>
                </div>
                <div class="w-full h-48 bg-gradient-to-b from-blue-600 to-blue-800 rounded-t-[2.5rem] shadow-xl border-t-2 border-blue-400 flex flex-col items-center justify-center relative overflow-hidden">
                    <span class="text-5xl font-black text-white tracking-tighter">{{ number_format($leaderboard[0]->total_poin ?? 0) }}</span>
                    <span class="text-xs font-black text-blue-100 uppercase tracking-[0.3em] mt-2 italic">🏆 Top Contributor</span>
                </div>
            </div>
            @endif

            {{-- Juara 3 --}}
            @if(isset($leaderboard[2]))
            <div class="w-full md:w-64 order-3 group flex flex-col items-center">
                <div class="relative mb-6 transform group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center text-3xl font-black text-orange-400 shadow-xl border-4 border-orange-50 relative z-10 overflow-hidden">
                        {{ substr($leaderboard[2]->name, 0, 2) }}
                    </div>
                    <div class="absolute -top-4 -right-4 w-12 h-12 bg-orange-400 text-white rounded-full flex items-center justify-center border-4 border-white font-black italic shadow-lg z-20 text-lg">3</div>
                </div>
                <div class="text-center mb-4">
                    <h3 class="font-black text-slate-950 text-lg line-clamp-1 leading-none">{{ $leaderboard[2]->name }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2 bg-slate-100 px-3 py-1 rounded-full inline-block">
                        {{ $leaderboard[2]->class }} {{ $leaderboard[2]->major }}
                    </p>
                </div>
                <div class="w-full h-24 bg-gradient-to-b from-slate-50 to-white rounded-t-[2rem] border-t-2 border-slate-100 flex flex-col items-center justify-center relative overflow-hidden">
                    <span class="text-3xl font-black text-blue-700">{{ number_format($leaderboard[2]->total_poin ?? 0) }}</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Poin Poin</span>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Tabel List --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-xl font-black text-slate-950 tracking-tight leading-none">Global Ranking</h3>
            <div class="text-sm text-slate-500 font-medium bg-white px-4 py-2 rounded-full border border-slate-100 shadow-inner">
                Total Siswa: <span class="font-bold text-slate-800">{{ $leaderboard->total() }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white">
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest border-b border-slate-100">Rank</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest border-b border-slate-100">Student Info</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right border-b border-slate-100">Total Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($leaderboard as $siswa)
                        @php $actualRank = ($leaderboard->currentPage() - 1) * $leaderboard->perPage() + $loop->iteration; @endphp
                    <tr class="{{ $siswa->id == auth()->id() ? 'bg-blue-50/70' : 'hover:bg-slate-50/70' }} transition-all group">
                        <td class="px-8 py-6 relative">
                            @if($siswa->id == auth()->id()) <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-600 rounded-r"></div> @endif
                            <span class="font-black italic text-lg {{ $actualRank <= 3 ? 'text-blue-600' : 'text-slate-300' }}">#{{ $actualRank }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-500 font-black text-xs border border-slate-100 shadow-inner">
                                    {{ substr($siswa->name, 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-900 leading-tight">{{ $siswa->name }}</h4>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-black uppercase tracking-widest">
                                            {{ $siswa->class }}
                                        </span>
                                        <span class="text-[9px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-black uppercase tracking-widest">
                                            {{ $siswa->major }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-2xl font-black text-slate-950 italic leading-none">{{ number_format($siswa->total_poin ?? 0) }}</span>
                            <span class="text-[10px] font-bold text-slate-400 ml-1">Poin</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10 mb-8 pagination-blue">
        {{ $leaderboard->links() }}
    </div>
</div>

<style>
    @keyframes shine { 100% { left: 125%; } }
    .group:hover .group-hover\:animate-shine { animation: shine 0.8s; }
    .pagination-blue nav span[aria-current="page"] span { background-color: #2563eb !important; color: white !important; border-radius: 0.5rem; }
</style>

<script>
function shareRanking() {
    const text = 'Alhamdulillah! Saya berada di peringkat #{{ $myRank }} dengan total {{ $myTotalPoin }} Poin di Hall of Fame Leaderboard! 🚀';
    const url = window.location.href;

    if (navigator.share) {
        navigator.share({ title: 'Leaderboard CodeLab', text: text, url: url });
    } else {
        const temp = document.createElement("input");
        document.body.appendChild(temp);
        temp.value = text + " " + url;
        temp.select();
        document.execCommand("copy");
        document.body.removeChild(temp);
        alert("Pesan & Link disalin ke clipboard! Bagikan ke Social Media kamu.");
    }
}
</script>
@endsection