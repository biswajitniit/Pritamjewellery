<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatternsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('patterns')->insert([
            ['pid' => '1', 'pat_desc' => 'PLAIN', 'is_active' => 'Yes'],
            ['pid' => '2', 'pat_desc' => 'ANTIQUE', 'is_active' => 'Yes'],
            ['pid' => '3', 'pat_desc' => 'HIGH POLISH', 'is_active' => 'Yes'],
            ['pid' => '4', 'pat_desc' => 'TRI COLOR', 'is_active' => 'Yes'],
            ['pid' => '5', 'pat_desc' => 'LIGHT ANTIQUE', 'is_active' => 'Yes'],
            ['pid' => '6', 'pat_desc' => 'PLAIN ENAMEL', 'is_active' => 'Yes'],
            ['pid' => '7', 'pat_desc' => 'ANTIQUE ENAMEL', 'is_active' => 'Yes'],
            ['pid' => '8', 'pat_desc' => 'KUNDAN ENAMEL', 'is_active' => 'Yes'],
            ['pid' => '9', 'pat_desc' => 'GERU POLISH', 'is_active' => 'Yes'],
            ['pid' => 'G', 'pat_desc' => 'Gold Plain', 'is_active' => 'Yes'],
            ['pid' => 'J', 'pat_desc' => 'Gold with Stones', 'is_active' => 'Yes'],
            ['pid' => 'E', 'pat_desc' => 'Gold with Precious Stones', 'is_active' => 'Yes'],
            ['pid' => 'H', 'pat_desc' => 'Gold with Chakri', 'is_active' => 'Yes'],
            ['pid' => 'K', 'pat_desc' => 'Polki - Jadau / Diamond Polki', 'is_active' => 'Yes'],
            ['pid' => 'O', 'pat_desc' => 'Polki - Open Setting', 'is_active' => 'Yes'],
            ['pid' => 'T', 'pat_desc' => 'Studded - GIS', 'is_active' => 'Yes'],
            ['pid' => 'D', 'pat_desc' => 'Studded - DIS', 'is_active' => 'Yes'],
            ['pid' => 'S', 'pat_desc' => 'Studded Solitaires', 'is_active' => 'Yes'],
            ['pid' => 'B', 'pat_desc' => 'Studded Bi-Metal', 'is_active' => 'Yes'],
            ['pid' => 'P', 'pat_desc' => 'Studded Platinum', 'is_active' => 'Yes'],
            ['pid' => 'C', 'pat_desc' => 'Coin', 'is_active' => 'Yes'],
            ['pid' => 'L', 'pat_desc' => 'Silver', 'is_active' => 'Yes'],
            ['pid' => 'R', 'pat_desc' => 'Raw Material', 'is_active' => 'Yes'],
            ['pid' => 'xx', 'pat_desc' => 'temp', 'is_active' => 'Yes'],
        ]);
    }
}
