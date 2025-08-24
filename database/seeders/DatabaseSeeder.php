<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your other seeders
        $this->call([
            UsersTableSeeder::class,
            RolePermissionUsersTableSeeder::class,
            RolePermissionsTableSeeder::class,
            PcodesTableSeeder::class,
            UomsTableSeeder::class,
            SizesTableSeeder::class,
            PatternsTableSeeder::class,
            StonesTableSeeder::class,
            CustomersTableSeeder::class,
            KarigarTableSeeder::class,
            ProductsTableSeeder::class,
            MetalTableSeeder::class,
            MetalPurityTableSeeder::class,
            LocationsTableSeeder::class,
            VoucherTypesTableSeeder::class,
        ]);
    }
}
