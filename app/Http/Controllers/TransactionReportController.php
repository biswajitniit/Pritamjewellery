<?php

namespace App\Http\Controllers;

use App\Enums\ItemCategoryEnum;
use App\Models\Metal;
use App\Models\Miscellaneous;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Stone;
use Exception;
use Illuminate\Http\Request;

class TransactionReportController extends Controller
{
    public function purchase_ledger()
    {
        $purchases = Purchase::with(['vendor:id,name,vendor_code'])
            ->withSum('items', 'total_amount')
            ->simplePaginate(25);

        return view('transaction-reports.purchase-ledger', compact('purchases'));
    }


    public function purchase_ledger_items(string $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            throw new Exception('Invalid purchase id!', 404);
        }
        $items = [];
        foreach ($purchase->items as $item) {
            $name = '';
            switch ($item->itemable_type) {
                case ItemCategoryEnum::Metal->value:
                    $itemable = Metal::find($item->itemable_id);
                    if ($itemable) {
                        $name = $itemable->metal_name;
                    }
                    break;
                case ItemCategoryEnum::Findings->value:
                    $itemable = Stone::find($item->itemable_id);
                    if ($itemable) {
                        $name = $itemable->description;
                    }
                    break;
                case ItemCategoryEnum::Miscellaneous->value:
                    $itemable = Miscellaneous::find($item->itemable_id);
                    if ($itemable) {
                        $name = $itemable->product_name;
                    }
                    break;
            }
            $item->name = $name;
            $items[] = $item;
        }

        return response()->json([
            'invoice_no' => $purchase->invoice_no,
            'vendor' => $purchase->vendor->name . ($purchase->vendor->vendor_code ? ' - '.$purchase->vendor->vendor_code : ''),
            'date' => date('d-m-Y', strtotime($purchase->purchase_on)),
            'items' => $items,
        ], 200);
    }


    public function sales_register()
    {
        $sales = Sale::with(['customer:id,cust_name,cust_code'])
            ->withSum('items', 'total_amount')
            ->simplePaginate(25);

        return view('transaction-reports.sales-register', compact('sales'));
    }


    public function sales_register_items(string $id)
    {
        $sale = Sale::find($id);
        if (!$sale) {
            throw new Exception('Invalid sale id!', 404);
        }
        $items = [];
        foreach ($sale->items as $item) {
            $name = '';
            switch ($item->itemable_type) {
                case ItemCategoryEnum::Metal->value:
                    $itemable = Metal::find($item->itemable_id);
                    if ($itemable) {
                        $name = $itemable->metal_name;
                    }
                    break;
                case ItemCategoryEnum::Findings->value:
                    $itemable = Stone::find($item->itemable_id);
                    if ($itemable) {
                        $name = $itemable->description;
                    }
                    break;
                case ItemCategoryEnum::Miscellaneous->value:
                    $itemable = Miscellaneous::find($item->itemable_id);
                    if ($itemable) {
                        $name = $itemable->product_name;
                    }
                    break;
                case ItemCategoryEnum::Product->value:
                    $itemable = Product::find($item->itemable_id);
                    if ($itemable) {
                        $name = $itemable->item_code.' - '.str_replace('"', "'", $itemable->description);
                    }
                    break;
            }
            $item->name = $name;
            $items[] = $item;
        }

        return response()->json([
            'invoice_no' => $sale->invoice_no,
            'customer' => $sale->customer->cust_name . ($sale->customer->cust_code ? ' - '.$sale->customer->cust_code : ''),
            'date' => date('d-m-Y', strtotime($sale->sold_on)),
            'items' => $items,
        ], 200);
    }
}
