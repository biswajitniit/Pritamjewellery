<?php

namespace App\Http\Controllers;

use App\Enums\ItemCategoryEnum;
use App\Models\Metal;
use App\Models\Miscellaneous;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stone;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('purchases.add', [
            'vendors' => $vendors,
            'itemTypes' => $this->itemTypes,
            'metals' => json_encode($metals),
            'findings' => json_encode($findings),
            'miscellaneouses' => json_encode($miscellaneouses),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate(
                [
                    'po_no'              => 'required',
                    'vendor'             => 'required|integer',
                    'invoice_no'         => 'required',
                    'purchase_on'        => 'required',
                    'item_type'          => 'required|array',
                    'item_type.*'        => 'required|string',
                    'item'               => 'required|array',
                    'item.*'             => 'required|integer',
                    'purity'             => 'nullable|array',
                    'purity.*'           => 'nullable|string',
                    'hsn'                => 'required|array',
                    'hsn.*'              => 'required|string',
                    'price'              => 'required|array',
                    'price.*'            => 'required|numeric',
                    'quantity'           => 'required|array',
                    'quantity.*'         => 'required|integer|min:1',
                    'gstin_percent'      => 'required|array',
                    'gstin_percent.*'    => 'required|numeric',
                ],
                [
                    'po_no.required'             => 'PO No. is required', // custom message
                    'vendor.required'            => 'Vendor is required', // custom message
                    'invoice_no.required'        => 'Invoice No. is required', // custom message
                    'purchase_on.required'       => 'Purchase Date is required', // custom message
                ]
            );

            $items = [];
            $purchase = Purchase::create([
                'po_no' => strip_tags($request->po_no),
                'vendor_id' => strip_tags($request->vendor),
                'invoice_no' => strip_tags($request->invoice_no),
                'purchase_on' => strip_tags($request->purchase_on),
                'created_by' => Auth::user()->name,
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
                    $price = (float) $request->price[$key] ?? 0;
                    $quantity = (int) $request->quantity[$key] ?? 0;
                    $subtotalAmount = ($price * $quantity);
                    $gstPercent = (float) $request->gstin_percent[$key] ?? 0;
                    $gstAmount = (float) ($subtotalAmount * $gstPercent) / 100;
                    $items[] = [
                        'itemable_type' => $itemable_type,
                        'itemable_id' => $itemable_id,
                        'purity' => $request->purity[$key],
                        'hsn' => $request->hsn[$key],
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal_amount' => $subtotalAmount,
                        'gstin_percent' => $gstPercent,
                        'gstin_amount' => $gstAmount,
                        'total_amount' => $subtotalAmount + $gstAmount,
                    ];
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

        return view('purchases.edit', [
            'purchase' => $purchase,
            'vendors' => $vendors,
            'itemTypes' => $this->itemTypes,
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
                    'po_no'              => 'required',
                    'vendor'             => 'required|integer',
                    'invoice_no'         => 'required',
                    'purchase_on'        => 'required',
                    'item_type'          => 'required|array',
                    'item_type.*'        => 'required|string',
                    'item'               => 'required|array',
                    'item.*'             => 'required|integer',
                    'purity'             => 'nullable|array',
                    'purity.*'           => 'nullable|string',
                    'hsn'                => 'required|array',
                    'hsn.*'              => 'required|string',
                    'price'              => 'required|array',
                    'price.*'            => 'required|numeric',
                    'quantity'           => 'required|array',
                    'quantity.*'         => 'required|integer|min:1',
                    'gstin_percent'      => 'required|array',
                    'gstin_percent.*'    => 'required|numeric',
                ],
                [
                    'po_no.required'             => 'PO No. is required', // custom message
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
                'po_no' => strip_tags($request->po_no),
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
                    $price = (float) $request->price[$key] ?? 0;
                    $quantity = (int) $request->quantity[$key] ?? 0;
                    $subtotalAmount = ($price * $quantity);
                    $gstPercent = (float) $request->gstin_percent[$key] ?? 0;
                    $gstAmount = (float) ($subtotalAmount * $gstPercent) / 100;
                    $itemData = [
                        'itemable_type' => $itemable_type,
                        'itemable_id' => $itemable_id,
                        'purity' => $request->purity[$key],
                        'hsn' => $request->hsn[$key],
                        'quantity' => $quantity,
                        'price' => $price,
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
        //
    }
}
