    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 px-4">
        <div class="flex items-center gap-5">
            <a href="{{ route('guru.submateri.dashboard', $materi_id) }}" class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Studio Materi</h1>
                <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-widest">Materi Induk: <span class="text-blue-600">{{ $materi->judul }}</span></p>
            </div>
        </div>
    </div>

    <div class="flex justify-center mb-8">
        <div class="flex bg-slate-100 p-1.5 rounded-[2rem] border border-slate-200 shadow-inner">
            <button @click="tipeMateri = 'materi'; tab = 'info'" 
                :class="tipeMateri === 'materi' ? 'bg-white shadow-md text-blue-600' : 'text-slate-400'"
                class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                <i class="fas fa-book-open"></i> Materi Lengkap
            </button>
            <button @click="tipeMateri = 'kuis'; tab = 'kuis'" 
                :class="tipeMateri === 'kuis' ? 'bg-amber-500 shadow-md text-white' : 'text-slate-400'"
                class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                <i class="fas fa-tasks"></i> Hanya Kuis
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">