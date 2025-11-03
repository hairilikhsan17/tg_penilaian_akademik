<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\NilaiMahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dosen()
    {
        $dosenId = auth()->id();
        
        // Jumlah mata kuliah diampu (ini harus selalu dihitung, bahkan jika 0)
        $jumlahMatakuliah = Matakuliah::where('dosen_id', $dosenId)->count();
        
        // Get semua mata kuliah dosen
        $matakuliahIds = Matakuliah::where('dosen_id', $dosenId)->pluck('id')->toArray();
        
        // Inisialisasi variabel default
        $jumlahMahasiswa = 0;
        $tertinggi = null;
        $terendah = null;
        $distribusiNilai = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0];
        $chartLabels = [];
        $chartData = [];
        $matakuliahs = collect([]);
        
        // Jika ada mata kuliah, hitung statistik
        if (!empty($matakuliahIds)) {
            // Jumlah mahasiswa terdaftar (mahasiswa yang terdaftar di mata kuliah dosen ini)
            // Menggunakan collection untuk memastikan semua mahasiswa terhitung dengan benar
            $jumlahMahasiswa = NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)
                ->get()
                ->pluck('mahasiswa_id')
                ->unique()
                ->count();
            
            // Nilai tertinggi dan terendah (hanya dari mata kuliah dosen ini)
            $tertinggi = NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)
                ->with(['mahasiswa', 'matakuliah'])
                ->orderByDesc('nilai_akhir')
                ->first();
            
            $terendah = NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)
                ->with(['mahasiswa', 'matakuliah'])
                ->where('nilai_akhir', '>', 0)
                ->orderBy('nilai_akhir')
                ->first();

            // Grafik rata-rata nilai per mata kuliah
            $rataRataPerMatakuliah = NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)
                ->select('matakuliah_id')
                ->selectRaw('AVG(nilai_akhir) as rata_rata')
                ->groupBy('matakuliah_id')
                ->with('matakuliah')
                ->orderBy('matakuliah_id')
                ->get();

            // Data untuk chart (label dan data)
            $chartLabels = $rataRataPerMatakuliah->pluck('matakuliah.nama_mk')->filter()->toArray();
            $chartData = $rataRataPerMatakuliah->pluck('rata_rata')->map(function($value) {
                return round($value, 2);
            })->toArray();

            // Data distribusi nilai (hanya dari mata kuliah dosen ini)
            $distribusiNilai = [
                'A' => NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)->where('nilai_akhir', '>=', 85)->count(),
                'B' => NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)->whereBetween('nilai_akhir', [75, 84.99])->count(),
                'C' => NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)->whereBetween('nilai_akhir', [65, 74.99])->count(),
                'D' => NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)->whereBetween('nilai_akhir', [55, 64.99])->count(),
                'E' => NilaiMahasiswa::whereIn('matakuliah_id', $matakuliahIds)->where('nilai_akhir', '<', 55)->where('nilai_akhir', '>', 0)->count(),
            ];
        }

        // Data mata kuliah dengan jumlah mahasiswa (selalu diambil, bahkan jika kosong)
        $matakuliahs = Matakuliah::where('dosen_id', $dosenId)
            ->withCount('nilaiMahasiswas')
            ->orderBy('kode_mk')
            ->get();

        return view('dosen.dashboard', compact(
            'jumlahMahasiswa',
            'jumlahMatakuliah',
            'tertinggi',
            'terendah',
            'distribusiNilai',
            'matakuliahs',
            'chartLabels',
            'chartData'
        ));
    }

    public function mahasiswa()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return view('mahasiswa.dashboard', [
                'mahasiswa' => null,
                'jumlahMatakuliahAktif' => 0,
                'rataRataSemester' => 0,
                'ipk' => 0
            ]);
        }

        $semesterAktif = $mahasiswa->semester ?? 1;

        // Jumlah mata kuliah aktif semester ini
        $jumlahMatakuliahAktif = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('matakuliah', function($q) use ($semesterAktif) {
                $q->where('semester', $semesterAktif);
            })
            ->count();

        // Nilai rata-rata semester berjalan
        $nilaiSemesterBerjalan = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('matakuliah', function($q) use ($semesterAktif) {
                $q->where('semester', $semesterAktif);
            })
            ->with('matakuliah')
            ->get();

        $rataRataSemester = 0;
        if ($nilaiSemesterBerjalan->count() > 0) {
            $totalNilai = $nilaiSemesterBerjalan->sum('nilai_akhir');
            $rataRataSemester = $totalNilai / $nilaiSemesterBerjalan->count();
        }

        // Hitung IPK
        $nilaiMahasiswa = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('matakuliah')
            ->get();

        $totalNilai = 0;
        $totalSks = 0;
        foreach ($nilaiMahasiswa as $nilai) {
            $bobot = $this->getBobotNilai($nilai->nilai_akhir);
            $sks = $nilai->matakuliah->sks ?? 0;
            $totalNilai += $bobot * $sks;
            $totalSks += $sks;
        }
        $ipk = $totalSks > 0 ? $totalNilai / $totalSks : 0;

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'jumlahMatakuliahAktif',
            'rataRataSemester',
            'ipk',
            'semesterAktif'
        ));
    }

    public function dosenProfil()
    {
        $user = auth()->user();
        $dosen = $user->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Load matakuliahs with their nilaiMahasiswas relationship
        $dosen->load(['matakuliahs.nilaiMahasiswas']);

        return view('dosen.profil', compact('dosen', 'user'));
    }

    public function updateDosenProfil(Request $request)
    {
        $user = auth()->user();
        $dosen = $user->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosens,nip,' . $dosen->id,
            'email' => 'required|string|email|max:255|unique:dosens,email,' . $dosen->id,
            'user_email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user email
        $user->email = $validated['user_email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update data dosen
        $dosen->nama = $validated['nama'];
        $dosen->nip = $validated['nip'];
        $dosen->email = $validated['email'];

        // Handle upload foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($dosen->foto_profil && Storage::disk('public')->exists('foto_profil/' . $dosen->foto_profil)) {
                Storage::disk('public')->delete('foto_profil/' . $dosen->foto_profil);
            }

            // Upload foto baru
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('foto_profil', $filename, 'public');
            $dosen->foto_profil = $filename;
        }

        $dosen->save();

        return redirect()->route('dosen.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function deleteFotoProfil()
    {
        $user = auth()->user();
        $dosen = $user->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Hapus foto dari storage
        if ($dosen->foto_profil && Storage::disk('public')->exists('foto_profil/' . $dosen->foto_profil)) {
            Storage::disk('public')->delete('foto_profil/' . $dosen->foto_profil);
        }

        // Hapus path dari database
        $dosen->foto_profil = null;
        $dosen->save();

        return redirect()->route('dosen.profil')->with('success', 'Foto profil berhasil dihapus!');
    }

    private function getBobotNilai($nilai)
    {
        if ($nilai >= 85) return 4.0;
        if ($nilai >= 80) return 3.75;
        if ($nilai >= 75) return 3.5;
        if ($nilai >= 70) return 3.0;
        if ($nilai >= 65) return 2.75;
        if ($nilai >= 60) return 2.5;
        if ($nilai >= 55) return 2.0;
        if ($nilai >= 50) return 1.5;
        if ($nilai >= 40) return 1.0;
        return 0;
    }
}
