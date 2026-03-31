@extends('guru.layouts.app')

@section('title', 'Manajemen Leaderboard - Guru CodeLab')

@section('content')
<div class="p-6 space-y-8">
    {{-- Header & Stats --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Leaderboard Siswa 🏆</h1>
            <p class="text-slate-500 font-medium">Pantau performa dan distribusi poin seluruh siswa.</p>
        </div>
        
        <div class="flex gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none">Total Siswa</p>
                    <p class="text-lg font-bold text-slate-900">{{ $stats['total_siswa'] }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none">Poin Beredar</p>
                    <p class="text-lg font-bold text-slate-900">{{ number_format($stats['total_poin_beredar']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Rank</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tugas Selesai</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Total Poin</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($leaderboard as $index => $siswa)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            @php $rank = $leaderboard->firstItem() + $index; @endphp
                            @if($rank <= 3)
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center 
                                    {{ $rank == 1 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $rank == 2 ? 'bg-slate-200 text-slate-700' : '' }}
                                    {{ $rank == 3 ? 'bg-orange-100 text-orange-700' : '' }}">
                                    <i class="fas fa-medal text-xs"></i>
                                </div>
                            @else
                                <span class="text-slate-400 font-bold ml-3">#{{ $rank }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->name) }}&background=random" alt="">
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 group-hover:text-blue-600 transition">{{ $siswa->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">{{ $siswa->class }} - {{ $siswa->major }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black text-slate-600 uppercase">
                                {{ $siswa->tugas_selesai }} Proyek
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-lg font-black text-slate-900">{{ number_format($siswa->total_poin ?? 0) }}</span>
                            <span class="text-[10px] font-bold text-blue-600 ml-1">XP</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <button class="p-2 text-slate-400 hover:text-blue-600 transition" title="Lihat Detail">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <i class="fas fa-users-slash text-4xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-medium">Belum ada data peringkat siswa.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-8 bg-slate-50/50 border-t border-slate-100">
            {{ $leaderboard->links() }}
        </div>
    </div>
</div>

<style>
    /* Styling untuk pagination Laravel agar matching dengan Tailwind */
    .pagination { @apply flex gap-2; }
    .page-item { @apply rounded-xl overflow-hidden border border-slate-200; }
    .page-link { @apply px-4 py-2 bg-white text-slate-600 font-bold text-xs hover:bg-blue-600 hover:text-white transition; }
    .page-item.active .page-link { @apply bg-blue-600 text-white border-blue-600; }
</style>
@endsection