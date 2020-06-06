<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kota extends Model
{   
    use SoftDeletes;
    protected $guarded = [];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class,'provinsi_id');
    }

    public function pasiens()
    {
       return $this->hasMany(Pasien::class,'kota_id');
    }
}
