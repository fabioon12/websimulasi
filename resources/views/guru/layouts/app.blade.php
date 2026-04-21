<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guru Panel - CodeLab')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .sidebar-active { background: #f0fdf4; color: #059669; border-right: 4px solid #059669; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-slate-50" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-emerald-900/40 backdrop-blur-sm lg:hidden" x-cloak>
    </div>

    {{-- Sidebar --}}
    <aside 
        :class="{
            'translate-x-0': sidebarOpen, 
            '-translate-x-full': !sidebarOpen,
            'lg:w-72': !sidebarCollapsed,
            'lg:w-20': sidebarCollapsed,
            'lg:translate-x-0': true
        }"
        class="fixed top-0 left-0 z-50 h-screen bg-white border-r border-slate-200 sidebar-transition flex flex-col">
        
        {{-- 1. Header Sidebar (Tetap) --}}
        <div class="p-6 shrink-0">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-600 p-2 rounded-xl shadow-lg shadow-emerald-100 shrink-0">
                        <i class="fas fa-chalkboard-user text-white text-lg"></i>
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity class="leading-none">
                        <span class="font-extrabold text-xl tracking-tight text-slate-900 block">CodeLab</span>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Teacher Space</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        {{-- 2. Area Navigasi (Bisa di-scroll) --}}
        <div class="flex-grow overflow-y-auto overflow-x-hidden custom-scrollbar px-6">
            <nav class="space-y-1" x-data="{ openMonitoring: {{ Request::is('guru/workspace*', 'guru/logbook*') ? 'true' : 'false' }} }">
                <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Utama</p>
                
                <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('guru/dashboard') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-house-chimney-window w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Beranda Guru</span>
                </a>

                <a href="{{ route('guru.materi.dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('guru/materi*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-book w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Materi Saya</span>
                </a>

                <a href="{{ route('guru.proyek.dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('guru/proyek/*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-project-diagram w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Manajemen Proyek</span>
                </a>

                <p x-show="!sidebarCollapsed" class="px-4 mt-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Manajemen Proyek</p>

                {{-- Menu Persetujuan --}}
               <a href="{{ route('guru.proposal.index') }}" 
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('guru/proposal*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                
                <div class="relative">
                    <i class="fas fa-file-signature w-5 shrink-0 text-center"></i>
                    
                    {{-- Dot Notif Tetap Ada --}}
                    <template x-if="sidebarCollapsed && {{ $pendingCount ?? 0 }} > 0">
                        <span class="absolute -top-1 -right-1 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                        </span>
                    </template>
                </div>

                <span x-show="!sidebarCollapsed" x-transition.opacity>Persetujuan</span>

                @if(isset($pendingCount) && $pendingCount > 0)
                    <div x-show="!sidebarCollapsed" x-transition.opacity class="ml-auto">
                        <span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full font-black shadow-lg">
                            {{ $pendingCount }}
                        </span>
                    </div>
                @endif
            </a>

            {{-- 2. Workspace --}}
            <a href="{{ route('guru.workspace.index') }}" 
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('guru/workspace*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                <i class="fas fa-rocket w-5 shrink-0 text-center"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity>Workspace</span>
            </a>

            {{-- 3. Leaderboard --}}
            <a href="{{ route('guru.leaderboard.index') }}" 
                class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('guru/leaderboard*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                <i class="fas fa-trophy w-5 shrink-0 text-center"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity>Leaderboard</span>
            </a>
                
                {{-- Tambahan Spacer agar tidak mentok ke footer saat scroll maksimal --}}
                <div class="h-10"></div>
            </nav>
        </div>

        {{-- 3. Footer Sidebar (Tetap di bawah) --}}
        <div class="shrink-0 p-4 border-t border-slate-100 bg-white">
            <div x-show="!sidebarCollapsed" class="flex items-center gap-3 mb-4 px-2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=059669&color=fff" class="w-10 h-10 rounded-xl">
                <div class="leading-none text-left">
                    <p class="text-xs font-bold text-slate-900 truncate w-32">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] text-emerald-600 font-bold uppercase mt-1">Mentor Utama</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
            <button type="button" onclick="confirmLogout()" class="flex items-center gap-4 px-4 py-3 w-full rounded-xl font-bold text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition group">
                <i class="fas fa-arrow-right-from-bracket w-5 shrink-0 text-center"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity>Keluar Portal</span>
            </button>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="sidebar-transition" :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-72'">
        
        <header class="sticky top-0 z-30 flex items-center justify-between h-20 px-8 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-emerald-50 text-emerald-600">
                    <i class="fas fa-bars-staggered"></i>
                </button>

                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl bg-slate-50 text-slate-500 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                    <i class="fas" :class="sidebarCollapsed ? 'fa-indent' : 'fa-outdent'"></i>
                </button>

                <div class="hidden md:block">
                    <h2 class="text-sm font-bold text-slate-900">Dashboard Pembimbing</h2>
                    <p class="text-[10px] text-slate-400 font-medium italic uppercase tracking-wider">Tahun Pelajaran 2025/2026</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 relative">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[8px] font-bold flex items-center justify-center rounded-full border-2 border-white">3</span>
                </div>
            </div>
        </header>

        <main class="p-8 lg:p-12 min-h-[calc(100vh-80px)]">
            @yield('content')
        </main>

        <footer class="px-12 py-6 text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] flex justify-between border-t border-slate-100 bg-white">
            <span>&copy; 2026 CodeLab PjBL System</span>
            <span class="text-emerald-500 italic uppercase">Guru Portal</span>
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
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                });
            </script>
        @endif
        function confirmLogout() {
            Swal.fire({
                title: 'Ingin Keluar?',
                text: "Sesi Anda akan berakhir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logout-form').submit();
            });
        }
    </script>
    @stack('scripts')
</body>
</html>