@extends('guru.layouts.app')

@section('title', 'Dashboard Proyek - CodeLab')

@section('content')
<div class="max-w-7xl mx-auto px-4 md:px-8 pb-32 space-y-12">
    
    {{-- Glassmorphism Hero Header --}}
    <div class="relative mt-8 p-10 md:p-16 overflow-hidden bg-slate-900 rounded-[4rem] shadow-2xl shadow-emerald-200/50">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
            <div class="space-y-6 max-w-2xl">
                <div class="inline-flex items-center gap-3 px-5 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.4em]">Instructor Command Center</span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-none italic uppercase">
                    Welcome, <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-400 to-lime-400">
                        {{ Auth::user()->name }}
                    </span>
                </h1>
                
                <div class="flex items-center gap-6">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Active Projects</span>
                        <span class="text-3xl font-black text-white italic">{{ $proyeks->total() }}</span>
                    </div>
                    <div class="w-px h-10 bg-slate-800"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Status</span>
                        <span class="text-xs font-black text-emerald-400 uppercase px-3 py-1 bg-emerald-500/10 rounded-lg mt-1 border border-emerald-500/20">Verified Admin</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('guru.proyek.create') }}" 
               class="group relative inline-flex items-center justify-center px-12 py-6 font-black text-slate-900 transition-all duration-300 bg-white rounded-[2.5rem] hover:bg-emerald-500 hover:text-white hover:scale-105 active:scale-95 shadow-xl shadow-white/10 uppercase tracking-widest text-xs">
                <i class="fas fa-plus-circle mr-3 text-lg group-hover:rotate-90 transition-transform duration-500"></i> 
                Rancang Misi Baru
            </a>
        </div>

        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-10 pointer-events-none">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <path fill="#10B981" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,79.6,-45.8C87.4,-32.6,90,-16.3,88.5,-0.9C87,14.5,81.4,29,72.4,40.4C63.4,51.8,51,60.1,38,66.8C25,73.5,12.5,78.6,-0.8,80.1C-14.2,81.5,-28.4,79.2,-41.2,72.4C-54,65.6,-65.4,54.4,-72.6,41.2C-79.8,28.1,-82.9,14,-82.1,0.5C-81.2,-13.1,-76.5,-26.1,-68.8,-37.6C-61.1,-49.1,-50.4,-59.1,-38.3,-67.6C-26.2,-76.1,-13.1,-83.1,1,-84.9C15.1,-86.7,30.3,-83.5,44.7,-76.4Z" transform="translate(100 100)" />
            </svg>
        </div>
    </div>

    {{-- Search & Filter Section --}}
    <div class="flex flex-col md:flex-row gap-6 items-center">
        <form action="{{ route('guru.proyek.dashboard') }}" method="GET" class="w-full relative group">
            <i class="fas fa-search absolute left-8 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-600 transition-colors"></i>
            <input type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari kode proyek atau nama kelas..." 
                class="w-full pl-16 pr-8 py-6 bg-white border border-slate-100 rounded-[2.5rem] text-sm font-bold shadow-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 px-8 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all">
                Search
            </button>
        </form>
    </div>

    {{-- Project Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        @forelse($proyeks as $proyek)
        <div class="group relative">
            {{-- Glowing Background on Hover --}}
            <div class="absolute inset-x-10 inset-y-0 {{ $proyek->mode == 'kelompok' ? 'bg-emerald-600' : 'bg-amber-500' }} rounded-[4rem] blur-3xl opacity-0 group-hover:opacity-10 transition duration-500"></div>
            
            <div class="relative bg-white border border-slate-100 rounded-[4rem] p-4 shadow-sm group-hover:shadow-2xl group-hover:-translate-y-2 transition-all duration-500 flex flex-col">
                
                {{-- Cover Image Container --}}
                <div class="relative w-full h-72 rounded-[3.5rem] overflow-hidden">
                    <div class="absolute inset-0 bg-slate-900 mix-blend-multiply opacity-20 group-hover:opacity-0 transition-opacity"></div>
                    @if($proyek->cover)
                        <img src="{{ asset('storage/' . $proyek->cover) }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                            <i class="fas {{ $proyek->mode == 'kelompok' ? 'fa-users-cog text-emerald-600' : 'fa-user-astronaut text-amber-500' }} text-6xl opacity-30"></i>
                        </div>
                    @endif

                    {{-- Badges Over Image --}}
                    <div class="absolute top-6 left-6 flex flex-wrap gap-2">
                        <span class="px-5 py-2.5 bg-white/90 backdrop-blur rounded-2xl text-[9px] font-black uppercase tracking-widest text-slate-900 shadow-xl border border-white">
                             <i class="fas {{ $proyek->mode == 'kelompok' ? 'fa-users text-emerald-600' : 'fa-user text-amber-500' }} mr-2"></i>
                             {{ $proyek->mode == 'kelompok' ? 'Team Mode' : 'Solo Mode' }}
                        </span>
                        
                        @php
                            $diffClasses = [
                                'mudah' => 'bg-emerald-500/90 text-white',
                                'menengah' => 'bg-emerald-600/90 text-white', // Diubah menjadi emerald agar dominan hijau
                                'sulit' => 'bg-rose-600/90 text-white'
                            ];
                            $diffLabels = ['mudah' => 'Beginner', 'menengah' => 'Advanced', 'sulit' => 'Expert'];
                        @endphp
                        <span class="px-5 py-2.5 {{ $diffClasses[$proyek->kesulitan] ?? 'bg-slate-800' }} backdrop-blur rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-xl">
                            {{ $diffLabels[$proyek->kesulitan] ?? 'Standard' }}
                        </span>
                    </div>

                    {{-- Quick Action Buttons on Image --}}
                    <div class="absolute bottom-6 right-6 flex gap-2">
                         <a href="{{ route('guru.proyek.edit', $proyek->id) }}" class="w-12 h-12 bg-white/95 backdrop-blur rounded-2xl flex items-center justify-center text-slate-900 hover:bg-emerald-600 hover:text-white transition-all shadow-xl">
                            <i class="fas fa-pen-nib text-xs"></i>
                        </a>
                        <form action="{{ route('guru.proyek.destroy', $proyek->id) }}" method="POST" id="delete-form-{{ $proyek->id }}">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete('{{ $proyek->id }}', '{{ $proyek->nama_proyek }}')" 
                                    class="w-12 h-12 bg-white/95 backdrop-blur rounded-2xl flex items-center justify-center text-rose-500 hover:bg-rose-600 hover:text-white transition-all shadow-xl">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Project Content --}}
                <div class="px-8 py-8 space-y-6 flex-grow flex flex-col bg-white rounded-[3rem] border border-slate-100 shadow-sm group">
                    {{-- Header Card --}}
                    <div class="flex justify-between items-center">
                        <div class="px-4 py-1.5 bg-slate-50 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest border border-slate-100">
                            {{ $proyek->kelas }}
                        </div>
                        <div class="flex items-center gap-2 text-[10px] font-black text-rose-500 uppercase tracking-widest">
                            <i class="far fa-clock"></i> 
                            {{ \Carbon\Carbon::parse($proyek->deadline)->format('d M Y') }}
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="space-y-3">
                        <h4 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic leading-[0.9] group-hover:text-emerald-600 transition-colors duration-300">
                            {{ $proyek->nama_proyek }}
                        </h4>

                        <p class="text-sm text-slate-500 font-medium line-clamp-2 leading-relaxed">
                            {{ $proyek->deskripsi ?? 'Ready for deployment. Instructor has not provided detailed specs yet.' }}
                        </p>
                    </div>

                    {{-- Footer Section --}}
                    <div class="pt-6 mt-auto border-t border-slate-50 space-y-6">
                        {{-- Avatar Group --}}
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Team Assigned</span>
                            <div class="flex -space-x-3">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-user-circle text-slate-300 text-sm"></i>
                                    </div>
                                @endfor
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-emerald-600 flex items-center justify-center text-[10px] font-black text-white shadow-sm">
                                    +{{ $proyek->roles->count() }}
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-3">
                            {{-- Button 1: Edit Tugas (Icon Only - Outline) --}}
                            <a href="{{ route('guru.proyek.roadmap', $proyek->id) }}" 
                            class="w-14 h-14 inline-flex items-center justify-center bg-transparent border-2 border-slate-900 text-slate-900 rounded-[1.5rem] hover:bg-slate-900 hover:text-white hover:-translate-y-1 transition-all duration-300 group/btn"
                            title="Edit Tugas">
                                <i class="fas fa-edit text-lg group-hover/btn:rotate-12 transition-transform"></i>
                            </a>

                            {{-- Button 2: Pantau Progress (Full Width - Solid Emerald) --}}
                            <a href="{{ route('guru.review.index', $proyek->id) }}" 
                            class="flex-1 inline-flex items-center justify-center gap-3 px-6 py-4 bg-emerald-500 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] hover:bg-emerald-600 hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-emerald-100 group/btn">
                                <i class="fas fa-chart-line text-[12px] group-hover/btn:scale-110 transition-transform"></i>
                                <span>Pantau Progress</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="lg:col-span-2 py-32 text-center bg-white rounded-[5rem] border-4 border-dashed border-slate-100">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
                <i class="fas fa-ghost text-slate-200 text-4xl"></i>
            </div>
            <h3 class="text-3xl font-black text-slate-900 uppercase italic">Proyek Kosong</h3>
            <p class="text-xs text-slate-400 mt-4 font-bold uppercase tracking-[0.3em]">Belum ada proyek yang diluncurkan</p>
        </div>
        @endforelse
    </div>

    {{-- Custom Pagination --}}
    <div class="mt-20 flex justify-center">
        {{ $proyeks->appends(request()->query())->links() }}
    </div>

</div>

<style>
    /* Styling khusus Pagination */
    .pagination { @apply flex gap-3; }
    .page-item .page-link { @apply rounded-2xl border-none bg-white px-6 py-4 text-xs font-black text-slate-900 shadow-sm transition-all hover:scale-110; }
    .page-item.active .page-link { @apply bg-emerald-600 text-white shadow-xl shadow-emerald-200; }
    
    /* Hover Effects */
    .group:hover .group-hover\:-translate-y-2 { transform: translateY(-0.5rem); }
</style>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: '<span class="italic uppercase font-black tracking-tighter">Hapus Misi?</span>',
            html: `<p class="text-sm font-medium text-slate-500">Proyek <b class="text-slate-900">"${name}"</b> akan dihapus dari sistem secara permanen.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0f172a', // Slate 900
            cancelButtonColor: '#f43f5e',  // Rose 500
            confirmButtonText: 'YA, TERMINASI',
            cancelButtonText: 'BATAL',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[3rem] p-10',
                confirmButton: 'rounded-2xl font-black px-8 py-4 text-[10px] uppercase tracking-widest',
                cancelButton: 'rounded-2xl font-black px-8 py-4 text-[10px] uppercase tracking-widest'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection