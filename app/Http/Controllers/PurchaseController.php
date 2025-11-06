<?php

namespace App\Http\Controllers;

use App\Enums\ItemCategoryEnum;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Metal;
use App\Models\Metalpurity;
use App\Models\Miscellaneous;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stone;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StockEffect;
use App\Models\Location;
use App\Models\Metalreceiveentry;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    protected $itemTypes = [
        ItemCategoryEnum::Metal->value => 'Metals',
        ItemCategoryEnum::Findings->value => 'Findings',
        ItemCategoryEnum::Miscellaneous->value => 'Miscellaneous',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with(['vendor:id,name,vendor_code'])
            ->withCount('items')
            ->simplePaginate(25);

        return view('purchases.list', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::where('is_active', 'Yes')->get();
        $metals = Metal::select('metal_id', 'metal_name')->where('is_active', 'Yes')->get()->map(function ($metal) {
            return [
                'id' => $metal->metal_id,
                'name' => $metal->metal_name,
            ];
        });
        $findings = Stone::select('id', 'description')->where('is_active', 'Yes')->get()->map(function ($finding) {
            return [
                'id' => $finding->id,
                'name' => $finding->description,
            ];
        });
        $miscellaneouses = Miscellaneous::select('id', 'product_name')->where('is_active', 'Yes')->get()->map(function ($miscellaneous) {
            return [
                'id' => $miscellaneous->id,
                'name' => $miscellaneous->product_name,
            ];
        });
        $gold = Metal::select('metal_id')->where('metal_name', 'GOLD')->first();
        $purities = Metalpurity::select('purity_id', 'purity')
            ->where('is_active', 'Yes')
            ->where('metal_id', @$gold->metal_id ?? null)
            ->get();
        $locations = Location::get();

        return view('purchases.add', [
            'locations' => $locations,
            'vendors' => $vendors,
            'itemTypes' => $this->itemTypes,
            'purities' => $purities,
            'metals' => json_encode($metals),
            'findings' => json_encode($findings),
            'miscellaneouses' => json_encode($miscellaneouses),
        ]);
    }

    public function createStockEffectEntries(array $data, Customer $customer, $vendor): void
    {
        // Company Stock Effect
        StockEffect::create(array_merge($data, [
            'ledger_name' => $customer->cust_name,
            'ledger_code' => $customer->id,
            'ledger_type' => 'Company',
        ]));

        // Company Stock Effect
        // StockEffect::create(array_merge($data, [
        //     'ledger_name' => $vendor->name,
        //     'ledger_code' => $vendor->id,
        //     'ledger_type' => 'Vendor',
        // ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate(
                [
                    'location_id'        => 'required|exists:locations,id',
                    'vendor'             => 'required|integer',
                    'invoice_no'         => 'required',
                    'purchase_on'        => 'required',
                    'item_type'          => 'required|array',
                    'item_type.*'        => 'required|string',
                    'item'               => 'required|array',
                    'item.*'             => 'required|integer',
                    'purity'             => 'nullable|array',
                    'purity.*'           => 'nullable|integer',
                    'hsn'                => 'required|array',
                    'hsn.*'              => 'required|string',
                    'rate'               => 'required|array',
                    'rate.*'             => 'required|numeric',
                    'quantity'           => 'required|array',
                    'quantity.*'         => 'required|integer|min:1',
                    'gstin_percent'      => 'required|array',
                    'gstin_percent.*'    => 'required|numeric',
                ],
                [
                    'location_id'                => 'Location is required', // custom message
                    'vendor.required'            => 'Vendor is required', // custom message
                    'invoice_no.required'        => 'Invoice No. is required', // custom message
                    'purchase_on.required'       => 'Purchase Date is required', // custom message
                ]
            );

            $items = [];
            $purchase = Purchase::create([
                'location_id' => strip_tags($request->location_id),
                'vendor_id'   => strip_tags($request->vendor),
                'invoice_no'  => strip_tags($request->invoice_no),
                'purchase_on' => strip_tags($request->purchase_on),
                'created_by'  => Auth::user()->name,
            ]);


            $financialYearId = getFinancialYearIdByDate($request->purchase_on);
            if (! $financialYearId) {
                throw new \Exception('No financial year found for the given date: ' . $request->purchase_on);
            }
            // Get customer safely
            $customer = Customer::find(2);
            if (! $customer) {
                throw new \Exception('Customer not found with ID 1.');
            }

            foreach ($request->item_type as $key => $itemType) {
                $itemable_type = null;
                $itemable_id = null;
                $metalCategory = null;
                $metalName     = null;
                switch ($itemType) {
                    case ItemCategoryEnum::Metal->value:
                        $itemable_type = Metal::class;
                        $itemable_id = Metal::find($request->item[$key])->metal_id;
                        $metalCategory = 'Metal';
                        $metalName     = Metal::find($request->item[$key])->metal_name;

                        if ($metalCategory == 'Metal' && $metalName == 'GOLD') {
                            $quantity = (int) $request->quantity[$key] ?? 0;
                            // Compute purity-based weight if available
                            if (!empty($request->purity[$key])) {
                                $metalPurity = Metalpurity::where('purity_id', $request->purity[$key])->value('purity');
                                $pureWt = round(($quantity * $metalPurity) / 100, 3);
                            } else {
                                $metalPurity = null;
                                $pureWt = null;
                            }

                            // Save Metal Receive Entry for GOLD only
                            Metalreceiveentry::create([
                                'metalreceiveentries_id'     => (string) Str::uuid(),
                                'financial_year_id'          => $financialYearId,
                                'location_id'                => $request->location_id,
                                'vou_no'                     => $purchase->invoice_no,
                                'metal_receive_entries_date' => $request->purchase_on,
                                'customer_id'                => $customer->id,
                                'cust_name'                  => $customer->cust_name,
                                'item_type'                  => $metalCategory,
                                'item'                       => $itemable_id,
                                'purity_id'                  => $request->purity[$key] ?? null,
                                'weight'                     => $quantity ?? 0,
                                'balance_qty'                => $quantity ?? 0,
                                'dv_no'                      => $purchase->invoice_no ?? null,
                                'dv_date'                    => $request->purchase_on ?? null,
                                'created_by'                 => Auth::user()->name ?? 'System',
                            ]);
                        } else {
                            $quantity = (int) $request->quantity[$key] ?? 0;
                            // Compute purity-based weight if available
                            if (!empty($request->purity[$key])) {
                                $metalPurity = Metalpurity::where('purity_id', $request->purity[$key])->value('purity');
                                $pureWt = round(($quantity * $metalPurity) / 100, 3);
                            } else {
                                $metalPurity = null;
                                $pureWt = null;
                            }

                            // Save Metal Receive Entry for GOLD only
                            Metalreceiveentry::create([
                                'metalreceiveentries_id'     => (string) Str::uuid(),
                                'financial_year_id'          => $financialYearId,
                                'location_id'                => $request->location_id,
                                'vou_no'                     => $purchase->invoice_no,
                                'metal_receive_entries_date' => $request->purchase_on,
                                'customer_id'                => $customer->id,
                                'cust_name'                  => $customer->cust_name,
                                'item_type'                  => $metalCategory,
                                'item'                       => $itemable_id,
                                'purity_id'                  => null,
                                'weight'                     => (int) $request->quantity[$key] ?? 0,
                                'balance_qty'                => (int) $request->quantity[$key] ?? 0,
                                'dv_no'                      => $purchase->invoice_no ?? null,
                                'dv_date'                    => $request->purchase_on ?? null,
                                'created_by'                 => Auth::user()->name ?? 'System',
                            ]);
                        }


                        break;
                    case ItemCategoryEnum::Findings->value:
                        $itemable_type = Stone::class;
                        $itemable_id = Stone::find($request->item[$key])->id;
                        $metalCategory = 'Stone';
                        $metalName     = Stone::find($request->item[$key])->description;

                        // Save Metal Receive Entry for GOLD only
                        Metalreceiveentry::create([
                            'metalreceiveentries_id'     => (string) Str::uuid(),
                            'financial_year_id'          => $financialYearId,
                            'location_id'                => $request->location_id,
                            'vou_no'                     => $purchase->invoice_no,
                            'metal_receive_entries_date' => $request->purchase_on,
                            'customer_id'                => $customer->id,
                            'cust_name'                  => $customer->cust_name,
                            'item_type'                  => $metalCategory,
                            'item'                       => $itemable_id,
                            'purity_id'                  => null,
                            'weight'                     => (int) $request->quantity[$key] ?? 0,
                            'balance_qty'                => (int) $request->quantity[$key] ?? 0,
                            'dv_no'                      => $purchase->invoice_no ?? null,
                            'dv_date'                    => $request->purchase_on ?? null,
                            'created_by'                 => Auth::user()->name ?? 'System',
                        ]);

                        break;
                    case ItemCategoryEnum::Miscellaneous->value:
                        $itemable_type = Miscellaneous::class;
                        $itemable_id = Miscellaneous::find($request->item[$key])->id;
                        $metalCategory = 'Miscellaneous';
                        $metalName     = Miscellaneous::find($request->item[$key])->product_name;


                        // Save Metal Receive Entry for GOLD only
                        Metalreceiveentry::create([
                            'metalreceiveentries_id'     => (string) Str::uuid(),
                            'financial_year_id'          => $financialYearId,
                            'location_id'                => $request->location_id,
                            'vou_no'                     => $purchase->invoice_no,
                            'metal_receive_entries_date' => $request->purchase_on,
                            'customer_id'                => $customer->id,
                            'cust_name'                  => $customer->cust_name,
                            'item_type'                  => $metalCategory,
                            'item'                       => $itemable_id,
                            'purity_id'                  => null,
                            'weight'                     => (int) $request->quantity[$key] ?? 0,
                            'balance_qty'                => (int) $request->quantity[$key] ?? 0,
                            'dv_no'                      => $purchase->invoice_no ?? null,
                            'dv_date'                    => $request->purchase_on ?? null,
                            'created_by'                 => Auth::user()->name ?? 'System',
                        ]);

                        break;
                }

                if ($itemable_type && $itemable_id) {
                    $rate = (float) $request->rate[$key] ?? 0;
                    $quantity = (int) $request->quantity[$key] ?? 0;
                    $subtotalAmount = ($rate * $quantity);
                    $gstPercent = (float) $request->gstin_percent[$key] ?? 0;
                    $gstAmount = (float) ($subtotalAmount * $gstPercent) / 100;
                    $items[] = [
                        'itemable_type' => $itemable_type,
                        'itemable_id' => $itemable_id,
                        'purity_id' => $request->purity[$key] ?? null,
                        'hsn' => $request->hsn[$key],
                        'quantity' => $quantity,
                        'rate' => $rate,
                        'subtotal_amount' => $subtotalAmount,
                        'gstin_percent' => $gstPercent,
                        'gstin_amount' => $gstAmount,
                        'total_amount' => $subtotalAmount + $gstAmount,
                    ];

                    // Compute purity-based weight if available
                    if (!empty($request->purity[$key])) {
                        $metalPurity = Metalpurity::where('purity_id', $request->purity[$key])->value('purity');
                        $pureWt = round(($quantity * $metalPurity) / 100, 3);
                    } else {
                        $metalPurity = null;
                        $pureWt = null;
                    }

                    $locationName = Location::where('id', $request->location_id)->value('location_name');
                    $vendor   = Vendor::where('id', $request->vendor)->first();
                    // ---------------- Create Stock Effect ----------------
                    $baseData = [
                        'vou_no'                      => $purchase->invoice_no,
                        'metal_receive_entries_date'  => $request->purchase_on,
                        'location_name'               => $locationName, // you can change dynamically
                        'metal_category'              => $metalCategory,
                        'metal_name'                  => $metalName,
                        'net_wt'                      => $quantity,
                        'purity'                      => $metalPurity,
                        'pure_wt'                     => $pureWt,
                    ];
                    // Create stock effect for Vendor
                    $company = Customer::find(2);
                    $this->createStockEffectEntries($baseData, $company, $vendor);
                }
            }
            $purchase->items()->createMany($items);

            return redirect()->route('purchases.index')->withSuccess('Purchase record created successfully.');
        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
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
        $purchase = Purchase::findOrFail($id);
        $vendors = Vendor::where('is_active', 'Yes')
            ->orWhere('id', $purchase->vendor_id)
            ->get();
        $metals = Metal::select('metal_id', 'metal_name')->where('is_active', 'Yes')->get()->map(function ($metal) {
            return [
                'id' => $metal->metal_id,
                'name' => $metal->metal_name,
            ];
        });
        $findings = Stone::select('id', 'description')->where('is_active', 'Yes')->get()->map(function ($finding) {
            return [
                'id' => $finding->id,
                'name' => $finding->description,
            ];
        });
        $miscellaneouses = Miscellaneous::select('id', 'product_name')->where('is_active', 'Yes')->get()->map(function ($miscellaneous) {
            return [
                'id' => $miscellaneous->id,
                'name' => $miscellaneous->product_name,
            ];
        });
        $gold = Metal::select('metal_id')->where('metal_name', 'GOLD')->first();
        $purities = Metalpurity::select('purity_id', 'purity')
            ->where('is_active', 'Yes')
            ->where('metal_id', @$gold->metal_id ?? null)
            ->get();

        return view('purchases.edit', [
            'purchase' => $purchase,
            'vendors' => $vendors,
            'itemTypes' => $this->itemTypes,
            'purities' => $purities,
            'metals' => $metals,
            'findings' => $findings,
            'miscellaneouses' => $miscellaneouses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate(
                [
                    'vendor'             => 'required|integer',
                    'invoice_no'         => 'required',
                    'purchase_on'        => 'required',
                    'item_type'          => 'required|array',
                    'item_type.*'        => 'required|string',
                    'item'               => 'required|array',
                    'item.*'             => 'required|integer',
                    'purity'             => 'nullable|array',
                    'purity.*'           => 'nullable|integer',
                    'hsn'                => 'required|array',
                    'hsn.*'              => 'required|string',
                    'rate'              => 'required|array',
                    'rate.*'            => 'required|numeric',
                    'quantity'           => 'required|array',
                    'quantity.*'         => 'required|integer|min:1',
                    'gstin_percent'      => 'required|array',
                    'gstin_percent.*'    => 'required|numeric',
                ],
                [
                    'vendor.required'            => 'Vendor is required', // custom message
                    'invoice_no.required'        => 'Invoice No. is required', // custom message
                    'purchase_on.required'       => 'Purchase Date is required', // custom message
                ]
            );

            $items = [];
            $purchase = Purchase::findOrFail($id);
            if (!$purchase) {
                throw new Exception('Invalid purchase ID');
            }
            Purchase::whereRaw('id = ?', [$id])->update([
                'vendor_id' => strip_tags($request->vendor),
                'invoice_no' => strip_tags($request->invoice_no),
                'purchase_on' => strip_tags($request->purchase_on),
                'updated_by' => Auth::user()->name,
            ]);

            foreach ($request->item_type as $key => $itemType) {
                $itemable_type = null;
                $itemable_id = null;
                switch ($itemType) {
                    case ItemCategoryEnum::Metal->value:
                        $itemable_type = Metal::class;
                        $itemable_id = Metal::find($request->item[$key])->metal_id;
                        break;
                    case ItemCategoryEnum::Findings->value:
                        $itemable_type = Stone::class;
                        $itemable_id = Stone::find($request->item[$key])->id;
                        break;
                    case ItemCategoryEnum::Miscellaneous->value:
                        $itemable_type = Miscellaneous::class;
                        $itemable_id = Miscellaneous::find($request->item[$key])->id;
                        break;
                }

                if ($itemable_type && $itemable_id) {
                    $rate = (float) $request->rate[$key] ?? 0;
                    $quantity = (int) $request->quantity[$key] ?? 0;
                    $subtotalAmount = ($rate * $quantity);
                    $gstPercent = (float) $request->gstin_percent[$key] ?? 0;
                    $gstAmount = (float) ($subtotalAmount * $gstPercent) / 100;
                    $itemData = [
                        'itemable_type' => $itemable_type,
                        'itemable_id' => $itemable_id,
                        'purity_id' => $request->purity[$key] ?? null,
                        'hsn' => $request->hsn[$key],
                        'quantity' => $quantity,
                        'rate' => $rate,
                        'subtotal_amount' => $subtotalAmount,
                        'gstin_percent' => $gstPercent,
                        'gstin_amount' => $gstAmount,
                        'total_amount' => $subtotalAmount + $gstAmount,
                    ];
                    if (isset($request->item_id[$key])) {
                        if (isset($request->is_deleted[$key]) && @$request->is_deleted[$key] == 1) {
                            PurchaseItem::whereRaw('id = ?', $request->item_id[$key])->delete();
                        } else {
                            PurchaseItem::whereRaw('id = ?', $request->item_id[$key])->update($itemData);
                        }
                    } else {
                        $items[] = $itemData;
                    }
                }
            }
            if (count($items) > 0) {
                $purchase->items()->createMany($items);
            }

            return redirect()->route('purchases.edit', ['purchase' => $purchase->id])->withSuccess('Purchase record updated successfully.');
        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::whereRaw('id = ?', [$id])->firstOrFail();
        if ($purchase) {
            $purchase->delete();
            return redirect('/purchases')->with('success', 'Purchase record deleted successfully.');
        }
        return redirect('/purchases')->with('success', 'Invalid Purchase ID.');
    }
}
