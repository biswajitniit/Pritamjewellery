<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('metals')->insert([
            ['metal_id' => 1, 'metal_name' => 'GOLD', 'metal_category' => 'Gold', 'metal_hsn' => '7113', 'metal_sac' => '998892', 'description' => 'GOLD', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['metal_id' => 2, 'metal_name' => 'FINDING', 'metal_category' => 'Finding', 'metal_hsn' => null, 'metal_sac' => null, 'description' => 'FINDING', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['metal_id' => 3, 'metal_name' => 'SILVER', 'metal_category' => 'Alloy', 'metal_hsn' => '7106', 'metal_sac' => null, 'description' => 'SILVER', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['metal_id' => 4, 'metal_name' => 'COPPER', 'metal_category' => 'Alloy', 'metal_hsn' => '7407', 'metal_sac' => null, 'description' => 'COPPER', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['metal_id' => 5, 'metal_name' => 'SOLDER', 'metal_category' => 'Finding', 'metal_hsn' => null, 'metal_sac' => null, 'description' => 'SOLDER', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['metal_id' => 6, 'metal_name' => 'SAMPLE', 'metal_category' => 'Finding', 'metal_hsn' => null, 'metal_sac' => null, 'description' => 'SAMPLE', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['metal_id' => 7, 'metal_name' => 'GOLD WITH ALLOY', 'metal_category' => 'Gold', 'metal_hsn' => null, 'metal_sac' => null, 'description' => 'ALLOY GOLD', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            // ['metal_id' => 8, 'metal_name' => 'SAKHA', 'metal_category' => 'Miscellaneous', 'metal_hsn' => null, 'metal_sac' => null, 'description' => 'SAKHA', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            // ['metal_id' => 9, 'metal_name' => 'POLA', 'metal_category' => 'Miscellaneous', 'metal_hsn' => null, 'metal_sac' => null, 'description' => 'POLA', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}