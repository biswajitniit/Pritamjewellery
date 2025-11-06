<?php

use App\Models\FinancialYear;
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


if (!function_exists('convertToIndianCurrencyWords')) {
    function convertToIndianCurrencyWords($number)
    {
        $no = floor($number);
        $decimal = round($number - $no, 2) * 100;

        $words = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        );

        $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
        $str = [];

        $i = 0;
        while ($no > 0) {
            $divider = ($i == 2) ? 10 : 100;
            $number = $no % $divider;
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;

            if ($number) {
                $plural = ($counter = count($str)) && $number > 9 ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }

        $result = implode('', array_reverse($str));
        $rupees = $result ? $result . 'Rupees' : '';
        $paise = ($decimal) ? " and " . $words[floor($decimal / 10) * 10] . " " . $words[$decimal % 10] . ' Paise' : '';

        return trim($rupees . $paise . ' Only');
    }

    if (!function_exists('getFinancialYearIdByDate')) {
        function getFinancialYearIdByDate($date)
        {
            return FinancialYear::whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->value('id');
        }
    }
}