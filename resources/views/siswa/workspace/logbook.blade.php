@extends('siswa.layouts.app')

@section('title', 'Project Timeline - ' . $project->judul)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10" x-data="{ showModal: false }">
    
    {{-- Breadcrumb & Header --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <a href="{{ route('siswa.workspace.show', $project->id) }}" class="group inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 mb-4 transition-all">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Workspace
            </a>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Work<span class="text-blue-600">Log.</span></h1>
            <p class="text-slate-500 font-medium italic">Riwayat aktivitas proyek: {{ $project->judul }}</p>
        </div>

        <button @click="showModal = true" class="px-8 py-4 bg-slate-900 text-white rounded-[2rem] text-[11px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-2xl shadow-slate-200">
            <i class="fas fa-plus mr-2"></i> Tambah Logbook
        </button>
    </div>

    {{-- Logbook Timeline --}}
    <div class="relative">
        {{-- Garis Tengah Timeline --}}
        <div class="absolute left-0 md:left-1/2 top-0 bottom-0 w-1 bg-slate-100 -translate-x-1/2 hidden md:block"></div>

        <div class="space-y-12">
            @forelse($project->logbooks as $index => $log)
                <div class="relative flex flex-col md:flex-row items-center gap-8 {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }}">
                    
                    {{-- Dot Timeline --}}
                    <div class="absolute left-0 md:left-1/2 w-4 h-4 bg-white border-4 border-blue-600 rounded-full -translate-x-1/2 z-10 hidden md:block"></div>

                    {{-- Card Konten --}}
                    <div class="w-full md:w-[45%]">
                        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
                            
                            {{-- Header Card --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[9px] font-black px-3 py-1 bg-blue-50 text-blue-600 rounded-full uppercase tracking-widest">
                                    {{ \Carbon\Carbon::parse($log->tanggal_kerjakan)->format('d M Y') }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400">{{ $log->created_at->format('H:i') }}</span>
                            </div>

                            {{-- Badge Milestone --}}
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-full mb-3">
                                <i class="fas fa-bullseye text-[10px] text-slate-400"></i>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-tighter">
                                    Target: {{ $log->milestone->nama_milestone ?? 'General' }}
                                </span>
                            </div>
                            
                            {{-- Judul & Deskripsi --}}
                            <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $log->judul }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed mb-6 italic">"{{ $log->deskripsi }}"</p>
                            @if($log->feedback_guru)
                                <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-2xl">
                                    <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Pesan dari Guru:</p>
                                    <p class="text-xs text-slate-700 mt-1 font-semibold">{{ $log->feedback_guru }}</p>
                                </div>
                            @endif
                            @if($log->lampiran)
                                <div class="mt-4 pt-4 border-t border-slate-50">
                                    @php $ext = pathinfo($log->lampiran, PATHINFO_EXTENSION); @endphp
                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                        <a href="{{ asset('storage/' . $log->lampiran) }}" target="_blank" class="block overflow-hidden rounded-2xl">
                                            <img src="{{ asset('storage/' . $log->lampiran) }}" 
                                                 class="w-full h-40 object-cover hover:scale-110 transition-transform duration-500">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $log->lampiran) }}" target="_blank" 
                                           class="flex items-center gap-3 p-4 bg-rose-50 text-rose-600 rounded-2xl hover:bg-rose-100 transition">
                                            <i class="fas fa-file-pdf text-xl"></i>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Lihat Dokumen PDF</span>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            {{-- Footer Kontributor --}}
                            <div class="flex items-center gap-3 pt-6 mt-4 border-t border-slate-50">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&background=random" class="w-8 h-8 rounded-xl shadow-sm">
                                <div>
                                    <p class="text-[10px] font-black text-slate-900 leading-none">{{ $log->user->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Kontributor</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Spacer --}}
                    <div class="hidden md:block md:w-[45%]"></div>
                </div>
            @empty
                <div class="py-20 text-center bg-slate-50 rounded-[4rem] border-4 border-dashed border-white">
                    <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-terminal text-slate-200 text-2xl"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Belum ada aktivitas terekam</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal Pop Up --}}
    <div 
        x-show="showModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div @click.away="showModal = false" 
             class="bg-white w-full max-w-2xl rounded-[3.5rem] p-8 md:p-12 shadow-2xl relative overflow-y-auto max-h-[90vh]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <button @click="showModal = false" class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition-colors z-10">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <div class="relative mb-10">
                <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-4">
                    New Entry
                </span>
                <h3 class="text-3xl font-black text-slate-900 leading-tight">Catat Progress<br>Hari Ini.</h3>
            </div>
            
            <form action="{{ route('siswa.logbook.store', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative">
                @csrf
                
                {{-- Milestone Selection --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Milestone Terkait</label>
                    <select name="milestone_id" required class="w-full px-8 py-5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all outline-none appearance-none">
                        <option value="" disabled selected>-- Pilih Milestone yang sedang dikerjakan --</option>
                        @foreach($project->milestones->where('status_review', 'disetujui') as $ms)
                            <option value="{{ $ms->id }}">{{ $ms->nama_milestone }}</option>
                        @endforeach
                    </select>
                    @if($project->milestones->where('status_review', 'disetujui')->count() == 0)
                        <p class="mt-2 ml-4 text-[9px] text-rose-500 font-bold uppercase">
                            <i class="fas fa-exclamation-triangle"></i> Guru belum menyetujui milestone apa pun.
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Apa yang dikerjakan?</label>
                        <input type="text" name="judul" required placeholder="Slicing UI Dashboard" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal Pengerjaan</label>
                        <input type="date" name="tanggal_kerjakan" required value="{{ date('Y-m-d') }}"
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Detail Aktivitas</label>
                    <textarea name="deskripsi" rows="4" required placeholder="Jelaskan proses pengerjaan..." 
                              class="w-full px-8 py-5 bg-slate-50 border-none focus:ring-2 focus:ring-blue-600 rounded-[2rem] text-sm font-bold transition-all outline-none resize-none"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Lampiran (Foto/PDF)</label>
                    <div class="relative group">
                        <input type="file" name="lampiran" accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full px-8 py-4 bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-blue-400 rounded-2xl text-xs font-bold transition-all outline-none">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-4">
                    <button type="button" @click="showModal = false" 
                            class="flex-grow py-5 bg-slate-100 text-slate-500 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Batalkan
                    </button>
                    <button type="submit" 
                            class="flex-grow py-5 bg-blue-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-200">
                        Kirim Laporan <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    body { background-color: #fcfdfe; }
</style>
@endsection