<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = ['nip', 'nama', 'email'];

    public function kelases()
    {
        return $this->hasMany(Kelas::class);
    }
}
