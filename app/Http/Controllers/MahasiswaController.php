<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        // Fitur pencarian berdasarkan NIM atau nama
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $mahasiswas = $query->orderBy('nim')->paginate(15)->withQueryString();
        
        return view('dosen.mahasiswas.index', compact('mahasiswas'));
    }

    public function create()
    {
        return view('dosen.mahasiswas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|string|max:50|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
            'jurusan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        // Buat user account untuk mahasiswa jika belum ada
        $user = User::create([
            'name' => $data['nama'],
            'email' => $data['email'] ?? $data['nim'] . '@example.com',
            'password' => Hash::make('password123'), // Password default, bisa diubah nanti
            'role' => 'mahasiswa',
        ]);

        // Tambahkan user_id ke data mahasiswa
        $data['user_id'] = $user->id;

        Mahasiswa::create($data);

        return redirect()->route('mahasiswas.index')->with('success', 'Data mahasiswa berhasil ditambahkan. Pastikan untuk mengatur password agar mahasiswa bisa login.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('dosen.mahasiswas.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nim' => 'required|string|max:50|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
            'jurusan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        // Update data mahasiswa
        $mahasiswa->update($data);

        // Update nama di tabel users juga agar tampil di sidebar dan navbar
        if ($mahasiswa->user) {
            $mahasiswa->user->update([
                'name' => $data['nama']
            ]);
        }

        return redirect()->route('mahasiswas.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $nama = $mahasiswa->nama;
        
        // Hapus user account jika ada (cascade akan menghapus otomatis, tapi kita pastikan)
        if ($mahasiswa->user) {
            $mahasiswa->user->delete();
        }
        
        $mahasiswa->delete();
        return redirect()->route('mahasiswas.index')->with('success', 'Data mahasiswa ' . $nama . ' berhasil dihapus.');
    }

    public function showPassword(Mahasiswa $mahasiswa)
    {
        // Pastikan mahasiswa punya user account
        if (!$mahasiswa->user) {
            // Buat user account jika belum ada
            $user = User::create([
                'name' => $mahasiswa->nama,
                'email' => $mahasiswa->email ?? $mahasiswa->nim . '@example.com',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]);
            $mahasiswa->update(['user_id' => $user->id]);
            $mahasiswa->refresh();
        }

        return view('dosen.mahasiswas.password', compact('mahasiswa'));
    }

    public function updatePassword(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Pastikan mahasiswa punya user account
        if (!$mahasiswa->user) {
            $user = User::create([
                'name' => $mahasiswa->nama,
                'email' => $mahasiswa->email ?? $mahasiswa->nim . '@example.com',
                'password' => Hash::make($validated['password']),
                'role' => 'mahasiswa',
            ]);
            $mahasiswa->update(['user_id' => $user->id]);
        } else {
            // Update password user yang sudah ada
            $mahasiswa->user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('mahasiswas.index')->with('success', 'Password mahasiswa ' . $mahasiswa->nama . ' berhasil diatur.');
    }
}


