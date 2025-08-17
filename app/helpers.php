<?php

use App\Models\Product;
use App\Models\Karigar;
use App\Models\Rolepermission;
use App\Models\Stone;
use Illuminate\Support\Facades\DB;

if (!function_exists('Get_item_codes')) {
    function Get_item_codes()
    {
        return Product::orderBy('item_code')->get();
    }
}


if (!function_exists('Get_Stone_codes')) {
    function Get_Stone_codes()
    {
        return Stone::where('is_active', 'Yes')->orderBy('additional_charge_id')->get();
    }
}

if (!function_exists('Get_karigars')) {
    function Get_karigars()
    {
        $karigars = Karigar::where('is_active', 'Yes')->get();
        $karigarhtml = '<select name="karigar_id[]" class="form-select rounded-0"><option selected="selected">Select</option>';
        foreach ($karigars as $karigar) {
            $karigarhtml .= '<option value="' . $karigar->id . '">' . $karigar->kname . '</option>';
        }
        $karigarhtml .= '</select>';
        return $karigarhtml;
    }
}


if (!function_exists('GetItemcodeRate')) {
    function GetItemcodeRate($itemcode)
    {
        return Product::where('item_code', $itemcode)->first('lab_charge');
    }
}

if (!function_exists('GetItemcodeLoss')) {
    function GetItemcodeLoss($itemcode)
    {
        return Product::where('item_code', $itemcode)->first('loss');
    }
}

if (!function_exists('GetProductPurity')) {
    function GetProductPurity($itemcode)
    {
        return Product::where('item_code', $itemcode)->first('purity');
    }
}

if (!function_exists('GetItemcodeAlabStoneChg')) {
    function GetItemcodeAlabStoneChg($itemcode)
    {
        //DB::enableQueryLog();
        $get_a_lab = DB::table('products')->select('productstonedetails.category', 'productstonedetails.pcs', 'productstonedetails.amount')
            ->leftJoin('productstonedetails', 'products.id', '=', 'productstonedetails.product_id')
            ->where('products.item_code', $itemcode)
            ->first();

        //dd(DB::getQueryLog());
    }
}

if (!function_exists('getUserMenuPermission')) {
    function getUserMenuPermission($user_id, $menu_name, $db_column)
    {
        //DB::enableQueryLog();
        return Rolepermission::where('user_id', $user_id)->where('menu_name', $menu_name)->first($db_column);
        //dd(DB::getQueryLog());
    }
}
