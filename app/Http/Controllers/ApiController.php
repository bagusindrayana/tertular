<?php

namespace App\Http\Controllers;

use App\Kota;
use App\Provinsi;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getProvinsiMap()
    {
        $str = file_get_contents(url("geojson/provinsi.json"));
        $json = json_decode($str, true);
        $ranges = ['0-100', '100-200', '200-500', '500-1000', '1000-2000', '2000-5000', '5000-10000', '10000+'];
        $colors = ['#FFEDA0', '#FED976', '#FEB24C', '#FD8D3C', '#FC4E2A', '#E31A1C', '#BD0026', '#800026'];
        foreach ($json['features'] as $index => $feature) {
            $nama_provinsi = $feature['properties']['NAME_1'];
            $cek = Provinsi::where('nama_provinsi','LIKE','%'.$nama_provinsi.'%')->first();
            if($cek){
                $positif = $cek->pasiens()->where(function($w){
                    $w->where('status','Positif')->orWhere('status','Sembuh')->orWhere('status','Meninggal');
                })->count();
                $color = $colors[0];
                for ($i=0; $i < count($ranges); $i++) { 
                    if($ranges[$i] == '10000+'){
                        if($positif > 10000){
                            $color = $colors[$i];
                        }
                    } else {
                        $ex = explode('-',$ranges[$i]);
                        if($ex[0] <= $positif && $ex[1] > $positif){
                            $color = $colors[$i];
                        }
                    }
                    

                }

                $properties = [
                    'nama_provinsi'=>$nama_provinsi,
                    'id'=>$cek->id,
                    'total_kasus_positif'=>$positif,
                    'total_kasus_sembuh'=>$cek->pasiens()->where('status','Sembuh')->count(),
                    'total_kasus_meninggal'=>$cek->pasiens()->where('status','Meninggal')->count(),
                    'color'=>$color
                ];
                $json['features'][$index]['properties'] = $properties;
                $json['features'][$index]['id'] = $cek->id;
            }
            
        }

        return json_encode($json);
    }

    public function getKotaMap()
    {
        $str = file_get_contents(url("/geojson/kabkota.json"));
   
        $json = json_decode($str, true);
        
        $ranges = ['0-10', '10-20', '20-50', '50-100', '100-200', '200-500', '500-1000', '1000+'];
        $colors = ['#FFEDA0', '#FED976', '#FEB24C', '#FD8D3C', '#FC4E2A', '#E31A1C', '#BD0026', '#800026'];
        foreach ($json['features'] as $index => $feature) {
            $nama_kota = $feature['properties']['primary_name'];
            $cek = Kota::where('nama_kota','LIKE','%'.$nama_kota.'%')->first();
            if($cek){
                $positif = $cek->pasiens()->where(function($w){
                    $w->where('status','Positif')->orWhere('status','Sembuh')->orWhere('status','Meninggal');
                })->count();
                $color = $colors[0];
                for ($i=0; $i < count($ranges); $i++) { 
                    if($ranges[$i] == '10000+'){
                        if($positif > 10000){
                            $color = $colors[$i];
                        }
                    } else {
                        $ex = explode('-',$ranges[$i]);
                        if($ex[0] <= $positif && $ex[1] > $positif){
                            $color = $colors[$i];
                        }
                    }
                    

                }

                $properties = [
                    'nama_kota'=>$nama_kota,
                    'id'=>$cek->id,
                    'total_kasus_positif'=>$positif,
                    'total_kasus_sembuh'=>$cek->pasiens()->where('status','Sembuh')->count(),
                    'total_kasus_meninggal'=>$cek->pasiens()->where('status','Meninggal')->count(),
                    'color'=>$color
                ];
                $json['features'][$index]['properties'] = $properties;
                $json['features'][$index]['id'] = $cek->id;
                // $json['features'][$index]['geometry']['type'] = "MultiPolygon";
            
            }
            
        }

        return json_encode($json);
    }

    
}
