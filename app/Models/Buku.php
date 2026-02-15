<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buku extends Model
{
    use HasUuids;

    protected $fillable = ['kode', 'judul', 'pengarang', 'kategori_id'];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}
