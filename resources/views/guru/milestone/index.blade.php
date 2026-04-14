@extends('guru.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
        <div>
            {{-- Tombol Kembali --}}
            <a href="{{ route('guru.workspace.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali ke Workspace
            </a>
        </div>
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-900">Review <span class="text-blue-600">Milestone.</span></h1>
        <p class="text-slate-500">Validasi progres target besar dari setiap kelompok proyek.</p>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($pendingMilestones as $ms)
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    
                    {{-- Informasi Milestone --}}
                    <div class="flex-grow">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase rounded-full">
                                {{ $ms->proposal->judul }}
                            </span>
                            <span class="text-slate-300 text-xs">|</span>
                            <span class="text-slate-400 text-xs font-bold">
                                <i class="far fa-calendar-alt mr-1"></i> Deadline: {{ $ms->deadline->format('d M Y') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-4">{{ $ms->nama_milestone }}</h3>
                        
                        @if($ms->is_completed)
                            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 mb-4">
                                <p class="text-[10px] font-black text-emerald-600 uppercase mb-1">Tipe Review: Validasi Hasil</p>
                                <p class="text-sm text-slate-700">Siswa mengeklaim target ini **SUDAH SELESAI**. Silakan cek logbook terkait.</p>
                            </div>
                        @else
                            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-4">
                                <p class="text-[10px] font-black text-amber-600 uppercase mb-1">Tipe Review: Persetujuan Rencana</p>
                                <p class="text-sm text-slate-700">Ini adalah **RENCANA** baru. Setujui agar siswa bisa mulai mengisi logbook.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Form Review --}}
                    <div class="w-full md:w-1/3 border-l border-slate-100 pl-0 md:pl-6">
                        <form action="{{ route('guru.milestone.update', $ms->id) }}" method="POST" class="space-y-4">
                            @csrf @method('PATCH')
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-2">Berikan Feedback</label>
                                <textarea name="feedback_guru" rows="3" required placeholder="Contoh: Pekerjaan bagus, lanjutkan ke tahap berikutnya..."
                                    class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-600 transition resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button type="submit" name="status_review" value="revisi" 
                                    class="py-3 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase hover:bg-rose-100 transition">
                                    Minta Revisi
                                </button>
                                <button type="submit" name="status_review" value="disetujui" 
                                    class="py-3 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase hover:bg-slate-900 transition shadow-lg shadow-emerald-100">
                                    Setujui
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-slate-50 rounded-[4rem] border-4 border-dashed border-white">
                <i class="fas fa-check-circle text-5xl text-slate-200 mb-4"></i>
                <p class="text-sm font-black text-slate-400 uppercase tracking-widest">Tidak ada milestone yang perlu direview</p>
            </div>
        @endforelse
    </div>
</div>
@endsection