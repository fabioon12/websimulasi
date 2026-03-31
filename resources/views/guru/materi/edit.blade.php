@extends('guru.layouts.app')

@section('title', 'Edit Materi - CodeLab Guru')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-8">
    <div class="flex items-center justify-between px-4">
        <a href="{{ route('guru.materi.dashboard') }}" class="group flex items-center gap-3 text-slate-500 hover:text-indigo-600 transition">
            <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:shadow-lg transition">
                <i class="fas fa-arrow-left text-sm"></i>
            </div>
            <span class="text-xs font-black uppercase tracking-widest">Batal Edit</span>
        </a>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl shadow-indigo-100/50 overflow-hidden">
        <div class="bg-amber-500 p-8 md:p-10 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-2xl md:text-3xl font-black">Edit Materi ✏️</h1>
                <p class="text-amber-100 text-sm mt-2 opacity-80">Perbarui informasi materi "{{ $materi->judul }}"</p>
            </div>
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
        </div>

        <form action="{{ route('guru.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-8">
            @csrf
            @method('PUT')

            {{-- Input Hidden untuk Status agar bisa diubah via tombol --}}
            <input type="hidden" name="status" id="status_input" value="{{ $materi->status }}">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                {{-- Thumbnail Section --}}
                <div class="lg:col-span-5 space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Thumbnail Saat Ini</label>
                    <div class="relative group">
                        <div class="w-full h-64 rounded-[2.5rem] border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden relative">
                            <img id="img-preview" src="{{ asset('storage/' . $materi->thumbnail) }}" class="absolute inset-0 w-full h-full object-cover z-10">
                            <input type="file" name="thumbnail" onchange="previewImage(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                        </div>
                    </div>
                    <p class="text-[9px] text-center text-slate-400 font-bold uppercase">Klik gambar untuk mengganti</p>
                    @error('thumbnail')
                        <p class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fields Section --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Judul Materi</label>
                        <input type="text" name="judul" value="{{ old('judul', $materi->judul) }}" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-slate-700 transition">
                        @error('judul')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Kategori</label>
                        <select name="kategori" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-slate-700 transition">
                            <option value="Web Development" {{ old('kategori', $materi->kategori) == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                            <option value="Mobile App" {{ old('kategori', $materi->kategori) == 'Mobile App' ? 'selected' : '' }}>Mobile App</option>
                            <option value="UI/UX Design" {{ old('kategori', $materi->kategori) == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-medium text-slate-700 transition">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-slate-50">
                {{-- Tombol Simpan (Published) --}}
                <button type="submit" onclick="setStatus('published')" class="flex-1 py-4 bg-amber-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-amber-100 hover:bg-amber-600 hover:-translate-y-1 transition-all">
                    <i class="fas fa-check-circle mr-2"></i> Update & Terbitkan
                </button>
                
                {{-- Tombol Simpan ke Draft --}}
                <button type="submit" onclick="setStatus('draft')" class="px-8 py-4 bg-slate-100 text-slate-500 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                    <i class="fas fa-file-alt mr-2"></i> Jadikan Draft
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function setStatus(status) {
        document.getElementById('status_input').value = status;
    }

    function previewImage(input) {
        const preview = document.getElementById('img-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection