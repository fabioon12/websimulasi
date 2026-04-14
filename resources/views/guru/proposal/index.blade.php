@extends('guru.layouts.app')

@section('title', 'Persetujuan Proposal - Guru Panel')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight text-emerald-600">Review <span class="text-slate-900">Proposal.</span></h1>
            <p class="text-slate-500 mt-2 font-medium text-lg">Tinjau ide proyek siswa dan berikan bimbingan awal.</p>
        </div>
        <div class="bg-emerald-50 px-6 py-3 rounded-2xl border border-emerald-100">
            <span class="text-emerald-700 font-bold text-sm">Total Antrean: {{ $proposals->count() }} Proposal</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        @forelse($proposals as $proposal)
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col lg:flex-row transition-all hover:shadow-2xl hover:shadow-emerald-900/5 group">
            
            {{-- Thumbnail Area --}}
            <div class="lg:w-80 h-56 lg:h-auto bg-slate-100 shrink-0 relative overflow-hidden">
                @if($proposal->thumbnail)
                    <img src="{{ asset('storage/' . $proposal->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                        <i class="fas fa-image text-5xl mb-2"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">No Thumbnail</span>
                    </div>
                @endif
                <div class="absolute top-6 left-6">
                    <span class="px-4 py-2 rounded-xl bg-white/90 backdrop-blur text-emerald-600 text-[10px] font-black uppercase tracking-widest shadow-sm">
                        {{ $proposal->mode }}
                    </span>
                </div>
            </div>

            {{-- Info Area --}}
            <div class="p-8 lg:p-10 flex-grow flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs font-bold">
                            {{ substr($proposal->pengaju->name, 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Oleh: <span class="text-slate-900">{{ $proposal->pengaju->name }}</span>
                        </span>
                    </div>

                    <h2 class="text-2xl font-black text-slate-900 mb-3 group-hover:text-emerald-600 transition-colors">{{ $proposal->judul }}</h2>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8 line-clamp-3">
                        {{ $proposal->deskripsi }}
                    </p>
                    <div class="flex items-center gap-6 mb-8 p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50 w-fit">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                <i class="fas fa-calendar-plus text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">Mulai</p>
                                <p class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($proposal->tanggal_mulai)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="h-8 w-[1px] bg-emerald-200/50"></div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-rose-500 shadow-sm">
                                <i class="fas fa-calendar-check text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">Target Selesai</p>
                                <p class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($proposal->tanggal_selesai)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="h-8 w-[1px] bg-emerald-200/50"></div>

                        <div class="pr-2">
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">Durasi</p>
                            <p class="text-xs font-bold text-emerald-700">
                                {{ \Carbon\Carbon::parse($proposal->tanggal_mulai)->diffInDays($proposal->tanggal_selesai) }} Hari
                            </p>
                        </div>
                    </div>
                    {{-- Team Members --}}
                    <div class="flex flex-wrap gap-3 mb-8">
                        @foreach($proposal->anggota as $member)
                        <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="relative">
                                <div class="w-6 h-6 rounded-lg bg-emerald-500 flex items-center justify-center text-[10px] text-white font-bold">
                                    {{ substr($member->user->name, 0, 1) }}
                                </div>
                                @if($member->is_leader)
                                    <div class="absolute -top-2 -right-2 text-[10px] text-amber-500">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="leading-none">
                                <p class="text-[11px] font-bold text-slate-800">{{ $member->user->name }}</p>
                                <p class="text-[9px] text-emerald-600 font-black uppercase mt-0.5">{{ $member->role }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between pt-8 border-t border-slate-50">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                        Diterima: {{ $proposal->created_at->format('d M Y') }}
                    </span>

                    <div class="flex gap-4">
                        <form action="{{ route('guru.proposal.reject', $proposal->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Tolak proposal ini?')" 
                                class="px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                                <i class="fas fa-times mr-2"></i> Tolak
                            </button>
                        </form>

                        <form action="{{ route('guru.proposal.approve', $proposal->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                class="px-8 py-3 bg-emerald-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-1 transition-all">
                                <i class="fas fa-check-circle mr-2"></i> Setujui Proyek
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-24 text-center bg-white rounded-[4rem] border-2 border-dashed border-slate-100">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clipboard-check text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900 italic">Antrean Bersih!</h3>
            <p class="text-slate-400 mt-1 font-medium text-sm">Belum ada proposal baru yang membutuhkan persetujuan Anda.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection