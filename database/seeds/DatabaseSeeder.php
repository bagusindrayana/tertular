<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserSeeder::class,
            // ProvinsisSeeder::class,
            // KotasSeeder::class,
            // KecamatansSeeder::class,
            // KelurahansSeeder::class,
            // KlastersSeeder::class,
            //PasiensSeeder::class,
            PasienStatusesSeeder::class
        ]);
    }
}
