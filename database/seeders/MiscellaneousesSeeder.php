<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MiscellaneousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('miscellaneouses')->truncate(); // ✅ clears old data

        DB::table('miscellaneouses')->insert([
            [
                'id'           => 1,
                'product_code' => 'SAKHA',
                'product_name' => 'SAKHA',
                'uom'          => 'PCS',
                'size'         => null,
                'is_active'    => 'Yes',
                'created_by'   => 'Super Admin',
                'updated_by'   => null,
                'created_at'   => '2025-10-02 09:00:52',
                'updated_at'   => '2025-10-02 09:00:52',
                'deleted_at'   => null,
            ],
            [
                'id'           => 2,
                'product_code' => 'POLA',
                'product_name' => 'POLA',
                'uom'          => 'PCS',
                'size'         => null,
                'is_active'    => 'Yes',
                'created_by'   => 'Super Admin',
                'updated_by'   => null,
                'created_at'   => '2025-10-02 09:01:16',
                'updated_at'   => '2025-10-02 09:01:16',
                'deleted_at'   => null,
            ],
        ]);
    }
}
