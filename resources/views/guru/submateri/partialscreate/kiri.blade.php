        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-[3.5rem] p-2 border border-slate-100 shadow-sm overflow-hidden">
                
                {{-- Tab Navigation --}}
                <div class="flex bg-slate-50 p-2 gap-2 rounded-[3rem]">
                    <template x-if="tipeMateri === 'materi'">
                        <div class="flex flex-1 gap-2">
                            <button @click="tab = 'info'" :class="tab === 'info' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="flex-1 py-4 rounded-[2rem] text-[10px] font-black uppercase tracking-widest transition flex items-center justify-center gap-2">
                                <i class="fas fa-edit"></i> 1. Info & Teks
                            </button>
                            <button @click="tab = 'media'" :class="tab === 'media' ? 'bg-white shadow-sm text-rose-600' : 'text-slate-400'" class="flex-1 py-4 rounded-[2rem] text-[10px] font-black uppercase tracking-widest transition flex items-center justify-center gap-2">
                                <i class="fas fa-play-circle"></i> 2. Video & PDF
                            </button>
                            <button @click="tab = 'coding'" :class="tab === 'coding' ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-400'" class="flex-1 py-4 rounded-[2rem] text-[10px] font-black uppercase tracking-widest transition flex items-center justify-center gap-2">
                                <i class="fas fa-code"></i> 3. Coding Lab
                            </button>
                        </div>
                    </template>

                    {{-- Tab Kuis selalu muncul sebagai opsi terakhir, atau menjadi satu-satunya jika tipe kuis --}}
                </div>
                <div class="p-8">
                    {{-- Tab 1: Info --}}
                    <div x-show="tab === 'info' && tipeMateri === 'materi'" x-transition class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-6 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Judul Sub Materi</label>
                                <input type="text" x-model="form.judul" placeholder="Contoh: Pengenalan HTML" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-4 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Kategori</label>
                                <input type="text" x-model="form.kategori" placeholder="Contoh: Dasar" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Urutan</label>
                                <input type="number" x-model="form.urutan" class="w-full px-4 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700 text-center focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        {{-- RICH TEXT EDITOR DENGAN JUSTIFY & IMAGE --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Isi Materi (Media & Teks Rapi)</label>
                            <div class="bg-slate-50 rounded-[2.5rem] overflow-hidden border border-transparent focus-within:border-blue-500 transition-all">
                                <input id="bacaan_trix" type="hidden" name="bacaan" x-model="form.bacaan">
                                <trix-editor input="bacaan_trix" x-ref="trixEditor" 
                                    class="prose prose-slate max-w-none min-h-[400px] focus:outline-none">
                                </trix-editor>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Media --}}
                    <div x-show="tab === 'media' && tipeMateri === 'materi'" x-transition class="space-y-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">ID Video Youtube</label>
                            <div class="relative">
                                <i class="fab fa-youtube absolute left-6 top-5 text-rose-500 text-xl"></i>
                                <input type="text" x-model="form.video" placeholder="Hanya ID-nya saja, contoh: dQw4w9WgXcQ" class="w-full pl-16 pr-8 py-5 bg-slate-50 border-none rounded-3xl font-bold text-slate-700">
                            </div>
                        </div>
                        <template x-if="form.video">
                            <div class="mt-4 p-4 bg-slate-900 rounded-[2rem] overflow-hidden shadow-lg">
                                <div class="aspect-video">
                                    <iframe 
                                        class="w-full h-full rounded-2xl"
                                        :src="'https://www.youtube.com/embed/' + form.video" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <div class="mt-2 text-center">
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Preview Video Terdeteksi</span>
                                </div>
                            </div>
                        </template>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">File Modul (PDF)</label>
                            <div class="relative border-2 border-dashed rounded-[2.5rem] p-12 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer"
                                :class="form.pdfName ? 'border-emerald-400 bg-emerald-50/30' : 'border-slate-100 bg-slate-50 hover:border-blue-400'"
                                @click="$refs.fileInput.click()">
                                <input type="file" x-ref="fileInput" class="hidden" accept="application/pdf" 
                                    @change="if($event.target.files[0]) { form.pdfFile = $event.target.files[0]; form.pdfName = form.pdfFile.name; }">
                                
                                <template x-if="!form.pdfName">
                                    <div class="text-center text-slate-400">
                                        <i class="fas fa-file-pdf text-4xl mb-3"></i>
                                        <p class="text-[10px] font-black uppercase tracking-widest">Klik untuk pilih PDF</p>
                                    </div>
                                </template>

                                <template x-if="form.pdfName">
                                    <div class="flex items-center gap-4 bg-white p-5 rounded-3xl shadow-sm border border-emerald-100">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white"><i class="fas fa-check"></i></div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-xs font-black text-slate-700 truncate max-w-[200px]" x-text="form.pdfName"></span>
                                            <span class="text-[9px] font-bold text-emerald-500 uppercase">File Siap</span>
                                        </div>
                                        <button type="button" @click.stop="form.pdfFile = null; form.pdfName = ''; $refs.fileInput.value = ''" class="ml-4 text-slate-300 hover:text-rose-500 transition">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Coding --}}
                    <div x-show="tab === 'coding' && tipeMateri === 'materi'" x-transition class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Instruksi Lab (Mendukung Format Kode)</label>
                            <div class="bg-slate-50 rounded-[2.5rem] overflow-hidden border border-transparent focus-within:border-emerald-500 transition-all">
                                {{-- Tambahkan ID unik untuk input instruksi --}}
                                <input id="instruksi_trix" type="hidden" name="instruksi_coding" x-model="form.instruksi">
                                <trix-editor input="instruksi_trix" 
                                    class="prose prose-emerald max-w-none min-h-[200px] focus:outline-none">
                                </trix-editor>
                            </div>
                        </div>
                        
                        <div class="bg-[#1e1e1e] rounded-[2.5rem] overflow-hidden border border-slate-800">
                            <div class="px-8 py-4 bg-[#252526] text-[10px] font-black text-slate-500 uppercase tracking-widest">Editor Kode Awal</div>
                            <textarea id="editor-final"></textarea>
                        </div>
                    </div>


                {{-- Tab 4: Hanya Kuis --}}
                <div x-show="tab === 'kuis'" x-transition class="space-y-6">
                    
                    {{-- Header: Poin & Gambar Utama Soal --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Poin Soal</label>
                            <input type="number" x-model="tempKuis.point" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold text-slate-700 shadow-sm" placeholder="Contoh: 10">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Gambar Pertanyaan (Opsional)</label>
                            <input type="file" @change="uploadGambarKuis($event.target.files[0], 'gambar_pertanyaan')" class="w-full px-4 py-3 bg-white border-none rounded-2xl text-xs text-slate-500 shadow-sm">
                            
                            <template x-if="tempKuis.gambar_pertanyaan">
                                <div class="mt-2 relative inline-block">
                                    <img :src="tempKuis.gambar_pertanyaan" class="h-20 rounded-lg border-2 border-white shadow-sm">
                                    <button @click="tempKuis.gambar_pertanyaan = null" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-5 h-5 text-[10px]"><i class="fas fa-times"></i></button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Form Input Soal --}}
                    <div class="bg-amber-50/50 border border-amber-100 rounded-[2.5rem] p-8 space-y-6">
                        {{-- Input Pertanyaan --}}
                        <textarea x-model="tempKuis.pertanyaan" placeholder="Pertanyaan..." class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold text-slate-700 min-h-[100px] shadow-sm"></textarea>
                        
                        {{-- Input Opsi A-D dengan Gambar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_a" placeholder="Teks Opsi A" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_a_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_a_img">
                                        <img :src="tempKuis.opsi_a_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_b" placeholder="Teks Opsi B" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_b_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_b_img">
                                        <img :src="tempKuis.opsi_b_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_c" placeholder="Teks Opsi C" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_c_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_c_img">
                                        <img :src="tempKuis.opsi_c_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <input type="text" x-model="tempKuis.opsi_d" placeholder="Teks Opsi D" class="w-full px-6 py-4 bg-white border-none rounded-2xl font-bold shadow-sm">
                                <div class="flex items-center gap-3 ml-2">
                                    <input type="file" @change="uploadGambarKuis($event.target.files[0], 'opsi_d_img')" class="text-[10px] text-slate-400 w-full">
                                    <template x-if="tempKuis.opsi_d_img">
                                        <img :src="tempKuis.opsi_d_img" class="h-10 w-10 object-cover rounded-lg border-2 border-white shadow-md">
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Form: Jawaban & Simpan --}}
                        <div class="flex items-center justify-between pt-4 border-t border-amber-100">
                            <select x-model="tempKuis.jawaban" class="px-6 py-3 bg-white border-none rounded-xl font-black text-blue-600 text-xs shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="a">JAWABAN A</option>
                                <option value="b">JAWABAN B</option>
                                <option value="c">JAWABAN C</option>
                                <option value="d">JAWABAN D</option>
                            </select>
                            <button type="button" @click="tambahSoal()" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-amber-200">
                                Simpan Soal
                            </button>
                        </div>
                    </div>

                    {{-- Preview List Soal --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Daftar Soal Antrean</label>
                        <template x-for="(soal, i) in form.kuis" :key="i">
                            <div class="flex items-center gap-4 p-4 bg-white border border-slate-100 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs" x-text="i+1"></div>
                                <div class="flex-1 flex flex-col">
                                    <span class="font-bold text-slate-700 truncate" x-text="soal.pertanyaan"></span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase" x-text="'Poin: ' + soal.point"></span>
                                </div>
                                {{-- Preview kecil jika ada gambar pertanyaan --}}
                                <template x-if="soal.gambar_pertanyaan">
                                    <img :src="soal.gambar_pertanyaan" class="w-8 h-8 rounded bg-slate-100 object-cover">
                                </template>
                                <button @click="form.kuis.splice(i, 1)" class="w-8 h-8 text-rose-500 hover:bg-rose-50 rounded-full transition-colors">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                    {{-- Tombol Tambah Ke Antrean --}}
                    <div class="mt-10 pt-8 border-t border-slate-50 flex justify-end">
                        <button type="button" @click="tambahKeAntrean()" class="px-12 py-5 bg-blue-600 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-blue-100 active:scale-95 transition flex items-center gap-4">
                            Tambah Ke Antrean <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>