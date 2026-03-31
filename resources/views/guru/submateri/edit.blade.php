@extends('guru.layouts.app')

@section('title', 'Edit Sub Materi - CodeLab')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    {{-- Header --}}
    <div class="flex items-center gap-5 mb-10">
        <a href="{{ route('guru.submateri.dashboard', $materi_id) }}" class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900">Edit Sub Materi</h1>
            <p class="text-sm font-bold text-slate-400">Perbarui konten dan pengaturan materi ini.</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('guru.submateri.update', $subMateri->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm space-y-6">
            
            {{-- Baris 1: Judul, Kategori & Urutan --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                {{-- Judul --}}
                <div class="md:col-span-6 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Judul Materi</label>
                    <input type="text" name="judul" value="{{ old('judul', $subMateri->judul) }}" required
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Kategori (TAMBAHAN PERBAIKAN) --}}
                <div class="md:col-span-4 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $subMateri->kategori) }}" required
                           placeholder="Contoh: Frontend"
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Urutan --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $subMateri->urutan) }}" required
                           class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 text-center focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Bacaan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Penjelasan Teks</label>
                <textarea name="bacaan" rows="6" 
                          class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-medium text-slate-600 leading-relaxed focus:ring-2 focus:ring-blue-500">{{ old('bacaan', $subMateri->bacaan) }}</textarea>
            </div>

            {{-- Video & PDF --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Youtube Video ID</label>
                    <div class="relative">
                        <input type="text" name="video_url" value="{{ old('video_url', $subMateri->video_url) }}" 
                               placeholder="Contoh: nb7XJz24xV8"
                               class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Update File PDF (Opsional)</label>
                    <input type="file" name="pdf_file" accept="application/pdf"
                           class="w-full px-6 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-500 file:hidden cursor-pointer">
                    @if($subMateri->pdf_path)
                        <div class="flex items-center gap-2 ml-2 mt-1">
                            <i class="fas fa-check-circle text-emerald-500 text-[10px]"></i>
                            <p class="text-[9px] text-emerald-500 font-black uppercase tracking-tight">PDF saat ini sudah tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Coding Lab --}}
            <div class="space-y-4 pt-6 border-t border-slate-50">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-500 uppercase ml-2 tracking-widest">Instruksi Coding</label>
                    <textarea name="instruksi_coding" rows="3" 
                              placeholder="Masukkan instruksi tugas untuk siswa..."
                              class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 text-sm focus:ring-2 focus:ring-emerald-500">{{ old('instruksi_coding', $subMateri->instruksi_coding) }}</textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Starter Code</label>
                    <div class="rounded-3xl overflow-hidden border border-slate-100 shadow-inner">
                        <textarea id="editor-edit" name="starter_code">{{ old('starter_code', $subMateri->starter_code) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-blue-600 active:scale-[0.98] transition-all duration-300">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- CodeMirror Logic --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editor = CodeMirror.fromTextArea(document.getElementById("editor-edit"), {
            lineNumbers: true,
            theme: "dracula",
            mode: "xml",
            tabSize: 2,
            lineWrapping: true
        });
        editor.setSize("100%", 350);
    });
</script>

<style>
    .CodeMirror { 
        font-family: 'Fira Code', monospace; 
        font-size: 13px; 
        padding: 10px;
    }
</style>
@endsection