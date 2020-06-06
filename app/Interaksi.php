<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interaksi extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class,'kelurahan_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class,'kecamatan_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class,'kota_id');
    }

    public function porvinsi()
    {
        return $this->belongsTo(Provinsi::class,'provinsi_id');
    }
}
