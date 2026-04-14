@extends('siswa.layouts.app')

@section('title', 'Ajukan Proposal - CodeLab')

@section('content')
<div class="max-w-6xl mx-auto" x-data="proposalLogic()">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('siswa.workspace.index') }}" class="group flex items-center gap-3 text-slate-400 hover:text-blue-600 transition-colors w-fit">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:bg-blue-50 transition-all">
                <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Kembali ke Workspace</span>
        </a>
    </div>
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Mulai Proyek <span class="text-blue-600">Baru.</span></h1>
            <p class="text-slate-500 mt-2 font-medium text-lg">Tuangkan ide kreatifmu dan bangun tim impianmu.</p>
        </div>
        
        <div class="flex bg-slate-200/50 p-1.5 rounded-[2rem] shadow-inner w-fit">
            <button type="button" @click="mode = 'mandiri'" 
                :class="mode === 'mandiri' ? 'bg-white shadow-md text-blue-600' : 'text-slate-500'"
                class="px-8 py-3 rounded-[1.8rem] text-sm font-black uppercase tracking-widest transition-all duration-300">
                Mandiri
            </button>
            <button type="button" @click="mode = 'kelompok'" 
                :class="mode === 'kelompok' ? 'bg-white shadow-md text-indigo-600' : 'text-slate-500'"
                class="px-8 py-3 rounded-[1.8rem] text-sm font-black uppercase tracking-widest transition-all duration-300">
                Kelompok
            </button>
        </div>
    </div>

    <form action="{{ route('siswa.proposal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        {{-- Hidden input untuk mode agar terkirim ke controller --}}
        <input type="hidden" name="mode" :value="mode">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 md:p-10 rounded-[3rem] shadow-sm border border-slate-100 space-y-8">
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-blue-600 mb-3 ml-1">Nama Mahakarya</label>
                        <input type="text" name="judul" required placeholder="Apa nama proyek hebatmu?" 
                            class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl text-slate-900 font-bold text-lg focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-blue-600 mb-3 ml-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" required placeholder="Ceritakan sedikit tentang proyek ini..." 
                            class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl text-slate-900 font-medium focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-[2.5rem] border border-blue-100/50">
                        <div class="col-span-2 mb-1">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 ml-1 flex items-center gap-2 text-blue-600">
                                <i class="fas fa-calendar-day"></i> Periode Pelaksanaan Proyek
                            </label>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Tanggal Mulai</p>
                            <input type="date" name="tanggal_mulai" required 
                                class="w-full px-6 py-4 bg-white border-none rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">Target Selesai</p>
                            <input type="date" name="tanggal_selesai" required 
                                class="w-full px-6 py-4 bg-white border-none rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-blue-600 mb-3 ml-1">Pilih Mentor</label>
                        <select name="guru_id" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl text-slate-900 font-bold focus:ring-2 focus:ring-blue-500 transition appearance-none">
                            <option value="">Pilih Guru Mentor...</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 sticky top-24">
                    <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-blue-600 mb-6 text-center">Thumbnail Proyek</label>
                    <div class="relative group aspect-square rounded-[2.5rem] overflow-hidden bg-slate-50 border-2 border-dashed border-slate-200 hover:border-blue-400 transition-all cursor-pointer shadow-inner">
                        <template x-if="!imagePreview">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-300 p-6 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-blue-500 mb-4"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Unggah cover</span>
                            </div>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                        </template>
                        <input type="file" name="thumbnail" class="absolute inset-0 opacity-0 cursor-pointer" @change="previewImage">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Tim (Muncul jika mode Kelompok) --}}
        <div x-show="mode === 'kelompok'" x-transition class="bg-slate-900 rounded-[3.5rem] p-8 md:p-12 shadow-2xl shadow-blue-900/20">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-2xl font-black text-white">Konfigurasi Kelompok</h2>
                <button type="button" @click="addMember" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[11px] font-black uppercase tracking-widest transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Rekan
                </button>
            </div>

            <div class="space-y-4">
                {{-- Anggota Pertama: Pengaju Proyek (Otomatis) --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 p-6 bg-blue-600/10 rounded-3xl border border-blue-500/30 items-center">
                    <div class="md:col-span-5 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-black uppercase">Me</div>
                        <div>
                            <p class="text-white font-bold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-blue-400 text-[9px] font-black uppercase">Pemilik Ide</p>
                            <input type="hidden" name="team[0][user_id]" value="{{ auth()->id() }}">
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <input type="text" 
                            name="team[0][role]" 
                            :required="mode === 'kelompok'" 
                            :value="mode === 'mandiri' ? 'Solo Developer' : ''"
                            placeholder="Peran Anda (mis: Lead)" 
                            class="w-full px-5 py-3 bg-slate-800 border-none rounded-xl text-blue-400 text-sm font-bold">
                    </div>
                    <div class="md:col-span-2 flex justify-center">
                        <label class="cursor-pointer">
                            <input type="radio" name="leader_selection" value="0" checked class="hidden peer">
                            <div class="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-500 peer-checked:bg-amber-500 peer-checked:text-white transition-all uppercase">
                                Leader
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Rekan Tim Tambahan --}}
                <template x-for="(member, index) in members" :key="member.id">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 p-6 bg-white/5 rounded-3xl border border-white/10 items-center animate-fadeIn">
                        
                        {{-- Cari Nama dengan Autocomplete --}}
                        <div class="md:col-span-5">
                            <div class="relative custom-ts-container">
                                <select :id="'select-siswa-' + member.id" 
                                        :name="'team[' + member.id + '][user_id]'" 
                                        placeholder="cari teman....." 
                                        class="user-autocomplete" required>

                                    @foreach($siswas as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Role Tetap Input Teks --}}
                        <div class="md:col-span-4">
                            <input type="text" :name="'team[' + member.id + '][role]'" required placeholder="Peran" 
                                class="w-full px-5 py-3 bg-slate-800 border-none rounded-xl text-blue-400 text-sm font-bold uppercase tracking-tighter placeholder:text-slate-600">
                        </div>

                        {{-- Leader --}}
                        <div class="md:col-span-2 flex justify-center">
                            <label class="cursor-pointer">
                                <input type="radio" name="leader_selection" :value="member.id" class="hidden peer">
                                <div class="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-500 peer-checked:bg-amber-500 peer-checked:text-white transition-all uppercase">
                                    Leader
                                </div>
                            </label>
                        </div>

                        <div class="md:col-span-1 text-center">
                            <button type="button" @click="removeMember(index)" class="text-slate-600 hover:text-red-500 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6 pt-6">
            <button type="submit" class="group px-12 py-5 bg-blue-600 text-white rounded-[2rem] text-sm font-black uppercase tracking-widest shadow-2xl shadow-blue-600/40 hover:bg-blue-700 transition-all duration-300">
                Kirim Proposal <i class="fas fa-paper-plane ml-3 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</div>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    function proposalLogic() {
        return {
            mode: 'mandiri',
            imagePreview: null,
            members: [],

            addMember() {
                const uniqueId = Date.now();
                this.members.push({ id: uniqueId });

                // Inisialisasi pencarian setelah elemen muncul di DOM
                this.$nextTick(() => {
                    new TomSelect(`#select-siswa-${uniqueId}`, {
                        create: false,
                        sortField: { field: "text", order: "asc" },
                        allowEmptyOption: true,
                        // Styling kustom agar menyatu dengan tema gelap
                        render: {
                            option: function(data, escape) {
                                return `<div class="py-2 px-3 text-sm font-medium">${escape(data.text)}</div>`;
                            },
                            item: function(data, escape) {
                                return `<div class="text-white">${escape(data.text)}</div>`;
                            }
                        }
                    });
                });
            },

            removeMember(index) {
                this.members.splice(index, 1);
            },

            previewImage(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => { this.imagePreview = event.target.result; };
                    reader.readAsDataURL(file);
                }
            }
        }
    }
</script>
<style>
    /* Menyesuaikan TomSelect dengan tema gelap Codelab */
    .ts-control {
        background-color: rgba(30, 41, 59, 0.5) !important; /* bg-slate-800/50 */
        border: none !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1.25rem !important;
        color: white !important;
    }
    .ts-control input {
        color: white !important;
    }
    .ts-dropdown {
        background: #0f172a !important; /* bg-slate-900 */
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 1rem !important;
        margin-top: 5px !important;
        color: white !important;
    }
    .ts-dropdown .active {
        background-color: #2563eb !important; /* bg-blue-600 */
        color: white !important;
    }
    .ts-dropdown .option:hover {
        background-color: rgba(37, 99, 235, 0.2) !important;
    }
</style>
@endsection