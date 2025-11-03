<?php

namespace App\Http\Controllers;

use App\Models\NilaiMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = Auth::id();
        
        // Get all mata kuliah owned by this dosen for filter dropdown
        $matakuliahs = Matakuliah::where('dosen_id', $dosenId)
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();
        
        // Get unique semesters from mata kuliah
        $semesters = Matakuliah::where('dosen_id', $dosenId)
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester')
            ->toArray();

        // Query nilai - only from mata kuliah owned by this dosen
        $query = NilaiMahasiswa::with(['mahasiswa', 'matakuliah.komponenPenilaian'])
            ->whereHas('matakuliah', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            });

        // Filter by semester
        if ($request->filled('semester')) {
            $query->whereHas('matakuliah', function ($q) use ($request) {
                $q->where('semester', (int)$request->semester);
            });
        }

        // Filter by matakuliah
        if ($request->filled('matakuliah_id')) {
            $query->where('matakuliah_id', $request->matakuliah_id);
        }

        $nilai = $query->orderBy('matakuliah_id')
            ->orderBy('mahasiswa_id')
            ->paginate(50)
            ->withQueryString();

        return view('dosen.laporan.index', compact('nilai', 'matakuliahs', 'semesters'));
    }

    public function exportPdf(Request $request)
    {
        $dosenId = Auth::id();
        
        $query = NilaiMahasiswa::with(['mahasiswa', 'matakuliah.komponenPenilaian'])
            ->whereHas('matakuliah', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            });

        if ($request->filled('semester')) {
            $query->whereHas('matakuliah', function ($q) use ($request) {
                $q->where('semester', (int)$request->semester);
            });
        }

        if ($request->filled('matakuliah_id')) {
            $query->where('matakuliah_id', $request->matakuliah_id);
        }

        $nilai = $query->orderBy('matakuliah_id')
            ->orderBy('mahasiswa_id')
            ->get();

        return view('dosen.laporan.pdf', compact('nilai'));
    }

    public function exportExcel(Request $request)
    {
        $dosenId = Auth::id();
        
        $query = NilaiMahasiswa::with(['mahasiswa', 'matakuliah.komponenPenilaian'])
            ->whereHas('matakuliah', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            });

        if ($request->filled('semester')) {
            $query->whereHas('matakuliah', function ($q) use ($request) {
                $q->where('semester', (int)$request->semester);
            });
        }

        if ($request->filled('matakuliah_id')) {
            $query->where('matakuliah_id', $request->matakuliah_id);
        }

        $nilai = $query->orderBy('matakuliah_id')
            ->orderBy('mahasiswa_id')
            ->get();

        $filename = 'laporan_nilai_' . date('Y-m-d_His') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        return response()->view('dosen.laporan.excel', compact('nilai'), 200, $headers);
    }
}





