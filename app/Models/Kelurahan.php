<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelurahan extends Model
{
    protected $table = 'reg_villages';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'district_id', 'name'];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'district_id');
    }
}
