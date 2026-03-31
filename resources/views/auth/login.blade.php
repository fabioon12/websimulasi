<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke CodeLab - Web Simulation</title>
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
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 px-4 py-12">
        <div class="max-w-md w-full">
            
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-slate-100 p-8 lg:p-12 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full opacity-50"></div>
                
                <div class="relative z-10">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-6">
                            <i class="fas fa-laptop-code text-white text-3xl"></i>
                        </div>
                        <h2 class="text-3xl font-extrabold text-slate-900">Selamat Datang</h2>
                        <p class="text-slate-500 mt-2 font-medium">Silakan masuk ke akun CodeLab Anda</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        @if($errors->any())
                            <div class="bg-rose-50 border border-rose-100 text-rose-600 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="block w-full pl-11 pr-4 py-4 bg-slate-50 border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none"
                                    placeholder="nama@email.com">
                            </div>
                        </div>

                        <div x-data="{ show: false }">
                            <div class="flex justify-between mb-2 ml-1">
                                <label class="text-sm font-bold text-slate-700">Kata Sandi</label>
                                <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition">Lupa?</a>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                
                                <input :type="show ? 'text' : 'password'" name="password" required
                                    class="block w-full pl-11 pr-12 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none"
                                    placeholder="••••••••">

                                <button type="button" @click="show = !show" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-600 transition">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">Masuk Sebagai</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="siswa" class="peer hidden" 
                                        {{ old('role', 'siswa') == 'siswa' ? 'checked' : '' }}>
                                    <div class="text-center p-3 rounded-xl border border-slate-100 bg-slate-50 peer-checked:bg-blue-600 peer-checked:text-white transition shadow-sm">
                                        <i class="fas fa-user-graduate block mb-1"></i>
                                        <span class="text-[10px] font-bold uppercase">Siswa</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="guru" class="peer hidden" 
                                        {{ old('role') == 'guru' ? 'checked' : '' }}>
                                    <div class="text-center p-3 rounded-xl border border-slate-100 bg-slate-50 peer-checked:bg-blue-600 peer-checked:text-white transition shadow-sm">
                                        <i class="fas fa-chalkboard-teacher block mb-1"></i>
                                        <span class="text-[10px] font-bold uppercase">Guru</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="admin" class="peer hidden" 
                                        {{ old('role') == 'admin' ? 'checked' : '' }}>
                                    <div class="text-center p-3 rounded-xl border border-slate-100 bg-slate-50 peer-checked:bg-blue-600 peer-checked:text-white transition shadow-sm">
                                        <i class="fas fa-user-shield block mb-1"></i>
                                        <span class="text-[10px] font-bold uppercase">Admin</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" 
                            class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold text-lg hover:bg-blue-700 shadow-xl shadow-blue-100 transition transform active:scale-95 flex items-center justify-center gap-3">
                            Masuk Sekarang
                            <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </form>

                    <p class="text-center mt-10 text-slate-500 font-medium">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar di sini</a>
                    </p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="/" class="text-slate-400 hover:text-slate-600 transition font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-chevron-left text-xs"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>