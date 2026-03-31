<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - CodeLab')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .sidebar-active { background: #f8fafc; color: #4f46e5; border-right: 4px solid #4f46e5; }
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
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden" x-cloak>
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
                    <div class="bg-slate-900 p-2 rounded-xl shadow-lg shrink-0">
                        <i class="fas fa-shield-halved text-white text-lg"></i>
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity class="leading-none">
                        <span class="font-extrabold text-xl tracking-tight text-slate-900 block">CodeLab</span>
                        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Admin Panel</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="space-y-1">
                <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Main Menu</p>
                
                <a href="/superadmin/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('superadmin/dashboard') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-home w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Dashboard</span>
                </a>
                
                <a href="/superadmin/users" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('superadmin/users*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-user-group w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Manajemen User</span>
                </a>

                <a href="/superadmin/proyek" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('superadmin/proyek*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-folder-tree w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Daftar Proyek</span>
                </a>

                <a href="/superadmin/materi" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('superadmin/materi*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-layer-group w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Kelola Materi</span>
                </a>

                <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mt-8 mb-4">Sistem</p>

                <a href="/superadmin/laporan" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('superadmin/laporan*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-chart-line w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Laporan</span>
                </a>

                <a href="/superadmin/settings" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('superadmin/settings*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-sliders w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Konfigurasi</span>
                </a>
            </nav>
        </div>

        <div class="absolute bottom-0 w-full p-4 border-t border-slate-100 bg-white">
            <div x-show="!sidebarCollapsed" class="flex items-center gap-3 mb-4 px-2">
                <img src="https://ui-avatars.com/api/?name=Admin&background=0f172a&color=fff" class="w-8 h-8 rounded-lg">
                <div class="leading-none">
                    <p class="text-xs font-bold text-slate-900">Administrator</p>
                    <p class="text-[9px] text-slate-400 uppercase">Super Admin</p>
                </div>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-4 px-4 py-3 w-full rounded-xl font-bold text-rose-500 hover:bg-rose-50 transition">
                    <i class="fas fa-power-off w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-transition" :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-72'">
        
        <header class="sticky top-0 z-30 flex items-center justify-between h-20 px-8 bg-white/90 backdrop-blur-xl border-b border-slate-100">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600">
                    <i class="fas fa-bars-staggered"></i>
                </button>

                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                    <i class="fas" :class="sidebarCollapsed ? 'fa-indent' : 'fa-outdent'"></i>
                </button>

                <h2 class="hidden md:block text-sm font-bold text-slate-400 italic">PjBL Management System v1.0</h2>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center bg-slate-100 px-4 py-2 rounded-2xl gap-3 mr-4">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-500 uppercase">Server Online</span>
                </div>
                <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center justify-center border border-slate-100">
                    <i class="fas fa-search text-sm"></i>
                </button>
                <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center justify-center border border-slate-100 relative">
                    <i class="fas fa-cog text-sm"></i>
                </button>
            </div>
        </header>

        <main class="p-8 lg:p-12 min-h-[calc(100vh-80px)]">
            @yield('content')
        </main>

        <footer class="px-12 py-6 text-slate-400 text-[10px] font-bold uppercase tracking-widest flex justify-between border-t border-slate-100 bg-white">
            <span>CodeLab Admin System</span>
            <span>&copy; 2026 CodeLab PjBL</span>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>