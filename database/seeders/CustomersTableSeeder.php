<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'id' => 1,
                'cid' => 'TCL KOL',
                'cust_name' => 'TITAN COMPANY LIMITED',
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
            ],
            [
                'id' => 2,
                'cid' => 'TCL HWH',
                'cust_name' => 'TITAN COMPANY LIMITED',
                'cust_code' => null,
                'address' => 'Gems & Jewellery Park, SDF Block - B, 5th Floor, Mouza - Ankurhati',
                'city' => 'Howrah - 711409',
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
            ],
            [
                'id' => 3,
                'cid' => 'TCL HSR',
                'cust_name' => 'TITAN COMPANY LIMITED',
                'cust_code' => null,
                'address' => '29,Sipcot Industrial Complex, SIKKIM',
                'city' => 'Hosur - 635126',
                'state' => 'Tamilnadu',
                'phone' => '0',
                'mobile' => '0',
                'cont_person' => 'NA',
                'gstin' => '33AAACT5131A1Z4',
                'statecode' => '33',
                'is_validation' => 'Yes',
                'is_active' => 'Yes',
                'created_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'cid' => 'PRJ',
                'cust_name' => 'PRITAM JEWELLERS PRIVATE LIMITED',
                'cust_code' => null,
                'address' => '',
                'city' => '',
                'state' => '',
                'phone' => '',
                'mobile' => '',
                'cont_person' => '',
                'gstin' => '',
                'statecode' => '19',
                'is_validation' => 'No',
                'is_active' => 'Yes',
                'created_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'cid' => 'TBZ',
                'cust_name' => 'Tribhubandas Bhimji Zavery',
                'cust_code' => '91',
                'address' => 'Mumbai, Zavery Bazar',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'phone' => null,
                'mobile' => '7575002009',
                'cont_person' => 'Uday k Mehta',
                'gstin' => '27AACCT7182P1ZL',
                'statecode' => '27',
                'is_validation' => 'Yes',
                'is_active' => 'Yes',
                'created_by' => null,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
