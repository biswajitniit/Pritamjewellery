<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('uoms')->insert([
            ['uomid' => 'SET7', 'description' => 'SET7', 'is_active' => 'Yes'],
            ['uomid' => 'NOS', 'description' => 'NOS', 'is_active' => 'Yes'],
            ['uomid' => 'PR', 'description' => 'PAIR', 'is_active' => 'Yes'],
            ['uomid' => 'ST3', 'description' => 'SET3', 'is_active' => 'Yes'],
            ['uomid' => 'ST4', 'description' => 'SET4', 'is_active' => 'Yes'],
            ['uomid' => 'ST12', 'description' => 'SET12', 'is_active' => 'Yes'],
            ['uomid' => 'ST6', 'description' => 'SET6', 'is_active' => 'Yes'],
            ['uomid' => 'SET', 'description' => 'SET', 'is_active' => 'Yes'],
            ['uomid' => 'SET5', 'description' => 'SET5', 'is_active' => 'Yes'],
            ['uomid' => 'ST8', 'description' => 'SET8', 'is_active' => 'Yes'],
            ['uomid' => 'N', 'description' => 'N', 'is_active' => 'Yes'],
            ['uomid' => 'xx', 'description' => 'Temp', 'is_active' => 'Yes'],
        ]);
    }
}
