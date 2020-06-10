<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provinsi extends Model
{   
    use SoftDeletes;
    protected $guarded = [];

    public function pasiens()
    {
       return $this->hasMany(Pasien::class,'provinsi_id');
    }

    public function lokasi_pasiens()
    {
       return $this->hasMany(Pasien::class,'lokasi_provinsi_id');
    }

    public function getTotalPasienAttribute()
    {
        return $this->pasiens()->count();
    }

    public function getTotalKasusPositifAttribute()
    {
        return $this->pasiens()->whereHas('statuses',function($w){
            $w->where('status','Positif');
        })->count();
    }

    public function getTotalKasusMeninggalAttribute()
    {
        return $this->pasiens()->whereHas('statuses',function($w){
            $w->where('status','Meninggal');
        })->count();
    }

    public function getTotalKasusSembuhAttribute()
    {
        return $this->pasiens()->whereHas('statuses',function($w){
            $w->where('status','Sembuh;');
        })->count();
    }
}
