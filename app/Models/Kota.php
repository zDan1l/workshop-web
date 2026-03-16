<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    protected $table = 'reg_regencies';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'province_id', 'name'];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'province_id');
    }

    public function kecamatans(): HasMany
    {
        return $this->hasMany(Kecamatan::class, 'regency_id');
    }
}
