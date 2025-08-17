<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetalPurityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('metalpurities')->insert([
            ['purity_id' => 2, 'metal_id' => 1, 'purity' => '99.96', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 3, 'metal_id' => 1, 'purity' => '99.5', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 4, 'metal_id' => 1, 'purity' => '99.7', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 5, 'metal_id' => 2, 'purity' => '91.7', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 6, 'metal_id' => 1, 'purity' => '99.6', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 7, 'metal_id' => 1, 'purity' => '99.8', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 8, 'metal_id' => 1, 'purity' => '99.99', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 9, 'metal_id' => 3, 'purity' => '99.9', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 10, 'metal_id' => 1, 'purity' => '99.94', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 11, 'metal_id' => 4, 'purity' => '99.99', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 12, 'metal_id' => 1, 'purity' => '99.84', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 13, 'metal_id' => 1, 'purity' => '99.88', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 14, 'metal_id' => 1, 'purity' => '99.87', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 15, 'metal_id' => 1, 'purity' => '99.9', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 16, 'metal_id' => 1, 'purity' => '99.89', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 17, 'metal_id' => 1, 'purity' => '99.97', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 18, 'metal_id' => 1, 'purity' => '99.92', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 19, 'metal_id' => 5, 'purity' => '91.7', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 20, 'metal_id' => 1, 'purity' => '99.95', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 21, 'metal_id' => 1, 'purity' => '99.91', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 22, 'metal_id' => 1, 'purity' => '99.93', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 23, 'metal_id' => 2, 'purity' => '75', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 24, 'metal_id' => 1, 'purity' => '99.86', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 25, 'metal_id' => 1, 'purity' => '99.83', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 26, 'metal_id' => 6, 'purity' => '91.7', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 27, 'metal_id' => 1, 'purity' => '99.64', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 28, 'metal_id' => 1, 'purity' => '99.85', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 29, 'metal_id' => 1, 'purity' => '99.72', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 30, 'metal_id' => 1, 'purity' => '99.98', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 31, 'metal_id' => 1, 'purity' => '99.82', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 32, 'metal_id' => 3, 'purity' => '99.86', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 33, 'metal_id' => 3, 'purity' => '99.96', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 34, 'metal_id' => 1, 'purity' => '0.1', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 35, 'metal_id' => 7, 'purity' => '91.7', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 36, 'metal_id' => 4, 'purity' => '0', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 37, 'metal_id' => 4, 'purity' => '99.9', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 38, 'metal_id' => 3, 'purity' => '99.93', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 39, 'metal_id' => 2, 'purity' => '91.6', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 40, 'metal_id' => 5, 'purity' => '91.6', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 41, 'metal_id' => 1, 'purity' => '91.6', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['purity_id' => 42, 'metal_id' => 7, 'purity' => '91.6', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
