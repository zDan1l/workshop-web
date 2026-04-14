<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $primaryKey = "kode";
    public $incrementing = false;
    protected $keyType = "string";
    protected $guarded = [];
}
