@extends('guru.layouts.app')

@section('title', 'Langkah 1: Setup Proyek - CodeLab')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20">
    
    {{-- Header Navigasi --}}
    <div class="flex items-center justify-between px-4 pt-4">
        <div class="flex items-center gap-5">
            <a href="{{ route('guru.proyek.dashboard') }}" 
               class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-100 hover:bg-rose-50 transition-all shadow-sm group"
               title="Kembali ke Dashboard">
                <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition"></i>
            </a>

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-xl">
                    <i class="fas fa-rocket text-sm"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 uppercase italic leading-none">Setup Proyek</h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 text-blue-600">Langkah 1: Identitas & Struktur</p>
                </div>
            </div>
        </div>

        <div class="hidden md:flex gap-2">
            <div class="w-16 h-2 rounded-full bg-slate-900"></div>
            <div class="w-16 h-2 rounded-full bg-slate-100"></div>
        </div>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-100 text-rose-600 px-6 py-4 rounded-3xl text-xs font-bold uppercase italic tracking-widest animate-bounce">
            <ul>
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.proyek.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        {{-- Card 1: Informasi Utama --}}
        <div class="bg-white border border-slate-100 rounded-[3rem] p-8 md:p-10 shadow-sm space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                {{-- Upload Cover --}}
                <div class="md:col-span-4 space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 flex items-center gap-2">
                        <i class="fas fa-image text-blue-500"></i> Banner Proyek
                    </label>
                    <div class="relative group h-52 rounded-[2.5rem] bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center overflow-hidden hover:border-blue-400 transition-all cursor-pointer">
                        <div id="placeholder" class="text-center p-4">
                            <i class="fas fa-camera-retro text-2xl text-slate-300 mb-2 group-hover:rotate-12 transition"></i>
                            <p class="text-[9px] font-bold text-slate-400 uppercase leading-tight">Klik untuk<br>Upload Cover</p>
                        </div>
                        <img id="preview" src="#" class="hidden absolute inset-0 w-full h-full object-cover">
                        <input type="file" name="cover" onchange="previewImage(this)" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>

                {{-- Detail Proyek --}}
                <div class="md:col-span-8 space-y-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Nama Proyek / Aplikasi</label>
                        <input type="text" name="nama_proyek" value="{{ old('nama_proyek') }}" required placeholder="Contoh: Aplikasi POS Kasir" 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-inner transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 text-blue-500">Target Kelas</label>
                            <select name="kelas" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm outline-none shadow-inner cursor-pointer">
                                <option value="Kelas X" {{ old('kelas') == 'Kelas X' ? 'selected' : '' }}>Kelas X</option>
                                <option value="Kelas XI" {{ old('kelas') == 'Kelas XI' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="Kelas XII" {{ old('kelas') == 'Kelas XII' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 text-rose-500">Deadline Final Proyek</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}" required class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-sm outline-none shadow-inner text-slate-600">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Deskripsi Singkat (Garis Besar Proyek)</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan apa yang harus dicapai siswa dalam proyek ini..." 
                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-medium outline-none shadow-inner leading-relaxed transition-all focus:bg-white focus:ring-2 focus:ring-blue-100">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        {{-- Card 2: Tingkat Kesulitan (NEW) --}}
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

        {{-- Card 3: Struktur & Role --}}
        <div class="bg-white border border-slate-100 rounded-[3rem] p-8 md:p-10 shadow-sm space-y-6">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                <h3 class="font-black text-slate-900 uppercase italic text-sm tracking-widest">Struktur Pengerjaan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="relative cursor-pointer">
                    <input type="radio" name="mode" value="individu" class="peer hidden" onchange="switchMode('individu')" {{ old('mode') == 'individu' ? 'checked' : '' }}>
                    <div class="p-6 border-2 border-slate-50 rounded-[2rem] bg-slate-50/50 transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 flex items-center gap-4 hover:shadow-md">
                        <div class="w-12 h-12 rounded-xl bg-white text-amber-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-user text-xl"></i>
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-xs uppercase italic">Mandiri (Individu)</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">Siswa bekerja personal</p>
                        </div>
                    </div>
                </label>

                <label class="relative cursor-pointer">
                    <input type="radio" name="mode" value="kelompok" class="peer hidden" onchange="switchMode('kelompok')" {{ old('mode', 'kelompok') == 'kelompok' ? 'checked' : '' }}>
                    <div class="p-6 border-2 border-slate-50 rounded-[2rem] bg-slate-50/50 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 flex items-center gap-4 hover:shadow-md">
                        <div class="w-12 h-12 rounded-xl bg-white text-emerald-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-xs uppercase italic">Kolaborasi (Kelompok)</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">Dikerjakan dalam tim</p>
                        </div>
                    </div>
                </label>
            </div>

            <div id="role-section" class="pt-6 border-t border-slate-50 space-y-6 transition-all duration-500">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic flex items-center gap-2">
                        <i class="fas fa-stream text-blue-500"></i> Pilih Peran yang Terlibat:
                    </p>
                    <button type="button" onclick="addCustomRole()" class="text-[9px] font-black text-blue-600 bg-blue-50 px-4 py-2 rounded-xl border border-blue-100 hover:bg-blue-600 hover:text-white transition-all">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Role Custom
                    </button>
                </div>

                <div id="role-container" class="grid grid-cols-2 md:grid-cols-3 gap-5">
                    @foreach(['UI/UX Designer', 'Frontend Dev', 'Backend Dev'] as $r)
                    <label class="relative group cursor-pointer role-card">
                        <input type="checkbox" name="roles[]" value="{{ $r }}" class="peer hidden" {{ (is_array(old('roles')) && in_array($r, old('roles'))) ? 'checked' : '' }}>
                        <div class="p-5 border-2 border-slate-50 rounded-[2.5rem] bg-slate-50/50 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 text-center flex flex-col items-center">
                            <div class="w-10 h-10 mb-3 rounded-xl bg-white flex items-center justify-center text-slate-400 peer-checked:text-blue-500 transition shadow-sm">
                                <i class="fas fa-user-tag text-xs"></i>
                            </div>
                            <p class="font-black text-slate-900 text-[10px] uppercase italic tracking-tighter">{{ $r }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="pt-4 flex items-center gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 italic">Maks. Siswa per Kelompok</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="max_siswa" id="max_siswa_input" value="{{ old('max_siswa', 4) }}" min="1" class="w-20 px-4 py-4 bg-slate-900 text-white rounded-2xl font-black text-sm text-center shadow-lg">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Siswa per tim</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Form --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 px-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-lightbulb text-amber-400"></i>
                <p class="text-[10px] font-bold text-slate-400 italic leading-relaxed">
                    Pastikan data identitas sudah benar. Langkah berikutnya <br class="hidden md:block"> adalah menyusun roadmap tugas spesifik tiap role.
                </p>
            </div>
            <button type="submit" class="w-full md:w-auto bg-slate-900 hover:bg-indigo-600 text-white px-16 py-6 rounded-[2.5rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 transition-all flex items-center justify-center gap-4 group">
                Lanjut Atur Roadmap <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
            </button>
        </div>
    </form>
</div>

{{-- Scripts tetap sama --}}
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                document.getElementById('placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function switchMode(mode) {
        const section = document.getElementById('role-section');
        const maxInput = document.getElementById('max_siswa_input');
        if (mode === 'individu') {
            section.classList.add('opacity-30', 'pointer-events-none', 'grayscale');
            maxInput.value = 1;
        } else {
            section.classList.remove('opacity-30', 'pointer-events-none', 'grayscale');
            maxInput.value = 4;
        }
    }

    window.onload = function() {
        const modeInput = document.querySelector('input[name="mode"]:checked');
        if(modeInput) switchMode(modeInput.value);
    }

    function addCustomRole() {
        const name = prompt("Masukkan Nama Role Baru:");
        if (name) {
            const container = document.getElementById('role-container');
            const html = `
                <label class="relative group cursor-pointer role-card animate-scale-up">
                    <input type="checkbox" name="roles[]" value="${name}" class="peer hidden" checked>
                    <div class="p-5 border-2 border-indigo-500 bg-indigo-50 rounded-[2.5rem] text-center flex flex-col items-center shadow-lg shadow-indigo-100">
                        <div class="w-10 h-10 mb-3 rounded-xl bg-white flex items-center justify-center text-indigo-500 shadow-sm"><i class="fas fa-user-plus text-xs"></i></div>
                        <p class="font-black text-slate-900 text-[10px] uppercase italic tracking-tighter">${name}</p>
                    </div>
                </label>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }
    }
</script>

<style>
    @keyframes scaleUp {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .animate-scale-up { animation: scaleUp 0.3s ease-out forwards; }
</style>
@endsection