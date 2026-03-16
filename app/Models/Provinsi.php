<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    protected $table = 'reg_provinces';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'name'];

    public function kotas(): HasMany
    {
        return $this->hasMany(Kota::class, 'province_id');
    }
}
