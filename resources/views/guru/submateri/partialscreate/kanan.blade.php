        <div class="lg:col-span-4 space-y-6">
            <div class="bg-slate-900 rounded-[3rem] p-8 shadow-2xl sticky top-6">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xs font-black text-white uppercase tracking-[0.2em]">Antrean Materi</h3>
                        <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest">Siap diproses</p>
                    </div>
                    <span class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-blue-400 font-black text-sm" x-text="listMateri.length"></span>
                </div>

                <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="(m, index) in listMateri" :key="index">
                        <div class="bg-white/5 border border-white/10 p-5 rounded-3xl flex items-center gap-4 group hover:bg-white/10 transition">
                            <div class="w-10 h-10 rounded-2xl bg-blue-600 flex items-center justify-center text-[10px] font-black text-white shadow-lg" x-text="m.urutan"></div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-100 truncate" x-text="m.judul"></h4>
                                <span class="text-[8px] font-black text-blue-400 uppercase tracking-widest" x-text="m.kategori"></span>
                            </div>
                            <button @click="hapusDariAntrean(index)" class="text-slate-600 hover:text-rose-500 transition opacity-0 group-hover:opacity-100">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </template>

                    <template x-if="listMateri.length === 0">
                        <div class="text-center py-20 bg-white/5 rounded-[2.5rem] border border-dashed border-white/10">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Belum ada antrean</p>
                        </div>
                    </template>
                </div>

                <div class="mt-8 pt-8 border-t border-white/10">
                    <button @click="simpanKeDatabase()" class="w-full py-5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-900/40 transition active:scale-95">
                        Simpan & Publikasikan
                    </button>
                </div>
            </div>
        </div>