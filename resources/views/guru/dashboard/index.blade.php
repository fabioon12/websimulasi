@extends('guru.layouts.app')

@section('title', 'Guru Dashboard - CodeLab')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Monitor Proyek 📊</h1>
            <p class="text-slate-500 font-medium mt-1">Selamat datang kembali, Mentor. Berikut ringkasan progres siswa Anda.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('guru.proyek.create') }}" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                <i class="fas fa-plus mr-2"></i> Buat Proyek Baru
            </a>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Total Siswa Bimbingan</h3>
            <p class="text-3xl font-black text-slate-900">{{ $totalSiswa }}</p>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                <i class="fas fa-project-diagram"></i>
            </div>
            <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Proyek Berjalan</h3>
            <p class="text-3xl font-black text-slate-900">{{ $proyekAktif }}</p>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Perlu Review (Pending)</h3>
            <p class="text-3xl font-black text-slate-900">{{ $tugasPending }}</p>
            @if($tugasPending > 0)
                <span class="absolute top-6 right-6 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
            @endif
        </div>

        <div class="bg-slate-900 p-6 rounded-[2.5rem] text-white shadow-xl">
            <div class="w-12 h-12 bg-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center mb-4 text-xl">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Avg. Progres Siswa</h3>
            <p class="text-3xl font-black text-white">78%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Tabel Tugas Masuk --}}
        <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-black text-slate-900 italic uppercase">Tugas Masuk Terkini</h2>
                <a href="{{ route('guru.review.index') }}" class="text-emerald-600 font-bold text-xs uppercase tracking-widest hover:underline">Review Semua →</a>
            </div>
            
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Siswa</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Role</th> 
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Tugas</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentSubmissions as $sub)
                        <tr class="hover:bg-emerald-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 font-black text-[10px]">
                                        {{ substr($sub->user->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $sub->user->name }}</span>
                                </div>
                            </td>
                            {{-- Menampilkan Role yang diambil siswa --}}
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[9px] font-black rounded-full uppercase tracking-widest">
                                    {{ $sub->user->proyekSiswa->where('proyek_id', $sub->proyek_id)->first()->role->nama_role ?? 'No Role' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-slate-900">{{ $sub->roadmap->judul_tugas }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $sub->proyek->nama_proyek }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('guru.review.index') }}" class="w-8 h-8 bg-slate-100 text-slate-400 rounded-full inline-flex items-center justify-center hover:bg-emerald-600 hover:text-white transition">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-slate-400 font-bold italic text-sm">Belum ada tugas baru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-8">
            @php
                $totalStatus = $statsChart['diterima'] + $statsChart['pending'] + $statsChart['ditolak'];
            @endphp

            {{-- Card Status Tugas --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 italic">Status Tugas</h3>
                <div class="space-y-5">
                    {{-- Diterima --}}
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2 text-emerald-600">
                            <span>Diterima</span>
                            <span>{{ $statsChart['diterima'] }}</span>
                        </div>
                        <div class="w-full bg-slate-50 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000" 
                                 style="width: {{ $totalStatus > 0 ? ($statsChart['diterima'] / $totalStatus) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Pending --}}
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2 text-amber-500">
                            <span>Pending</span>
                            <span>{{ $statsChart['pending'] }}</span>
                        </div>
                        <div class="w-full bg-slate-50 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full transition-all duration-1000" 
                                 style="width: {{ $totalStatus > 0 ? ($statsChart['pending'] / $totalStatus) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Ditolak --}}
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2 text-rose-500">
                            <span>Ditolak</span>
                            <span>{{ $statsChart['ditolak'] }}</span>
                        </div>
                        <div class="w-full bg-slate-50 h-2 rounded-full overflow-hidden">
                            <div class="bg-rose-500 h-full rounded-full transition-all duration-1000" 
                                 style="width: {{ $totalStatus > 0 ? ($statsChart['ditolak'] / $totalStatus) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Card Green --}}
            <div class="bg-emerald-600 p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden group">
                <h3 class="text-lg font-black mb-2 italic">Sistem Pointing</h3>
                <p class="text-emerald-100 text-sm font-medium mb-6 opacity-80">Pastikan memberikan feedback yang konstruktif saat menolak tugas.</p>
                <a href="#" class="inline-block bg-white text-emerald-600 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition">
                    Lihat Panduan
                </a>
                <i class="fas fa-leaf absolute -bottom-5 -right-5 text-7xl text-white/10 -rotate-12 group-hover:rotate-0 transition duration-500"></i>
            </div>
        </div>
    </div>
</div>
@endsection