@extends('superadmin.layouts.app')

@section('title', 'Daftar Proyek - CodeLab Admin')

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Eksplorasi Proyek 📂</h1>
            <p class="text-slate-500 font-medium">Total <span class="text-indigo-600 font-bold">42 Proyek</span> sedang berjalan di semester ini.</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-white border border-slate-200 text-slate-700 px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-slate-50 transition">
                <i class="fas fa-file-excel mr-2 text-emerald-500"></i> Export Excel
            </button>
            <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                <i class="fas fa-plus mr-2 text-xs"></i> Proyek Manual
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-amber-50 border border-amber-100 p-4 rounded-3xl flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                <i class="fas fa-hourglass-start text-xs"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-amber-600 uppercase">Menunggu Validasi</p>
                <p class="text-lg font-black text-slate-900">08</p>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-100 p-4 rounded-3xl flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                <i class="fas fa-spinner text-xs"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase">Sedang Berjalan</p>
                <p class="text-lg font-black text-slate-900">24</p>
            </div>
        </div>
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-3xl flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                <i class="fas fa-check-double text-xs"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-600 uppercase">Selesai/Arsip</p>
                <p class="text-lg font-black text-slate-900">10</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="font-extrabold text-slate-900 italic text-lg text-center md:text-left">Daftar Proyek Siswa</h3>
            <div class="flex items-center bg-slate-50 rounded-2xl px-4 py-2 w-full md:w-auto">
                <i class="fas fa-filter text-slate-400 mr-3 text-xs"></i>
                <select class="bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 outline-none cursor-pointer">
                    <option>Semua Angkatan</option>
                    <option>Kelas XI</option>
                    <option>Kelas XII</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Proyek</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Anggota</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Progres</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-lg shadow-sm">
                                    B
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 mb-1">E-Commerce Batik Lokal</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                        <i class="fas fa-tag mr-1 text-indigo-400"></i> Web Development
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center -space-x-3">
                                <img src="https://i.pravatar.cc/100?u=1" class="w-8 h-8 rounded-full border-2 border-white shadow-sm" title="Naufal">
                                <img src="https://i.pravatar.cc/100?u=2" class="w-8 h-8 rounded-full border-2 border-white shadow-sm" title="Reza">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[8px] font-bold text-slate-400">+3</div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-600">
                                    <span>75%</span>
                                    <span class="text-blue-500 italic">21/30 Tasks</span>
                                </div>
                                <div class="w-32 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-blue-500 h-full w-[75%] rounded-full shadow-sm"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg uppercase border border-blue-100">Ongoing</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition flex items-center justify-center shadow-sm">
                                    <i class="fas fa-ban text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center font-black text-lg shadow-sm">
                                    C
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 mb-1">Chat Bot AI SMK</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter italic text-amber-600">
                                        Menunggu Persetujuan
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-xs font-bold text-slate-400">3 Anggota</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-bold text-slate-300 uppercase italic">Belum Dimulai</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black rounded-lg uppercase border border-amber-100 animate-pulse">Pending</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="bg-amber-500 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-amber-600 transition shadow-lg shadow-amber-100">
                                    VALIDASI
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection