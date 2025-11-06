<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendors')->insert([
            'id'             => 1,
            'vid'            => 'V002',
            'name'           => 'Jagadamba',
            'vendor_code'    => '',
            'address'        => '4A, camac street, opposite of pantaloons, Kolkata- 700016',
            'city'           => 'Kolkata',
            'state'          => 'West Bengal',
            'phone'          => '9330915916',
            'mobile'         => '',
            'contact_person' => 'Sandhya Mam',
            'gstin'          => '',
            'statecode'      => '19',
            'is_active'      => 'Yes',
            'created_by'     => 'Super Admin',
            'updated_by'     => null,
            'created_at'     => Carbon::parse('2025-10-25 07:58:13'),
            'updated_at'     => Carbon::parse('2025-10-25 07:58:13'),
        ]);
    }
}
