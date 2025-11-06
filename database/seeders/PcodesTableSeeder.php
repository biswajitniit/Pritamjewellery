<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PcodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pcodes')->insert([
            ['id' => 1, 'code' => '0', 'description' => 'CHAIN_PENDENT_EARRING', 'is_active' => 'Yes'],
            ['id' => 2, 'code' => '1', 'description' => 'PENDANT & E_RING', 'is_active' => 'Yes'],
            ['id' => 3, 'code' => '2', 'description' => 'NECKWEAR WITH EARRING', 'is_active' => 'Yes'],
            ['id' => 4, 'code' => '3', 'description' => 'N + E_RING+ TIKA', 'is_active' => 'Yes'],
            ['id' => 5, 'code' => '4', 'description' => 'N + E_RING+ TIKA+BANGLE', 'is_active' => 'Yes'],
            ['id' => 6, 'code' => '5', 'description' => 'N + E_RING +TIKA + BANGLE + NATH', 'is_active' => 'Yes'],
            ['id' => 7, 'code' => '6', 'description' => 'N + E_RING+ F_RING+TIKKA+NATH+BANGLE', 'is_active' => 'Yes'],
            ['id' => 8, 'code' => '7', 'description' => 'N + E_RING+ F_RING+TIKKA+NATH+BANGLE+HARAM', 'is_active' => 'Yes'],
            ['id' => 9, 'code' => '8', 'description' => 'N + E_RING+ TIKA+ F_RING', 'is_active' => 'Yes'],
            ['id' => 10, 'code' => '9', 'description' => 'N + E_RING+ F_RING+TIKKA+NATH+BANGLE+HARAM+MANTASHA+CHOKER', 'is_active' => 'Yes'],
            ['id' => 11, 'code' => 'A', 'description' => 'ARMLATE', 'is_active' => 'Yes'],
            ['id' => 12, 'code' => 'B', 'description' => 'BRACELET', 'is_active' => 'Yes'],
            ['id' => 13, 'code' => 'C', 'description' => 'CHAIN', 'is_active' => 'Yes'],
            ['id' => 14, 'code' => 'D', 'description' => 'DROP EARRING', 'is_active' => 'Yes'],
            ['id' => 15, 'code' => 'E', 'description' => 'HARAM', 'is_active' => 'Yes'],
            ['id' => 16, 'code' => 'F', 'description' => 'FINGER RING', 'is_active' => 'Yes'],
            ['id' => 17, 'code' => 'G', 'description' => 'PENDENT WITH CHAIN', 'is_active' => 'Yes'],
            ['id' => 18, 'code' => 'H', 'description' => 'HOOP EAR RING', 'is_active' => 'Yes'],
            ['id' => 19, 'code' => 'J', 'description' => 'JHUMKA', 'is_active' => 'Yes'],
            ['id' => 20, 'code' => 'K', 'description' => 'TIKKA', 'is_active' => 'Yes'],
            ['id' => 21, 'code' => 'L', 'description' => 'ANKLET', 'is_active' => 'Yes'],
            ['id' => 22, 'code' => 'N', 'description' => 'NECKLACE', 'is_active' => 'Yes'],
            ['id' => 23, 'code' => 'O', 'description' => 'NATH', 'is_active' => 'Yes'],
            ['id' => 24, 'code' => 'P', 'description' => 'PENDENT', 'is_active' => 'Yes'],
            ['id' => 25, 'code' => 'S', 'description' => 'STUD EAR RING', 'is_active' => 'Yes'],
            ['id' => 26, 'code' => 'V', 'description' => 'BANGLE', 'is_active' => 'Yes'],
            ['id' => 27, 'code' => 'W', 'description' => 'WAIST BELT', 'is_active' => 'Yes'],
            ['id' => 28, 'code' => 'X', 'description' => 'OTHER', 'is_active' => 'Yes'],
            ['id' => 29, 'code' => 'Y', 'description' => 'MANGAL SUTRA', 'is_active' => 'Yes'],
            ['id' => 30, 'code' => 'Z', 'description' => 'COIN', 'is_active' => 'Yes'],
            ['id' => 31, 'code' => 'NF', 'description' => 'Not Found', 'is_active' => 'Yes'],
        ]);
    }
}
