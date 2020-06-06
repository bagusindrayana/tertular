<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function kota()
    {
        return $this->belongsTo(Kota::class,'kota_id');
    }
}
