<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CodeLab - Web Simulation')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white text-slate-900" x-data="{ open: false }">

    <nav class="fixed w-full z-50 border-b border-slate-100 glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-4">
                    <button @click="open = true" class="md:hidden p-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600 p-2 rounded-xl shadow-lg shadow-blue-200">
                            <i class="fas fa-laptop-code text-white text-xl"></i>
                        </div>
                        <span class="font-extrabold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                            CodeLab
                        </span>
                    </div>
                </div>
                
                <div class="hidden md:flex space-x-10 font-semibold text-slate-600">
                    @if(Request::is('about'))
                        <a href="/" class="hover:text-blue-600 transition">Beranda</a>
                        <a href="/about" class="text-blue-600 transition">Tentang Kami</a>
                    @else
                        <a href="#hero" class="hover:text-blue-600 transition">Beranda</a>
                        <a href="#fitur" class="hover:text-blue-600 transition">Fitur</a>
                        <a href="#role" class="hover:text-blue-600 transition">Role</a>
                        <a href="/about" class="hover:text-blue-600 transition">Tentang Kami</a>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <a href="/login" class="hidden sm:block text-slate-600 font-bold hover:text-blue-600 transition">Masuk</a>
                    <a href="/register" class="bg-blue-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                        Daftar <span class="hidden sm:inline">Gratis</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div x-cloak>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             @click="open = false"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[60] md:hidden">
        </div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="-translate-x-full"
             class="fixed top-0 left-0 h-full w-80 bg-white z-[70] shadow-2xl p-6 md:hidden">
            
            <div class="flex justify-between items-center mb-10">
                <span class="font-bold text-xl text-blue-600 italic">Menu CodeLab</span>
                <button @click="open = false" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="flex flex-col space-y-2">
                @if(Request::is('about'))
                    <a href="/" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-blue-50 text-slate-600 transition font-semibold">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <a href="/about" class="flex items-center gap-4 p-4 rounded-2xl bg-blue-600 text-white font-semibold shadow-lg shadow-blue-100">
                        <i class="fas fa-info-circle"></i> Tentang Kami
                    </a>
                @else
                    <a href="#hero" @click="open = false" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-blue-50 text-slate-600 transition font-semibold">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <a href="#fitur" @click="open = false" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-blue-50 text-slate-600 transition font-semibold">
                        <i class="fas fa-th-large"></i> Fitur
                    </a>
                    <a href="#role" @click="open = false" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-blue-50 text-slate-600 transition font-semibold">
                        <i class="fas fa-users-cog"></i> Role
                    </a>
                    <a href="/about" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-blue-50 text-slate-600 transition font-semibold">
                        <i class="fas fa-info-circle"></i> Tentang Kami
                    </a>
                @endif
            </div>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="bg-slate-50 border-t border-slate-200 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-blue-600 p-2 rounded-lg text-white">
                            <i class="fas fa-laptop-code text-white text-xl"></i>
                        </div>
                        <span class="font-bold text-2xl tracking-tight">CodeLab</span>
                    </div>
                    <p class="text-slate-500 max-w-sm leading-relaxed">
                        Inovasi media pembelajaran untuk simulasi proyek industri bagi siswa SMK. Membangun kompetensi kolaborasi dan manajemen proyek secara digital.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Navigasi</h4>
                    <ul class="space-y-4 text-slate-600 font-medium">
                        <li><a href="#" class="hover:text-blue-600">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-blue-600">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-blue-600">Bantuan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Kontak</h4>
                    <p class="text-slate-600 font-medium mb-2">Naufal Albion Zhafran S.</p>
                    <p class="text-slate-500 text-sm">S2 Pendidikan Kejuruan</p>
                </div>
            </div>
            <div class="border-t border-slate-200 pt-8 flex flex-col md:row justify-between items-center gap-4">
                <p class="text-slate-400 text-sm font-medium">
                    &copy; 2026 CodeLab Web Simulation. All Rights Reserved.
                </p>
                <div class="flex gap-6 text-slate-400">
                    <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-github text-xl"></i></a>
                    <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-linkedin text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>