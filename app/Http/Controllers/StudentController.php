<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\NilaiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Hitung statistik
        $totalMatakuliah = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)->count();
        
        // Hitung IPK
        $nilai = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('matakuliah')
            ->get();
        
        $totalSKS = $nilai->sum(function($item) {
            return $item->matakuliah->sks ?? 0;
        });

        $totalBobot = $nilai->sum(function($item) {
            $sks = $item->matakuliah->sks ?? 0;
            $nilaiAkhir = $item->nilai_akhir ?? 0;
            $hurufMutu = $item->huruf_mutu ?? '-';
            
            $bobot = 0;
            if ($hurufMutu == 'A') $bobot = 4;
            elseif ($hurufMutu == 'B') $bobot = 3;
            elseif ($hurufMutu == 'C') $bobot = 2;
            elseif ($hurufMutu == 'D') $bobot = 1;
            elseif ($hurufMutu == 'E') $bobot = 0;
            else {
                if ($nilaiAkhir >= 85) $bobot = 4;
                elseif ($nilaiAkhir >= 75) $bobot = 3;
                elseif ($nilaiAkhir >= 65) $bobot = 2;
                elseif ($nilaiAkhir >= 55) $bobot = 1;
                else $bobot = 0;
            }
            
            return $sks * $bobot;
        });

        $ipk = $totalSKS > 0 ? $totalBobot / $totalSKS : 0;

        return view('mahasiswa.profil', compact('mahasiswa', 'user', 'totalMatakuliah', 'ipk'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Validasi
        $rules = [
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:mahasiswas,nim,' . $mahasiswa->id,
            'email' => 'required|string|email|max:255|unique:mahasiswas,email,' . $mahasiswa->id,
            'user_email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'semester' => 'required|integer|min:1|max:8',
            'jurusan' => 'required|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Jika password diisi, wajib ada konfirmasi
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Update user (email, nama, dan password jika ada)
        $user->name = $validated['nama'];
        $user->email = $validated['user_email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update data mahasiswa
        $mahasiswa->nama = $validated['nama'];
        $mahasiswa->nim = $validated['nim'];
        $mahasiswa->email = $validated['email'];
        $mahasiswa->semester = $validated['semester'];
        $mahasiswa->jurusan = $validated['jurusan'];

        // Handle upload foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($mahasiswa->foto_profil && Storage::disk('public')->exists('foto_profil/' . $mahasiswa->foto_profil)) {
                Storage::disk('public')->delete('foto_profil/' . $mahasiswa->foto_profil);
            }

            // Upload foto baru
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('foto_profil', $filename, 'public');
            $mahasiswa->foto_profil = $filename;
        }

        $mahasiswa->save();

        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function deleteFotoProfil()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Hapus foto dari storage
        if ($mahasiswa->foto_profil && Storage::disk('public')->exists('foto_profil/' . $mahasiswa->foto_profil)) {
            Storage::disk('public')->delete('foto_profil/' . $mahasiswa->foto_profil);
        }

        // Hapus path dari database
        $mahasiswa->foto_profil = null;
        $mahasiswa->save();

        return redirect()->route('mahasiswa.profile')->with('success', 'Foto profil berhasil dihapus!');
    }

    public function nilai(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Query nilai dengan relasi
        $query = NilaiMahasiswa::with(['matakuliah.komponenPenilaian'])
            ->where('mahasiswa_id', $mahasiswa->id);

        // Filter berdasarkan semester jika dipilih
        if ($request->filled('semester')) {
            $query->whereHas('matakuliah', function($q) use ($request) {
                $q->where('semester', (int)$request->semester);
            });
        }

        $nilai = $query->orderBy('matakuliah_id')->get();

        // Get daftar semester yang tersedia
        $semesters = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('matakuliah')
            ->get()
            ->pluck('matakuliah.semester')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('mahasiswa.nilai-akademik', compact('mahasiswa', 'nilai', 'semesters'));
    }

    public function khs()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $nilai = NilaiMahasiswa::with('matakuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('matakuliah_id')
            ->get();

        // Hitung IPK
        $totalSKS = $nilai->sum(function($item) {
            return $item->matakuliah->sks ?? 0;
        });

        $totalBobot = $nilai->sum(function($item) {
            $sks = $item->matakuliah->sks ?? 0;
            $nilaiAkhir = $item->nilai_akhir ?? 0;
            $hurufMutu = $item->huruf_mutu ?? '-';
            
            // Konversi huruf mutu ke bobot
            $bobot = 0;
            if ($hurufMutu == 'A') $bobot = 4;
            elseif ($hurufMutu == 'B') $bobot = 3;
            elseif ($hurufMutu == 'C') $bobot = 2;
            elseif ($hurufMutu == 'D') $bobot = 1;
            elseif ($hurufMutu == 'E') $bobot = 0;
            else {
                // Jika belum ada huruf mutu, hitung dari nilai akhir
                if ($nilaiAkhir >= 85) $bobot = 4;
                elseif ($nilaiAkhir >= 75) $bobot = 3;
                elseif ($nilaiAkhir >= 65) $bobot = 2;
                elseif ($nilaiAkhir >= 55) $bobot = 1;
                else $bobot = 0;
            }
            
            return $sks * $bobot;
        });

        $ipk = $totalSKS > 0 ? $totalBobot / $totalSKS : 0;

        return view('mahasiswa.khs-transkrip', compact('mahasiswa', 'nilai', 'ipk', 'totalSKS'));
    }

    public function cetakKhs()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $nilai = NilaiMahasiswa::with('matakuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('matakuliah_id')
            ->get();

        // Hitung IPK
        $totalSKS = $nilai->sum(function($item) {
            return $item->matakuliah->sks ?? 0;
        });

        $totalBobot = $nilai->sum(function($item) {
            $sks = $item->matakuliah->sks ?? 0;
            $nilaiAkhir = $item->nilai_akhir ?? 0;
            $hurufMutu = $item->huruf_mutu ?? '-';
            
            $bobot = 0;
            if ($hurufMutu == 'A') $bobot = 4;
            elseif ($hurufMutu == 'B') $bobot = 3;
            elseif ($hurufMutu == 'C') $bobot = 2;
            elseif ($hurufMutu == 'D') $bobot = 1;
            elseif ($hurufMutu == 'E') $bobot = 0;
            else {
                if ($nilaiAkhir >= 85) $bobot = 4;
                elseif ($nilaiAkhir >= 75) $bobot = 3;
                elseif ($nilaiAkhir >= 65) $bobot = 2;
                elseif ($nilaiAkhir >= 55) $bobot = 1;
                else $bobot = 0;
            }
            
            return $sks * $bobot;
        });

        $ipk = $totalSKS > 0 ? $totalBobot / $totalSKS : 0;

        return view('mahasiswa.cetak-khs', compact('mahasiswa', 'nilai', 'ipk', 'totalSKS'));
    }

    public function showNilai(Mahasiswa $mahasiswa)
    {
        $nilai = NilaiMahasiswa::with('matakuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->get();
        return view('mahasiswa.nilai', compact('mahasiswa', 'nilai'));
    }

    public function printKhs(Mahasiswa $mahasiswa)
    {
        $nilai = NilaiMahasiswa::with('matakuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('matakuliah_id')
            ->get();
        return view('mahasiswa.print', compact('mahasiswa', 'nilai'));
    }

    public function goByNim(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|string'
        ]);
        $m = Mahasiswa::where('nim', $data['nim'])->first();
        if (!$m) {
            return back()->withErrors(['nim' => 'Mahasiswa dengan NIM tersebut tidak ditemukan.']);
        }
        return redirect()->route('mahasiswa.show.nilai', $m);
    }
}


