@extends('superadmin.layouts.app')

@section('title', 'Kelola Materi - CodeLab Admin')

@section('content')
<div class="space-y-8" x-data="{ uploadModal: false }">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Perpustakaan Materi 📚</h1>
            <p class="text-slate-500 font-medium">Unggah dan kelola modul pembelajaran untuk mendukung proyek siswa.</p>
        </div>
        <button @click="uploadModal = true" class="bg-indigo-600 text-white px-6 py-4 rounded-[2rem] font-bold text-sm hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 flex items-center justify-center gap-3">
            <i class="fas fa-cloud-arrow-up"></i> Unggah Materi Baru
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-indigo-200 transition group cursor-pointer">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i class="fas fa-file-code text-xl"></i>
            </div>
            <h4 class="font-black text-slate-900">Web Dev</h4>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">12 Modul</p>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-emerald-200 transition group cursor-pointer">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i class="fas fa-microchip text-xl"></i>
            </div>
            <h4 class="font-black text-slate-900">IoT & Robotik</h4>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">8 Modul</p>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:border-purple-200 transition group cursor-pointer">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i class="fas fa-palette text-xl"></i>
            </div>
            <h4 class="font-black text-slate-900">UI/UX Design</h4>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">5 Modul</p>
        </div>
        <div class="bg-slate-900 p-6 rounded-[2.5rem] shadow-sm flex flex-col justify-center text-white">
            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Penyimpanan</p>
            <h4 class="text-xl font-black">2.4 GB <span class="text-xs font-normal text-slate-500">/ 10 GB</span></h4>
            <div class="w-full bg-slate-800 h-1.5 rounded-full mt-3">
                <div class="bg-indigo-500 h-full w-[24%] rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                <input type="text" placeholder="Cari nama materi..." class="w-full pl-11 pr-6 py-3 bg-slate-50 border-none rounded-2xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
            </div>
            <div class="flex gap-2">
                <button class="p-3 text-slate-400 hover:text-indigo-600"><i class="fas fa-th-large"></i></button>
                <button class="p-3 text-indigo-600 bg-indigo-50 rounded-xl"><i class="fas fa-list"></i></button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Materi</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tipe</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-file-pdf text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition">Panduan Integrasi Laravel & Midtrans</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">12.5 MB • Diunggah 2 hari lalu</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-lg uppercase">Web Dev</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <i class="fas fa-file-invoice text-slate-300" title="PDF"></i>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center"><i class="fas fa-edit text-[10px]"></i></button>
                                <button class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition flex items-center justify-center"><i class="fas fa-trash text-[10px]"></i></button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-video text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition">Tutorial Dasar Arduino Uno</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Video Link • Diunggah 1 minggu lalu</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-lg uppercase">IoT</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <i class="fas fa-play-circle text-slate-300" title="Video"></i>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center"><i class="fas fa-edit text-[10px]"></i></button>
                                <button class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition flex items-center justify-center"><i class="fas fa-trash text-[10px]"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <template x-if="uploadModal">
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4">
            <div @click="uploadModal = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-md"></div>
            <div class="relative bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden p-10"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <h2 class="text-2xl font-black text-slate-900 mb-8 italic">Unggah File Materi</h2>
                
                <div class="space-y-6">
                    <div class="border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center hover:border-indigo-400 transition cursor-pointer group bg-slate-50/50">
                        <i class="fas fa-cloud-upload-alt text-4xl text-slate-300 group-hover:text-indigo-500 mb-4 transition"></i>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tarik file ke sini atau <span class="text-indigo-600">Klik Telusuri</span></p>
                        <p class="text-[10px] text-slate-400 mt-2 italic">Maksimal ukuran file: 50MB (PDF, ZIP, MP4)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Judul Materi</label>
                            <input type="text" placeholder="Contoh: Modul React JS" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Kategori</label>
                            <select class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                                <option>Web Dev</option>
                                <option>IoT</option>
                                <option>UI/UX</option>
                            </select>
                        </div>
                    </div>

                    <button class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-slate-900 transition mt-4 shadow-xl shadow-indigo-100 uppercase tracking-widest">
                        Mulai Publikasikan <i class="fas fa-check-circle ml-2 text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection