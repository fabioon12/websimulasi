@extends('layouts.app')

@section('title', 'Tentang Pengembang - CodeLab')

@section('content')
    <section class="pt-32 pb-16 bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 mb-4">Profil Pengembang</h1>
            <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="w-64 h-64 flex-shrink-0 relative">
                    <div class="absolute inset-0 bg-blue-600 rounded-[3rem] rotate-6"></div>
                    <div class="absolute inset-0 bg-slate-200 rounded-[3rem] overflow-hidden border-4 border-white shadow-xl">
                        <img src="https://ui-avatars.com/api/?name=Naufal+Albion&size=512&background=0D8ABC&color=fff" alt="Naufal Albion Zhafran Sentanu" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Naufal Albion Zhafran Sentanu</h2>
                    <p class="text-blue-600 font-semibold text-lg mb-6">Mahasiswa S2 Pendidikan Kejuruan</p>
                    
                    <div class="space-y-4 text-slate-600 leading-relaxed">
                        <p>
                            Halo! Saya Naufal, seorang akademisi dan pengembang di bidang pendidikan kejuruan. Fokus utama saya adalah mengintegrasikan teknologi industri ke dalam kurikulum sekolah menengah untuk menciptakan lulusan yang siap kerja.
                        </p>
                        <p>
                            Proyek <strong>CodeLab Web Simulation</strong> ini merupakan buah dari observasi mendalam saya di SMK Negeri 8 Malang. Saya percaya bahwa keterbatasan perangkat keras tidak boleh menjadi penghalang bagi siswa untuk belajar pemrograman secara profesional dan kolaboratif.
                        </p>
                    </div>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <i class="fas fa-id-card text-blue-600"></i>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">NIM</p>
                                <p class="font-bold text-slate-800">250551829806</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <i class="fas fa-university text-blue-600"></i>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">Konsentrasi</p>
                                <p class="font-bold text-slate-800">Media Pembelajaran Inovatif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-blue-600 rounded-[3rem] p-10 lg:p-16 text-white relative overflow-hidden">
                <i class="fas fa-quote-right absolute top-10 right-10 text-8xl opacity-10"></i>
                <div class="relative z-10 max-w-3xl">
                    <h3 class="text-2xl lg:text-3xl font-bold mb-6 italic">"Visi saya adalah mendemokratisasi pendidikan teknologi, di mana setiap siswa memiliki kesempatan yang sama untuk merasakan simulasi industri, terlepas dari apa pun perangkat yang mereka gunakan."</h3>
                    <p class="font-medium text-blue-100">— Naufal Albion Z S</p>
                </div>
            </div>
        </div>
    </section>
@endsection