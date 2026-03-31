@extends('superadmin.layouts.app') {{-- Pastikan buat layout admin yang mirip dengan student tadi --}}

@section('title', 'Admin Panel - CodeLab')

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Halo, Admin CodeLab! 👋</h1>
            <p class="text-slate-500 font-medium">Berikut adalah rangkuman aktivitas seluruh sekolah hari ini.</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-slate-200 text-slate-700 px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-slate-50 transition shadow-sm">
                <i class="fas fa-file-export mr-2 text-blue-500"></i> Laporan Cetak
            </button>
            <button class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-blue-600 transition shadow-lg shadow-slate-200">
                <i class="fas fa-plus mr-2"></i> Buat Proyek Baru
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-users text-lg"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Total Siswa</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">1,240</h3>
            <p class="text-[10px] text-emerald-500 font-bold mt-2"><i class="fas fa-caret-up mr-1"></i> +12 Siswa Baru</p>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-project-diagram text-lg"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Proyek Aktif</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">42</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2">Dalam 12 Kategori</p>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-clock text-lg"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Butuh Approval</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">08</h3>
            <p class="text-[10px] text-rose-500 font-bold mt-2">Segera periksa</p>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-award text-lg"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Sertifikat Terbit</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">567</h3>
            <p class="text-[10px] text-emerald-500 font-bold mt-2">Tahun Ajaran Ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-extrabold text-slate-900 italic text-lg">Proyek Sedang Berjalan</h3>
                <button class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Proyek</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ketua Tim</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Progress</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/50 transition cursor-pointer">
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-900">E-Commerce Batik</p>
                                <p class="text-[10px] text-slate-400">Web Development</p>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-medium">Naufal Albion</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-20 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-blue-600 h-full w-3/4"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-900">75%</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 italic font-bold text-blue-600 text-[10px] uppercase">Ongoing</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition cursor-pointer">
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-900">Smart Greenhouse</p>
                                <p class="text-[10px] text-slate-400">IoT / Hardware</p>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-medium">Reza Prasetyo</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-20 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-emerald-600 h-full w-full"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-900">100%</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 italic font-bold text-emerald-600 text-[10px] uppercase">Done</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="font-extrabold text-slate-900 italic text-lg ml-2">Persetujuan Proposal</h3>
            
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs italic">A</div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Aris Munandar</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Proposal: App Kantin</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="flex-grow py-2 bg-blue-600 text-white rounded-xl text-[10px] font-bold hover:bg-blue-700 transition">TERIMA</button>
                    <button class="flex-grow py-2 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-bold hover:bg-rose-50 hover:text-rose-600 transition">TOLAK</button>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden">
                <h3 class="font-bold mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-history text-blue-400"></i> Log Sistem
                </h3>
                <div class="space-y-4 relative z-10">
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-1"></div>
                        <p class="text-[10px] text-slate-400 leading-relaxed"><span class="text-white font-bold">Pak Budi</span> memberikan nilai pada proyek <span class="text-blue-400">IoT Greenhouse</span></p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-1"></div>
                        <p class="text-[10px] text-slate-400 leading-relaxed"><span class="text-white font-bold">System</span> menerbitkan sertifikat untuk <span class="text-emerald-400">Naufal Albion</span></p>
                    </div>
                </div>
                <i class="fas fa-shield-alt absolute -bottom-6 -right-6 text-8xl text-white/5 rotate-12"></i>
            </div>
        </div>

    </div>
</div>
@endsection