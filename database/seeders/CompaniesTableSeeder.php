<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'id' => 1,
                'cid' => 'Pritam',
                'cust_name' => 'PRITAM COMPANY LIMITED',
                'cust_code' => '51',
                'address' => '22, Camac Street, Block - B, 5th Floor',
                'city' => 'Kolkata - 700016',
                'state' => 'West Bengal',
                'phone' => '0',
                'mobile' => '0',
                'cont_person' => 'RAJIVLOCHAN',
                'gstin' => '19AAACT5131A1ZU',
                'statecode' => '19',
                'is_validation' => 'Yes',
                'is_active' => 'Yes',
                'created_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
