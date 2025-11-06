<?php

namespace App\Http\Controllers;

use App\Enums\ItemCategoryEnum;
use App\Exports\LedgerReportExport;
use App\Models\Metal;
use App\Models\Miscellaneous;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Stone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            'vendor' => $purchase->vendor->name . ($purchase->vendor->vendor_code ? ' - ' . $purchase->vendor->vendor_code : ''),
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
                        $name = $itemable->item_code . ' - ' . str_replace('"', "'", $itemable->description);
                    }
                    break;
            }
            $item->name = $name;
            $items[] = $item;
        }

        return response()->json([
            'invoice_no' => $sale->invoice_no,
            'customer' => $sale->customer->cust_name . ($sale->customer->cust_code ? ' - ' . $sale->customer->cust_code : ''),
            'date' => date('d-m-Y', strtotime($sale->sold_on)),
            'items' => $items,
        ], 200);
    }


    public function report_new()
    {
        $ledger_types = DB::table('stock_effects')->select('ledger_type')->distinct()->get();

        return view('transaction-reports.new_report', compact('ledger_types'));
    }


    public function getLedgerNames(Request $request)
    {
        $ledger_names = DB::table('stock_effects')
            ->where('ledger_type', $request->ledger_type)
            ->distinct()
            ->pluck('ledger_name');

        return response()->json($ledger_names);
    }


    public function generateReport(Request $request)
    {
        $request->validate([
            'ledger_type' => 'required',
            'ledger_name' => 'required',
            'date_from'   => 'required|date',
            'date_to'     => 'required|date|after_or_equal:date_from',
        ]);

        $company = DB::table('companies')->select('cust_name')->first();
        // Fetch filtered data
        $transactions = DB::table('stock_effects')
            ->where('ledger_type', $request->ledger_type)
            ->where('ledger_name', $request->ledger_name)
            ->whereBetween('metal_receive_entries_date', [$request->date_from, $request->date_to])
            ->orderBy('metal_receive_entries_date')
            ->orderBy('vou_no')
            ->get();

        // Initialize running balances
        $running_gold_balance = 0;
        $running_other_balance = 0;

        // Process each transaction
        $processed_transactions = [];

        foreach ($transactions as $index => $transaction) {
            // Get customer name from ledger_name where ledger_type is 'Customer' for this vou_no
            $customer_info = DB::table('stock_effects')
                ->where('vou_no', $transaction->vou_no)
                ->where('ledger_name', $request->ledger_name)
                ->first();

            $row = [
                'vou_no' => $transaction->vou_no,
                'date' => $transaction->metal_receive_entries_date,
                'metal_name' => $transaction->metal_name,
                'purity' => $transaction->purity,
                'wt_pcs' => $transaction->net_wt,
                'opening_gold' => $running_gold_balance,
                'opening_other' => $running_other_balance,
                'issue_gold' => 0,
                'issue_other' => 0,
                'receive_gold' => 0,
                'receive_other' => 0,
                'loss_gold' => 0,
                'closing_gold' => 0,
                'closing_other' => 0,
                'customer_name' => $customer_info->ledger_name ?? 'N/A',
            ];

            // Determine if metal is Gold
            $isGold = strtolower($transaction->metal_name) === 'gold';
            $isLoss = strtolower($transaction->metal_name) === 'loss';

            // Determine which weight to use
            $weight_to_use = $isGold ? $transaction->pure_wt : $transaction->net_wt;

            // Handle Loss separately
            if ($isLoss) {
                $row['loss_gold'] = abs($transaction->pure_wt);
            } else {
                // Issue (positive weight) - Given to worker, increases balance
                if ($weight_to_use > 0) {
                    if ($isGold) {
                        $row['issue_gold'] = $weight_to_use;
                    } else {
                        $row['issue_other'] = $weight_to_use;
                    }
                }
                // Receive (negative weight) - Received back from worker, decreases balance
                elseif ($weight_to_use < 0) {
                    if ($isGold) {
                        $row['receive_gold'] = abs($weight_to_use);
                    } else {
                        $row['receive_other'] = abs($weight_to_use);
                    }
                }
            }

            // Calculate Closing Balance: Opening + Issue - Receive - Loss
            $row['closing_gold'] = $row['opening_gold'] + $row['issue_gold'] - $row['receive_gold'] - $row['loss_gold'];
            $row['closing_other'] = $row['opening_other'] + $row['issue_other'] - $row['receive_other'];

            // Update running balances for next iteration
            $running_gold_balance = $row['closing_gold'];
            $running_other_balance = $row['closing_other'];

            $processed_transactions[] = $row;
        }

        // Calculate totals for summary tables
        $gold_summary = [
            'opening' => $processed_transactions[0]['opening_gold'] ?? 0,
            'issue' => array_sum(array_column($processed_transactions, 'issue_gold')),
            'receive' => array_sum(array_column($processed_transactions, 'receive_gold')),
            'loss' => array_sum(array_column($processed_transactions, 'loss_gold')),
            'closing' => end($processed_transactions)['closing_gold'] ?? 0,
        ];

        // Group by metal name for GOLD items with customer info (using pure_wt)
        $gold_items = DB::table('stock_effects')
            ->where('ledger_type', $request->ledger_type)
            ->where('ledger_name', $request->ledger_name)
            ->whereBetween('metal_receive_entries_date', [$request->date_from, $request->date_to])
            ->whereRaw('LOWER(metal_name) = ?', ['gold'])
            ->select(
                'metal_name',
                'purity',
                'vou_no',
                DB::raw('SUM(CASE WHEN pure_wt > 0 THEN pure_wt ELSE 0 END) as total_issue'),
                DB::raw('SUM(CASE WHEN pure_wt < 0 THEN ABS(pure_wt) ELSE 0 END) as total_receive')
            )
            ->groupBy('metal_name', 'purity', 'vou_no')
            ->get();

        // Prepare gold summary table data
        $gold_summary_table = [];
        foreach ($gold_items as $item) {
            // Get customer name for this voucher
            $customer_info = DB::table('stock_effects')
                ->where('vou_no', $item->vou_no)
                ->first();

            $gold_summary_table[] = [
                'description' => $item->metal_name,
                'customer' => $customer_info->ledger_name ?? 'N/A',
                'purity' => number_format($item->purity, 3),
                'opening' => number_format($gold_summary['opening'], 3),
                'receive' => number_format($item->total_receive, 3),
                'issue' => number_format($item->total_issue, 3),
                'closing' => number_format($gold_summary['closing'], 3),
            ];
        }

        // Remove duplicates from gold summary (if same customer appears multiple times)
        $gold_summary_table = collect($gold_summary_table)->unique(function ($item) {
            return $item['customer'] . $item['purity'];
        })->values()->all();

        // Group by metal name for NON-GOLD items (Other Articles) using net_wt
        $other_items = DB::table('stock_effects')
            ->where('ledger_type', $request->ledger_type)
            ->where('ledger_name', $request->ledger_name)
            ->whereBetween('metal_receive_entries_date', [$request->date_from, $request->date_to])
            ->whereRaw('LOWER(metal_name) != ?', ['gold'])
            ->whereRaw('LOWER(metal_name) != ?', ['loss'])
            ->select(
                'metal_name',
                'purity',
                DB::raw('SUM(CASE WHEN net_wt > 0 THEN net_wt ELSE 0 END) as total_issue'),
                DB::raw('SUM(CASE WHEN net_wt < 0 THEN ABS(net_wt) ELSE 0 END) as total_receive')
            )
            ->groupBy('metal_name', 'purity')
            ->get();

        // Prepare other articles summary table data
        $other_summary_table = [];
        $other_opening = $processed_transactions[0]['opening_other'] ?? 0;
        $other_closing = end($processed_transactions)['closing_other'] ?? 0;

        foreach ($other_items as $item) {
            // Calculate closing for other items: Opening + Issue - Receive
            $item_closing = $other_opening + $item->total_issue - $item->total_receive;

            $other_summary_table[] = [
                'description' => $item->metal_name,
                'purity' => $item->purity ? number_format($item->purity, 3) : 'N/A',
                'opening' => number_format($other_opening, 3),
                'receive' => number_format($item->total_receive, 3),
                'issue' => number_format($item->total_issue, 3),
                'closing' => number_format($item_closing, 3),
            ];
        }

        return view('transaction-reports.view_report', [
            'company_name' => $company->cust_name ?? null,
            'ledger_name' => $request->ledger_name,
            'ledger_type' => $request->ledger_type,
            'date_from'   => $request->date_from,
            'date_to'     => $request->date_to,
            'transactions' => $processed_transactions,
            'gold_summary_table' => $gold_summary_table,
            'other_summary_table' => $other_summary_table,
            'gold_summary' => $gold_summary,
        ]);
    }


    public function exportToExcel(Request $request)
    {
        $request->validate([
            'ledger_type' => 'required',
            'ledger_name' => 'required',
            'date_from'   => 'required|date',
            'date_to'     => 'required|date|after_or_equal:date_from',
        ]);

        $company = DB::table('companies')->select('cust_name')->first();

        // Fetch filtered data (same logic as generateReport)
        $transactions = DB::table('stock_effects')
            ->where('ledger_type', $request->ledger_type)
            ->where('ledger_name', $request->ledger_name)
            ->whereBetween('metal_receive_entries_date', [$request->date_from, $request->date_to])
            ->orderBy('metal_receive_entries_date')
            ->orderBy('vou_no')
            ->get();

        // Initialize running balances
        $running_gold_balance = 0;
        $running_other_balance = 0;
        $processed_transactions = [];

        foreach ($transactions as $transaction) {
            $customer_info = DB::table('stock_effects')
                ->where('vou_no', $transaction->vou_no)
                ->where('ledger_name', $request->ledger_name)
                ->first();

            $row = [
                'vou_no' => $transaction->vou_no,
                'date' => $transaction->metal_receive_entries_date,
                'metal_name' => $transaction->metal_name,
                'purity' => $transaction->purity,
                'wt_pcs' => $transaction->net_wt,
                'opening_gold' => $running_gold_balance,
                'opening_other' => $running_other_balance,
                'issue_gold' => 0,
                'issue_other' => 0,
                'receive_gold' => 0,
                'receive_other' => 0,
                'loss_gold' => 0,
                'closing_gold' => 0,
                'closing_other' => 0,
                'customer_name' => $customer_info->ledger_name ?? 'N/A',
            ];

            $isGold = strtolower($transaction->metal_name) === 'gold';
            $isLoss = strtolower($transaction->metal_name) === 'loss';
            $weight_to_use = $isGold ? $transaction->pure_wt : $transaction->net_wt;

            if ($isLoss) {
                $row['loss_gold'] = abs($transaction->pure_wt);
            } else {
                if ($weight_to_use > 0) {
                    if ($isGold) {
                        $row['issue_gold'] = $weight_to_use;
                    } else {
                        $row['issue_other'] = $weight_to_use;
                    }
                } elseif ($weight_to_use < 0) {
                    if ($isGold) {
                        $row['receive_gold'] = abs($weight_to_use);
                    } else {
                        $row['receive_other'] = abs($weight_to_use);
                    }
                }
            }

            $row['closing_gold'] = $row['opening_gold'] + $row['issue_gold'] - $row['receive_gold'] - $row['loss_gold'];
            $row['closing_other'] = $row['opening_other'] + $row['issue_other'] - $row['receive_other'];

            $running_gold_balance = $row['closing_gold'];
            $running_other_balance = $row['closing_other'];

            $processed_transactions[] = $row;
        }

        // Calculate gold summary
        $gold_summary = [
            'opening' => $processed_transactions[0]['opening_gold'] ?? 0,
            'issue' => array_sum(array_column($processed_transactions, 'issue_gold')),
            'receive' => array_sum(array_column($processed_transactions, 'receive_gold')),
            'loss' => array_sum(array_column($processed_transactions, 'loss_gold')),
            'closing' => end($processed_transactions)['closing_gold'] ?? 0,
        ];

        // Gold items
        $gold_items = DB::table('stock_effects')
            ->where('ledger_type', $request->ledger_type)
            ->where('ledger_name', $request->ledger_name)
            ->whereBetween('metal_receive_entries_date', [$request->date_from, $request->date_to])
            ->whereRaw('LOWER(metal_name) = ?', ['gold'])
            ->select(
                'metal_name',
                'purity',
                'vou_no',
                DB::raw('SUM(CASE WHEN pure_wt > 0 THEN pure_wt ELSE 0 END) as total_issue'),
                DB::raw('SUM(CASE WHEN pure_wt < 0 THEN ABS(pure_wt) ELSE 0 END) as total_receive')
            )
            ->groupBy('metal_name', 'purity', 'vou_no')
            ->get();

        $gold_summary_table = [];
        foreach ($gold_items as $item) {
            $customer_info = DB::table('stock_effects')
                ->where('vou_no', $item->vou_no)
                ->first();

            $gold_summary_table[] = [
                'description' => $item->metal_name,
                'customer' => $customer_info->ledger_name ?? 'N/A',
                'purity' => $item->purity,
                'opening' => $gold_summary['opening'],
                'receive' => $item->total_receive,
                'issue' => $item->total_issue,
                'closing' => $gold_summary['closing'],
            ];
        }

        $gold_summary_table = collect($gold_summary_table)->unique(function ($item) {
            return $item['customer'] . $item['purity'];
        })->values()->all();

        // Other items
        $other_items = DB::table('stock_effects')
            ->where('ledger_type', $request->ledger_type)
            ->where('ledger_name', $request->ledger_name)
            ->whereBetween('metal_receive_entries_date', [$request->date_from, $request->date_to])
            ->whereRaw('LOWER(metal_name) != ?', ['gold'])
            ->whereRaw('LOWER(metal_name) != ?', ['loss'])
            ->select(
                'metal_name',
                'purity',
                DB::raw('SUM(CASE WHEN net_wt > 0 THEN net_wt ELSE 0 END) as total_issue'),
                DB::raw('SUM(CASE WHEN net_wt < 0 THEN ABS(net_wt) ELSE 0 END) as total_receive')
            )
            ->groupBy('metal_name', 'purity')
            ->get();

        $other_summary_table = [];
        $other_opening = $processed_transactions[0]['opening_other'] ?? 0;

        foreach ($other_items as $item) {
            $item_closing = $other_opening + $item->total_issue - $item->total_receive;

            $other_summary_table[] = [
                'description' => $item->metal_name,
                'purity' => $item->purity,
                'opening' => $other_opening,
                'receive' => $item->total_receive,
                'issue' => $item->total_issue,
                'closing' => $item_closing,
            ];
        }

        $data = [
            'company_name' => $company->cust_name ?? 'Company Name',
            'ledger_name' => $request->ledger_name,
            'ledger_type' => $request->ledger_type,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'transactions' => $processed_transactions,
            'gold_summary_table' => $gold_summary_table,
            'other_summary_table' => $other_summary_table,
            'gold_summary' => $gold_summary,
        ];

        $filename = 'Ledger_Report_' . $request->ledger_name . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new LedgerReportExport($data), $filename);
    }
}
