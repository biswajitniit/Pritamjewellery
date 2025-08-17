<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Customerordertemp;
use App\Models\Customerordertempitem;
use App\Models\Karigar;
use App\Models\Product;
use App\Models\Productstonedetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CustomerordertempController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerordertempitems = Customerordertemp::with('customer')->simplePaginate(25);
        return view('customerordertemps.list', compact('customerordertempitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $customers    = Customer::where('is_active', 'Yes')->orderBy('cust_name')->get();
        $products    = Product::orderBy('item_code')->get();

        $type = $request->query('type'); // 'bulk' or 'manual'
        if ($type === 'bulk') {
            // Load bulk upload view or logic
            return view('customerordertemps.add-bulk', compact('customers', 'products'));
        } elseif ($type === 'manual') {
            // Load manual entry view or logic
            return view('customerordertemps.add-manual', compact('customers', 'products'));
        } else {
            // Handle invalid or missing type
            abort(404, 'Invalid type provided.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //$customerordertempitems = Customerordertempitem::where('order_id', $id)->get();
        // $customerordertempitems = DB::table('customerordertempitems')
        //     ->leftJoin('products', 'customerordertempitems.item_code', '=', 'products.item_code')
        //     ->leftJoin('customerordertemps', 'customerordertempitems.order_id', '=', 'customerordertemps.id')
        //     ->select(
        //         'customerordertemps.jo_no',
        //         'customerordertempitems.item_code',
        //         'customerordertempitems.description',
        //         'customerordertempitems.kid',
        //         'customerordertempitems.size',
        //         'customerordertempitems.std_wt',
        //         'customerordertempitems.total_wt',
        //         'customerordertempitems.ord_qty',
        //         'customerordertempitems.lab_chg',
        //         'customerordertempitems.add_l_chg',
        //         'customerordertempitems.stone_chg',
        //         'products.design_num',
        //         DB::raw("CASE
        //             WHEN products.item_code IS NULL THEN 'Item Not Found'
        //             ELSE 'Item Found'
        //         END as status")
        //     )
        //     ->get();
        $customerordertempitems = DB::table('customerordertempitems AS i')
            ->leftJoin('products AS p', 'i.item_code', '=', 'p.item_code')
            ->leftJoin('customerordertemps AS o', 'i.order_id', '=', 'o.id')
            ->select([
                'o.jo_no',
                'o.type',
                'i.item_code',
                'i.design',
                'i.description',
                'i.kid',
                'i.size',
                'i.std_wt',
                'i.total_wt',
                'i.ord_qty',
                'i.lab_chg',
                'i.add_l_chg',
                'i.stone_chg',
                'i.total_value',
                'p.design_num',
                DB::raw("CASE WHEN p.item_code IS NULL THEN 'Item Not Found' ELSE 'Item Found' END as status"),
            ])
            ->orderBy('o.jo_no')
            ->get();
        return view('customerordertemps.show', compact('customerordertempitems'));
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

    public function destroy(string $jo_no)
    {
        // Wrap in DB transaction for safety
        DB::transaction(function () use ($jo_no) {
            // Fetch all temp orders with matching jo_no and their related items
            $tempOrders = Customerordertemp::with('customerordertempitems')
                ->where('jo_no', $jo_no)
                ->get();

            if ($tempOrders->isEmpty()) {
                // Throwing an exception to rollback transaction
                throw new \Exception("No customer order found for JO No: $jo_no");
            }

            // Delete each temp order and its related items
            foreach ($tempOrders as $tempOrder) {
                $tempOrder->customerordertempitems()->delete(); // Delete child items
                $tempOrder->delete();                           // Delete parent order
            }
        });

        return redirect()->route('customerorders.index')->with('success', 'Temp Customer order(s) deleted successfully.');
    }


    public function getproductinfo(Request $request)
    {
        $product = Product::where('item_code', $request->itemval)->orderBy('item_code')->first();
        $karigar = Karigar::where('id', $product->kid)->first();
        $productstonedetails_add_l_chg = Productstonedetails::where('product_id', $product->id)->sum('amount');
        return response()->json([
            "design"      => $product->design_num,
            "description" => $product->description,
            "size"        => $product->size,
            "uom"         => $product->uom,
            "kt"          => $product->kt,
            "std_wt"      => $product->standard_wt,
            "kid"         => $karigar->kid,
            "add_l_chg"   => $productstonedetails_add_l_chg,
            "lab_charge"  => $product->lab_charge,
            "loss"        => $product->loss,
            "company_id"  => $product->company_id,
        ]);
    }


    public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $insertedIds = [];

        // Extract company name from first non-empty line
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $CompanyName = preg_replace('/[^A-Za-z0-9 ]/', '', $line);
                break;
            }
        }

        $GetCompanyId = Customer::where('cust_name', 'like', '%' . strtoupper(trim($CompanyName)) . '%')->first();
        if (!$GetCompanyId) {
            return back()->with('error', 'Company not found in system: ' . $CompanyName);
        }

        foreach ($blocks as $block) {
            if (trim($block) === '') continue;

            // Log block content for debugging
            Log::info("Processing JO block:", ['block' => $block]);

            preg_match('/JO No\s*:\s*(\S+)/', $block, $joNoMatch);
            preg_match('/JO Date\s*:\s*([\d\/]+\s[\d:]+\s[APM]+)/', $block, $joDateMatch);
            preg_match('/Delivery Required Before:\s+(\d{2}\/\d{2}\/\d{4})/', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block:", ['block' => $block]);
                continue;
            }

            $joNo = $joNoMatch[1];
            $joDate = $joDateMatch[1];

            $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
            $formattedDate = $carbonDate->format('Y-m-d h:i:s a');

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');

            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no' => $joNo,
                'jo_date' => $formattedDate,
                'order_type' => 'AutoUpload',
                'type' => $request->type,
                'is_active' => strip_tags($request->is_active),
                'created_by' => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            $blockLines = explode("\n", $block);
            $dataStart = false;
            $slCounter = 1;

            foreach ($blockLines as $line) {
                $line = trim($line);

                // Detect start of item rows reliably, skip malformed or empty headers
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart || empty($line)) continue;

                $columns = preg_split('/\s{2,}/', $line);

                if (!in_array(count($columns), [16, 17, 18])) {
                    Log::warning("Skipping line due to unexpected column count: " . $line);
                    continue;
                }

                $sl_no_and_item_code = explode(' ', trim($columns[0]));
                if (count($sl_no_and_item_code) < 2 || empty($sl_no_and_item_code[1])) continue;

                $itemData = [
                    'order_id' => $customerorder->id,
                    'sl_no' => $sl_no_and_item_code[0],
                    'item_code' => $sl_no_and_item_code[1],
                    'design' => substr($sl_no_and_item_code[1], 2, 9),
                    'delivery_date' => $deliveryDate
                ];

                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                if (count($columns) === 16) {
                    $itemData += [
                        'description' => $columns[1],
                        'size' => $columns[2],
                        'uom' => $columns[3],
                        'kt' => $columns[4],
                        'std_wt' => $columns[5],
                        'ord_qty' => $columns[6],
                        'total_wt' => $columns[7],
                        'lab_chg' => $columns[8],
                        'stone_chg' => $columns[9],
                        'add_l_chg' => $columns[10],
                        'total_value' => $columns[11],
                        'loss_percent' => $columns[12],
                        'min_wt' => $columns[13],
                        'max_wt' => $columns[14],
                        'ord' => $columns[15]
                    ];
                } elseif (count($columns) === 17) {
                    $itemData += [
                        'description' => $columns[1],
                        'size' => $columns[2],
                        'finding' => $columns[3],
                        'uom' => $columns[4],
                        'kt' => $columns[5],
                        'std_wt' => $columns[6],
                        'ord_qty' => $columns[7],
                        'total_wt' => $columns[8],
                        'lab_chg' => $columns[9],
                        'stone_chg' => $columns[10],
                        'add_l_chg' => $columns[11],
                        'total_value' => $columns[12],
                        'loss_percent' => $columns[13],
                        'min_wt' => $columns[14],
                        'max_wt' => $columns[15],
                        'ord' => $columns[16]
                    ];
                } elseif (count($columns) === 18) {
                    $itemData += [
                        'description' => $columns[1],
                        'size' => $columns[2],
                        'finding' => $columns[3],
                        'uom' => $columns[4],
                        'kt' => $columns[5],
                        'std_wt' => $columns[6],
                        'conv_wt' => $columns[7],
                        'ord_qty' => $columns[8],
                        'total_wt' => $columns[9],
                        'lab_chg' => $columns[10],
                        'stone_chg' => $columns[11],
                        'add_l_chg' => $columns[12],
                        'total_value' => $columns[13],
                        'loss_percent' => $columns[14],
                        'min_wt' => $columns[15],
                        'max_wt' => $columns[16],
                        'ord' => $columns[17]
                    ];
                }

                $itemData['kt'] = $itemData['kt'] ?? null;
                $itemData['ord_qty'] = $itemData['ord_qty'] ?? 0;
                $itemData['total_value'] = $itemData['total_value'] ?? 0;
                $itemData['total_wt'] = $itemData['total_wt'] ?? 0;
                $itemData['std_wt'] = $itemData['std_wt'] ?? 0;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }

    public function savetempproducts(Request $request)
    {
        // Begin transaction
        DB::beginTransaction();

        try {
            // Get items where kid is null
            $items = Customerordertempitem::whereNull('kid')->get();


            foreach ($items as $item) {

                if (strlen($item->item_code) == 14) {
                    $company_id = 1; // TCL KOL
                } elseif (strlen($item->item_code) == 15) {
                    $company_id = 6; // NOVJL
                } else {
                    return redirect()->back()->withErrors(['item_code' => 'Itemcode must be exactly 14 or 15 characters long.']);
                }

                // Insert into products
                $product = Product::create([
                    'company_id'     => $company_id,
                    'item_code'      => $item->item_code,
                    'design_num'     => $item->design,
                    'description'    => $item->description,
                    'size'           => $item->size,
                    'uom'            => $item->uom,
                    'standard_wt'    => $item->std_wt ?? 0,
                    'kt'             => $item->kt . 'KT',
                    'kid'            => 30,
                    'purity'         => 0.00,
                    'remarks'        => $item->remarks,
                    'customer_order' => 'Yes',
                    'stone_charge'   => $item->stone_chg,
                    'lab_charge'     => $item->lab_chg,
                    'loss'           => $item->loss_percent,
                    'pcodechar'      => strlen($item->item_code),
                    'created_by'     => Auth::user()->name,
                ]);


                Productstonedetails::create([
                    'product_id' => $product->id,
                    'stone_id'   => 1,
                    'category'   => 'Default',
                    'pcs'        => '0',
                    'weight'     => '0',
                    'rate'       => '0',
                    'amount'     => '0',
                    'created_by' => Auth::user()->name,
                ]);
            }

            DB::commit();
            //return response()->json(['status' => 'success', 'message' => 'Products saved successfully.']);

            //  Redirect to products listing with query params
            return redirect()->route('products.index', ['customer_order' => 'Yes', 'search' => ''])
                ->with('success', 'Products saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
