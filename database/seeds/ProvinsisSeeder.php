<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO tb_provinsis (id, nama_provinsi, created_at, updated_at, deleted_at) VALUES
        (11, 'DI ACEH', NULL, NULL, NULL),
        (12, 'SUMATERA UTARA', NULL, NULL, NULL),
        (13, 'SUMATERA BARAT', NULL, NULL, NULL),
        (14, 'RIAU', NULL, NULL, NULL),
        (15, 'JAMBI', NULL, NULL, NULL),
        (16, 'SUMATERA SELATAN', NULL, NULL, NULL),
        (17, 'BENGKULU', NULL, NULL, NULL),
        (18, 'LAMPUNG', NULL, NULL, NULL),
        (19, 'KEPULAUAN BANGKA BELITUNG', NULL, NULL, NULL),
        (21, 'KEPULAUAN RIAU', NULL, NULL, NULL),
        (31, 'DKI JAKARTA', NULL, NULL, NULL),
        (32, 'JAWA BARAT', NULL, NULL, NULL),
        (33, 'JAWA TENGAH', NULL, NULL, NULL),
        (34, 'DI YOGYAKARTA', NULL, NULL, NULL),
        (35, 'JAWA TIMUR', NULL, NULL, NULL),
        (36, 'BANTEN', NULL, NULL, NULL),
        (51, 'BALI', NULL, NULL, NULL),
        (52, 'NUSA TENGGARA BARAT', NULL, NULL, NULL),
        (53, 'NUSA TENGGARA TIMUR', NULL, NULL, NULL),
        (61, 'KALIMANTAN BARAT', NULL, NULL, NULL),
        (62, 'KALIMANTAN TENGAH', NULL, NULL, NULL),
        (63, 'KALIMANTAN SELATAN', NULL, NULL, NULL),
        (64, 'KALIMANTAN TIMUR', NULL, NULL, NULL),
        (65, 'KALIMANTAN UTARA', NULL, NULL, NULL),
        (71, 'SULAWESI UTARA', NULL, NULL, NULL),
        (72, 'SULAWESI TENGAH', NULL, NULL, NULL),
        (73, 'SULAWESI SELATAN', NULL, NULL, NULL),
        (74, 'SULAWESI TENGGARA', NULL, NULL, NULL),
        (75, 'GORONTALO', NULL, NULL, NULL),
        (76, 'SULAWESI BARAT', NULL, NULL, NULL),
        (81, 'MALUKU', NULL, NULL, NULL),
        (82, 'MALUKU UTARA', NULL, NULL, NULL),
        (91, 'PAPUA BARAT', NULL, NULL, NULL),
        (94, 'PAPUA', NULL, NULL, NULL)");
    }
}
