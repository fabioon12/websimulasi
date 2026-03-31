@extends('guru.layouts.app')

@section('title', 'Edit Proyek - CodeLab')

@section('content')
<div class="max-w-4xl mx-auto pb-20 space-y-8">
    
    {{-- Header Navigasi --}}
    <div class="flex items-center justify-between px-4 pt-4">
        <div class="flex items-center gap-5">
            <a href="{{ route('guru.proyek.dashboard') }}" 
               class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-100 hover:bg-rose-50 transition-all shadow-sm group">
                <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 uppercase italic leading-none">Edit Proyek</h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 text-indigo-600">ID Proyek: #{{ $proyek->id }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden">
        {{-- Hero Header --}}
        <div class="bg-slate-900 p-10 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-3xl font-black uppercase italic tracking-tighter leading-none">Perbarui <span class="text-indigo-400">Konfigurasi</span></h2>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-3 italic opacity-80">Pastikan informasi sinkron dengan kebutuhan kurikulum.</p>
            </div>
            <i class="fas fa-edit absolute right-10 top-1/2 -translate-y-1/2 text-white/5 text-8xl rotate-12"></i>
        </div>

        <form action="{{ route('guru.proyek.update', $proyek->id) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-10">
            @csrf
            @method('PUT')

            {{-- Grid Informasi Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 flex items-center gap-2">
                        <i class="fas fa-tag text-indigo-500"></i> Nama Proyek
                    </label>
                    <input type="text" name="nama_proyek" value="{{ old('nama_proyek', $proyek->nama_proyek) }}" required 
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 shadow-inner">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-blue-500"></i> Kelas
                    </label>
                    <select name="kelas" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 shadow-inner cursor-pointer">
                        <option value="Kelas X" {{ old('kelas', $proyek->kelas) == 'Kelas X' ? 'selected' : '' }}>Kelas X</option>
                        <option value="Kelas XI" {{ old('kelas', $proyek->kelas) == 'Kelas XI' ? 'selected' : '' }}>Kelas XI</option>
                        <option value="Kelas XII" {{ old('kelas', $proyek->kelas) == 'Kelas XII' ? 'selected' : '' }}>Kelas XII</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-rose-500"></i> Deadline Global
                    </label>
                    <input type="date" name="deadline" value="{{ old('deadline', \Carbon\Carbon::parse($proyek->deadline)->format('Y-m-d')) }}" required 
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-rose-500 font-bold text-slate-700 shadow-inner">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 flex items-center gap-2">
                        <i class="fas fa-image text-emerald-500"></i> Cover Proyek
                    </label>
                    <div class="flex items-center gap-4 bg-slate-50 p-2 rounded-2xl border border-dashed border-slate-200">
                        @if($proyek->cover)
                            <img src="{{ asset('storage/' . $proyek->cover) }}" class="w-12 h-12 rounded-xl object-cover border-2 border-white shadow-sm">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400 text-xs italic">N/A</div>
                        @endif
                        <input type="file" name="cover" class="text-[9px] text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition-all">
                    </div>
                </div>
            </div>

            {{-- Section: Tingkat Kesulitan (Pembaruan) --}}
            <div class="bg-white border border-slate-100 rounded-[3rem] p-8 md:p-10 shadow-sm space-y-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                    <h3 class="font-black text-slate-900 uppercase italic text-sm tracking-widest">Tingkat Kesulitan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Mudah --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="kesulitan" value="mudah" class="peer hidden" {{ old('kesulitan') == 'mudah' ? 'checked' : '' }} required>
                        <div class="p-6 border-2 border-slate-50 rounded-[2.5rem] bg-slate-50/50 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 flex flex-col items-center gap-3 hover:shadow-md">
                            <div class="w-12 h-12 rounded-2xl bg-white text-emerald-500 flex items-center justify-center shadow-sm">
                                <i class="fas fa-leaf text-lg"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-black text-slate-900 text-[10px] uppercase italic">Mudah</p>
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Dasar & Pemula</p>
                            </div>
                        </div>
                    </label>

                    {{-- Menengah --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="kesulitan" value="menengah" class="peer hidden" {{ old('kesulitan', 'menengah') == 'menengah' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-slate-50 rounded-[2.5rem] bg-slate-50/50 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 flex flex-col items-center gap-3 hover:shadow-md">
                            <div class="w-12 h-12 rounded-2xl bg-white text-blue-500 flex items-center justify-center shadow-sm">
                                <i class="fas fa-chess-knight text-lg"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-black text-slate-900 text-[10px] uppercase italic">Menengah</p>
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Logika Standar</p>
                            </div>
                        </div>
                    </label>

                    {{-- Sulit --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="kesulitan" value="sulit" class="peer hidden" {{ old('kesulitan') == 'sulit' ? 'checked' : '' }}>
                        <div class="p-6 border-2 border-slate-50 rounded-[2.5rem] bg-slate-50/50 transition-all peer-checked:border-rose-500 peer-checked:bg-rose-50 flex flex-col items-center gap-3 hover:shadow-md">
                            <div class="w-12 h-12 rounded-2xl bg-white text-rose-500 flex items-center justify-center shadow-sm">
                                <i class="fas fa-fire text-lg"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-black text-slate-900 text-[10px] uppercase italic">Sulit</p>
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Kompleks & Advance</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 flex items-center gap-2">
                    <i class="fas fa-align-left text-indigo-500"></i> Deskripsi Singkat
                </label>
                <textarea name="deskripsi" rows="4" class="w-full px-6 py-4 bg-slate-50 border-none rounded-[2rem] focus:ring-2 focus:ring-indigo-500 font-medium text-slate-600 shadow-inner leading-relaxed transition-all">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
            </div>

            <div class="pt-8 flex flex-col md:flex-row gap-4">
                <button type="submit" class="flex-grow bg-slate-900 hover:bg-indigo-600 text-white font-black py-6 rounded-[2rem] uppercase tracking-[0.2em] text-[11px] transition-all shadow-2xl shadow-slate-200 flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('guru.proyek.dashboard') }}" class="px-12 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-400 font-black py-6 rounded-[2rem] uppercase tracking-[0.2em] text-[11px] transition-all text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection