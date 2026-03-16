<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $table = 'reg_districts';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'regency_id', 'name'];

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'regency_id');
    }

    public function kelurahans(): HasMany
    {
        return $this->hasMany(Kelurahan::class, 'district_id');
    }
}
