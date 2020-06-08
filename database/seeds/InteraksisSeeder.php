<?php

use App\Helpers\Turf;
use App\Interaksi;
use Illuminate\Database\Seeder;

class InteraksisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $str = file_get_contents(url("api/map-kabkota-geojson?provinsi_id=64"));
        $json = json_decode($str, true);
        $res = $this->randomInPolygon($json);
        $row = [];
        for ($i=1; $i <= 500; $i++) { 
            $res = null;
            while ($res == null) {
                $res = $this->randomInPolygon($json);
            }
            $row[] = [
                'pasien_id'=>$i,
                'keterangan'=>"Jalan-Jalan",
                'tanggal_interaksi'=>"2020-06-07",
                'lokasi'=>"Kalimantan Timur",
                'koordinat_lokasi'=>$res['geometry']['coordinates'][1].".".$res['geometry']['coordinates'][0],
                'provinsi_id'=>64,
                'kota_id'=>$res['properties']['id']
            ];
        }
        Interaksi::insert($row);
    }


    public function randomInPolygon($json = null)
    {
    
        foreach ($json['features'] as $key => $item) {
            $bbox = Turf::bbox($item);
            $points = Turf::randomPoint(1, [
                'bbox'=> $bbox
            ]);
            $cek = Turf::booleanPointInPolygon($points['features'][0],$item);
            if($cek){
                $points['features'][0]['properties'] = $item['properties'];
                return $points['features'][0];
            }
        }
    
        $this->randomInPolygon($json);
    
    }
}
