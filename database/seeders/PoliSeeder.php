<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('poli')->insert([
            [
                'poli_id' => 'P001',
                'nama'    => 'Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'poli_id' => 'P002',
                'nama'    => 'Kebidanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
