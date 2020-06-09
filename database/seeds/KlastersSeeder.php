<?php

use App\Klaster;
use Illuminate\Database\Seeder;

class KlastersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Klaster::create([
            'id'=>1,
            'nama_klaster'=>'Klaster Pertama'
        ]);
    }
}
