<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['id' => 3, 'vendorsite' => '2E-PX-KOL', 'company_id' => 1, 'item_code' => '511155PS7AA100', 'design_num' => '1155PS7', 'description' => '22KT -1155PSC -PENDANT FROM 1155YSC SPL', 'pattern' => 'Plain Gold', 'size' => 'A', 'uom' => 'NOS', 'standard_wt' => 3.64, 'kid' => 1, 'lead_time_karigar' => '8', 'product_lead_time' => '10', 'stone_charge' => '0', 'lab_charge' => '22.5', 'loss' => '1.85', 'purity' => 91.60, 'item_pic' => '', 'kt' => '22', 'pcodechar' => '', 'remarks' => '', 'bulk_upload' => 'No', 'customer_order' => 'No', 'karigar_loss' => null, 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'vendorsite' => '2E-PX-KOL', 'company_id' => 1, 'item_code' => '511300VGPQ1500', 'design_num' => '1300VGP', 'description' => '1300VGP BANGLE -4C COLLECTION', 'pattern' => 'Plain Gold', 'size' => 'Q', 'uom' => 'NOS', 'standard_wt' => 22.06, 'kid' => 1, 'lead_time_karigar' => '8', 'product_lead_time' => '10', 'stone_charge' => '0', 'lab_charge' => '22.5', 'loss' => '1.85', 'purity' => 91.60, 'item_pic' => '', 'kt' => '22', 'pcodechar' => '', 'remarks' => '', 'bulk_upload' => 'No', 'customer_order' => 'No', 'karigar_loss' => null, 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'vendorsite' => '2E-PX-KOL', 'company_id' => 1, 'item_code' => '5114182NWAB100', 'design_num' => '14182NW', 'description' => '22KT -14182WW - ONLY NECKWEAR (VPIM 4)', 'pattern' => 'Plain Gold', 'size' => 'A', 'uom' => 'SET', 'standard_wt' => 51.33, 'kid' => 1, 'lead_time_karigar' => '10', 'product_lead_time' => '12', 'stone_charge' => '0', 'lab_charge' => '22.5', 'loss' => '1.85', 'purity' => 91.60, 'item_pic' => '', 'kt' => '22', 'pcodechar' => '', 'remarks' => '', 'bulk_upload' => 'No', 'customer_order' => 'No', 'karigar_loss' => null, 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'vendorsite' => '2E-PX-KOL', 'company_id' => 1, 'item_code' => '5114182NWAB200', 'design_num' => '14182NW', 'description' => '22KT -14182WW - ONLY NECKWEAR (VPIM 4)', 'pattern' => 'Plain Gold', 'size' => 'A', 'uom' => 'SET', 'standard_wt' => 0.00, 'kid' => 1, 'lead_time_karigar' => '10', 'product_lead_time' => '12', 'stone_charge' => '0', 'lab_charge' => '22.5', 'loss' => '1.85', 'purity' => 91.60, 'item_pic' => '', 'kt' => '22', 'pcodechar' => '', 'remarks' => '', 'bulk_upload' => 'No', 'customer_order' => 'No', 'karigar_loss' => null, 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'vendorsite' => '2E-PX-KOL', 'company_id' => 1, 'item_code' => '5114182WNAB100', 'design_num' => '14182WN', 'description' => '22KT -14182WN -HALF SET (VPIM 4)', 'pattern' => 'Plain Gold', 'size' => 'A', 'uom' => 'SET', 'standard_wt' => 80.17, 'kid' => 1, 'lead_time_karigar' => '10', 'product_lead_time' => '12', 'stone_charge' => '0', 'lab_charge' => '22.5', 'loss' => '1.85', 'purity' => 91.60, 'item_pic' => '', 'kt' => '22', 'pcodechar' => '', 'remarks' => '', 'bulk_upload' => 'No', 'customer_order' => 'No', 'karigar_loss' => null, 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'vendorsite' => '2E-PX-KOL', 'company_id' => 1, 'item_code' => '5114182WQAB100', 'design_num' => '14182WQ', 'description' => '22KT -14182WQ -HALF SET (VPIM 4)', 'pattern' => 'Plain Gold', 'size' => 'A', 'uom' => 'SET', 'standard_wt' => 44.65, 'kid' => 1, 'lead_time_karigar' => '10', 'product_lead_time' => '12', 'stone_charge' => '0', 'lab_charge' => '22.5', 'loss' => '1.85', 'purity' => 91.60, 'item_pic' => '', 'kt' => '22', 'pcodechar' => '', 'remarks' => '', 'bulk_upload' => 'No', 'customer_order' => 'No', 'karigar_loss' => null, 'created_by' => null, 'updated_by' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
