@extends('siswa.layouts.app')

@section('title', 'Preview Proyek - CodeLab')

@section('content')
{{-- Load CSS Trix --}}
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

<div class="max-w-[1200px] mx-auto pb-20 px-4 md:px-0">
    
    {{-- Breadcrumb & Back --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('siswa.katalog.index') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-900 shadow-sm border border-slate-100 transition">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
            Katalog Proyek <span class="mx-2 text-slate-200">/</span> <span class="text-blue-600">Preview Detail</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- SISI KIRI: KONTEN UTAMA --}}
        <div class="lg:col-span-8 space-y-12">

            {{-- Hero Info Card --}}
            <div class="bg-white rounded-[3.5rem] p-10 md:p-14 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="relative z-10 space-y-8">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        {{-- Thumbnail Cover --}}
                        <div class="relative group overflow-hidden rounded-[2.5rem] bg-slate-200 w-full md:w-56 aspect-[4/3] md:aspect-square flex-shrink-0 border border-slate-100">
                            @php
                                $thumbnailUrl = $proyek->cover 
                                    ? asset('storage/' . $proyek->cover) 
                                    : 'https://images.unsplash.com/photo-1557821552-17105176677c?q=80&w=800';
                            @endphp
                            <img src="{{ $thumbnailUrl }}" alt="{{ $proyek->nama_proyek }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>

                        {{-- Judul & Deskripsi --}}
                        <div class="flex-grow space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-4 py-1.5 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg uppercase tracking-widest shadow-sm">
                                    {{ $proyek->mode ?? 'Industrial' }}
                                </span>
                                <div class="flex items-center gap-2 text-amber-500 bg-amber-50 px-4 py-1.5 rounded-lg border border-amber-100">
                                    <i class="fas fa-star text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $totalPoin ?? 0 }} PTS</span>
                                </div>
                            </div>
                            <h1 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight tracking-tight">{{ $proyek->nama_proyek }}</h1>
                            <p class="text-slate-500 font-medium leading-relaxed text-base italic">
                                "{{ $proyek->deskripsi }}"
                            </p>
                        </div>
                    </div>

                    {{-- Stats Bar --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 border-t border-slate-50 pt-8 mt-4">
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest italic">Deadline Utama</p>
                            <p class="text-sm font-bold text-slate-700 uppercase">{{ \Carbon\Carbon::parse($proyek->deadline)->format('d F Y') }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest italic">Tingkat Kesulitan</p>
                            <p class="text-sm font-bold text-slate-700 uppercase">{{ $proyek->kesulitan }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest italic">Kapasitas Tim</p>
                            <p class="text-sm font-bold text-slate-700 uppercase">{{ $proyek->max_siswa }} Siswa</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
            </div>

            {{-- ROADMAP PER ROLE --}}
            <div class="space-y-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Eksplorasi Peran & Tugas 🚀</h3>
                    
                    @php
                        // Ambil daftar ID role yang sudah terisi untuk proyek ini dari tabel pivot
                        $roleTerisi = \DB::table('proyek_siswa')
                            ->where('proyek_id', $proyek->id)
                            ->pluck('proyek_role_id')
                            ->toArray();

                        $firstVisibleRole = null;
                    @endphp

                    {{-- Role Switcher Tab --}}
                    <div class="flex bg-slate-100 p-1.5 rounded-2xl overflow-x-auto no-scrollbar">
                        @foreach($proyek->roles as $role)
                            {{-- Hanya tampilkan tab jika ID role TIDAK ada dalam daftar role yang sudah terisi --}}
                            @if(!in_array($role->id, $roleTerisi)) 
                                @if(!$firstVisibleRole) @php $firstVisibleRole = $role->id; @endphp @endif
                                <button onclick="switchRole('role-{{ $role->id }}')" 
                                        id="btn-role-{{ $role->id }}" 
                                        class="role-btn whitespace-nowrap px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $role->id == $firstVisibleRole ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                                    {{ $role->nama_role }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="relative">
                    @php $adaRoleTersedia = false; @endphp

                    @foreach($proyek->roles as $role)
                        @if(!in_array($role->id, $roleTerisi))
                            @php $adaRoleTersedia = true; @endphp
                            <div id="content-role-{{ $role->id }}" class="role-content space-y-4 {{ $role->id == $firstVisibleRole ? '' : 'hidden' }}">
                                
                                {{-- Info Box & Join Button --}}
                                <div class="bg-blue-600 rounded-[2.5rem] p-8 mb-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-blue-200">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-70 mb-1">Anda Sedang Melihat Role</p>
                                        <h4 class="text-2xl font-black uppercase italic">{{ $role->nama_role }}</h4>
                                    </div>

                                    <form action="{{ route('siswa.proyek.join') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="proyek_id" value="{{ $proyek->id }}">
                                        <input type="hidden" name="proyek_role_id" value="{{ $role->id }}">
                                        <button type="submit" class="px-8 py-4 bg-white text-blue-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition-all active:scale-95 shadow-lg">
                                            Join Sebagai {{ $role->nama_role }} <i class="fas fa-arrow-right ml-2"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- List Tugas --}}
                                @forelse($role->roadmaps->sortBy('urutan') as $task)
                                    <div class="group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                        <div class="flex items-start gap-6">
                                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex flex-shrink-0 flex-col items-center justify-center border border-slate-100 group-hover:bg-blue-600 transition-colors">
                                                <span class="text-[10px] font-black text-slate-400 group-hover:text-blue-200 uppercase">Step</span>
                                                <span class="text-lg font-black text-slate-900 group-hover:text-white">{{ $task->urutan }}</span>
                                            </div>

                                            <div class="flex-grow min-w-0">
                                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-4">
                                                    <h4 class="font-black text-slate-900 group-hover:text-blue-600 transition uppercase tracking-tight">{{ $task->judul_tugas }}</h4>
                                                    <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-3 py-1 rounded-lg border border-blue-100 uppercase">
                                                        {{ $task->poin }} PTS
                                                    </span>
                                                </div>
                                                <div class="instruction-content text-xs text-slate-500 font-medium leading-relaxed prose prose-slate max-w-none">
                                                    {!! $task->instruksi !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[3rem] p-12 text-center">
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Tugas Belum Tersedia.</p>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    @endforeach

                    {{-- Tampilan jika semua role sudah diambil --}}
                    @if(!$adaRoleTersedia)
                        <div class="bg-rose-50 border-2 border-dashed border-rose-100 rounded-[3rem] p-12 text-center">
                            <div class="w-16 h-16 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users-slash text-xl"></i>
                            </div>
                            <h4 class="text-sm font-black text-rose-900 uppercase tracking-tight mb-1">Pendaftaran Ditutup</h4>
                            <p class="text-[11px] font-bold text-rose-500 uppercase tracking-widest italic">Maaf, semua posisi peran dalam proyek ini sudah terisi penuh.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SISI KANAN: SIDE INFO --}}
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-slate-900 rounded-[3.5rem] p-10 text-white shadow-2xl sticky top-8">
                <h3 class="text-lg font-black mb-6 tracking-tight italic uppercase">Informasi Penting</h3>
                
                <div class="space-y-6 mb-10">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center text-amber-400 flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                        </div>
                        <p class="text-[11px] font-medium text-slate-400 leading-relaxed italic">
                            Anda hanya bisa memilih **SATU ROLE** per proyek. Pilihlah role yang paling sesuai dengan keahlian Anda.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
                    <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-black text-xs uppercase">
                        {{ substr($proyek->guru->name ?? 'M', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Pembimbing</p>
                        <p class="text-xs font-bold text-white">{{ $proyek->guru->name ?? 'Mentor' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .instruction-content { word-break: break-word; color: #64748b; }
    .instruction-content img { max-width: 100% !important; height: auto !important; display: block; margin: 1.5rem 0; border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    .instruction-content ul { list-style-type: disc !important; padding-left: 1.5rem !important; margin: 1rem 0 !important; }
    .instruction-content ol { list-style-type: decimal !important; padding-left: 1.5rem !important; margin: 1rem 0 !important; }
    .instruction-content b, .instruction-content strong { font-weight: 800; color: #1e293b; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    function switchRole(roleId) {
        document.querySelectorAll('.role-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('content-' + roleId).classList.remove('hidden');
        document.querySelectorAll('.role-btn').forEach(b => {
            b.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
            b.classList.add('text-slate-400');
        });
        const btn = document.getElementById('btn-' + roleId);
        btn.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
        btn.classList.remove('text-slate-400');
    }
</script>
@endsection