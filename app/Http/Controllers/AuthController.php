<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            if ($role === 'dosen') {
                return redirect()->intended(route('dosen.dashboard'));
            }
            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:mahasiswa,dosen'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // Validasi tambahan untuk mahasiswa
        if ($request->role === 'mahasiswa') {
            $rules['nim'] = ['required', 'string', 'max:50', 'unique:mahasiswas,nim'];
            $rules['semester'] = ['required', 'integer', 'min:1', 'max:14'];
            $rules['jurusan'] = ['required', 'string', 'max:255'];
        }

        // Validasi tambahan untuk dosen
        if ($request->role === 'dosen') {
            $rules['nip'] = ['required', 'string', 'max:50', 'unique:dosens,nip'];
        }

        $validated = $request->validate($rules);

        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // hashed by cast
            'role' => $validated['role'],
        ]);

        // Buat data mahasiswa atau dosen berdasarkan role
        if ($validated['role'] === 'mahasiswa') {
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $validated['nim'],
                'nama' => $validated['name'],
                'semester' => $validated['semester'],
                'jurusan' => $validated['jurusan'],
                'email' => $validated['email'],
            ]);
        } elseif ($validated['role'] === 'dosen') {
            Dosen::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'nama' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}








