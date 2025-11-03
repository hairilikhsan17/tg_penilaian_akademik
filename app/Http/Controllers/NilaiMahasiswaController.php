<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\NilaiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiMahasiswaController extends Controller
{
    public function list(Request $request)
    {
        $query = Matakuliah::with(['komponenPenilaian', 'dosen'])
            ->where('dosen_id', Auth::id());

        // Filter berdasarkan semester
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

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
            if ($request->status === 'siap') {
                $query->has('komponenPenilaian')->whereHas('komponenPenilaian', function($q) {
                    $q->where('total', 100);
                });
            } elseif ($request->status === 'belum') {
                $query->doesntHave('komponenPenilaian');
            } elseif ($request->status === 'belum_lengkap') {
                $query->has('komponenPenilaian')->whereHas('komponenPenilaian', function($q) {
                    $q->where('total', '!=', 100);
                });
            }
        }

        $matakuliahs = $query->orderBy('semester')->orderBy('kode_mk')->paginate(15)->withQueryString();
        
        // Statistik
        $totalMatakuliah = Matakuliah::where('dosen_id', Auth::id())->count();
        $siapInputNilai = Matakuliah::where('dosen_id', Auth::id())
            ->has('komponenPenilaian')
            ->whereHas('komponenPenilaian', function($q) {
                $q->where('total', 100);
            })->count();
        $belumSiap = $totalMatakuliah - $siapInputNilai;

        // Ambil daftar semester yang ada
        $semesters = Matakuliah::where('dosen_id', Auth::id())
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester');

        return view('dosen.nilai-mahasiswa.index', compact('matakuliahs', 'totalMatakuliah', 'siapInputNilai', 'belumSiap', 'semesters'));
    }

    public function index(Matakuliah $matakuliah)
    {
        // Pastikan hanya dosen pemilik yang bisa akses
        if ($matakuliah->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mata kuliah ini.');
        }

        // Load komponen penilaian
        $matakuliah->load('komponenPenilaian');

        $mahasiswas = Mahasiswa::where('semester', $matakuliah->semester)->orderBy('nim')->get();
        $nilaiMap = NilaiMahasiswa::where('matakuliah_id', $matakuliah->id)->get()->keyBy('mahasiswa_id');
        return view('dosen.nilai_mahasiswas.index', compact('matakuliah', 'mahasiswas', 'nilaiMap'));
    }

    public function storeOrUpdate(Request $request, Matakuliah $matakuliah)
    {
        // Pastikan hanya dosen pemilik yang bisa akses
        if ($matakuliah->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mata kuliah ini.');
        }

        $komponen = $matakuliah->komponenPenilaian;
        if (!$komponen || $komponen->total !== 100) {
            return back()->withErrors(['komponen' => 'Komponen penilaian belum lengkap atau total ≠ 100.']);
        }

        $entries = $request->input('entries', []);

        foreach ($entries as $mahasiswaId => $row) {
            $validated = validator($row, [
                'kehadiran' => 'nullable|numeric|min:0|max:100',
                'tugas' => 'nullable|numeric|min:0|max:100',
                'kuis' => 'nullable|numeric|min:0|max:100',
                'project' => 'nullable|numeric|min:0|max:100',
                'uts' => 'nullable|numeric|min:0|max:100',
                'uas' => 'nullable|numeric|min:0|max:100',
            ])->validate();

            $kehadiran = (float)($validated['kehadiran'] ?? 0);
            $tugas = (float)($validated['tugas'] ?? 0);
            $kuis = (float)($validated['kuis'] ?? 0);
            $project = (float)($validated['project'] ?? 0);
            $uts = (float)($validated['uts'] ?? 0);
            $uas = (float)($validated['uas'] ?? 0);

            $nilaiAkhir = (
                $kehadiran * $komponen->kehadiran +
                $tugas * $komponen->tugas +
                $kuis * $komponen->kuis +
                $project * $komponen->project +
                $uts * $komponen->uts +
                $uas * $komponen->uas
            ) / 100.0;

            // Konversi nilai ke huruf mutu
            $hurufMutu = $this->konversiNilai($nilaiAkhir);
            $keterangan = $this->getKeteranganNilai($nilaiAkhir);

            NilaiMahasiswa::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswaId,
                    'matakuliah_id' => $matakuliah->id,
                ],
                [
                    'kehadiran' => $kehadiran,
                    'tugas' => $tugas,
                    'kuis' => $kuis,
                    'project' => $project,
                    'uts' => $uts,
                    'uas' => $uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'huruf_mutu' => $hurufMutu,
                    'keterangan' => $keterangan,
                ]
            );
        }

        return redirect()->route('nilai.index', $matakuliah)->with('success', 'Nilai disimpan.');
    }

    protected function konversiNilai($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 75) return 'B';
        if ($nilai >= 65) return 'C';
        if ($nilai >= 55) return 'D';
        return 'E';
    }

    protected function getKeteranganNilai($nilai)
    {
        if ($nilai >= 85) return 'Sangat Baik';
        if ($nilai >= 75) return 'Baik';
        if ($nilai >= 65) return 'Cukup';
        if ($nilai >= 55) return 'Kurang';
        return 'Sangat Kurang';
    }
}


