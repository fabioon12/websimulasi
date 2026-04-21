@extends('guru.layouts.app')

@section('title', 'Logbook Monitor - ' . $project->judul)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    
    {{-- Header --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <a href="{{ route('guru.workspace.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 hover:text-emerald-600 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali ke Monitoring
            </a>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Work<span class="text-emerald-600">Log.</span></h1>
            <p class="text-slate-500 font-medium italic">Logbook Terkelompok Milestone: {{ $project->judul }}</p>
        </div>

        <div class="bg-emerald-50 px-6 py-4 rounded-3xl border border-emerald-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                <i class="fas fa-clipboard-list text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Total Aktivitas</p>
                <p class="text-xl font-black text-slate-900">{{ $project->milestones->flatMap->logbooks->count() }} Laporan</p>
            </div>
        </div>
    </div>

    {{-- Dropdown Filter Milestone --}}
    <div class="mb-12 flex flex-col md:flex-row items-center justify-between gap-4 bg-white p-4 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3 px-4">
            <i class="fas fa-filter text-emerald-500"></i>
            <span class="text-xs font-black text-slate-700 uppercase tracking-widest">Filter Milestone</span>
        </div>
        
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            <select name="milestone_id" onchange="this.form.submit()" 
                class="flex-grow md:w-72 bg-slate-50 border-none focus:ring-2 focus:ring-emerald-500 rounded-2xl text-xs font-bold py-3 px-5 outline-none transition-all">
                <option value="">Tampilkan Semua Milestone</option>
                @foreach($allMilestones as $m)
                    <option value="{{ $m->id }}" {{ request('milestone_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->nama_milestone }}
                    </option>
                @endforeach
            </select>
            
            @if(request('milestone_id'))
                <a href="{{ url()->current() }}" class="w-11 h-11 flex items-center justify-center bg-rose-50 text-rose-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Reset Filter">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Looping Milestone --}}
    <div class="space-y-16">
        @forelse($project->milestones as $ms)
            <div class="relative">
                {{-- Milestone Header Label --}}
                <div class="flex items-center gap-4 mb-8">
                    <div class="px-6 py-2 bg-slate-900 text-white rounded-2xl shadow-xl shadow-slate-200">
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-400 block">Milestone Target</span>
                        <h2 class="text-lg font-black">{{ $ms->nama_milestone }}</h2>
                    </div>
                    <div class="h-[2px] flex-grow bg-slate-100"></div>
                    <div class="flex items-center gap-2">
                        @if($ms->is_completed)
                            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-black uppercase">Selesai</span>
                        @else
                            <span class="px-4 py-1.5 bg-amber-100 text-amber-600 rounded-full text-[10px] font-black uppercase">In Progress</span>
                        @endif
                    </div>
                </div>

                {{-- Timeline Logbook --}}
                <div class="relative ml-4 md:ml-8 border-l-2 border-slate-100 pl-8 md:pl-12 space-y-12">
                    @forelse($ms->logbooks as $log)
                        <div class="relative group">
                            {{-- Dot Timeline --}}
                            <div class="absolute -left-[41px] md:-left-[57px] top-6 w-4 h-4 bg-white border-4 border-emerald-500 rounded-full z-10 shadow-[0_0_0_8px_rgba(16,185,129,0.1)]"></div>

                            {{-- Card Logbook --}}
                            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                                
                                {{-- Header Card --}}
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&background=random" class="w-10 h-10 rounded-2xl shadow-sm border-2 border-white">
                                        <div>
                                            <p class="text-[11px] font-black text-slate-900 leading-none">{{ $log->user->name }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1">Kontributor Proyek</p>
                                        </div>
                                    </div>
                                    
                                    {{-- Badge Tanggal & Jam --}}
                                    <div class="flex items-center gap-4 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="flex items-center gap-2 pr-3 border-r border-slate-200">
                                            <i class="far fa-calendar-alt text-emerald-500 text-[10px]"></i>
                                            <span class="text-[10px] font-black text-slate-600">
                                                {{ \Carbon\Carbon::parse($log->tanggal_kerjakan)->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="far fa-clock text-blue-500 text-[10px]"></i>
                                            <span class="text-[10px] font-black text-slate-600">
                                                {{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="mb-6">
                                    <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-emerald-600 transition-colors">{{ $log->judul }}</h3>
                                    <div class="relative">
                                        <i class="fas fa-quote-left absolute -left-4 -top-2 text-slate-100 text-3xl"></i>
                                        <p class="text-sm text-slate-500 leading-relaxed italic relative z-10">
                                            {{ $log->deskripsi }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Lampiran --}}
                                @if($log->lampiran)
                                    <div class="mb-8 pt-6 border-t border-slate-50">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Dokumen Lampiran</p>
                                        @if($log->file_type === 'image')
                                            <a href="{{ asset('storage/' . $log->lampiran) }}" target="_blank" class="block w-full md:w-72 group/img relative overflow-hidden rounded-[2rem] border-4 border-white shadow-md">
                                                <img src="{{ asset('storage/' . $log->lampiran) }}" class="w-full h-40 object-cover group-hover/img:scale-110 transition-transform duration-700">
                                                <div class="absolute inset-0 bg-emerald-900/60 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white">
                                                        <i class="fas fa-expand"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $log->lampiran) }}" target="_blank" 
                                            class="inline-flex items-center gap-4 p-4 bg-rose-50 text-rose-600 rounded-2xl hover:bg-rose-600 hover:text-white transition-all border border-rose-100 group/file">
                                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover/file:bg-rose-500">
                                                    <i class="fas fa-file-pdf text-lg"></i>
                                                </div>
                                                <span class="text-[10px] font-black uppercase tracking-widest">Buka Dokumen Hasil.pdf</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Feedback Guru --}}
                                <div class="mt-4 pt-6 border-t-2 border-dashed border-slate-100">
                                    @if($log->feedback_guru)
                                        <div class="bg-emerald-50/50 p-5 rounded-[1.5rem] border border-emerald-100 mb-4 relative group/msg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="w-5 h-5 bg-emerald-500 rounded-lg flex items-center justify-center text-[8px] text-white">
                                                    <i class="fas fa-comment-dots"></i>
                                                </div>
                                                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Feedback Anda</p>
                                            </div>
                                            <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $log->feedback_guru }}</p>
                                            
                                            <button onclick="toggleEdit('{{ $log->id }}')" 
                                                class="absolute top-5 right-5 opacity-0 group-hover/msg:opacity-100 transition-opacity bg-white w-8 h-8 rounded-lg shadow-sm border border-emerald-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-600 hover:text-white">
                                                <i class="fas fa-pen text-[10px]"></i>
                                            </button>
                                        </div>
                                    @endif

                                    <div id="form-feedback-{{ $log->id }}" class="{{ $log->feedback_guru ? 'hidden' : '' }} animate-in fade-in slide-in-from-top-2">
                                        <form action="{{ route('guru.logbook.feedback', $log->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <div class="flex flex-col md:flex-row gap-3">
                                                <div class="relative flex-grow">
                                                    <input type="text" name="feedback_guru" 
                                                        placeholder="Berikan arahan atau apresiasi..." 
                                                        class="w-full bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white rounded-[1.25rem] text-xs font-medium py-4 pl-6 pr-4 transition-all outline-none"
                                                        value="{{ $log->feedback_guru }}">
                                                </div>
                                                <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-[1.25rem] text-[10px] font-black uppercase tracking-[0.1em] hover:bg-emerald-600 shadow-lg shadow-slate-200 hover:shadow-emerald-200 transition-all shrink-0">
                                                    Kirim Pesan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="p-12 bg-slate-50/50 rounded-[3rem] border-4 border-dashed border-slate-100 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Belum ada aktivitas di milestone ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="py-24 text-center bg-white rounded-[4rem] border-4 border-dashed border-slate-100">
                <i class="fas fa-search text-5xl text-slate-100 mb-6"></i>
                <h3 class="text-lg font-black text-slate-400 uppercase tracking-[0.2em]">Tidak Ada Data</h3>
                <p class="text-slate-300 text-sm italic">Silakan sesuaikan filter atau tunggu update dari siswa.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function toggleEdit(id) {
        const form = document.getElementById('form-feedback-' + id);
        form.classList.toggle('hidden');
    }
</script>
@endsection