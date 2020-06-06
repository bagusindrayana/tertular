<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nama'=>'Admin',
            'username'=>'iniadmin',
            'password'=>Hash::make('admin4321'),
            'level'=>'Admin'
        ]);
    }
}
