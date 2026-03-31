@extends('layouts.app')

@section('title', 'CodeLab - Simulasi PjBL Masa Depan SMK')

@section('content')
    <section id="hero" class="pt-32 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2 space-y-8">
                    <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 px-4 py-2 rounded-full">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                        </span>
                        <span class="text-blue-700 text-sm font-bold">Inovasi Pembelajaran Digital</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-[1.1]">
                        Simulasi <span class="text-blue-600">Proyek Industri</span> Dalam Genggaman.
                    </h1>
                    
                    <p class="text-lg text-slate-500 leading-relaxed max-w-lg">
                        CodeLab menghadirkan pengalaman belajar berbasis proyek (PjBL) yang interaktif, membantu siswa SMK menguasai alur kerja profesional sebelum terjun ke dunia kerja.
                    </p>

                    <div class="flex flex-col sm:row gap-4">
                        <a href="/register" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-xl shadow-blue-200 text-center">
                            Mulai Simulasi Sekarang
                        </a>
                        <a href="#fitur" class="px-8 py-4 rounded-2xl font-bold text-slate-600 hover:bg-slate-100 transition border border-slate-200 text-center">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                <div class="md:w-1/2 relative">
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                    <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                    
                    <div class="relative bg-white p-4 rounded-[2.5rem] shadow-2xl border border-slate-100">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" 
                             alt="Student Collaboration" 
                             class="rounded-[2rem] object-cover w-full h-[400px]">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Fitur Utama CodeLab</h2>
                <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-[2rem] border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-8">
                        <i class="fas fa-layer-group text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Simulasi Workflow</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Alur kerja proyek yang disesuaikan dengan standar industri (Agile & Kanban) untuk membiasakan siswa dengan manajemen waktu.</p>
                </div>

                <div class="bg-white p-10 rounded-[2rem] border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-8">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Kolaborasi Real-time</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Fitur koordinasi antar anggota tim dalam satu dashboard, memudahkan pembagian tugas dan komunikasi internal.</p>
                </div>

                <div class="bg-white p-10 rounded-[2rem] border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-8">
                        <i class="fas fa-award text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Portofolio Digital</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Hasil proyek terekam secara sistematis, menjadi modal kuat bagi siswa saat melamar pekerjaan di industri.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="role" class="py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="md:w-1/2">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-8">Satu Platform, Berbagai Peran.</h2>
                    
                    <div class="space-y-6">
                        <div class="flex gap-6 p-6 rounded-2xl hover:bg-slate-50 transition">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900">Sebagai Siswa</h4>
                                <p class="text-slate-500 mt-1">Belajar mengelola proyek, bekerja dalam tim, dan menghasilkan produk nyata dari tantangan yang diberikan.</p>
                            </div>
                        </div>

                        <div class="flex gap-6 p-6 rounded-2xl hover:bg-slate-50 transition">
                            <div class="flex-shrink-0 w-12 h-12 bg-slate-900 rounded-full flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900">Sebagai Guru / Mentor</h4>
                                <p class="text-slate-500 mt-1">Memantau progres kelompok secara objektif, memberikan feedback langsung, dan menilai efektivitas kerja siswa.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-1/2 grid grid-cols-2 gap-4">
                    <div class="space-y-4 pt-12">
                        <div class="h-64 bg-blue-50 rounded-3xl flex items-end p-6">
                             <div class="w-full h-1/2 bg-blue-600/10 rounded-xl animate-pulse"></div>
                        </div>
                        <div class="h-40 bg-slate-100 rounded-3xl"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-40 bg-indigo-50 rounded-3xl"></div>
                        <div class="h-64 bg-slate-900 rounded-3xl p-6 flex flex-col justify-between">
                            <i class="fas fa-quote-left text-white/20 text-4xl"></i>
                            <p class="text-white font-medium italic text-sm text-center">"Belajar lebih bermakna dengan praktek nyata."</p>
                            <i class="fas fa-quote-right text-white/20 text-4xl self-end"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-24 px-4">
        <div class="max-w-5xl mx-auto bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[3rem] p-10 md:p-20 text-center text-white shadow-2xl shadow-blue-200 relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-3xl md:text-5xl font-extrabold mb-6">Siap Mencetak Lulusan SMK Unggulan?</h2>
                <p class="text-blue-100 text-lg mb-10 max-w-2xl mx-auto">Bergabunglah dengan ribuan siswa lainnya dan mulai pengalaman simulasi industri yang mendalam.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="/register" class="bg-white text-blue-600 px-10 py-4 rounded-2xl font-bold hover:bg-slate-50 transition">Daftar Sekarang</a>
                    <a href="#" class="bg-blue-500/30 backdrop-blur-md text-white border border-blue-400/50 px-10 py-4 rounded-2xl font-bold hover:bg-blue-500/40 transition">Jadwalkan Demo</a>
                </div>
            </div>
        </div>
    </section>
@endsection