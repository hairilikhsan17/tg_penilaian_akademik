<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'matakuliah_id',
        'kehadiran',
        'tugas',
        'kuis',
        'project',
        'uts',
        'uas',
        'nilai_akhir',
        'huruf_mutu',
        'keterangan',
    ];

    // Helper method untuk konversi nilai ke huruf mutu
    public function getHurufMutuAttribute($value)
    {
        if (!$value && $this->nilai_akhir) {
            return $this->konversiNilai($this->nilai_akhir);
        }
        return $value;
    }

    // Helper method untuk konversi nilai ke huruf mutu
    protected function konversiNilai($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 75) return 'B';
        if ($nilai >= 65) return 'C';
        if ($nilai >= 55) return 'D';
        return 'E';
    }

    // Accessor untuk keterangan
    public function getKeteranganAttribute($value)
    {
        if (!$value && $this->nilai_akhir) {
            return $this->getKeteranganNilai($this->nilai_akhir);
        }
        return $value;
    }

    protected function getKeteranganNilai($nilai)
    {
        if ($nilai >= 85) return 'Sangat Baik';
        if ($nilai >= 75) return 'Baik';
        if ($nilai >= 65) return 'Cukup';
        if ($nilai >= 55) return 'Kurang';
        return 'Sangat Kurang';
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class);
    }
}





