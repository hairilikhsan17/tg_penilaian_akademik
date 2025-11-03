<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Matakuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'semester',
        'dosen_id',
        'sks',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function komponenPenilaian(): HasOne
    {
        return $this->hasOne(KomponenPenilaian::class);
    }

    public function nilaiMahasiswas(): HasMany
    {
        return $this->hasMany(NilaiMahasiswa::class);
    }
}





