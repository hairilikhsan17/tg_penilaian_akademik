<?php

namespace App\Http\Controllers;

use App\Models\KomponenPenilaian;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomponenPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $query = Matakuliah::with(['komponenPenilaian', 'dosen'])
            ->where('dosen_id', Auth::id());

        // Fitur pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_mk', 'like', '%' . $search . '%')
                  ->orWhere('nama_mk', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan status komponen penilaian
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'sudah') {
                $query->has('komponenPenilaian');
            } elseif ($request->status === 'belum') {
                $query->doesntHave('komponenPenilaian');
            }
        }

        $matakuliahs = $query->orderBy('semester')->orderBy('kode_mk')->paginate(15)->withQueryString();
        
        // Statistik
        $totalMatakuliah = Matakuliah::where('dosen_id', Auth::id())->count();
        $sudahAdaKomponen = Matakuliah::where('dosen_id', Auth::id())->has('komponenPenilaian')->count();
        $belumAdaKomponen = $totalMatakuliah - $sudahAdaKomponen;

        return view('dosen.komponen-penilaian.index', compact('matakuliahs', 'totalMatakuliah', 'sudahAdaKomponen', 'belumAdaKomponen'));
    }

    public function create(Matakuliah $matakuliah)
    {
        // Pastikan hanya dosen pemilik yang bisa akses
        if ($matakuliah->dosen_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses komponen penilaian ini.');
        }

        $komponen = $matakuliah->komponenPenilaian;
        
        // Set default values jika belum ada (hanya untuk pre-fill form, bisa diubah oleh dosen)
        if (!$komponen) {
            $komponen = (object)[
                'kehadiran' => null,
                'tugas' => null,
                'kuis' => null,
                'project' => null,
                'uts' => null,
                'uas' => null,
            ];
        }

        return view('dosen.matakuliahs.komponen', compact('matakuliah', 'komponen'));
    }

    public function storeOrUpdate(Request $request, Matakuliah $matakuliah)
    {
        // Pastikan hanya dosen pemilik yang bisa update
        if ($matakuliah->dosen_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah komponen penilaian ini.');
        }

        $data = $request->validate([
            'kehadiran' => 'required|integer|min:0|max:100',
            'tugas' => 'required|integer|min:0|max:100',
            'kuis' => 'required|integer|min:0|max:100',
            'project' => 'required|integer|min:0|max:100',
            'uts' => 'required|integer|min:0|max:100',
            'uas' => 'required|integer|min:0|max:100',
        ]);

        $total = $data['kehadiran'] + $data['tugas'] + $data['kuis'] + $data['project'] + $data['uts'] + $data['uas'];
        
        if ($total !== 100) {
            return back()->withInput()->withErrors(['total' => 'Total bobot harus tepat 100%. Saat ini: ' . $total . '%']);
        }

        $payload = array_merge($data, [
            'matakuliah_id' => $matakuliah->id,
            'total' => $total,
        ]);

        $komponen = $matakuliah->komponenPenilaian;
        if ($komponen) {
            $komponen->update($payload);
            $message = 'Komponen penilaian berhasil diperbarui.';
        } else {
            KomponenPenilaian::create($payload);
            $message = 'Komponen penilaian berhasil disimpan.';
        }

        return redirect()->route('komponen.index')->with('success', $message);
    }
}


