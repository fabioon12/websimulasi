@extends('guru.layouts.app')

@section('title', 'Buat Sub Materi - CodeLab Guru')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-8">
    {{-- Tombol Kembali --}}
    <div class="flex items-center justify-between px-4">
        <a href="{{ route('guru.materi.dashboard') }}" class="group flex items-center gap-3 text-slate-500 hover:text-indigo-600 transition">
            <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:shadow-lg transition">
                <i class="fas fa-arrow-left text-sm"></i>
            </div>
            <span class="text-xs font-black uppercase tracking-widest">Kembali</span>
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
    <div class="bg-emerald-500 text-white p-4 rounded-2xl font-bold text-sm mx-4 animate-bounce text-center">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl shadow-indigo-100/50 overflow-hidden">
        {{-- Header Card --}}
        <div class="bg-indigo-700 p-8 md:p-10 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-2xl md:text-3xl font-black">Buat Materi Baru 📚</h1>
                <p class="text-indigo-100 text-sm mt-2 opacity-80">Masukkan informasi dasar dan cover materi untuk menarik minat siswa.</p>
            </div>
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
        </div>

        {{-- Form Start --}}
        <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-8" id="formMateri">
            @csrf
            
            {{-- Input Hidden untuk Status --}}
            <input type="hidden" name="status" id="status_input" value="published">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                {{-- Kolom Kiri: Thumbnail --}}
                <div class="lg:col-span-5 space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Thumbnail Materi</label>
                    <div class="relative group">
                        <div class="w-full h-64 rounded-[2.5rem] border-2 border-dashed {{ $errors->has('thumbnail') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50' }} flex flex-col items-center justify-center group-hover:border-indigo-400 transition-all overflow-hidden relative">
                            <i class="fas fa-cloud-upload-alt text-4xl {{ $errors->has('thumbnail') ? 'text-rose-400' : 'text-slate-300' }} mb-3 group-hover:text-indigo-400 transition"></i>
                            <p class="text-[10px] font-bold text-slate-400 uppercase px-6 text-center">Klik untuk upload gambar</p>
                            
                            <input type="file" name="thumbnail" onchange="previewImage(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20" required>
                            
                            {{-- Preview Image Area --}}
                            <img id="img-preview" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                        </div>
                    </div>
                    @error('thumbnail')
                        <p class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kolom Kanan: Detail --}}
                <div class="lg:col-span-7 space-y-6">
                    {{-- Judul --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Judul Materi</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Belajar Laravel Dasar" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 {{ $errors->has('judul') ? 'focus:ring-rose-500 ring-2 ring-rose-200' : 'focus:ring-indigo-500' }} font-bold text-slate-700 transition" required>
                        @error('judul')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Pilih Kategori</label>
                        <div class="relative">
                            <select name="kategori" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 transition appearance-none cursor-pointer" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Web Development" {{ old('kategori') == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                <option value="Mobile App" {{ old('kategori') == 'Mobile App' ? 'selected' : '' }}>Mobile App</option>
                                <option value="UI/UX Design" {{ old('kategori') == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                        </div>
                        @error('kategori')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Deskripsi Materi</label>
                        <textarea name="deskripsi" rows="4" placeholder="Jelaskan secara singkat isi materi ini..." 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 transition" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-50">
                {{-- Tombol Terbitkan --}}
                <button type="submit" onclick="setStatus('published')" 
                    class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-check-circle mr-2"></i> Terbitkan Materi
                </button>
                
                {{-- Tombol Simpan Draft --}}
                <button type="submit" onclick="setStatus('draft')" 
                    class="px-8 py-4 bg-slate-100 text-slate-500 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                    <i class="fas fa-file-alt mr-2"></i> Simpan Draft
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script>
    // Fungsi untuk mengubah nilai status sebelum submit
    function setStatus(status) {
        document.getElementById('status_input').value = status;
    }

    // Fungsi Preview Gambar
    function previewImage(input) {
        const preview = document.getElementById('img-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection