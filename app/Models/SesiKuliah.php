<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiKuliah extends Model
{
    protected $fillable = ['kelas_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'is_aktif'];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'is_aktif' => 'boolean',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
