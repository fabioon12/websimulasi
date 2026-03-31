<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    protected function redirectByUserRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Admin!');
        } elseif ($user->role === 'guru') {
            return redirect()->route('guru.dashboard')->with('success', 'Selamat Datang Guru!');
        } elseif ($user->role === 'siswa') {
            return redirect()->route('siswa.dashboard')->with('success', 'Selamat Datang Siswa!');
        }

        return redirect('/')->with('error', 'Role tidak dikenali.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:siswa,guru,admin'],
            'identity_number' => ['required_if:role,guru', 'nullable', 'string', 'unique:users'],
            'class' => ['required_if:role,siswa', 'nullable', 'string'],
            'major' => ['required_if:role,siswa', 'nullable', 'string'],
            'terms' => ['required'],
        ], [
            'identity_number.required_if' => 'NIP wajib diisi untuk pendaftaran guru.',
            'class.required_if' => 'Kelas wajib dipilih untuk siswa.',
            'major.required_if' => 'Jurusan wajib dipilih untuk siswa.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'identity_number' => $request->role === 'guru' ? $request->identity_number : null,
            'class' => $request->role === 'siswa' ? $request->class : null,
            'major' => $request->role === 'siswa' ? $request->major : null,
        ]);

        Auth::login($user);

        return $this->redirectByUserRole($user);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required'], 
        ]);

        // Login dengan mencocokkan email, password, dan role sekaligus
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'role' => $credentials['role']])) {
            $request->session()->regenerate();

            return $this->redirectByUserRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email, password, atau role yang dipilih tidak cocok.',
        ])->onlyInput('email', 'role');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
