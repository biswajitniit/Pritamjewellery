<?php

namespace App\Http\Controllers;

use App\Enums\ItemCategoryEnum;
use App\Models\Customer;
use App\Models\Metal;
use App\Models\Metalpurity;
use App\Models\Miscellaneous;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stone;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    protected $itemTypes = [
        ItemCategoryEnum::Metal->value => 'Metals',
        ItemCategoryEnum::Findings->value => 'Findings',
        ItemCategoryEnum::Miscellaneous->value => 'Miscellaneous',
        ItemCategoryEnum::Product->value => 'Product',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['customer:id,cust_name,cust_code'])
            ->withCount('items')
            ->simplePaginate(25);

        return view('sales.list', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', 'Yes')->get();
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
        $products = Product::select('id', 'item_code', 'description')->orderBy('item_code')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->item_code.' - '.str_replace('"', "'", $product->description),
            ];
        });
        $gold = Metal::select('metal_id')->where('metal_name', 'GOLD')->first();
        $purities = Metalpurity::select('purity_id', 'purity')
            ->where('is_active', 'Yes')
            ->where('metal_id', @$gold->metal_id ?? null)
            ->get();

        return view('sales.add', [
            'customers' => $customers,
            'itemTypes' => $this->itemTypes,
            'purities' => $purities,
            'products' => json_encode($products),
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
                    'customer'           => 'required|integer',
                    'invoice_no'         => 'required',
                    'sold_on'            => 'required',
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
                    'customer.required'   => 'Customer is required', // custom message
                    'invoice_no.required' => 'Invoice No. is required', // custom message
                    'sold_on.required'    => 'Sale Date is required', // custom message
                ]
            );

            $items = [];
            $sale = Sale::create([
                'customer_id' => strip_tags($request->customer),
                'invoice_no' => strip_tags($request->invoice_no),
                'sold_on' => strip_tags($request->sold_on),
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
                    case ItemCategoryEnum::Product->value:
                        $itemable_type = Product::class;
                        $itemable_id = Product::find($request->item[$key])->id;
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
                }
            }
            $sale->items()->createMany($items);

            return redirect()->route('sales.index')->withSuccess('Sale record created successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
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
        $sale = Sale::findOrFail($id);
        $customers = Customer::where('is_active', 'Yes')
            ->orWhere('id', $sale->customer_id)
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
        $products = Product::select('id', 'item_code', 'description')->orderBy('item_code')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->item_code.' - '.str_replace('"', "'", $product->description),
            ];
        });
        $gold = Metal::select('metal_id')->where('metal_name', 'GOLD')->first();
        $purities = Metalpurity::select('purity_id', 'purity')
            ->where('is_active', 'Yes')
            ->where('metal_id', @$gold->metal_id ?? null)
            ->get();

        return view('sales.edit', [
            'sale' => $sale,
            'customers' => $customers,
            'itemTypes' => $this->itemTypes,
            'purities' => $purities,
            'products' => $products,
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
                    'customer'           => 'required|integer',
                    'invoice_no'         => 'required',
                    'sold_on'            => 'required',
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
                    'customer.required'            => 'Customer is required', // custom message
                    'invoice_no.required'        => 'Invoice No. is required', // custom message
                    'sold_on.required'       => 'Sale Date is required', // custom message
                ]
            );

            $items = [];
            $sale = Sale::findOrFail($id);
            if (!$sale) {
                throw new Exception('Invalid sale ID');
            }
            Sale::whereRaw('id = ?', [$id])->update([
                'customer_id' => strip_tags($request->customer),
                'invoice_no' => strip_tags($request->invoice_no),
                'sold_on' => strip_tags($request->sold_on),
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
                    case ItemCategoryEnum::Product->value:
                        $itemable_type = Product::class;
                        $itemable_id = Product::find($request->item[$key])->id;
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
                            SaleItem::whereRaw('id = ?', $request->item_id[$key])->delete();
                        } else {
                            SaleItem::whereRaw('id = ?', $request->item_id[$key])->update($itemData);
                        }
                    } else {
                        $items[] = $itemData;
                    }
                }
            }
            if (count($items) > 0) {
                $sale->items()->createMany($items);
            }

            return redirect()->route('sales.edit', ['sale' => $sale->id])->withSuccess('Sale record updated successfully.');
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
        $sale = Sale::whereRaw('id = ?', [$id])->firstOrFail();
        if ($sale) {
            $sale->delete();
            return redirect('/sales')->with('success', 'Sale record deleted successfully.');
        }
        return redirect('/sales')->with('success', 'Invalid Sale ID.');
    }

    public function invoice(string $id)
    {
        $sale = Sale::findOrFail($id);
        try {
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
                            $name = $itemable->item_code.' - '.$itemable->description;
                        }
                        break;
                }
                $item->name = $name;
                $items[] = $item;
            }

            $pdf = Pdf::loadView('sales.invoice', ['sale' => $sale, 'items' => $items]);

            return $pdf->stream(time().'.pdf');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
