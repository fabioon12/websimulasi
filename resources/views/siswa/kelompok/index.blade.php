@extends('siswa.layouts.app')

@section('title', 'Manajemen Tugas - CodeLab')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Rangkaian Tugas Tim</h1>
            <p class="text-slate-500">Pantau perkembangan setiap modul proyek dan distribusi beban kerja tim.</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 shadow-sm">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-100">
                <i class="fas fa-plus mr-2 text-xs"></i> Tugas Baru
            </button>
        </div>
    </div>

    <div class="flex overflow-x-auto pb-6 gap-6 outline-none">
        
        <div class="flex-shrink-0 w-80">
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <h3 class="font-extrabold text-slate-700 text-sm uppercase tracking-widest">Akan Dikerjakan</h3>
                    <span class="bg-slate-200 text-slate-600 text-[10px] px-2 py-0.5 rounded-full font-bold">4</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:border-blue-300 transition-all cursor-grab active:cursor-grabbing group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="px-2 py-0.5 bg-purple-50 text-purple-600 text-[10px] font-bold rounded-md uppercase">UI Design</span>
                        <i class="fas fa-ellipsis-h text-slate-300 group-hover:text-slate-500"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm mb-4 leading-relaxed">Membuat Wireframe Halaman Checkout</h4>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/100?u=4" class="w-7 h-7 rounded-full border-2 border-white">
                        </div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                            <i class="far fa-clock mr-1"></i> 2 Hari Lagi
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0 w-80">
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <h3 class="font-extrabold text-slate-700 text-sm uppercase tracking-widest text-blue-600">Sedang Dikerjakan</h3>
                    <span class="bg-blue-100 text-blue-600 text-[10px] px-2 py-0.5 rounded-full font-bold">2</span>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white p-5 rounded-[2rem] border-l-4 border-blue-500 shadow-md hover:shadow-lg transition-all group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-md uppercase">Backend</span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm mb-4 leading-relaxed">Integrasi Midtrans Payment Gateway</h4>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                        <div class="flex items-center gap-2">
                            <img src="https://i.pravatar.cc/100?u=1" class="w-7 h-7 rounded-full">
                            <span class="text-[10px] font-bold text-slate-600">Naufal (You)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0 w-80">
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <h3 class="font-extrabold text-slate-700 text-sm uppercase tracking-widest text-amber-600">Tinjauan Mentor</h3>
                    <span class="bg-amber-100 text-amber-600 text-[10px] px-2 py-0.5 rounded-full font-bold">1</span>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-amber-50/50 p-5 rounded-[2rem] border border-amber-200 border-dashed shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-md uppercase">Database</span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm mb-4 leading-relaxed italic">Skema Tabel Produk & Stok</h4>
                    <div class="p-3 bg-white rounded-xl text-[10px] text-slate-500 font-medium border border-amber-100 mb-4">
                        <i class="fas fa-info-circle mr-1 text-amber-500"></i> Menunggu feedback dari Pak Budi...
                    </div>
                    <div class="flex -space-x-2">
                        <img src="https://i.pravatar.cc/100?u=5" class="w-7 h-7 rounded-full border-2 border-white">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0 w-80">
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <h3 class="font-extrabold text-slate-700 text-sm uppercase tracking-widest text-emerald-600">Selesai</h3>
                    <span class="bg-emerald-100 text-emerald-600 text-[10px] px-2 py-0.5 rounded-full font-bold">12</span>
                </div>
            </div>

            <div class="space-y-4 opacity-70 hover:opacity-100 transition-opacity">
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-2 -top-2 w-12 h-12 bg-emerald-500 rotate-45 flex items-end justify-center pb-1">
                        <i class="fas fa-check text-white text-[10px] -rotate-45"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm mb-2 line-through decoration-slate-400">Setup Repository Laravel</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter italic">Selesai 3 hari yang lalu</p>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-slate-900 rounded-[3rem] p-8 md:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-8">
        <div>
            <h3 class="text-xl font-bold mb-2">Statistik Rangkaian Tugas</h3>
            <p class="text-slate-400 text-sm">Tim Delta Tech telah menyelesaikan 12 dari 19 rangkaian tugas utama.</p>
        </div>
        <div class="flex gap-8">
            <div class="text-center">
                <p class="text-3xl font-black text-blue-400">63%</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Kesiapan Proyek</p>
            </div>
            <div class="h-12 w-[1px] bg-slate-800"></div>
            <div class="text-center">
                <p class="text-3xl font-black text-emerald-400">04</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Tugas Verifikasi</p>
            </div>
        </div>
    </div>
</div>
@endsection