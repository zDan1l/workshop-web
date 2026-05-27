<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelases';

    protected $fillable = ['nama_kelas', 'kode_kelas', 'dosen_id'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswa');
    }

    public function sesiKuliahs()
    {
        return $this->hasMany(SesiKuliah::class);
    }
}
