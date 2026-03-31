@extends('superadmin.layouts.app')

@section('title', 'Manajemen User - CodeLab Admin')

@section('content')
<div class="space-y-8" x-data="{ showModal: false }">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen User 👥</h1>
            <p class="text-slate-500 font-medium">Kelola data siswa, guru, dan hak akses sistem CodeLab.</p>
        </div>
        <button @click="showModal = true" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
            <i class="fas fa-plus text-xs"></i> Tambah User Baru
        </button>
    </div>

    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col lg:flex-row gap-4 justify-between items-center">
        <div class="flex flex-wrap gap-2 w-full lg:w-auto">
            <button class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xs uppercase tracking-widest">Semua</button>
            <button class="px-5 py-2.5 bg-white text-slate-400 hover:bg-slate-50 rounded-xl font-bold text-xs uppercase tracking-widest transition">Siswa</button>
            <button class="px-5 py-2.5 bg-white text-slate-400 hover:bg-slate-50 rounded-xl font-bold text-xs uppercase tracking-widest transition">Guru</button>
            <button class="px-5 py-2.5 bg-white text-slate-400 hover:bg-slate-50 rounded-xl font-bold text-xs uppercase tracking-widest transition">Admin</button>
        </div>
        
        <div class="relative w-full lg:w-96">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
            <input type="text" placeholder="Cari berdasarkan nama, email, atau NIS..." 
                   class="w-full pl-11 pr-6 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">User Info</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Role</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Terdaftar</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <img src="https://i.pravatar.cc/100?u=1" class="w-10 h-10 rounded-xl object-cover ring-2 ring-transparent group-hover:ring-indigo-100 transition">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 leading-none mb-1">Naufal Albion</p>
                                    <p class="text-xs text-slate-400">naufal@smk.sch.id</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg uppercase tracking-tighter">Siswa</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                <span class="text-xs font-bold text-slate-700">Aktif</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-xs font-bold text-slate-500 italic">12 Jan 2026</td>
                        <td class="px-8 py-5">
                            <div class="flex justify-center gap-2">
                                <button class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <img src="https://i.pravatar.cc/100?u=2" class="w-10 h-10 rounded-xl object-cover ring-2 ring-transparent group-hover:ring-indigo-100 transition">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 leading-none mb-1">Budi Santoso</p>
                                    <p class="text-xs text-slate-400">budi@mentor.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-purple-50 text-purple-600 text-[10px] font-black rounded-lg uppercase tracking-tighter">Guru</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                <span class="text-xs font-bold text-slate-700">Aktif</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-xs font-bold text-slate-500 italic">05 Jan 2026</td>
                        <td class="px-8 py-5 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-8 bg-slate-50/50 border-t border-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menampilkan 2 dari 1,240 User</p>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition">PREV</button>
                <button class="px-4 py-2 bg-indigo-600 border border-indigo-600 rounded-xl text-[10px] font-black text-white shadow-lg shadow-indigo-100">NEXT</button>
            </div>
        </div>
    </div>

    <template x-if="showModal">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div @click="showModal = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            
            <div class="relative bg-white w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <div class="p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black text-slate-900 italic">User Baru</h2>
                        <button @click="showModal = false" class="text-slate-300 hover:text-rose-500 transition"><i class="fas fa-times-circle text-2xl"></i></button>
                    </div>

                    <form class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Nama Lengkap</label>
                                <input type="text" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Role</label>
                                <select class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                                    <option>Siswa</option>
                                    <option>Guru</option>
                                    <option>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Alamat Email</label>
                            <input type="email" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Password</label>
                            <input type="password" placeholder="Min. 8 Karakter" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-slate-900 transition mt-4 shadow-xl shadow-indigo-100 uppercase tracking-widest">
                            Simpan Data <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection