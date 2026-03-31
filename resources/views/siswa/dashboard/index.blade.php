@extends('siswa.layouts.app')

@section('title', 'Dashboard Siswa - CodeLab')

@section('content')
<div class="pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Dashboard --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Selamat Datang, {{ $user->name }}! 👋</h1>
                <p class="text-slate-500">Ayo selesaikan proyek simulasimu hari ini.</p>
            </div>
            <div class="flex gap-3">
                <button class="bg-white border border-slate-200 p-2.5 rounded-xl text-slate-600 hover:bg-slate-50 transition relative">
                    <i class="fas fa-bell"></i>
                    @if($logs->where('created_at', '>', now()->subDay())->count() > 0)
                        <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full"></span>
                    @endif
                </button>
                <div class="flex items-center gap-3 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-[10px] font-black uppercase">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    <span class="text-sm font-bold text-slate-700">{{ $user->kelas ?? 'Siswa CodeLab' }}</span>
                </div>
            </div>
        </div>

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Card Progres Tugas --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">{{ $persentaseTotal }}% Selesai</span>
                </div>
                <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Total Tugas Disetujui</h3>
                <p class="text-2xl font-black text-slate-900">{{ $tugasSelesai }} / {{ $totalTugas }}</p>
            </div>
            
            {{-- Card Deadline --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clock"></i>
                    </div>
                    @if($deadlineTerdekat)
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">
                            {{ \Carbon\Carbon::parse($deadlineTerdekat->proyek->deadline)->diffForHumans() }}
                        </span>
                    @endif
                </div>
                <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Deadline Terdekat</h3>
                <p class="text-xl font-black text-slate-900 truncate">
                    {{ $deadlineTerdekat->proyek->nama_proyek ?? 'Tidak Ada Proyek Aktif' }}
                </p>
            </div>

            {{-- Card Poin / Rank --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-award"></i>
                    </div>
                    {{-- Menampilkan total poin yang didapat --}}
                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg uppercase tracking-wider">
                        {{ number_format($myTotalPoin) }} Poin
                    </span>
                </div>
                <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Peringkat Global</h3>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-slate-900">#{{ $myRank }}</p>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Dari Seluruh Siswa</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- List Proyek Utama --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-slate-900 italic uppercase tracking-tight">Proyek Berjalan</h2>
                    <a href="{{ route('siswa.proyek.index') }}" class="text-blue-600 text-sm font-bold hover:underline">Lihat Semua</a>
                </div>

                @if($proyekUtama)
                <div class="group bg-white border border-slate-100 rounded-[2.5rem] p-8 shadow-sm hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase tracking-widest">
                                {{ $proyekUtama->proyek->kategori }}
                            </span>
                            <h3 class="text-2xl font-black text-slate-900 mt-3 group-hover:text-blue-600 transition">
                                {{ $proyekUtama->proyek->nama_proyek }}
                            </h3>
                        </div>
                        <div class="text-right hidden sm:block">
                            <span class="block text-sm font-black text-slate-900 uppercase italic">Tahap Pengerjaan</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Role: {{ $proyekUtama->role->nama_role }}</span>
                        </div>
                    </div>

                    <p class="text-slate-500 text-sm mb-8 leading-relaxed line-clamp-2 font-medium">
                        {{ $proyekUtama->proyek->deskripsi }}
                    </p>

                    <div class="space-y-4">
                        <div class="flex justify-between text-sm font-black uppercase tracking-tighter">
                            <span class="text-slate-700 italic">Personal Progress</span>
                            <span class="text-blue-600">{{ $proyekUtama->progress }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-4 rounded-2xl overflow-hidden p-1">
                            <div class="bg-blue-600 h-full rounded-xl transition-all duration-1000" style="width: {{ $proyekUtama->progress }}%"></div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3 text-slate-400 text-xs font-bold uppercase tracking-widest">
                            <i class="far fa-calendar-alt text-rose-500"></i>
                            <span>Deadline: {{ \Carbon\Carbon::parse($proyekUtama->proyek->deadline)->format('d F Y') }}</span>
                        </div>
                        <a href="{{ route('siswa.proyek.pengerjaan', $proyekUtama->proyek_id) }}" 
                           class="w-full sm:w-auto bg-slate-900 text-white px-8 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-slate-200 text-center">
                            Workspace <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                @else
                <div class="bg-white border-2 border-dashed border-slate-200 rounded-[2.5rem] p-12 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-rocket text-slate-300"></i>
                    </div>
                    <p class="text-slate-500 font-bold text-sm mb-4">Belum ada proyek aktif yang sedang dikerjakan.</p>
                    <a href="{{ route('siswa.katalog.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest">Cari Proyek</a>
                </div>
                @endif
            </div>

            {{-- Sidebar Dashboard --}}
            <div class="space-y-8">
                {{-- Pengumuman Pembimbing / Feedback Terakhir --}}
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 mb-6 italic uppercase tracking-tight">Feedback Mentor</h2>
                    @if($catatanGuru)
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center font-black text-sm">
                                    {{ substr($catatanGuru->proyek->guru->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-black italic tracking-tight">{{ $catatanGuru->proyek->guru->name }}</h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Mentor Proyek</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-300 leading-relaxed italic mb-6">
                                "{{ $catatanGuru->feedback_guru }}"
                            </p>
                            <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></span>
                                {{ $catatanGuru->updated_at->diffForHumans() }}
                            </div>
                        </div>
                        <i class="fas fa-quote-right absolute -bottom-4 -right-4 text-8xl text-white/5 rotate-12"></i>
                    </div>
                    @else
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 text-center">
                        <p class="text-xs font-bold text-slate-400 italic uppercase">Belum ada feedback masuk</p>
                    </div>
                    @endif
                </div>

                {{-- Aktivitas Terakhir --}}
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 mb-6 italic uppercase tracking-tight">Aktivitas Saya</h2>
                    <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 shadow-sm">
                        <div class="space-y-8 relative">
                            {{-- Vertical Line --}}
                            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-50"></div>

                            @forelse($logs as $log)
                            <div class="flex gap-6 relative z-10">
                                <div class="flex-shrink-0 w-8 h-8 {{ $log->status == 'diterima' ? 'bg-emerald-500 text-white' : ($log->status == 'ditolak' ? 'bg-rose-500 text-white' : 'bg-blue-600 text-white') }} rounded-xl flex items-center justify-center text-[10px] shadow-lg shadow-blue-100">
                                    <i class="fas {{ $log->status == 'diterima' ? 'fa-check' : ($log->status == 'ditolak' ? 'fa-times' : 'fa-clock') }}"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-900 leading-tight uppercase tracking-tight">
                                        {{ $log->roadmap->judul_tugas }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">
                                        {{ $log->status == 'diterima' ? 'Disetujui Mentor' : ($log->status == 'ditolak' ? 'Dibutuhkan Revisi' : 'Menunggu Review') }}
                                        • {{ $log->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-[10px] font-bold text-slate-400 uppercase italic">Belum ada aktivitas</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection