<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sizes')->insert([
            ['id' => 1, 'pcode_id' => 17, 'schar' => 'M', 'item_name' => 'BANGLE', 'ssize' => '42.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'pcode_id' => 17, 'schar' => 'N', 'item_name' => 'BANGLE', 'ssize' => '50.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'pcode_id' => 17, 'schar' => 'O', 'item_name' => 'BANGLE', 'ssize' => '52.50 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'pcode_id' => 17, 'schar' => 'P', 'item_name' => 'BANGLE', 'ssize' => '55.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'pcode_id' => 17, 'schar' => 'Q', 'item_name' => 'BANGLE', 'ssize' => '57.50 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'pcode_id' => 17, 'schar' => 'R', 'item_name' => 'BANGLE', 'ssize' => '60.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'pcode_id' => 17, 'schar' => 'S', 'item_name' => 'BANGLE', 'ssize' => '62.50 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 8, 'pcode_id' => 17, 'schar' => 'T', 'item_name' => 'BANGLE', 'ssize' => '65.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 9, 'pcode_id' => 17, 'schar' => 'U', 'item_name' => 'BANGLE', 'ssize' => '67.50 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 10, 'pcode_id' => 17, 'schar' => 'V', 'item_name' => 'BANGLE', 'ssize' => '70.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 11, 'pcode_id' => 17, 'schar' => 'X', 'item_name' => 'BANGLE', 'ssize' => '75.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 12, 'pcode_id' => 17, 'schar' => 'Y', 'item_name' => 'BANGLE', 'ssize' => '80.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'pcode_id' => 7, 'schar' => 'F', 'item_name' => 'BRACELET', 'ssize' => '20.00 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 14, 'pcode_id' => 7, 'schar' => 'G', 'item_name' => 'BRACELET', 'ssize' => '20.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 15, 'pcode_id' => 7, 'schar' => 'H', 'item_name' => 'BRACELET', 'ssize' => '21.00 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 16, 'pcode_id' => 7, 'schar' => 'I', 'item_name' => 'BRACELET', 'ssize' => '21.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 17, 'pcode_id' => 7, 'schar' => 'K', 'item_name' => 'BRACELET', 'ssize' => '22.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 18, 'pcode_id' => 7, 'schar' => 'L', 'item_name' => 'BRACELET', 'ssize' => '23.00 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 19, 'pcode_id' => 7, 'schar' => 'M', 'item_name' => 'BRACELET', 'ssize' => '23.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 20, 'pcode_id' => 7, 'schar' => 'N', 'item_name' => 'BRACELET', 'ssize' => '24.00 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 21, 'pcode_id' => 7, 'schar' => 'P', 'item_name' => 'BRACELET', 'ssize' => '12.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 22, 'pcode_id' => 7, 'schar' => 'R', 'item_name' => 'BRACELET', 'ssize' => '13.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 23, 'pcode_id' => 7, 'schar' => 'T', 'item_name' => 'BRACELET', 'ssize' => '14.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 24, 'pcode_id' => 7, 'schar' => 'U', 'item_name' => 'BRACELET', 'ssize' => '15.00 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 25, 'pcode_id' => 7, 'schar' => 'X', 'item_name' => 'BRACELET', 'ssize' => '16.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 26, 'pcode_id' => 7, 'schar' => 'Y', 'item_name' => 'BRACELET', 'ssize' => '17.00 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 27, 'pcode_id' => 7, 'schar' => 'Z', 'item_name' => 'BRACELET', 'ssize' => '17.50 CM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 28, 'pcode_id' => 8, 'schar' => 'F', 'item_name' => 'CHAIN', 'ssize' => '17 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 29, 'pcode_id' => 8, 'schar' => 'G', 'item_name' => 'CHAIN', 'ssize' => '18 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 30, 'pcode_id' => 8, 'schar' => 'H', 'item_name' => 'CHAIN', 'ssize' => '19 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 31, 'pcode_id' => 8, 'schar' => 'I', 'item_name' => 'CHAIN', 'ssize' => '20 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 32, 'pcode_id' => 8, 'schar' => 'J', 'item_name' => 'CHAIN', 'ssize' => '21 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 33, 'pcode_id' => 8, 'schar' => 'K', 'item_name' => 'CHAIN', 'ssize' => '22 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 34, 'pcode_id' => 8, 'schar' => 'L', 'item_name' => 'CHAIN', 'ssize' => '23 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 35, 'pcode_id' => 8, 'schar' => 'M', 'item_name' => 'CHAIN', 'ssize' => '24 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 36, 'pcode_id' => 8, 'schar' => 'N', 'item_name' => 'CHAIN', 'ssize' => '25 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 37, 'pcode_id' => 8, 'schar' => 'O', 'item_name' => 'CHAIN', 'ssize' => '26 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 38, 'pcode_id' => 8, 'schar' => 'P', 'item_name' => 'CHAIN', 'ssize' => '27 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 39, 'pcode_id' => 8, 'schar' => 'Q', 'item_name' => 'CHAIN', 'ssize' => '28 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 40, 'pcode_id' => 8, 'schar' => 'R', 'item_name' => 'CHAIN', 'ssize' => '29 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 41, 'pcode_id' => 8, 'schar' => 'S', 'item_name' => 'CHAIN', 'ssize' => '30 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 42, 'pcode_id' => 8, 'schar' => 'T', 'item_name' => 'CHAIN', 'ssize' => '31 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 43, 'pcode_id' => 8, 'schar' => 'U', 'item_name' => 'CHAIN', 'ssize' => '32 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 44, 'pcode_id' => 10, 'schar' => 'F', 'item_name' => 'FINGERRING', 'ssize' => '14.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 45, 'pcode_id' => 10, 'schar' => 'G', 'item_name' => 'FINGERRING', 'ssize' => '14.40 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 46, 'pcode_id' => 10, 'schar' => 'H', 'item_name' => 'FINGERRING', 'ssize' => '14.80 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 47, 'pcode_id' => 10, 'schar' => 'I', 'item_name' => 'FINGERRING', 'ssize' => '15.20 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 48, 'pcode_id' => 10, 'schar' => 'K', 'item_name' => 'FINGERRING', 'ssize' => '16.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 49, 'pcode_id' => 10, 'schar' => 'L', 'item_name' => 'FINGERRING', 'ssize' => '16.40 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 50, 'pcode_id' => 10, 'schar' => 'M', 'item_name' => 'FINGERRING', 'ssize' => '16.80 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 51, 'pcode_id' => 10, 'schar' => 'N', 'item_name' => 'FINGERRING', 'ssize' => '17.20 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 52, 'pcode_id' => 10, 'schar' => 'O', 'item_name' => 'FINGERRING', 'ssize' => '17.60 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 53, 'pcode_id' => 10, 'schar' => 'P', 'item_name' => 'FINGERRING', 'ssize' => '18.00 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 54, 'pcode_id' => 10, 'schar' => 'R', 'item_name' => 'FINGERRING', 'ssize' => '18.80 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 55, 'pcode_id' => 10, 'schar' => 'T', 'item_name' => 'FINGERRING', 'ssize' => '19.50 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 56, 'pcode_id' => 10, 'schar' => 'U', 'item_name' => 'FINGERRING', 'ssize' => '19.90 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 57, 'pcode_id' => 10, 'schar' => 'X', 'item_name' => 'FINGERRING', 'ssize' => '21.10 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 58, 'pcode_id' => 10, 'schar' => 'Y', 'item_name' => 'FINGERRING', 'ssize' => '21.50 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 59, 'pcode_id' => 10, 'schar' => 'Z', 'item_name' => 'FINGERRING', 'ssize' => '21.90 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 60, 'pcode_id' => 17, 'schar' => 'F', 'item_name' => 'OVAL BANGLE', 'ssize' => '53*60 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 61, 'pcode_id' => 17, 'schar' => 'G', 'item_name' => 'OVAL BANGLE', 'ssize' => '50*59 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 62, 'pcode_id' => 17, 'schar' => 'W', 'item_name' => 'ROUND BANGLE', 'ssize' => '72.50 MM', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 63, 'pcode_id' => 19, 'schar' => 'F', 'item_name' => 'MANGALSUTRA', 'ssize' => '17 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 64, 'pcode_id' => 19, 'schar' => 'G', 'item_name' => 'MANGALSUTRA', 'ssize' => '18 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 65, 'pcode_id' => 19, 'schar' => 'H', 'item_name' => 'MANGALSUTRA', 'ssize' => '19 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 66, 'pcode_id' => 19, 'schar' => 'I', 'item_name' => 'MANGALSUTRA', 'ssize' => '20 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 67, 'pcode_id' => 19, 'schar' => 'J', 'item_name' => 'MANGALSUTRA', 'ssize' => '21 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 68, 'pcode_id' => 19, 'schar' => 'K', 'item_name' => 'MANGALSUTRA', 'ssize' => '22 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 69, 'pcode_id' => 19, 'schar' => 'L', 'item_name' => 'MANGALSUTRA', 'ssize' => '23 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 70, 'pcode_id' => 19, 'schar' => 'M', 'item_name' => 'MANGALSUTRA', 'ssize' => '24 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 71, 'pcode_id' => 19, 'schar' => 'N', 'item_name' => 'MANGALSUTRA', 'ssize' => '25 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 72, 'pcode_id' => 19, 'schar' => 'O', 'item_name' => 'MANGALSUTRA', 'ssize' => '26 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 73, 'pcode_id' => 19, 'schar' => 'P', 'item_name' => 'MANGALSUTRA', 'ssize' => '27 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 74, 'pcode_id' => 19, 'schar' => 'Q', 'item_name' => 'MANGALSUTRA', 'ssize' => '28 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 75, 'pcode_id' => 19, 'schar' => 'R', 'item_name' => 'MANGALSUTRA', 'ssize' => '29 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 76, 'pcode_id' => 19, 'schar' => 'S', 'item_name' => 'MANGALSUTRA', 'ssize' => '30 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 77, 'pcode_id' => 19, 'schar' => 'T', 'item_name' => 'MANGALSUTRA', 'ssize' => '31 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 78, 'pcode_id' => 19, 'schar' => 'U', 'item_name' => 'MANGALSUTRA', 'ssize' => '32 INCHES', 'is_active' => 'Yes', 'created_by' => null, 'updated_by' => null, 'created_at' => null, 'updated_at' => null],
        ]);
    }
}
