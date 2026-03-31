@extends('guru.layouts.app')

@section('title', 'Manajemen Materi - CodeLab Guru')

@section('content')
<div class="space-y-8">
    {{-- Header & Button Tambah --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Materi</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Kelola dan pantau semua materi pembelajaran Anda di sini.</p>
        </div>
        <a href="{{ route('guru.materi.create') }}" class="inline-flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-bold transition shadow-lg shadow-blue-200 active:scale-95">
            <i class="fas fa-plus text-sm"></i>
            Tambah Materi Baru
        </a>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Materi</p>
                    <p class="text-xl font-extrabold text-slate-900">{{ $materis->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-eye text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Dilihat</p>
                    <p class="text-xl font-extrabold text-slate-900">0</p> {{-- Kamu bisa tambahkan kolom 'views' di migration nanti --}}
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-file-pdf text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lampiran File</p>
                    <p class="text-xl font-extrabold text-slate-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Favorit Siswa</p>
                    <p class="text-xl font-extrabold text-slate-900">0</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Materi --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-extrabold text-slate-900">Koleksi Materi Anda</h3>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchMateri" placeholder="Cari materi..." class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none w-64 transition">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <th class="px-8 py-5">Info Materi</th>
                        <th class="px-8 py-5">Kategori</th>
                        <th class="px-8 py-5 text-center">Tgl Terbit</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($materis as $materi)
                    <tr class="group hover:bg-slate-50/80 transition-all">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-100 shrink-0 overflow-hidden">
                                    @if($materi->thumbnail)
                                        <img src="{{ asset('storage/' . $materi->thumbnail) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-code"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition">{{ $materi->judul }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">ID: #{{ $materi->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-tighter">{{ $materi->kategori }}</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-[11px] font-bold text-slate-600">{{ $materi->created_at->format('d M Y') }}</p>
                        </td>
                       <td class="px-8 py-6">
                            @if($materi->status == 'published')
                                <div class="flex items-center gap-2 text-emerald-500 font-bold text-[10px] uppercase tracking-wider">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    Published
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-amber-500 font-bold text-[10px] uppercase tracking-wider">
                                    <span class="w-2 h-2 bg-amber-400 rounded-full border border-amber-200"></span>
                                    Draft Mode
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('guru.submateri.dashboard', ['materi_id' => $materi->id]) }}" class="group/btn relative flex items-center justify-center p-2.5 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Kelola Isi Sub Materi">
                                    <i class="fas fa-layer-group text-sm"></i>
                                    {{-- Badge jumlah sub materi (opsional jika sudah ada relasinya) --}}
                                    {{-- <span class="absolute -top-2 -right-2 bg-rose-500 text-white text-[8px] px-1.5 py-0.5 rounded-full border-2 border-white">3</span> --}}
                                </a>
                                {{-- Edit Button --}}
                                <a href="{{ route('guru.materi.edit', $materi->id) }}" class="p-2.5 rounded-xl bg-slate-100 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>
                                
                                {{-- Delete Button (Menggunakan Form agar aman) --}}
                                <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 rounded-xl bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-4xl text-slate-200 mb-3"></i>
                                <p class="text-slate-400 font-bold text-sm">Belum ada materi ditemukan</p>
                                <a href="{{ route('guru.materi.create') }}" class="text-blue-600 text-xs font-bold mt-2 hover:underline">Buat materi pertama Anda</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-slate-50/30 flex items-center justify-between">
            <p class="text-[10px] font-bold text-slate-400 uppercase">Total: {{ $materis->count() }} materi</p>
            {{-- Jika menggunakan pagination: {{ $materis->links() }} --}}
        </div>
    </div>
</div>
@endsection