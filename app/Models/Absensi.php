<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'mahasiswa_id', 'sesi_id', 'waktu_scan', 'status', 'nfc_serial_scanned'
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function sesi()
    {
        return $this->belongsTo(SesiKuliah::class);
    }
}
