<?php

namespace App\Http\Controllers;

use App\Interaksi;
use App\Kecamatan;
use App\Kelurahan;
use App\Kota;
use App\Pasien;
use App\Provinsi;
use DateTime;
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
            $nama_provinsi = $feature['properties']['nama_provinsi'];
            $cek = Provinsi::where('nama_provinsi','LIKE','%'.$nama_provinsi.'%')->first();
            if($cek){
                $positif = $cek->lokasi_pasiens()->whereHas('statuses',function($w){
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
                    'total_kasus_sembuh'=>$cek->pasiens()->whereHas('statuses',function($w){
                        $w->where('status','Sembuh');
                    })->count(),
                    'total_kasus_meninggal'=>$cek->pasiens()->whereHas('statuses',function($w){
                        $w->where('status','Meninggal');
                    })->count(),
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
        $new = [
            'type'=>'FeatureCollection',
            'crs'=>[
                'type'=>'name',
                'properties'=>[
                    'name'=>'test'
                ]
            ],
            'features'=>[
                
            ]
        ];
        foreach ($json['features'] as $index => $feature) {
            if($feature['properties']['id_provinsi'] == request()->provinsi_id || request()->provinsi_id == null){
                $nama_kota = $feature['properties']['nama_kota'];
                $cek = Kota::where('nama_kota','LIKE','%'.$nama_kota.'%')->first();
                if($cek){
                    $positif = $cek->lokasi_pasiens()->whereHas('statuses',function($w){
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
                        'id_provinsi'=>$cek->provinsi_id,
                        'total_kasus_positif'=>$positif,
                        'total_kasus_sembuh'=>$cek->pasiens()->whereHas('statuses',function($w){
                            $w->where('status','Sembuh');
                        })->count(),
                        'total_kasus_meninggal'=>$cek->pasiens()->whereHas('statuses',function($w){
                            $w->where('status','Meninggal');
                        })->count(),
                        'color'=>$color
                    ];
                    $json['features'][$index]['properties'] = $properties;
                    $json['features'][$index]['id'] = $cek->id;
                    $new['features'][] = $json['features'][$index];
                    // $json['features'][$index]['geometry']['type'] = "MultiPolygon";
                
                }
            }
            
            
        }

        return json_encode($new);
    }

    public function cekKoordinat($latitude,$longitude)
    {
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://api.opencagedata.com/geocode/v1/json?q=".$latitude.",".$longitude."&key=".env('GEOCODE_TOKEN'));

        // return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // tutup curl 
        curl_close($ch);      

        $json = json_decode($output,true);
        $kelurahan = $json['results'][0]['components']['village'] ?? null;
        $kelurahan = $json['results'][0]['components']['village'] ?? null;
        $kecamatan =  $json['results'][0]['components']['town'] ?? null;
        $kota = $json['results'][0]['components']['city'] ?? $json['results'][0]['components']['state_district'] ?? null;
        $removeAngin = str_replace(array("East ", "West ","South ","North "), array("", "","",""), $json['results'][0]['components']['state']) ?? null;
        $getAngin = str_replace(array("East ", "West ","South ","North ",$removeAngin), array("Timur", "Barat","Selatan","Utara",""), $json['results'][0]['components']['state']);


        $provinsi = $removeAngin." ".$getAngin;
        $data = [
            'kelurahan'=>[
                "id"=>null,
                "nama"=>null
            ],
            'kecamatan_id'=>[
                "id"=>null,
                "nama"=>null
            ],
            'kota_id'=>[
                "id"=>null,
                "nama"=>null
            ],
            'provinsi_id'=>[
                "id"=>null,
                "nama"=>null
            ],
            "formatted"=>$json['results'][0]['formatted']
        ];
        if($kelurahan != null){
            $data = Kelurahan::where('nama_kelurahan','LIKE','%'.$kelurahan.'%')->whereHas("kecamatan",function($w)use($kota,$provinsi){
                $w->whereHas('kota',function($ww)use($kota,$provinsi){
                    $ww->where('nama_kota','LIKE','%'.$kota.'%')->whereHas('provinsi',function($www)use($provinsi){
                        $www->where('nama_provinsi','LIKE','%'.$provinsi.'%');
                    });
                });
            })->first();
            $data = [
                'kelurahan'=>[
                    "id"=>$data->id,
                    "nama"=>$data->nama_kelurahan
                ],
                'kecamatan'=>[
                    "id"=>$data->kecamatan_id,
                    "nama"=>$data->kecamatan->nama_kecamatan
                ],
                'kota'=>[
                    "id"=>$data->kecamatan->kota_id,
                    "nama"=>$data->kecamatan->kota->nama_kota
                ],
                'provinsi'=>[
                    "id"=>$data->kecamatan->kota->provinsi_id,
                    "nama"=>$data->kecamatan->kota->provinsi->nama_provinsi
                ],
                "formatted"=>$json['results'][0]['formatted']
            ];
        } elseif($kecamatan != null){
            $data = Kecamatan::where('nama_kecamatan','LIKE','%'.$kecamatan.'%')->whereHas('kota',function($ww)use($kota,$provinsi){
                    $ww->where('nama_kota','LIKE','%'.$kota.'%')->whereHas('provinsi',function($www)use($provinsi){
                        $www->where('nama_provinsi','LIKE','%'.$provinsi.'%');
                    });
                })->first();
            $data = [
                'kelurahan'=>[
                    "id"=>null,
                    "nama"=>null
                ],
                'kecamatan'=>[
                    "id"=>$data->id,
                    "nama"=>$data->nama_kecamatan
                ],
                'kota'=>[
                    "id"=>$data->kota_id,
                    "nama"=>$data->kota->nama_kota
                ],
                'provinsi'=>[
                    "id"=>$data->kota->provinsi_id,
                    "nama"=>$data->kota->provinsi->nama_provinsi
                ],
                "formatted"=>$json['results'][0]['formatted']
            ];
        } elseif ($kota  != null){
           
            $data = Kota::where('nama_kota','LIKE','%'.$kota.'%')->whereHas('provinsi',function($www)use($provinsi){
                        $www->where('nama_provinsi','LIKE','%'.$provinsi.'%');
                    })->first();
            $data = [
                'kelurahan'=>[
                    "id"=>null,
                    "nama"=>null
                ],
                'kecamatan'=>[
                    "id"=>null,
                    "nama"=>null
                ],
                'kota'=>[
                    "id"=>$data->id,
                    "nama"=>$data->nama_kota
                ],
                'provinsi'=>[
                    "id"=>$data->provinsi_id,
                    "nama"=>$data->provinsi->nama_provinsi
                ],
                "formatted"=>$json['results'][0]['formatted']
            ];
        } elseif ($provinsi  != null){
            $data = Provinsi::where('nama_provinsi','LIKE','%'.$provinsi.'%')->first();
            $data = [
                'kelurahan'=>null,
                'kecamatan'=>null,
                'kota'=>null,
                'provinsi'=>[
                    "id"=>$data->id,
                    "nama"=>$data->nama_provinsi
                ],
                "formatted"=>$json['results'][0]['formatted']
            ];
        }
        return $data;
    }

    public function getSebaranPoint()
    {
        $data = [
            'type'=>'FeatureCollection',
            'crs'=>[
                'type'=>'name',
                'properties'=>[
                    'name'=>'test'
                ]
            ],
            'features'=>[
                
            ]
        ];

        $pasiens = Pasien::whereNotNull('koordinat_lokasi')->get();
      
        foreach ($pasiens as $p) {
            $raw = explode(',',$p->koordinat_lokasi);
            $koordinat = [$raw[1],$raw[0]];
            $b = [
                'type'=>'Feature',
                'properties'=>[
                    'id'=>$p->id,
                    'lokasi'=>$p->lokasi
                ],
                'geometry'=>[
                    'type'=>'Point',
                    'coordinates'=>$koordinat
                ]
            ];

            $data['features'][] = $b;
        }
        return $data;
    }

    public function getRawanPoint()
    {
        $data = [
            'type'=>'FeatureCollection',
            'crs'=>[
                'type'=>'name',
                'properties'=>[
                    'name'=>'test'
                ]
            ],
            'features'=>[
                
            ]
        ];

        $interaksi = Interaksi::whereNotNull('koordinat_lokasi')->get();
      
        foreach ($interaksi as $i) {
            $raw = explode(',',$i->koordinat_lokasi);
            $koordinat = [$raw[1],$raw[0]];
            $b = [
                'type'=>'Feature',
                'properties'=>[
                    'id'=>$i->id,
                    'lokasi'=>$i->lokasi
                ],
                'geometry'=>[
                    'type'=>'Point',
                    'coordinates'=>$koordinat
                ]
            ];

            $data['features'][] = $b;
        }
        return $data;
    }

    public function getPasienPerHari($days)
    {
    	$date = date('Y-m-d'); 
		$weekOfdays = array([
				'tanggal'=>$date,
				'data'=>Pasien::whereDate('created_at',$date)->count()
            ]);
            
		$date = new DateTime($date);
		for($i=1; $i < $days ; $i++){
		    $date->modify('-1 day');
		    $weekOfdays[] = [
		    	'tanggal'=>$date->format('Y-m-d'),
				'data'=>Pasien::whereDate('created_at',$date->format('Y-m-d'))->count()
		    ];
		}

		return $weekOfdays;
	}
    
}
