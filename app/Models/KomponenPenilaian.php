<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomponenPenilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'matakuliah_id',
        'kehadiran',
        'tugas',
        'kuis',
        'project',
        'uts',
        'uas',
        'total',
    ];

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class);
    }
}





