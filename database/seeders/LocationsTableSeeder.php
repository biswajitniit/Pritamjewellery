<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            [
                'id' => 3,
                'location_name' => 'Pritam-HO',
                'location_address' => '39/4/1, A K Mukherjee Road, Baranagar, Noapara, Kolkata - 700090',
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-07-31 08:53:33',
                'updated_at' => '2025-07-31 08:53:33',
            ],
            [
                'id' => 4,
                'location_name' => 'Pritam-HO (Titan)',
                'location_address' => '39/4/1, A K Mukherjee Road, Baranagar, Noapara, Kolkata - 700090',
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-07-31 08:54:21',
                'updated_at' => '2025-07-31 08:54:21',
            ],
            [
                'id' => 5,
                'location_name' => 'SGPL-Ghatal',
                'location_address' => '647/4/3, GHA, 1st Floor, Room 1, Konnagar, Paschim Medinipur, 721212',
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-07-31 08:57:49',
                'updated_at' => '2025-07-31 08:57:49',
            ],
        ]);
    }
}
