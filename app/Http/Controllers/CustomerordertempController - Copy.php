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
        $customerordertempitems = DB::table('customerordertempitems')
            ->leftJoin('products', 'customerordertempitems.item_code', '=', 'products.item_code')
            ->select(
                'customerordertempitems.item_code',
                'customerordertempitems.description',
                'customerordertempitems.kid',
                'customerordertempitems.size',
                'customerordertempitems.std_wt',
                'customerordertempitems.total_wt',
                'customerordertempitems.ord_qty',
                'customerordertempitems.lab_chg',
                'customerordertempitems.add_l_chg',
                'customerordertempitems.stone_chg',
                DB::raw("CASE
                    WHEN products.item_code IS NULL THEN 'Item Not Found'
                    ELSE 'Item Found'
                END as status")
            )
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

    /*public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
        ]);

        $filePath = $request->file('file')->getRealPath();
        $lines = file($filePath);

        $content = file_get_contents($filePath);

        $CompanyName = preg_replace('/[^A-Za-z0-9 ]/', '', explode("\n", $content)[0]); // Get first line with remove special charecter

        $GetCompanyId = Customer::where('cust_name', 'like', '%' . trim(strtoupper($CompanyName)) . '%')->first();
        if ($GetCompanyId) {

            // Extract JO No and JO Date using regex
            preg_match('/JO No\s*:\s*(\d+)/', $content, $joNoMatch);
            preg_match('/JO Date\s*:\s*([\d\/]+\s[\d:]+\s[APM]+)/', $content, $joDateMatch);
            preg_match('/Delivery Required Before:\s+(\d{2}\/\d{2}\/\d{4})/', $content, $matches);
            $deliveryDate = Carbon::createFromFormat('d/m/Y', $matches[1])->format('Y-m-d');


            if (isset($joNoMatch[1]) && isset($joDateMatch[1])) {
                $joNo = $joNoMatch[1];
                $joDate = $joDateMatch[1];

                // Check if jo_no already exists
                $existingOrder = Customerordertemp::where('jo_no', $joNo)->first();
                if ($existingOrder) {
                    return back()->with('error', 'JO No already exists. Duplicate upload is not allowed.');
                }

                $customerorder = Customerordertemp::create([
                    'customer_id'          => $GetCompanyId->id,
                    'jo_no'                => $joNo,
                    'jo_date'              => $joDate,
                    'order_type'           => 'AutoUpload',
                    'is_active'            => strip_tags($request->is_active),
                    'created_by'           => Auth::user()->name
                ]);
                $lastInsertedId = $customerorder->id;
            }

            $dataStart = false;
            foreach ($lines as $line) {
                // Check for data start using 'SLNo' as identifier
                if (str_contains($line, 'SLNo')) {
                    $dataStart = true;
                    continue;
                }

                if ($dataStart && !empty(trim($line))) {
                    $columns = preg_split('/\s{2,}/', trim($line));
                    $sl_no_and_item_code = explode(" ", $columns[0]);


                    if (count($columns) == 16) { // Without Finding

                        $getkid = Product::with('karigar')->where('item_code', $sl_no_and_item_code[1])->first();

                        if ($getkid && $getkid->karigar) {
                            foreach ($getkid->karigar as $karigars) {
                                $kid = $karigars->kid;
                                // Continue processing...
                            }
                        } else {
                            // Handle the case where product or karigar is not found
                            Log::warning('Product or karigar not found for item_code: ' . $sl_no_and_item_code[1]);
                        }

                        Customerordertempitem::create([
                            'order_id'     => $lastInsertedId,
                            'sl_no'        => $sl_no_and_item_code[0],
                            'item_code'    => $sl_no_and_item_code[1],
                            'kid'          => @$kid,
                            'description'  => $columns[1],
                            'size'         => $columns[2],
                            'uom'          => $columns[3],
                            'kt'           => $columns[4],
                            'std_wt'       => $columns[5],
                            'ord_qty'      => $columns[6],
                            'total_wt'     => $columns[7],
                            'lab_chg'      => $columns[8],
                            'stone_chg'    => $columns[9],
                            'add_l_chg'    => $columns[10],
                            'total_value'  => $columns[11],
                            'loss_percent' => $columns[12],
                            'min_wt'       => $columns[13],
                            'max_wt'       => $columns[14],
                            'ord'          => $columns[15],
                            'delivery_date' => $deliveryDate
                        ]);
                    }

                    if (count($columns) == 17) { // Including Finding
                        $getkid = Product::with('karigar')->where('item_code', $sl_no_and_item_code[1])->first();
                        if ($getkid && $getkid->karigar) {
                            foreach ($getkid->karigar as $karigars) {
                                $kid = $karigars->kid;
                                // Continue processing...
                            }
                        } else {
                            // Handle the case where product or karigar is not found
                            Log::warning('Product or karigar not found for item_code: ' . $sl_no_and_item_code[1]);
                        }

                        Customerordertempitem::create([
                            'order_id'     => $lastInsertedId,
                            'sl_no'        => $sl_no_and_item_code[0],
                            'item_code'    => $sl_no_and_item_code[1],
                            'kid'          => @$kid,
                            'description'  => $columns[1],
                            'size'         => $columns[2],
                            'finding'      => $columns[3],
                            'uom'          => $columns[4],
                            'kt'           => $columns[5],
                            'std_wt'       => $columns[6],
                            'ord_qty'      => $columns[7],
                            'total_wt'     => $columns[8],
                            'lab_chg'      => $columns[9],
                            'stone_chg'    => $columns[10],
                            'add_l_chg'    => $columns[11],
                            'total_value'  => $columns[12],
                            'loss_percent' => $columns[13],
                            'min_wt'       => $columns[14],
                            'max_wt'       => $columns[15],
                            'ord'          => $columns[16],
                            'delivery_date' => $deliveryDate
                        ]);
                    }

                    if (count($columns) == 18) { // Including conv wt
                        $getkid = Product::with('karigar')->where('item_code', $sl_no_and_item_code[1])->first();
                        if ($getkid && $getkid->karigar) {
                            foreach ($getkid->karigar as $karigars) {
                                $kid = $karigars->kid;
                                // Continue processing...
                            }
                        } else {
                            // Handle the case where product or karigar is not found
                            Log::warning('Product or karigar not found for item_code: ' . $sl_no_and_item_code[1]);
                        }
                        Customerordertempitem::create([
                            'order_id'     => $lastInsertedId,
                            'sl_no'        => $sl_no_and_item_code[0],
                            'item_code'    => $sl_no_and_item_code[1],
                            'kid'          => @$kid,
                            'description'  => $columns[1],
                            'size'         => $columns[2],
                            'finding'      => $columns[3],
                            'uom'          => $columns[4],
                            'kt'           => $columns[5],
                            'std_wt'       => $columns[6],
                            'conv_wt'      => $columns[7],
                            'ord_qty'      => $columns[8],
                            'total_wt'     => $columns[9],
                            'lab_chg'      => $columns[10],
                            'stone_chg'    => $columns[11],
                            'add_l_chg'    => $columns[12],
                            'total_value'  => $columns[13],
                            'loss_percent' => $columns[14],
                            'min_wt'       => $columns[15],
                            'max_wt'       => $columns[16],
                            'ord'          => $columns[17],
                            'delivery_date' => $deliveryDate
                        ]);
                    }
                }
            }
            //return back()->with('success', 'Successfully uploaded customer order!');
            //return redirect()->route('customerordertemps.index')->withSuccess('Successfully uploaded customer order.');
            // return redirect()->route('customerordertemps.show', $lastInsertedId)->withSuccess('Successfully uploaded customer order.');
            return redirect()->route('customerordertemps.show', $lastInsertedId);
        } else {
            return back()->with('error', 'Company is not found in our system!');
        }
    }*/
    public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $lastInsertedId = null;

        // Extract company name from first non-empty line of the entire file
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

            preg_match('/JO No\s*:\s*(\S+)/', $block, $joNoMatch);
            preg_match('/JO Date\s*:\s*([\d\/]+\s[\d:]+\s[APM]+)/', $block, $joDateMatch);
            preg_match('/Delivery Required Before:\s+(\d{2}\/\d{2}\/\d{4})/', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) continue;

            $joNo = $joNoMatch[1];
            $joDate = $joDateMatch[1];
            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');

            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no' => $joNo,
                'jo_date' => $joDate,
                'order_type' => 'AutoUpload',
                'is_active' => strip_tags($request->is_active),
                'created_by' => Auth::user()->name
            ]);
            $lastInsertedId = $customerorder->id;

            $lines = explode("\n", $block);
            $dataStart = false;

            foreach ($lines as $line) {
                $line = trim($line);

                if (str_contains($line, 'SLNo')) {
                    $dataStart = true;
                    continue; // Skip header row
                }

                if (!$dataStart || empty($line)) continue;

                if (str_starts_with($line, 'Total:') || str_starts_with($line, 'Remarks') || str_starts_with($line, 'Delivery Required')) {
                    continue;
                }

                $columns = preg_split('/\s{2,}/', $line);

                if (!in_array(count($columns), [16, 17, 18])) {
                    Log::warning("Invalid item line, column count " . count($columns) . ": " . $line);
                    continue;
                }

                $sl_no_and_item_code = explode(' ', trim($columns[0]));

                if (count($sl_no_and_item_code) < 2 || empty($sl_no_and_item_code[1])) {
                    Log::warning('Missing item_code. Skipping line: ' . $line);
                    continue;
                }

                $itemData = [
                    'order_id' => $lastInsertedId,
                    'sl_no' => $sl_no_and_item_code[0],
                    'item_code' => $sl_no_and_item_code[1],
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

                // Safeguard against missing required fields
                $itemData['kt'] = $itemData['kt'] ?? null;
                $itemData['ord_qty'] = $itemData['ord_qty'] ?? 0;
                $itemData['total_value'] = $itemData['total_value'] ?? 0;
                $itemData['total_wt'] = $itemData['total_wt'] ?? 0;
                $itemData['std_wt'] = $itemData['std_wt'] ?? 0;

                Customerordertempitem::create($itemData);
            }
        }

        return $lastInsertedId
            ? redirect()->route('customerordertemps.show', $lastInsertedId)->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }
}
