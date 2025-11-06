<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockEffect;
use App\Models\Vouchertype;

class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = DB::table('stock_effects')
            ->select('location_name', DB::raw('SUM(CAST(REPLACE(net_wt, "+", "") AS DECIMAL(10,2))) as total_net_wt'))
            ->where('ledger_name', 'PRITAM COMPANY LIMITED')
            ->whereRaw("CAST(REPLACE(net_wt, '+', '') AS DECIMAL(10,2)) > 0")
            ->groupBy('location_name')
            ->get();
        return view('stocktransfers.list', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|string',
            'to_location_id' => 'required|string|different:location_id',
            'metal_receive_entries_date' => 'required|date',
            'metal_name' => 'required|string',
            'qty' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Get current stock available for validation
            $currentStock = StockEffect::where('location_name', $request->location_id)
                ->where('metal_name', $request->metal_name)
                ->where('ledger_name', 'PRITAM COMPANY LIMITED')
                ->whereRaw("CAST(REPLACE(net_wt, '+', '') AS DECIMAL(10,2)) > 0")
                ->sum(DB::raw("CAST(REPLACE(net_wt, '+', '') AS DECIMAL(10,2))"));

            // Validate if sufficient stock is available
            if ($currentStock < $request->qty) {
                return redirect()->back()->withErrors(['qty' => 'Insufficient stock available. Available stock: ' . $currentStock])->withInput();
            }

            // Generate voucher number for stock transfer based on from location
            $voucherNo = $this->generateStockTransferVoucherNo($request->location_id);

            /*
            // Create outgoing entry (negative for from_location)
            StockEffect::create([
                'vou_no' => $voucherNo,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name' => $request->location_id,
                'ledger_name' => 'Stock Transfer',
                'ledger_code' => '1',
                'ledger_type' => 'Vendor',
                'metal_category' => $this->getMetalCategory($request->metal_name),
                'metal_name' => $request->metal_name,
                'net_wt' => '-' . $request->qty,
                'purity' => null,
                'pure_wt' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create incoming entry (positive for to_location)
            StockEffect::create([
                'vou_no' => $voucherNo,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name' => $request->to_location_id,
                'ledger_name' => 'Stock Transfer',
                'ledger_code' => '1',
                'ledger_type' => 'Vendor',
                'metal_category' => $this->getMetalCategory($request->metal_name),
                'metal_name' => $request->metal_name,
                'net_wt' => '+' . $request->qty,
                'purity' => null,
                'pure_wt' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);*/


            // Create outgoing entry (negative for from_location)
            StockEffect::create([
                'vou_no' => $voucherNo,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name' => $request->location_id,
                'ledger_name' => 'PRITAM COMPANY LIMITED',
                'ledger_code' => '1',
                'ledger_type' => 'Vendor',
                'metal_category' => $this->getMetalCategory($request->metal_name),
                'metal_name' => $request->metal_name,
                'net_wt' => '-' . $request->qty,
                'purity' => null,
                'pure_wt' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create incoming entry (positive for to_location)
            StockEffect::create([
                'vou_no' => $voucherNo,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name' => $request->to_location_id,
                'ledger_name' => 'PRITAM COMPANY LIMITED',
                'ledger_code' => '1',
                'ledger_type' => 'Vendor',
                'metal_category' => $this->getMetalCategory($request->metal_name),
                'metal_name' => $request->metal_name,
                'net_wt' =>  $request->qty,
                'purity' => null,
                'pure_wt' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            DB::commit();

            return redirect()->route('stock-transfers.index')->with('success', 'Stock transferred successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to transfer stock: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Generate stock transfer voucher number based on location
     */
    private function generateStockTransferVoucherNo($locationName)
    {
        // Map location names to location IDs
        $locationMap = [
            'Pritam-HO' => 3,
            'Pritam-HO (Titan)' => 4,
            'SGPL-Ghatal' => 5
        ];

        $locationId = $locationMap[$locationName] ?? null;

        if (!$locationId) {
            throw new \Exception("Invalid location: {$locationName}");
        }

        // Get the voucher type configuration for stock_transfer
        $voucherType = Vouchertype::where('location_id', $locationId)
            ->where('voucher_type', 'stock_transfer')
            ->where('status', 'Active')
            ->first();

        if (!$voucherType) {
            throw new \Exception("Stock transfer voucher type not configured for location: {$locationName}");
        }

        // Get current financial year
        $currentYear = date('Y');
        $nextYear = date('Y') + 1;
        $financialYear = $currentYear . '-' . substr($nextYear, -2);

        // Check if voucher type is applicable for current financial year
        if ($voucherType->applicable_year !== $financialYear) {
            throw new \Exception("Voucher type not applicable for current financial year. Expected: {$financialYear}, Found: {$voucherType->applicable_year}");
        }

        // Get the last used number and increment it
        $lastNo = $voucherType->lastno ? intval($voucherType->lastno) : intval($voucherType->startno) - 1;
        $newNo = $lastNo + 1;

        // Format the number with proper padding based on startno length
        $numberLength = strlen($voucherType->startno);
        $formattedNo = str_pad($newNo, $numberLength, '0', STR_PAD_LEFT);

        // Build voucher number
        $voucherNo = $voucherType->prefix . '/' . $formattedNo . '/' . $voucherType->suffix;

        // Update the lastno in vouchertypes table
        $voucherType->update([
            'lastno' => $formattedNo,
            'updated_at' => now()
        ]);

        return $voucherNo;
    }

    /**
     * Determine metal category based on metal name
     */
    private function getMetalCategory($metalName)
    {
        $metalCategories = [
            'GOLD' => 'Metal',
            'SILVER' => 'Metal',
            'PLATINUM' => 'Metal',
            'SAKHA' => 'Miscellaneous',
            'POLA' => 'Miscellaneous',
            'SEMI PRECIOUS' => 'Stone',
            // Add more mappings as needed
        ];

        return $metalCategories[$metalName] ?? 'Metal';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getlocationwisestockeffectItemname(Request $request)
    {
        $stockeffects = StockEffect::select('metal_name', DB::raw('SUM(CAST(REPLACE(net_wt, "+", "") AS DECIMAL(10,2))) as total_net_wt'))
            ->where('location_name', $request->location_name)
            ->where('ledger_name', 'PRITAM COMPANY LIMITED')
            ->whereRaw("CAST(REPLACE(net_wt, '+', '') AS DECIMAL(10,2)) > 0")
            ->groupBy('metal_name')
            ->get();

        $html = '<select name="metal_name" id="metal_name" class="form-select rounded-0" onchange="setStockAvailable(this)">';
        $html .= '<option value="">Choose...</option>';
        foreach ($stockeffects as $stockeffect) {
            $html .= '<option value="' . $stockeffect->metal_name . '">' . $stockeffect->metal_name . '</option>';
        }
        $html .= '</select>';

        return response()->json(['html' => $html]);
    }

    public function gettolocation(Request $request)
    {
        // $locations = DB::table('stock_effects')
        //     ->select('location_name', DB::raw('SUM(CAST(REPLACE(net_wt, "+", "") AS DECIMAL(10,2))) as total_net_wt'))
        //     ->where('ledger_name', 'PRITAM COMPANY LIMITED')
        //     ->where('location_name', '!=', $request->location_name)
        //     ->whereRaw("CAST(REPLACE(net_wt, '+', '') AS DECIMAL(10,2)) > 0")
        //     ->groupBy('location_name')
        //     ->get();

        $locations = Location::where('location_name', '!=', $request->location_name)->get();


        $html = '<select name="to_location_id" id="to_location_id" class="form-select rounded-0">';
        $html .= '<option value="">Choose...</option>';
        foreach ($locations as $location) {
            $html .= '<option value="' . $location->location_name . '" >' . $location->location_name . '</option>';
        }
        $html .= '</select>';

        return response()->json(['html' => $html]);
    }

    public function getstockavailable(Request $request)
    {
        $request->validate([
            'location_id' => 'required',
            'metal_name' => 'required',
            'ledger_name' => 'required'
        ]);

        try {
            // If you're using location_name directly from the request
            $locationName = $request->location_id; // if location_id actually contains location_name

            // $totalStock = StockEffect::where('metal_name', $request->metal_name)
            //     ->where('location_name', $request->location_id) // if location_id is actually the name
            //     ->where('ledger_name', $request->ledger_name)
            //     ->whereRaw("CAST(REPLACE(REPLACE(net_wt, '+', ''), ',', '') AS DECIMAL(10,3)) > 0")
            //     ->selectRaw("SUM(CAST(REPLACE(REPLACE(net_wt, '+', ''), ',', '') AS DECIMAL(10,3))) as total_net_wt")
            //     ->value('total_net_wt');

            $totalStock = StockEffect::where('metal_name', $request->metal_name)
                ->where('location_name', $request->location_id) // ensure this is the actual location name
                ->where('ledger_name', $request->ledger_name)
                ->selectRaw("SUM(CAST(REPLACE(REPLACE(net_wt, '+', ''), ',', '') AS DECIMAL(10,3))) as total_net_wt")
                ->value('total_net_wt');

            return response()->json([
                'total_stock' => $totalStock ?? 0,
                'formatted_stock' => number_format($totalStock ?? 0, 2)
            ]);
        } catch (\Exception $e) {
            \Log::error('Stock availability error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch stock data'], 500);
        }
    }
}
