@extends('guru.layouts.app')

@section('title', 'Manajemen Sub Materi - CodeLab')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 space-y-8">
    
    {{-- Top Navigation & Title --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            {{-- Tombol Kembali ke Dashboard Utama Materi --}}
            <a href="{{ route('guru.materi.dashboard') }}" class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Sub Materi</h1>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                    Materi: <span class="text-blue-600">{{ $materi->judul }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('guru.submateri.create', $materi->id) }}" class="bg-slate-900 text-white px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
                <i class="fas fa-plus mr-2"></i> Tambah Bab Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Main Content: List of Chapters --}}
        <div class="lg:col-span-8 space-y-4">
            
            @forelse($subMateris as $sub)
                <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition flex items-center justify-between group">
                    <div class="flex items-center gap-6">
                        {{-- Index Number (Auto-increment format 01, 02, dst) --}}
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 font-black flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                            {{ str_pad($sub->urutan, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 group-hover:text-blue-600 transition">{{ $sub->judul }}</h3>
                            <div class="flex items-center gap-4 mt-1">
                                {{-- Indikator Video --}}
                                @if($sub->video_url)
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        <i class="fas fa-play-circle mr-1 text-rose-500"></i> Video
                                    </span>
                                @endif

                                {{-- Indikator PDF --}}
                                @if($sub->pdf_path)
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        <i class="fas fa-file-pdf mr-1 text-orange-500"></i> PDF
                                    </span>
                                @endif

                                {{-- Indikator Coding Lab --}}
                                @if($sub->instruksi_coding || $sub->starter_code)
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        <i class="fas fa-file-code mr-1 text-emerald-500"></i> Lab
                                    </span>
                                @endif
                                {{-- Indikator Kuis --}}
                                @if($sub->tipe === 'kuis')
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        <i class="fas fa-tasks mr-1 text-amber-500"></i> Kuis
                                    </span>
                                @endif
                                {{-- Kategori Badge --}}
                                <span class="text-[9px] px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg font-bold uppercase tracking-tighter">
                                    {{ $sub->kategori }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Button Edit --}}
                        <a href="{{ route('guru.submateri.edit', ['materi_id' => $materi->id, 'id' => $sub->id]) }}" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-blue-600 transition flex items-center justify-center">
                            <i class="fas fa-edit text-xs"></i>
                        </a>

                        {{-- Button Delete --}}
                        <form action="{{ route('guru.submateri.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bab ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-600 transition flex items-center justify-center">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="bg-white rounded-[3rem] p-20 border border-dashed border-slate-200 text-center">
                    <i class="fas fa-layer-group text-4xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 font-bold">Belum ada sub materi yang dibuat untuk kurikulum ini.</p>
                    <a href="{{ route('guru.submateri.create', $materi->id) }}" class="inline-block mt-4 text-sm font-black text-blue-600 uppercase tracking-widest">Mulai Buat Materi Sekarang &rarr;</a>
                </div> 
            @endforelse

        </div>

        {{-- Sidebar: Stats & Info --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Summary Card Dinamis --}}
            <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-100">
                <h4 class="text-xs font-black uppercase tracking-widest opacity-70">Ringkasan Konten</h4>
                <div class="mt-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-white/10 pb-3">
                        <span class="text-xs font-bold">Total Bab</span>
                        <span class="text-xl font-black">{{ str_pad($stats['total'], 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-white/10 pb-3">
                        <span class="text-xs font-bold">Video Tutorial</span>
                        <span class="text-xl font-black">{{ str_pad($stats['video'], 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold">Latihan Koding</span>
                        <span class="text-xl font-black">{{ str_pad($stats['coding'], 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

            {{-- Guide Card --}}
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white">
                <h4 class="text-xs font-black uppercase tracking-widest text-blue-400 mb-4">Tips Pengajaran</h4>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Siswa lebih menyukai materi yang terfragmentasi (kecil-kecil namun padat). Gunakan <strong>Urutan</strong> untuk memecah topik besar menjadi beberapa sub-bab yang mudah dicerna.
                </p>
                <a href="#" class="block w-full mt-6 py-3 bg-white/5 border border-white/10 rounded-xl text-[9px] text-center font-black uppercase tracking-widest hover:bg-white/10 transition">
                    Lihat Dokumentasi
                </a>
            </div>

        </div>
    </div>
</div>
@endsection