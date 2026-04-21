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
        
        /* Custom Scrollbar untuk Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: #cbd5e1; }
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
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" x-cloak>
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
        class="fixed top-0 left-0 z-50 h-screen bg-white border-r border-slate-200 sidebar-transition flex flex-col shadow-2xl lg:shadow-none">
        
        <div class="p-6 shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-xl shadow-lg shadow-blue-200 shrink-0">
                        <i class="fas fa-laptop-code text-white text-lg"></i>
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity class="leading-none">
                        <span class="font-extrabold text-xl tracking-tight text-slate-900 block">CodeLab</span>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Student Hub</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto sidebar-scroll px-6 pb-24">
            <nav class="space-y-1" x-data="{ openWorkspace: {{ Request::is('siswa/workspace*', 'siswa/logbook*') ? 'true' : 'false' }} }">
                <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Utama</p>
                
                {{-- Dashboard --}}
                <a href="/siswa/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/dashboard') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-house-chimney w-5 shrink-0 text-center text-lg"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Beranda</span>
                </a>
                <a href="/siswa/materi" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/materi*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-book-open w-5 shrink-0 text-center"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Ruang Materi</span>
                </a>
                {{-- Katalog --}}
                <a href="/siswa/katalog" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/katalog*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-compass w-5 shrink-0 text-center text-lg"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Katalog Ide</span>
                </a>
                {{-- My Projects (Halaman Daftar Proyek & Proposal yang di-ACC) --}}
                <a href="/siswa/proyek" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/proyek*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-folder-open w-5 shrink-0 text-center text-lg"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Proyek Saya</span>
                </a>
                {{-- Undangan Kolaborasi --}}
                @php
                    // Tambahkan filter where('is_leader', false) 
                    // agar sistem tidak menghitung proyek yang dibuat oleh user itu sendiri
                    $invitationCount = \App\Models\ProposalMember::where('user_id', auth()->id())
                                        ->where('status_konfirmasi', 'pending')
                                        ->where('is_leader', false) 
                                        ->count();
                @endphp
                <a href="{{ route('siswa.invitation.index') }}" 
                    class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/invitation*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    
                    <div class="relative">
                        <i class="fas fa-envelope-open-text w-5 shrink-0 text-center text-lg"></i>
                        
                        {{-- Dot Notif saat Sidebar Terlipat --}}
                        @if($invitationCount > 0)
                            <template x-if="sidebarCollapsed">
                                <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                </span>
                            </template>
                        @endif
                    </div>

                    <span x-show="!sidebarCollapsed" x-transition.opacity>Undangan</span>

                    {{-- Badge Angka saat Sidebar Terbuka --}}
                    @if($invitationCount > 0)
                        <div x-show="!sidebarCollapsed" x-transition.opacity class="ml-auto">
                            <span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full font-black animate-pulse shadow-sm">
                                {{ $invitationCount }}
                            </span>
                        </div>
                    @endif
                </a>
                <p x-show="!sidebarCollapsed" class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mt-8 mb-2">Manajemen Proyek</p>

                {{-- Dropdown Workspace (Hanya muncul jika ada proyek aktif) --}}
                <a href="{{ route('siswa.workspace.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/workspace*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-rocket w-5 shrink-0 text-center text-lg transition-transform group-hover:scale-110 group-hover:-rotate-12"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Workspace</span>
                </a>

                <a href="/siswa/leaderboard" class="flex items-center gap-4 px-4 py-3 rounded-xl font-bold transition {{ Request::is('siswa/leaderboard*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <i class="fas fa-trophy w-5 shrink-0 text-center text-lg"></i>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Leaderboard</span>
                </a>
            </nav>
        </div>

        <div class="shrink-0 p-4 border-t border-slate-100 bg-white">
            <div x-show="!sidebarCollapsed" class="flex items-center gap-3 mb-4 px-2">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=2563eb&color=fff" class="w-10 h-10 rounded-xl shadow-md border-2 border-slate-50">
                <div class="leading-none overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name ?? 'Siswa CodeLab' }}</p>
                    <p class="text-[9px] text-blue-600 font-black uppercase mt-1">Student</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
            <button type="button" onclick="confirmLogout()" class="flex items-center gap-4 px-4 py-3 w-full rounded-xl font-bold text-slate-400 hover:text-red-500 hover:bg-red-50 transition group">
                <i class="fas fa-arrow-right-from-bracket w-5 shrink-0 text-center group-hover:translate-x-1 transition-transform"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity>Keluar</span>
            </button>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="sidebar-transition min-h-screen flex flex-col" :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-72'">
        
        <header class="sticky top-0 z-30 flex items-center justify-between h-20 px-8 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-blue-50 text-blue-600">
                    <i class="fas fa-bars-staggered"></i>
                </button>

                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2.5 rounded-xl bg-slate-50 text-slate-500 hover:bg-blue-600 hover:text-white transition-all">
                    <i class="fas" :class="sidebarCollapsed ? 'fa-indent' : 'fa-outdent'"></i>
                </button>

                <div class="hidden md:block">
                    <h2 class="text-sm font-bold text-slate-900 tracking-tight">Project Management</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tahun Pelajaran 2025/2026</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 relative cursor-pointer hover:bg-blue-100 transition">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[8px] font-bold flex items-center justify-center rounded-full border-2 border-white">2</span>
                </div>
            </div>
        </header>

        <main class="p-8 lg:p-12 flex-grow">
            @yield('content')
        </main>

        <footer class="px-12 py-6 text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] flex justify-between border-t border-slate-100 bg-white">
            <span>&copy; 2026 CodeLab PjBL System</span>
            <span class="text-blue-500 italic">Siswa Portal</span>
        </footer>
    </div>

    {{-- SweetAlert & Logout Script --}}
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
</html>