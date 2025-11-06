<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialYearsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            [
                'applicable_year'  => '2023-24',
                'start_date'       => '2023-04-01',
                'end_date'         => '2024-03-31',
                'status'           => 'Active',
            ],
            [
                'applicable_year'  => '2024-25',
                'start_date'       => '2024-04-01',
                'end_date'         => '2025-03-31',
                'status'           => 'Active',
            ],
            [
                'applicable_year'  => '2025-26',
                'start_date'       => '2025-04-01',
                'end_date'         => '2026-03-31',
                'status'           => 'Active',
            ],
        ];

        DB::table('financial_years')->insert($years);
    }
}