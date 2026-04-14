@extends('siswa.layouts.app')

@section('title', 'Undangan Tim - CodeLab')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-900">Undangan <span class="text-blue-600">Kolaborasi.</span></h1>
        <p class="text-slate-500 font-medium">Konfirmasi peranmu dalam proyek teman-temanmu.</p>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($invitations as $invite)
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 group hover:border-blue-200 transition-all">
                
                <div class="flex items-center gap-6">
                    {{-- Avatar Ketua --}}
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($invite->proposal->pengaju->name) }}&background=random" 
                             class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-amber-500 rounded-lg flex items-center justify-center text-white text-[10px] shadow-sm">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                            {{ $invite->proposal->judul }}
                        </h4>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                            <span class="text-xs font-bold text-slate-400">
                                <i class="fas fa-user-tie mr-1"></i> Inviter: {{ $invite->proposal->pengaju->name }}
                            </span>
                            <span class="text-xs font-black text-blue-500 uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded-md">
                                As: {{ $invite->role }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    {{-- Form Tolak --}}
                    <form action="{{ route('siswa.invitation.respond', [$invite->id, 'tolak']) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3.5 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-50 hover:text-rose-600 transition-all">
                            Tolak
                        </button>
                    </form>

                    {{-- Form Terima --}}
                    <form action="{{ route('siswa.invitation.respond', [$invite->id, 'setuju']) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-8 py-3.5 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-blue-600 transition-all">
                            Gabung Tim
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                <i class="fas fa-ghost text-4xl text-slate-200 mb-4"></i>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada undangan kolaborasi</p>
            </div>
        @endforelse
    </div>
</div>
@endsection