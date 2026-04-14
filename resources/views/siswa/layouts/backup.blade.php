<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Siswa Dashboard - CodeLab')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .sidebar-active { background: #eff6ff; color: #2563eb; border-right: 4px solid #2563eb; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-slate-50" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <div x-show="sidebarOpen" 
         x-transition:enter="transition opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" x-cloak>
    </div>

    <aside 
        :class="{
            'translate-x-0': sidebarOpen, 
            '-translate-x-full': !sidebarOpen,
            'lg:w-72': !sidebarCollapsed,
            'lg:w-20': sidebarCollapsed,
            'lg:translate-x-0': true
        }"
        class="fixed top-0 left-0 z-50 h-screen bg-white border-r border-slate-200 sidebar-transition overflow-y-auto overflow-x-hidden shadow-2xl lg:shadow-none">
        
        <div class="p-6">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-xl shadow-lg shadow-blue-200 shrink-0">
                        <i class="fas fa-laptop-code text-white text-lg"></i>
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity class="leading-none">
                        <span class="font-extrabold text-xl tracking-tight text-slate-900 block">CodeLab</span>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Siswa Dashboard</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="space-y-1">
                <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Utama</p>
                
                <a href="/siswa/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/dashboard') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-columns w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Dashboard</span>
                </a>
                
                <a href="/siswa/materi" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/materi*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-book-open w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Ruang Materi</span>
                </a>

                <a href="/siswa/katalog" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/katalog*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-compass w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Katalog Proyek</span>
                </a>

                <a href="/siswa/proyek" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/proyek*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-briefcase w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Proyek Saya</span>
                </a>

                <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mt-8 mb-4">Akademik</p>

                <a href="/siswa/leaderboard" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/leaderboard*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-users w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Leaderboard</span>
                </a>

            </nav>
        </div>

        <div class="absolute bottom-0 w-full p-4 border-t border-slate-100 bg-white">
            <div x-show="!sidebarCollapsed" class="flex items-center gap-3 mb-4 px-2">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Siswa' }}&background=2563eb&color=fff" class="w-8 h-8 rounded-lg">
                <div class="leading-none">
                    <p class="text-xs font-bold text-slate-900 truncate w-32">{{ Auth::user()->name ?? 'Nama Siswa' }}</p>
                    <p class="text-[9px] text-blue-600 font-bold uppercase">Siswa Aktif</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="button" onclick="confirmLogout()" 
                    class="flex items-center gap-4 px-4 py-3 w-full rounded-xl font-bold text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-sign-out-alt w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-transition" :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-72'">
        
        <header class="sticky top-0 z-30 flex items-center justify-between h-20 px-6 bg-white/80 backdrop-blur-md border-b border-slate-100">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="p-2.5 rounded-xl bg-slate-100 text-slate-600 lg:hidden hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-bars-staggered text-xl"></i>
                </button>

                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl bg-slate-50 text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas" :class="sidebarCollapsed ? 'fa-indent text-xl' : 'fa-outdent text-xl'"></i>
                </button>

                <div class="hidden md:flex relative w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" placeholder="Cari materi..." 
                           class="w-full pl-10 pr-4 py-2 bg-slate-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button class="relative p-2 text-slate-400 hover:text-blue-600 transition">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="h-8 w-[1px] bg-slate-200 mx-2"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block" x-show="!sidebarCollapsed">
                        <p class="text-xs font-extrabold text-slate-900 leading-none mb-1">{{ Auth::user()->name ?? 'Naufal Albion' }}</p>
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-tighter">Siswa Aktif</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Naufal' }}&background=2563eb&color=fff" 
                         class="w-10 h-10 rounded-xl shadow-sm border border-slate-200 ring-2 ring-transparent hover:ring-blue-100 transition">
                </div>
            </div>
        </header>

        <main class="p-6 lg:p-10 flex-grow">
            @yield('content')
        </main>

        <footer class="p-6 text-center text-slate-400 text-[10px] font-medium border-t border-slate-100">
            &copy; 2026 CodeLab PjBL System. All Rights Reserved.
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                
                showConfirmButton: true,
                confirmButtonText: 'Oke, Mengerti',
                confirmButtonColor: '#2563eb', 

                background: '#ffffff',
                customClass: {
                    popup: 'rounded-[2rem]',
                    title: 'font-extrabold text-slate-900',
                    confirmButton: 'rounded-xl px-10 py-3 text-sm font-bold' // Styling tombol via Tailwind
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#2563eb',
            });
        @endif
    </script>
    
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Ingin Keluar?',
                text: "Sesi Anda akan berakhir dan Anda harus login kembali.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', // Biru CodeLab
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-6 py-3 font-bold',
                    cancelButton: 'rounded-xl px-6 py-3 font-bold text-slate-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-5 right-5 z-[100] max-w-sm w-full" x-cloak>
    
    @if(session('success'))
    <div class="bg-white border border-slate-100 rounded-2xl shadow-2xl p-4 flex items-center gap-4">
        <div class="bg-emerald-100 text-emerald-600 w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-check-circle text-xl"></i>
        </div>
        <div class="flex-grow">
            <p class="text-sm font-extrabold text-slate-900">Berhasil!</p>
            <p class="text-xs font-medium text-slate-500">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif
</div>
</html>