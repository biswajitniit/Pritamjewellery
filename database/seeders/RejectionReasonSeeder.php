<?php

namespace Database\Seeders;

use App\Models\RejectionReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RejectionReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            ['id' => 1, 'reason' => 'OTHER'],
            ['id' => 2, 'reason' => 'PAIR MISMATCH'],
            ['id' => 3, 'reason' => 'SOLDERR NOT OK'],
            ['id' => 4, 'reason' => 'POLISH NOT OK'],
            ['id' => 5, 'reason' => 'DESIGN MISSMATCH'],
            ['id' => 6, 'reason' => 'ENAMEL NOT OK'],
            ['id' => 7, 'reason' => 'FINISH NOT OK'],
            ['id' => 8, 'reason' => 'SCREW MOVEMENT PROBLEM'],
            ['id' => 9, 'reason' => 'PURITY REJECTION'],
            ['id' => 10, 'reason' => 'WEIGHT LESS MORE'],
            ['id' => 11, 'reason' => 'STAMP PROBLEM'],
            ['id' => 12, 'reason' => 'MATERIAL DIFFECT'],
            ['id' => 13, 'reason' => 'SIZE MISSMATCH'],
            ['id' => 14, 'reason' => 'ACID PROBLEM'],
            ['id' => 15, 'reason' => 'COLOUR VARIATION'],
            ['id' => 16, 'reason' => 'THUMBLING PIN'],
            ['id' => 17, 'reason' => 'INGRAVE MISSING'],
            ['id' => 18, 'reason' => 'DT REWORK'],
            ['id' => 19, 'reason' => 'PURITY PROBLEM'],
        ];

        foreach ($reasons as $data) {
            RejectionReason::updateOrCreate(
                ['id' => $data['id']],
                ['reason' => $data['reason']]
            );
        }
    }
}