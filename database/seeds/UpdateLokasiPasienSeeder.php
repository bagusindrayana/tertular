<?php

use App\Helpers\Turf;
use App\Pasien;
use Illuminate\Database\Seeder;

class UpdateLokasiPasienSeeder extends Seeder
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
        for ($i=1; $i <= 500; $i++) { 
            $res = null;
            $pasien = Pasien::find($i);
            if($pasien){
                while ($res == null) {
                    $res = $this->randomInPolygon($json,$pasien);
                }
                $pasien->update([
                    'koordinat_lokasi'=>$res['geometry']['coordinates'][1].",".$res['geometry']['coordinates'][0],
                ]);
            }
        }
       
    }


    public function randomInPolygon($json = null,$pasien = null)
    {
    
        if($pasien){
            $item = $this->findFeature($json,$pasien);
            $bbox = Turf::bbox($item);
            $points = Turf::randomPoint(1, [
                'bbox'=> $bbox
            ]);
            $cek = Turf::booleanPointInPolygon($points['features'][0],$item);
            if($cek){
                $points['features'][0]['properties'] = $item['properties'];
                return $points['features'][0];
            }
        
            $this->randomInPolygon($json,$pasien);
        }
    
    }

    public function findFeature($json,$pasien)
    {
        foreach ($json['features'] as $key => $item) {
            if($item['id'] == $pasien->kota_id && $item['properties']['id_provinsi'] == $pasien->provinsi_id){
                return $item;
            }
        }
    }
}
