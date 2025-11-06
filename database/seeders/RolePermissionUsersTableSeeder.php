<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rolepermissionusers')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-07-11 11:01:28',
                'updated_at' => '2025-07-11 11:01:28',
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-07-31 08:39:40',
                'updated_at' => '2025-07-31 08:39:40',
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-08-11 00:57:42',
                'updated_at' => '2025-08-11 00:57:42',
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-08-11 00:58:08',
                'updated_at' => '2025-08-11 00:58:08',
            ],
            [
                'id' => 5,
                'user_id' => 5,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-08-11 00:58:36',
                'updated_at' => '2025-08-11 00:58:36',
            ],
            [
                'id' => 6,
                'user_id' => 6,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-08-11 00:58:53',
                'updated_at' => '2025-08-11 00:58:53',
            ],
            [
                'id' => 7,
                'user_id' => 7,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-08-11 00:59:09',
                'updated_at' => '2025-08-11 00:59:09',
            ],
            [
                'id' => 8,
                'user_id' => 8,
                'created_by' => 'Super Admin',
                'updated_by' => null,
                'created_at' => '2025-08-11 00:59:24',
                'updated_at' => '2025-08-11 00:59:24',
            ],
        ]);
    }
}
