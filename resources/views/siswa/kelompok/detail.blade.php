@extends('siswa.layouts.app')

@section('title', 'Kelompok Saya - CodeLab')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex text-xs font-bold text-blue-600 uppercase tracking-widest gap-2 mb-2">
                <span class="text-slate-400">Proyek:</span>
                <a href="#" class="hover:underline text-blue-600">E-Commerce Batik Lokal</a>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900">Kelompok Delta Tech 🚀</h1>
            <p class="text-slate-500">Kolaborasi tim untuk mencapai hasil simulasi industri yang maksimal.</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition">
                <i class="fas fa-comments mr-2 text-blue-500"></i> Chat Grup
            </button>
            <button class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                <i class="fas fa-plus mr-2 text-xs"></i> Undang Anggota
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-extrabold text-slate-900">Anggota Tim (5)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-[2rem] border-2 border-blue-500/20 shadow-sm relative overflow-hidden">
                    <div class="absolute top-4 right-4 bg-blue-500 text-white text-[8px] font-black px-2 py-1 rounded-full uppercase tracking-widest">Team Lead</div>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?u=1" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                        <div>
                            <h4 class="font-bold text-slate-900">Naufal Albion</h4>
                            <p class="text-xs text-slate-500 font-medium italic">Fullstack Developer</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-50 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Kontribusi: 88%</span>
                        <div class="flex gap-2">
                            <i class="fab fa-github text-slate-400 hover:text-slate-900 cursor-pointer"></i>
                            <i class="fas fa-envelope text-slate-400 hover:text-blue-500 cursor-pointer"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?u=4" class="w-14 h-14 rounded-2xl object-cover shadow-sm grayscale group-hover:grayscale-0">
                        <div>
                            <h4 class="font-bold text-slate-900">Reza Prasetyo</h4>
                            <p class="text-xs text-slate-500 font-medium italic">UI/UX Designer</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-50 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Kontribusi: 45%</span>
                        <div class="flex gap-2">
                            <i class="fab fa-behance text-slate-400 hover:text-blue-500 cursor-pointer"></i>
                            <i class="fas fa-envelope text-slate-400 hover:text-blue-500 cursor-pointer"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?u=5" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                        <div>
                            <h4 class="font-bold text-slate-900">Siti Aminah</h4>
                            <p class="text-xs text-slate-500 font-medium italic">Backend Specialist</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-50 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Kontribusi: 72%</span>
                        <div class="flex gap-2">
                            <i class="fab fa-github text-slate-400 hover:text-slate-900 cursor-pointer"></i>
                            <i class="fas fa-envelope text-slate-400 hover:text-blue-500 cursor-pointer"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 p-6 rounded-[2rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center group cursor-pointer hover:border-blue-400 transition">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 group-hover:text-blue-500 shadow-sm mb-2 transition">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 group-hover:text-blue-500 transition uppercase tracking-widest">Tambah Anggota</span>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-link text-blue-400"></i> Workspace Kelompok
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="#" class="flex items-center gap-3 p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition border border-white/10">
                        <i class="fab fa-github text-2xl"></i>
                        <div>
                            <p class="text-xs font-bold">Repository Github</p>
                            <p class="text-[10px] text-slate-400">github.com/deltatech/batik-lokal</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition border border-white/10">
                        <i class="fab fa-figma text-2xl text-pink-400"></i>
                        <div>
                            <p class="text-xs font-bold">Desain Figma</p>
                            <p class="text-[10px] text-slate-400">figma.com/file/delta-tech-design</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-6">Pembimbing Industri</h2>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <img src="https://i.pravatar.cc/100?u=teacher2" class="w-12 h-12 rounded-xl object-cover">
                        <div>
                            <h4 class="font-bold text-slate-900">Budi Santoso, S.Kom</h4>
                            <p class="text-[10px] text-blue-600 font-extrabold uppercase tracking-tighter">Senior Developer at TechID</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed italic border-l-2 border-blue-500 pl-4">
                        "Kelompok ini menunjukkan progres yang baik di backend, tapi tolong perhatikan dokumentasi API-nya."
                    </p>
                    <button class="w-full mt-6 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-xs hover:bg-blue-600 hover:text-white transition uppercase tracking-widest">
                        Hubungi via WhatsApp
                    </button>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-6">Statistik Tim</h2>
                <div class="space-y-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-double text-sm"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-600">Tugas Selesai</span>
                        </div>
                        <span class="text-lg font-black text-slate-900">24</span>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-sm"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-600">Sedang Berjalan</span>
                        </div>
                        <span class="text-lg font-black text-slate-900">08</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection