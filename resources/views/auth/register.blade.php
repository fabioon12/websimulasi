<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar CodeLab - Web Simulation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 px-4 py-20">
        <div class="max-w-2xl w-full">
            
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-slate-100 p-8 lg:p-12 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full opacity-50"></div>
                
                <div class="relative z-10">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-6">
                            <i class="fas fa-user-plus text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-extrabold text-slate-900">Buat Akun Baru</h2>
                        <p class="text-slate-500 mt-2 font-medium">Bergabung dengan ekosistem simulasi proyek SMK</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST" class="space-y-5" x-data="{ role: '{{ old('role', 'siswa') }}' }">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-rose-50 border border-rose-100 text-rose-600 px-6 py-4 rounded-[1.5rem] text-xs font-bold shadow-sm">
                                <p class="mb-2 uppercase tracking-widest text-[10px] opacity-70">Terjadi Kesalahan:</p>
                                <ul class="list-disc list-inside space-y-1 font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 ml-1">Daftar Sebagai</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="siswa" x-model="role" class="peer hidden">
                                    <div class="text-center p-4 rounded-2xl border border-slate-100 bg-slate-50 peer-checked:bg-blue-600 peer-checked:text-white transition shadow-sm">
                                        <i class="fas fa-user-graduate block mb-1 text-xl"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider">Siswa</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="guru" x-model="role" class="peer hidden">
                                    <div class="text-center p-4 rounded-2xl border border-slate-100 bg-slate-50 peer-checked:bg-blue-600 peer-checked:text-white transition shadow-sm">
                                        <i class="fas fa-chalkboard-teacher block mb-1 text-xl"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider">Guru</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div :class="role === 'guru' ? 'grid grid-cols-1 md:grid-cols-2 gap-5' : 'w-full'">
                            
                            <div :class="role === 'siswa' ? 'w-full' : ''">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="block w-full px-5 py-4 bg-slate-50 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                                    placeholder="Masukkan nama lengkap">
                            </div>

                            <div x-show="role === 'guru'" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                x-cloak>
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">NIP / ID Guru</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number') }}" 
                                    :required="role === 'guru'"
                                    class="block w-full px-5 py-4 bg-slate-50 border @error('identity_number') border-rose-500 @else border-slate-200 @enderror rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                                    placeholder="Masukkan NIP resmi Anda">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="block w-full px-5 py-4 bg-slate-50 border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                                placeholder="nama@email.com">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5" x-show="role === 'siswa'" x-transition.duration.300ms x-cloak>
                            <div class="relative">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kelas</label>
                                <select name="class" 
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition appearance-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Kelas</option>
                                    <option value="X" {{ old('class') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                    <option value="XI" {{ old('class') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                    <option value="XII" {{ old('class') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-[60px] text-slate-400 text-xs pointer-events-none"></i>
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Jurusan</label>
                                <select name="major" 
                                    class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition appearance-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Jurusan</option>
                                    <option value="RPL" {{ old('major') == 'RPL' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                                    <option value="TKJ" {{ old('major') == 'TKJ' ? 'selected' : '' }}>Teknik Komputer Jaringan</option>
                                    <option value="DKV" {{ old('major') == 'DKV' ? 'selected' : '' }}>Desain Komunikasi Visual</option>
                                    <option value="MM" {{ old('major') == 'MM' ? 'selected' : '' }}>Multimedia</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-[60px] text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5" x-data="{ showPass: false, showConfirm: false }">
                            <div class="relative">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Kata Sandi</label>
                                <div class="relative group">
                                    <input :type="showPass ? 'text' : 'password'" name="password" required
                                        class="block w-full px-5 py-4 bg-slate-50 border @error('password') border-rose-500 @else border-slate-200 @enderror rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition pr-14"
                                        placeholder="••••••••">
                                    
                                    <button type="button" @click="showPass = !showPass" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-slate-400 hover:text-blue-600 transition focus:outline-none">
                                        <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Konfirmasi Sandi</label>
                                <div class="relative group">
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                                        class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition pr-14"
                                        placeholder="••••••••">
                                    
                                    <button type="button" @click="showConfirm = !showConfirm" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-slate-400 hover:text-blue-600 transition focus:outline-none">
                                        <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 px-1 py-2">
                            <input type="checkbox" name="terms" required class="mt-1.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Saya setuju dengan <a href="#" class="text-blue-600 font-bold hover:underline">Ketentuan Layanan</a> dan kebijakan privasi CodeLab.
                            </p>
                        </div>

                        <button type="submit" 
                            class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-lg hover:bg-slate-800 shadow-xl transition transform active:scale-95 flex items-center justify-center gap-3 mt-4">
                            Buat Akun Sekarang
                            <i class="fas fa-rocket text-sm text-blue-400"></i>
                        </button>
                    </form>

                    <p class="text-center mt-10 text-slate-500 font-medium">
                        Sudah punya akun? 
                        <a href="/login" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>