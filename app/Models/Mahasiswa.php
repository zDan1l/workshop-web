<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['nim', 'nama', 'email', 'nfc_serial', 'foto'];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function kelases()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa');
    }
}
