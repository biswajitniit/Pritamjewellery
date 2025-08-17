<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => '2025-07-11 10:54:53',
                'password' => '$2y$12$qsEI1YXmus.fSLvxPd2WFOvYKQKFMT4sA000tSuHCvJRhCki5Rzqi',
                'mobile' => '8768624650',
                'status' => 'Yes',
                'remember_token' => 'PqJF75iKb7',
                'created_at' => '2025-07-11 10:54:53',
                'updated_at' => '2025-07-11 10:54:53',
            ],
            [
                'id' => 2,
                'name' => 'Ashim Debnath',
                'email' => 'asimdebnathpritam@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$nCzVUQsVWx8SOcs0.h5m8Oqjxup971GeQAh7et643uvKwKW1siuD6',
                'mobile' => '8100128181',
                'status' => 'Yes',
                'remember_token' => null,
                'created_at' => '2025-07-31 08:39:02',
                'updated_at' => '2025-07-31 08:39:02',
            ],
            [
                'id' => 3,
                'name' => 'Avijit Hota',
                'email' => 'justhota007@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$LYHSvfEly8iGiLGYpt6UNuiUiHyfLAzclRjUvZDfvTxsRPVyCFMga',
                'mobile' => '7980404627',
                'status' => 'Yes',
                'remember_token' => null,
                'created_at' => '2025-08-11 00:52:55',
                'updated_at' => '2025-08-11 00:52:55',
            ],
            [
                'id' => 4,
                'name' => 'Ankita Chakraborty',
                'email' => 'ankita555ch@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$Gp8/GzpbFB/OJ0blFHdIU.2G7ow6cxphFJMbVM16eXZIcSLZz5SlS',
                'mobile' => '8597785857',
                'status' => 'Yes',
                'remember_token' => null,
                'created_at' => '2025-08-11 00:53:46',
                'updated_at' => '2025-08-11 00:53:46',
            ],
            [
                'id' => 5,
                'name' => 'Ritu Poddar',
                'email' => 'pritamjewellers2023@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$COWFXxmmccgRrhkZ6ULw3Of3EocJh3UvXAhSn.ngd8UN6swZFLu7q',
                'mobile' => '9883999702',
                'status' => 'Yes',
                'remember_token' => null,
                'created_at' => '2025-08-11 00:54:31',
                'updated_at' => '2025-08-11 00:54:31',
            ],
            [
                'id' => 6,
                'name' => 'Maitry Chakraborty',
                'email' => 'maxitry212@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$rDflYtzBt1j0ZEamyE6Njum1.6ePSgSsfmwsM/OsZzqbOep.VdO.C',
                'mobile' => '8334930523',
                'status' => 'Yes',
                'remember_token' => null,
                'created_at' => '2025-08-11 00:55:13',
                'updated_at' => '2025-08-11 00:55:13',
            ],
            [
                'id' => 7,
                'name' => 'Pritam Chakraborty',
                'email' => 'pritamchakrabortyy2004@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$lWbois9d2T2pX.HXKI6OS.cnAZFNgffNFTSofauAwr6dZO2CKZvOG',
                'mobile' => '9836787291',
                'status' => 'Yes',
                'remember_token' => null,
                'created_at' => '2025-08-11 00:55:55',
                'updated_at' => '2025-08-11 00:55:55',
            ],
            [
                'id' => 8,
                'name' => 'Goutam Chakraborty',
                'email' => 'pritamjewellers@yahoo.com',
                'email_verified_at' => null,
                'password' => '$2y$12$9DEL0BOhwWtu.BIbHqIVu.RkAoykBb3RxPeklrAz.3/Z1rqoGL7TW',
                'mobile' => '9830916917',
                'status' => 'Yes',
                'remember_token' => null,
                'created_at' => '2025-08-11 00:56:36',
                'updated_at' => '2025-08-11 00:56:36',
            ],
        ]);
    }
}
