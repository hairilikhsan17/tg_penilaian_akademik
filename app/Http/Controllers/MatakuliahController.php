<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = Matakuliah::with('dosen');

        // Filter berdasarkan dosen yang login (hanya tampilkan mata kuliah dosen tersebut)
        $query->where('dosen_id', Auth::id());

        // Fitur pencarian berdasarkan kode atau nama mata kuliah
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_mk', 'like', '%' . $search . '%')
                  ->orWhere('nama_mk', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan semester
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        $matakuliahs = $query->orderBy('semester')->orderBy('kode_mk')->paginate(15)->withQueryString();
        
        return view('dosen.matakuliahs.index', compact('matakuliahs'));
    }

    public function create()
    {
        return view('dosen.matakuliahs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_mk' => 'required|string|max:50|unique:matakuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        // Set dosen_id dari user yang login
        $data['dosen_id'] = Auth::id();

        Matakuliah::create($data);

        return redirect()->route('matakuliahs.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(Matakuliah $matakuliah)
    {
        return view('dosen.matakuliahs.edit', compact('matakuliah'));
    }

    public function update(Request $request, Matakuliah $matakuliah)
    {
        // Pastikan hanya dosen pemilik yang bisa update
        if ($matakuliah->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit mata kuliah ini.');
        }

        $data = $request->validate([
            'kode_mk' => 'required|string|max:50|unique:matakuliahs,kode_mk,' . $matakuliah->id,
            'nama_mk' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $matakuliah->update($data);

        return redirect()->route('matakuliahs.index')->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function destroy(Matakuliah $matakuliah)
    {
        // Pastikan hanya dosen pemilik yang bisa hapus
        if ($matakuliah->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus mata kuliah ini.');
        }

        $nama = $matakuliah->nama_mk;
        $matakuliah->delete();
        
        return redirect()->route('matakuliahs.index')->with('success', 'Mata kuliah ' . $nama . ' berhasil dihapus.');
    }
}


