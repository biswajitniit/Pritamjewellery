<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stones')->insert([
            [
                'additional_charge_id' => '1',
                'category'             => 'stone',
                'description'          => 'SEMI PRECIOUS',
                'uom'                  => 'GM',
                'is_active'            => 'Yes',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'additional_charge_id' => '2',
                'category'             => 'stone',
                'description'          => 'KUNDAN',
                'uom'                  => 'PCS',
                'is_active'            => 'Yes',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'additional_charge_id' => '3',
                'category'             => 'stone',
                'description'          => 'TAKKAR',
                'uom'                  => 'PCS',
                'is_active'            => 'Yes',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'additional_charge_id' => '4',
                'category'             => 'stone',
                'description'          => 'SETTING STONE',
                'uom'                  => 'PCS',
                'is_active'            => 'Yes',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        ]);
    }
}
