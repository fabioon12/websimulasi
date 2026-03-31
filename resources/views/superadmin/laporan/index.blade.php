@extends('superadmin.layouts.app')

@section('title', 'Laporan Analitik - CodeLab Admin')

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Strategis 📊</h1>
            <p class="text-slate-500 font-medium">Analisis perkembangan kompetensi dan produktivitas proyek siswa.</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                <i class="fas fa-download mr-2 text-xs"></i> Unduh PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm relative overflow-hidden">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Penyelesaian Proyek</p>
            <div class="flex items-end gap-4">
                <h3 class="text-5xl font-black text-slate-900">84%</h3>
                <span class="text-emerald-500 font-bold text-sm mb-2"><i class="fas fa-arrow-up"></i> 12%</span>
            </div>
            <div class="mt-6 space-y-3">
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-indigo-600 h-full w-[84%]"></div>
                </div>
                <p class="text-[10px] text-slate-400 font-medium italic">Target: 90% di akhir semester</p>
            </div>
        </div>

        <div class="bg-slate-900 p-8 rounded-[3rem] shadow-xl text-white">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Top Kategori</p>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold">Web Dev</span>
                    <span class="text-xs font-black text-blue-400">45%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold">IoT</span>
                    <span class="text-xs font-black text-emerald-400">30%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold">Design</span>
                    <span class="text-xs font-black text-purple-400">25%</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Rata-rata Tugas / Tim</p>
            <div class="flex items-center gap-6">
                <div class="relative flex items-center justify-center">
                    <svg class="w-20 h-20 transform -rotate-90">
                        <circle cx="40" cy="40" r="35" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100" />
                        <circle cx="40" cy="40" r="35" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="219.9" stroke-dashoffset="65" class="text-indigo-600" />
                    </svg>
                    <span class="absolute text-sm font-black text-slate-900">18.5</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900 leading-tight">Produktivitas Meningkat</p>
                    <p class="text-[10px] text-slate-400 mt-1">Berdasarkan log aktivitas 7 hari terakhir.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-extrabold text-slate-900 italic">Tim Berprestasi</h3>
                <i class="fas fa-crown text-amber-400"></i>
            </div>
            <div class="p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                            <th class="px-4 py-3 text-left">Nama Tim</th>
                            <th class="px-4 py-3 text-center">Skor</th>
                            <th class="px-4 py-3 text-right">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr>
                            <td class="px-4 py-4">
                                <p class="text-sm font-bold text-slate-900">Delta Tech</p>
                                <p class="text-[10px] text-slate-400">Web Project</p>
                            </td>
                            <td class="px-4 py-4 text-center font-black text-indigo-600 text-sm">2,850</td>
                            <td class="px-4 py-4 text-right text-emerald-500 text-xs font-bold"><i class="fas fa-caret-up"></i> High</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <p class="text-sm font-bold text-slate-900">Cyber Squad</p>
                                <p class="text-[10px] text-slate-400">Security Project</p>
                            </td>
                            <td class="px-4 py-4 text-center font-black text-indigo-600 text-sm">2,620</td>
                            <td class="px-4 py-4 text-right text-emerald-500 text-xs font-bold"><i class="fas fa-caret-up"></i> Stable</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm p-8">
            <h3 class="font-extrabold text-slate-900 italic mb-8 text-center lg:text-left">Distribusi Nilai Proyek</h3>
            <div class="flex items-end justify-between h-48 px-4">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-12 bg-slate-100 h-20 rounded-t-xl"></div>
                    <span class="text-[10px] font-bold text-slate-400">D</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-12 bg-slate-200 h-32 rounded-t-xl"></div>
                    <span class="text-[10px] font-bold text-slate-400">C</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-12 bg-indigo-400 h-44 rounded-t-xl"></div>
                    <span class="text-[10px] font-bold text-slate-400">B</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-12 bg-indigo-600 h-48 rounded-t-xl shadow-lg shadow-indigo-100"></div>
                    <span class="text-[10px] font-bold text-slate-900">A</span>
                </div>
            </div>
            <p class="mt-8 text-center text-xs text-slate-400 font-medium uppercase tracking-widest">Sebaran akumulatif nilai akhir</p>
        </div>

    </div>

    <div class="bg-indigo-600 rounded-[3rem] p-10 text-white flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-md">
            <h4 class="text-xl font-bold mb-2 italic">Rekomendasi Admin 💡</h4>
            <p class="text-indigo-100 text-sm leading-relaxed">Sistem mendeteksi 5 tim yang belum mengunggah progres dalam 3 hari terakhir. Disarankan untuk mengirimkan notifikasi pengingat secara massal.</p>
        </div>
        <button class="bg-white text-indigo-600 px-8 py-4 rounded-2xl font-black text-sm hover:scale-105 transition shadow-2xl">
            Kirim Pengingat
        </button>
    </div>

</div>
@endsection