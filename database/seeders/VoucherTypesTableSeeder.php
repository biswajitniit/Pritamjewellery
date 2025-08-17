<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vouchertypes')->insert([
            [
                'id' => 8,
                'location_id' => 3,
                'voucher_type' => 'gold_receipt_entry',
                'applicable_year' => '2025-26',
                'applicable_date' => '2025-08-01',
                'startno' => '001',
                'prefix' => 'PHO',
                'suffix' => 'GRE/25-26',
                'status' => 'Active',
                'lastno' => '001',
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-07-31 09:01:29',
                'updated_at' => '2025-07-31 09:03:48',
            ],
            [
                'id' => 9,
                'location_id' => 3,
                'voucher_type' => 'gold_issue_entry',
                'applicable_year' => '2025-26',
                'applicable_date' => '2025-08-01',
                'startno' => '001',
                'prefix' => 'PHO',
                'suffix' => 'GI/25-26',
                'status' => 'Active',
                'lastno' => '001',
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-07-31 09:04:44',
                'updated_at' => '2025-07-31 09:04:44',
            ],
        ]);
    }
}
