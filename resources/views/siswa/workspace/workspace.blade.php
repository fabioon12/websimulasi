@extends('siswa.layouts.app')

@section('title', 'Project Roadmap - ' . $project->judul)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10" x-data="{ showAddModal: false }">
    
    {{-- Header Section --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <a href="{{ route('siswa.workspace.show', $project->id) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 mb-4 hover:gap-4 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Workspace
            </a>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Project<span class="text-blue-600">Roadmap.</span></h1>
            <p class="text-slate-500 font-medium italic">"Setiap langkah besar dimulai dari target yang jelas."</p>
        </div>

        {{-- Tombol Tambah (Hanya untuk Ketua/Pengaju) --}}
        @if(auth()->id() == $project->pengaju_id)
            @if(now()->lessThanOrEqualTo($project->tanggal_selesai))
                {{-- Tampilan Tombol Aktif --}}
                <button @click="showAddModal = true" class="px-8 py-4 bg-blue-600 text-white rounded-[2rem] text-[11px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-2xl shadow-blue-200">
                    <i class="fas fa-flag mr-2"></i> Tambah Target Baru
                </button>
            @else
                {{-- Tampilan Indikator Terkunci/Review Mode --}}
                <div class="flex flex-col items-end gap-2">
                    <div class="px-6 py-3 bg-slate-100 border border-slate-200 rounded-[2rem] flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <i class="fas fa-lock mr-1"></i> Review Mode Only
                        </span>
                    </div>
                    <p class="text-[9px] font-bold text-rose-500 uppercase tracking-tighter italic">
                        Batas waktu pengerjaan proyek telah berakhir
                    </p>
                </div>
            @endif
        @endif
    </div>

    {{-- Roadmap Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($project->milestones as $milestone)
            <div class="bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm relative flex flex-col h-full group hover:shadow-xl transition-all duration-500">
                
                {{-- Status Header --}}
                <div class="flex items-center justify-between mb-6">
                    <span class="text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest
                        {{ $milestone->status_review === 'disetujui' ? 'bg-emerald-100 text-emerald-600' : 
                           ($milestone->status_review === 'revisi' ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-500') }}">
                        {{ $milestone->status_review }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $milestone->deadline->format('d M Y') }}
                    </span>
                </div>

                <h3 class="text-xl font-black text-slate-900 mb-4 leading-tight">{{ $milestone->nama_milestone }}</h3>

                {{-- Feedback Section --}}
                <div class="flex-grow">
                    @if($milestone->feedback_guru)
                        <div class="p-5 bg-slate-50 rounded-[2rem] border border-slate-100 relative">
                            <i class="fas fa-quote-left absolute top-4 right-4 text-slate-200"></i>
                            <p class="text-[9px] font-black text-blue-600 uppercase mb-2">Mentor Feedback:</p>
                            <p class="text-xs text-slate-600 italic leading-relaxed">"{{ $milestone->feedback_guru }}"</p>
                        </div>
                    @else
                        <div class="py-6 border-2 border-dashed border-slate-50 rounded-[2rem] flex items-center justify-center text-slate-300">
                            <p class="text-[10px] font-black uppercase tracking-widest">Belum ada feedback</p>
                        </div>
                    @endif
                </div>

                {{-- Action Footer --}}
                <div class="mt-8 pt-6 border-t border-slate-50 flex flex-col gap-3">
                    
                    {{-- CASE 1: JIKA AKSES MASIH TERKUNCI (Project Belum Disetujui ATAU Milestone Belum Disetujui) --}}
                    {{-- Kita anggap milestone baru boleh diisi jika status_review-nya sudah 'disetujui' oleh guru --}}
                    @if($project->status != 'disetujui' || $milestone->status_review != 'disetujui')
                        
                        <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] p-6 text-center">
                            
                            @if(!$milestone->is_completed)
                                {{-- TAMPILAN: BELUM MENGAJUKAN RENCANA --}}
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <i class="fas fa-paper-plane text-slate-300"></i>
                                </div>
                                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-1">Rencana Dalam Peninjauan</h4>
                                <p class="text-[10px] text-slate-500 font-medium mb-4">Silahkan ajukan persiapkan rencana milestone ini untuk mengisi logbook.</p>
                                
                                <form action="{{ route('siswa.milestone.complete', $milestone->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg">
                                        Tunggu Verifikasi Guru
                                    </button>
                                </form>
                            @else
                                {{-- TAMPILAN: SUDAH MENGAJUKAN, TAPI BELUM DI-ACC GURU --}}
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <i class="fas fa-hourglass-half text-amber-400 animate-pulse"></i>
                                </div>
                                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-2">Menunggu Verifikasi</h4>
                                
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-600 rounded-full border border-amber-100">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Rencana Sedang Ditinjau Guru</span>
                                </div>
                                <p class="text-[9px] text-slate-400 mt-3 italic">*Logbook dan tombol selesai akan muncul setelah disetujui.</p>
                            @endif
                        </div>

                    {{-- CASE 2: SEMUA SUDAH DI-ACC (Akses Terbuka) --}}
                    @else
                        <div class="flex flex-col md:flex-row gap-3 w-full">
                            
                            {{-- TOMBOL LOGBOOK --}}
                            {{-- Jika sudah diklik "Selesaikan Target" (is_completed true lagi), maka ganti jadi mode Lihat --}}
                            {{-- Kita butuh logika tambahan di controller untuk membedakan is_completed saat AJUKAN RENCANA dan AJUKAN HASIL --}}
                            <a href="{{ route('siswa.logbook.index', $project->id) }}" 
                            class="flex-1 py-4 bg-amber-50 text-amber-600 border border-amber-200 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-100 transition-all text-center shadow-sm">
                                <i class="fas fa-book mr-2"></i> Isi Logbook
                            </a>

                            {{-- TOMBOL SELESAIKAN TARGET --}}
                            <form action="{{ route('siswa.milestone.complete', $milestone->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" 
                                        onclick="return confirm('Selesaikan milestone ini? Seluruh logbook untuk target ini akan dikunci.')"
                                        class="w-full py-4 bg-emerald-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100">
                                    <i class="fas fa-flag-checkered mr-2"></i> Selesaikan Target
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- FOOTER STATUS: PROYEK FINISH --}}
                    @if($project->is_finished)
                        <div class="flex items-center justify-center gap-2 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase border border-slate-800 shadow-xl mt-2">
                            <i class="fas fa-trophy text-lg text-amber-400"></i>
                            Proyek Selesai & Diarsipkan
                        </div>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="col-span-full py-20 bg-slate-50 rounded-[4rem] border-4 border-dashed border-white text-center">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i class="fas fa-map-signs text-3xl text-slate-200"></i>
                </div>
                <p class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Belum ada roadmap yang dibuat</p>
                @if(auth()->id() == $project->pengaju_id)
                    <button @click="showAddModal = true" class="mt-4 text-blue-600 font-black text-[10px] uppercase tracking-widest">Buat Sekarang &rarr;</button>
                @endif
            </div>
        @endforelse
    </div>

    {{-- MODAL SECTION --}}
    <div 
        x-show="showAddModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Modal Content --}}
        <div 
            @click.away="showAddModal = false" 
            class="bg-white w-full max-w-lg rounded-[3.5rem] p-10 shadow-2xl relative"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        >
            <button @click="showAddModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <div class="mb-8">
                <h3 class="text-2xl font-black text-slate-900 mb-1">Tambah Target.</h3>
                <p class="text-xs font-medium text-slate-400 tracking-tight">Apa milestone besar tim kamu berikutnya?</p>
            </div>
            
            <form action="{{ route('siswa.milestone.store', $project->id) }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Milestone</label>
                    <input type="text" name="nama_milestone" required placeholder="Contoh: Perancangan Database" 
                           class="w-full px-8 py-5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition outline-none">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Target Selesai (Deadline)</label>
                    <input type="date" name="deadline" required 
                           class="w-full px-8 py-5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition outline-none">
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-200">
                        Buat Roadmap <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection