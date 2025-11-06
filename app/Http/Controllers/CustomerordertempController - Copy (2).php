<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Customerordertemp;
use App\Models\Customerordertempitem;
use App\Models\Karigar;
use App\Models\Pcode;
use App\Models\Product;
use App\Models\Productstonedetails;
use App\Models\Size;
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
            ->leftJoin('karigars AS k', 'p.kid', '=', 'k.id')
            ->select([
                'o.jo_no',
                'o.type',
                'i.item_code',
                'i.design',
                'i.description',
                'k.kid',
                'i.size',
                'i.std_wt',
                'i.total_wt',
                'i.ord_qty',
                'i.lab_chg',
                'i.add_l_chg',
                'i.stone_chg',
                'i.total_value',
                'p.design_num',
                DB::raw("CASE WHEN p.item_code IS NULL THEN 'Item Not Found' ELSE 'Item Found' END as status")
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


    /* public function customerorderstempimporttxt(Request $request)
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
    }*/

    /*public function customerorderstempimporttxt(Request $request)
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

            // Extract header fields
            preg_match('/JO No\s*:\s*(\S+)/', $block, $joNoMatch);
            preg_match('/JO Date\s*:\s*([\d\/]+\s[\d:]+\s[APM]+)/', $block, $joDateMatch);
            preg_match('/Delivery Required Before:\s+(\d{2}\/\d{2}\/\d{4})/', $block, $deliveryDateMatch);
            preg_match('/Vendor Site\s*:\s*(.+)/', $block, $vendorSiteMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block:", ['block' => $block]);
                continue;
            }

            $joNo = $joNoMatch[1];
            $joDate = $joDateMatch[1];
            $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
            $formattedDate = $carbonDate->format('Y-m-d h:i:s a');
            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite = isset($vendorSiteMatch[1]) ? trim($vendorSiteMatch[1]) : null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Create JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no' => $joNo,
                'jo_date' => $formattedDate,
                'order_type' => 'AutoUpload',
                'type' => $request->type,
                'is_active' => strip_tags($request->is_active),
                'vendor_site' => $vendorSite, // ✅ Added Vendor Site
                'created_by' => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process JO lines
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = trim($line);

                // Detect start of item rows
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

                // Extract item code
                $sl_no_and_item_code = explode(' ', trim($columns[0]));
                if (count($sl_no_and_item_code) < 2 || empty($sl_no_and_item_code[1])) continue;

                $itemData = [
                    'order_id' => $customerorder->id,
                    'sl_no' => $sl_no_and_item_code[0],
                    'item_code' => $sl_no_and_item_code[1],
                    'design' => substr($sl_no_and_item_code[1], 2, 9),
                    'delivery_date' => $deliveryDate
                ];

                // Find Karigar ID (kid)
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                // Map columns based on count
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

                // Default values
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
    }*/

    /*
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            Log::info('Parsed Header', [
                'jo_no' => $joNoMatch[1] ?? null,
                'jo_date' => $joDateMatch[1] ?? null,
                'delivery_date' => $deliveryDateMatch[1] ?? null,
                'vendor_site' => $vendorSiteMatch[1] ?? null,
            ]);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ Fixed JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date with format d/m/Y h:i:s A: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite = isset($vendorSiteMatch[1]) ? trim($vendorSiteMatch[1]) : null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Create JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no' => $joNo,
                'jo_date' => $formattedDate,
                'order_type' => 'AutoUpload',
                'type' => $request->type,
                'is_active' => strip_tags($request->is_active),
                'vendor_site' => $vendorSite,
                'created_by' => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process JO lines
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = trim($line);

                // Detect start of item rows
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue; // ✅ Skip header line
                }

                if (!$dataStart || empty($line)) continue;

                $columns = preg_split('/\s{2,}/', $line);

                // Normalize (pad to 18 columns)
                while (count($columns) < 18) {
                    $columns[] = null;
                }

                // Skip lines that don’t start with numeric SLNo
                $sl_no_and_item_code = explode(' ', trim($columns[0]));
                if (empty($sl_no_and_item_code[0]) || !is_numeric($sl_no_and_item_code[0])) {
                    continue;
                }

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $sl_no_and_item_code[0],
                    'item_code'     => $sl_no_and_item_code[1] ?? null,
                    'design'        => isset($sl_no_and_item_code[1]) ? substr($sl_no_and_item_code[1], 2, 9) : null,
                    'delivery_date' => $deliveryDate
                ];

                // Find Karigar ID (kid)
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                // Map safely
                $itemData += [
                    'description'   => $columns[1] ?? null,
                    'size'          => $columns[2] ?? null,
                    'finding'       => $columns[3] ?? null,
                    'uom'           => $columns[4] ?? null,
                    'kt'            => $columns[5] ?? null,
                    'std_wt'        => is_numeric($columns[6]) ? $columns[6] : 0,
                    'conv_wt'       => $columns[7] ?? null,
                    'ord_qty'       => is_numeric($columns[8]) ? $columns[8] : 0,
                    'total_wt'      => is_numeric($columns[9]) ? $columns[9] : 0,
                    'lab_chg'       => is_numeric($columns[10]) ? $columns[10] : 0,
                    'stone_chg'     => is_numeric($columns[11]) ? $columns[11] : 0,
                    'add_l_chg'     => is_numeric($columns[12]) ? $columns[12] : 0,
                    'total_value'   => is_numeric($columns[13]) ? $columns[13] : 0,
                    'loss_percent'  => is_numeric($columns[14]) ? $columns[14] : 0,
                    'min_wt'        => is_numeric($columns[15]) ? $columns[15] : 0,
                    'max_wt'        => is_numeric($columns[16]) ? $columns[16] : 0,
                    'ord'           => $columns[17] ?? null,
                ];

                Log::info('Parsed Item', $itemData);

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/


    /*public function customerorderstempimporttxt(Request $request)
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => strip_tags($request->is_active),
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = trim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart || empty($line)) continue;

                $columns = preg_split('/\s{2,}/', $line);

                // Pad to 18 columns
                while (count($columns) < 18) {
                    $columns[] = null;
                }

                // Validate SLNo
                $sl_no_and_item_code = explode(' ', trim($columns[0]));
                if (empty($sl_no_and_item_code[0]) || !is_numeric($sl_no_and_item_code[0])) {
                    continue;
                }


                // $slNo       = trim(substr($line, 0, 5));
                // $itemCode   = trim(substr($line, 5, 18));
                // $desc       = trim(substr($line, 23, 35));
                // $size       = trim(substr($line, 58, 12));
                // $finding    = trim(substr($line, 70, 10));
                // $uom        = trim(substr($line, 80, 6));
                // $kt         = trim(substr($line, 86, 4));
                // $stdWt      = trim(substr($line, 90, 8));
                // $convWt     = trim(substr($line, 98, 8));
                // $ordQty     = trim(substr($line, 112, 6));
                // $totalWt    = trim(substr($line, 118, 10));

                // $labChg     = trim(substr($line, 128, 8));
                // $stoneChg   = trim(substr($line, 136, 8));
                // $addLChg    = trim(substr($line, 144, 10));
                // $totalVal   = trim(substr($line, 154, 12));
                // $lossPct    = trim(substr($line, 166, 6));
                // $minWt      = trim(substr($line, 172, 8));
                // $maxWt      = trim(substr($line, 180, 8));
                // $ord        = trim(substr($line, 188, 3));

                $slNo       = trim(substr($line, 0, 6));
                $itemCode   = trim(substr($line, 6, 17));
                $desc       = trim(substr($line, 23, 35));
                $size       = trim(substr($line, 58, 12));
                $finding    = trim(substr($line, 70, 10));
                $uom        = trim(substr($line, 80, 6));
                $kt         = trim(substr($line, 86, 4));
                $stdWt      = trim(substr($line, 90, 8));
                $convWt     = trim(substr($line, 98, 8));
                $ordQty     = trim(substr($line, 106, 6));
                $totalWt    = trim(substr($line, 112, 10));
                $labChg     = trim(substr($line, 122, 8));
                $stoneChg   = trim(substr($line, 130, 8));
                $addLChg    = trim(substr($line, 138, 10));
                $totalVal   = trim(substr($line, 148, 12));
                $lossPct    = trim(substr($line, 160, 6));
                $minWt      = trim(substr($line, 166, 8));
                $maxWt      = trim(substr($line, 174, 8));
                $ord        = trim(substr($line, 182, 3));



                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => $itemCode,
                    'design'        => $itemCode ? substr($itemCode, 2, 9) : null,
                    'description'   => $desc ?: null,
                    'size'          => (strtoupper($size) === 'N/A' || $size === '') ? null : $size,
                    'finding'       => $finding !== '' ? $finding : 'N/A',
                    'uom'           => $uom ?: null,
                    'kt'            => $kt ?: null,
                    'std_wt'        => is_numeric($stdWt) ? (float) $stdWt : 0,
                    'conv_wt'       => is_numeric($convWt) ? (float) $convWt : 0,
                    'ord_qty'       => is_numeric($ordQty) ? (float) $ordQty : 0,
                    'total_wt'      => is_numeric($totalWt) ? (float) $totalWt : 0,
                    'lab_chg'       => is_numeric($labChg) ? (float) $labChg : 0,
                    'stone_chg'     => is_numeric($stoneChg) ? (float) $stoneChg : 0,
                    'add_l_chg'     => is_numeric($addLChg) ? (float) $addLChg : 0,
                    'total_value'   => is_numeric($totalVal) ? (float) $totalVal : 0,
                    'loss_percent'  => is_numeric($lossPct) ? (float) $lossPct : 0,
                    'min_wt'        => is_numeric($minWt) ? (float) $minWt : 0,
                    'max_wt'        => is_numeric($maxWt) ? (float) $maxWt : 0,
                    'ord'           => $ord ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/

    /*public function customerorderstempimporttxt(Request $request)
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => strip_tags($request->is_active),
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = rtrim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart || empty(trim($line))) continue;

                // ✅ Fixed-width parsing
                $slNo       = trim(substr($line, 0, 6));
                $itemCode   = trim(substr($line, 6, 17));
                $desc       = trim(substr($line, 23, 35));
                $size       = trim(substr($line, 58, 12));
                $finding    = trim(substr($line, 70, 10));
                $uom        = trim(substr($line, 80, 6));
                $kt         = trim(substr($line, 86, 4));
                $stdWt      = trim(substr($line, 90, 8));
                $convWt     = trim(substr($line, 98, 8));
                $ordQty     = trim(substr($line, 106, 6));
                $totalWt    = trim(substr($line, 112, 10));
                $labChg     = trim(substr($line, 122, 8));
                $stoneChg   = trim(substr($line, 130, 8));
                $addLChg    = trim(substr($line, 138, 10));
                $totalVal   = trim(substr($line, 148, 12));
                $lossPct    = trim(substr($line, 160, 6));
                $minWt      = trim(substr($line, 166, 8));
                $maxWt      = trim(substr($line, 174, 8));
                $ord        = trim(substr($line, 182, 3));

                // ✅ Safe defaults
                $finding    = $finding !== '' ? $finding : 'N/A';
                $stdWt      = is_numeric($stdWt) ? (float) $stdWt : 0;
                $convWt     = is_numeric($convWt) ? (float) $convWt : 0;
                $ordQty     = is_numeric($ordQty) ? (float) $ordQty : 0;
                $totalWt    = is_numeric($totalWt) ? (float) $totalWt : 0;
                $labChg     = is_numeric($labChg) ? (float) $labChg : 0;
                $stoneChg   = is_numeric($stoneChg) ? (float) $stoneChg : 0;
                $addLChg    = is_numeric($addLChg) ? (float) $addLChg : 0;
                $totalVal   = is_numeric($totalVal) ? (float) $totalVal : 0;
                $lossPct    = is_numeric($lossPct) ? (float) $lossPct : 0;
                $minWt      = is_numeric($minWt) ? (float) $minWt : 0;
                $maxWt      = is_numeric($maxWt) ? (float) $maxWt : 0;

                // Skip header/separator rows (like dashed lines)
                if (!is_numeric($slNo)) continue;

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => $itemCode,
                    'design'        => $itemCode ? substr($itemCode, 2, 9) : null,
                    'description'   => $desc ?: null,
                    'size'          => (strtoupper($size) === 'N/A' || $size === '') ? null : $size,
                    'finding'       => $finding,
                    'uom'           => $uom ?: null,
                    'kt'            => $kt ?: null,
                    'std_wt'        => $stdWt,
                    'conv_wt'       => $convWt,
                    'ord_qty'       => $ordQty,
                    'total_wt'      => $totalWt,
                    'lab_chg'       => $labChg,
                    'stone_chg'     => $stoneChg,
                    'add_l_chg'     => $addLChg,
                    'total_value'   => $totalVal,
                    'loss_percent'  => $lossPct,
                    'min_wt'        => $minWt,
                    'max_wt'        => $maxWt,
                    'ord'           => $ord ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/

    /*
    public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
            //'type' => 'required|in:Customer,Regular',
            //'is_active' => 'sometimes|boolean'
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $insertedIds = [];

        // Extract company name from first non-empty line
        $lines = explode("\n", $content);
        $CompanyName = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !str_contains($line, '---') && !str_contains($line, 'CONSOLIDATED')) {
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => $request->is_active ?? true,
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows using regex pattern matching
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = rtrim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart) continue;

                // Skip empty lines or separator lines
                if (empty(trim($line)) || str_starts_with(trim($line), '---') || trim($line) === 'Total:') {
                    continue;
                }

                // ✅ Use regex to parse the data line
                // Pattern: SLNo (digits), Item Code (alphanumeric), Description (any chars), etc.
                $pattern = '/^\s*(\d+)\s+([A-Z0-9]+)\s+(.*?)\s+([A-Z0-9\.\s\/MM]+|N\s*\/\s*A)\s+([A-Z\s]*?)\s+([A-Z0-9]*)\s+([0-9]+)\s+([0-9\.]+)\s+([0-9\.]*)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([A-Z])/';

                if (preg_match($pattern, $line, $matches)) {
                    $slNo       = $matches[1];
                    $itemCode   = $matches[2];
                    $desc       = trim($matches[3]);
                    $size       = trim($matches[4]);
                    $finding    = trim($matches[5]);
                    $uom        = trim($matches[6]);
                    $kt         = $matches[7];
                    $stdWt      = $matches[8];
                    $convWt     = $matches[9] ?: 0;
                    $ordQty     = $matches[10];
                    $totalWt    = $matches[11];
                    $labChg     = $matches[12];
                    $stoneChg   = $matches[13];
                    $addLChg    = $matches[14];
                    $totalVal   = $matches[15];
                    $lossPct    = $matches[16];
                    $minWt      = $matches[17];
                    $maxWt      = $matches[18];
                    $ord        = $matches[19];
                } else {
                    // Alternative approach: split by multiple spaces (more flexible)
                    $parts = preg_split('/\s{2,}/', $line);
                    if (count($parts) >= 19 && is_numeric(trim($parts[0]))) {
                        $slNo       = trim($parts[0]);
                        $itemCode   = trim($parts[1]);
                        $desc       = trim($parts[2]);
                        $size       = trim($parts[3]);
                        $finding    = trim($parts[4]);
                        $uom        = trim($parts[5]);
                        $kt         = trim($parts[6]);
                        $stdWt      = trim($parts[7]);
                        $convWt     = trim($parts[8]) ?: 0;
                        $ordQty     = trim($parts[9]);
                        $totalWt    = trim($parts[10]);
                        $labChg     = trim($parts[11]);
                        $stoneChg   = trim($parts[12]);
                        $addLChg    = trim($parts[13]);
                        $totalVal   = trim($parts[14]);
                        $lossPct    = trim($parts[15]);
                        $minWt      = trim($parts[16]);
                        $maxWt      = trim($parts[17]);
                        $ord        = trim($parts[18]);
                    } else {
                        // Skip if it doesn't look like a data row
                        continue;
                    }
                }

                // Skip header/separator rows or non-numeric SLNo
                if (!is_numeric($slNo)) continue;

                // ✅ Clean and convert values
                $cleanNumeric = function ($value) {
                    $value = trim($value);
                    // Remove any non-numeric characters except decimal point
                    $value = preg_replace('/[^0-9\.]/', '', $value);
                    return is_numeric($value) ? (float) $value : 0;
                };

                $stdWt      = $cleanNumeric($stdWt);
                $convWt     = $cleanNumeric($convWt);
                $ordQty     = $cleanNumeric($ordQty);
                $totalWt    = $cleanNumeric($totalWt);
                $labChg     = $cleanNumeric($labChg);
                $stoneChg   = $cleanNumeric($stoneChg);
                $addLChg    = $cleanNumeric($addLChg);
                $totalVal   = $cleanNumeric($totalVal);
                $lossPct    = $cleanNumeric($lossPct);
                $minWt      = $cleanNumeric($minWt);
                $maxWt      = $cleanNumeric($maxWt);

                // Handle empty values
                $finding    = trim($finding) !== '' ? $finding : null;
                $uom        = trim($uom) !== '' ? $uom : null;
                $size       = (strtoupper(trim($size)) === 'N/A' || trim($size) === '') ? null : trim($size);

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => trim($itemCode),
                    'design'        => trim($itemCode) ? substr(trim($itemCode), 2, 9) : null,
                    'description'   => trim($desc) ?: null,
                    'size'          => $size,
                    'finding'       => $finding,
                    'uom'           => $uom,
                    'kt'            => trim($kt) ?: null,
                    'std_wt'        => $stdWt,
                    'conv_wt'       => $convWt,
                    'ord_qty'       => $ordQty,
                    'total_wt'      => $totalWt,
                    'lab_chg'       => $labChg,
                    'stone_chg'     => $stoneChg,
                    'add_l_chg'     => $addLChg,
                    'total_value'   => $totalVal,
                    'loss_percent'  => $lossPct,
                    'min_wt'        => $minWt,
                    'max_wt'        => $maxWt,
                    'ord'           => trim($ord) ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar - improved handling
                // $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                // $itemData['kid'] = $getkid && $getkid->karigar && $getkid->karigar->isNotEmpty()
                //     ? $getkid->karigar->first()->kid
                //     : null;

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;
                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/


    /*public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
            //'type' => 'required|in:Customer,Regular',
            //'is_active' => 'sometimes|boolean'
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $insertedIds = [];

        // Extract company name from first non-empty line
        $lines = explode("\n", $content);
        $CompanyName = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !str_contains($line, '---') && !str_contains($line, 'CONSOLIDATED')) {
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => $request->is_active ?? true,
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows using regex pattern matching
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = rtrim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart) continue;

                // Skip empty lines or separator lines
                if (empty(trim($line)) || str_starts_with(trim($line), '---') || trim($line) === 'Total:') {
                    continue;
                }

                // ✅ Use fixed position for UOM extraction (columns 80-86)
                $uom = trim(substr($line, 80, 6));
                $uom = preg_replace('/[^A-Z]/', '', $uom); // Remove any non-alphabet characters
                $uom = $uom !== '' ? $uom : null;

                // ✅ Use regex to parse the data line (excluding UOM since we already extracted it)
                $pattern = '/^\s*(\d+)\s+([A-Z0-9]+)\s+(.*?)\s+([A-Z0-9\.\s\/MM]+|N\s*\/\s*A)\s+([A-Z\s]*?)\s+([0-9]+)\s+([0-9\.]+)\s+([0-9\.]*)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([A-Z])/';

                if (preg_match($pattern, $line, $matches)) {
                    $slNo       = $matches[1];
                    $itemCode   = $matches[2];
                    $desc       = trim($matches[3]);
                    $size       = trim($matches[4]);
                    $finding    = trim($matches[5]);
                    $kt         = $matches[6];
                    $stdWt      = $matches[7];
                    $convWt     = $matches[8] ?: 0;
                    $ordQty     = $matches[9];
                    $totalWt    = $matches[10];
                    $labChg     = $matches[11];
                    $stoneChg   = $matches[12];
                    $addLChg    = $matches[13];
                    $totalVal   = $matches[14];
                    $lossPct    = $matches[15];
                    $minWt      = $matches[16];
                    $maxWt      = $matches[17];
                    $ord        = $matches[18];
                } else {
                    // Alternative approach: split by multiple spaces (more flexible)
                    $parts = preg_split('/\s{2,}/', $line);
                    if (count($parts) >= 18 && is_numeric(trim($parts[0]))) {
                        $slNo       = trim($parts[0]);
                        $itemCode   = trim($parts[1]);
                        $desc       = trim($parts[2]);
                        $size       = trim($parts[3]);
                        $finding    = trim($parts[4]);
                        // UOM is already extracted using fixed position above
                        $kt         = trim($parts[5]);
                        $stdWt      = trim($parts[6]);
                        $convWt     = isset($parts[7]) ? trim($parts[7]) : 0;
                        $ordQty     = isset($parts[8]) ? trim($parts[8]) : 0;
                        $totalWt    = isset($parts[9]) ? trim($parts[9]) : 0;
                        $labChg     = isset($parts[10]) ? trim($parts[10]) : 0;
                        $stoneChg   = isset($parts[11]) ? trim($parts[11]) : 0;
                        $addLChg    = isset($parts[12]) ? trim($parts[12]) : 0;
                        $totalVal   = isset($parts[13]) ? trim($parts[13]) : 0;
                        $lossPct    = isset($parts[14]) ? trim($parts[14]) : 0;
                        $minWt      = isset($parts[15]) ? trim($parts[15]) : 0;
                        $maxWt      = isset($parts[16]) ? trim($parts[16]) : 0;
                        $ord        = isset($parts[17]) ? trim($parts[17]) : null;
                    } else {
                        // Skip if it doesn't look like a data row
                        continue;
                    }
                }

                // Skip header/separator rows or non-numeric SLNo
                if (!is_numeric($slNo)) continue;

                // ✅ Clean and convert values
                $cleanNumeric = function ($value) {
                    $value = trim($value);
                    // Remove any non-numeric characters except decimal point
                    $value = preg_replace('/[^0-9\.]/', '', $value);
                    return is_numeric($value) ? (float) $value : 0;
                };

                $stdWt      = $cleanNumeric($stdWt);
                $convWt     = $cleanNumeric($convWt);
                $ordQty     = $cleanNumeric($ordQty);
                $totalWt    = $cleanNumeric($totalWt);
                $labChg     = $cleanNumeric($labChg);
                $stoneChg   = $cleanNumeric($stoneChg);
                $addLChg    = $cleanNumeric($addLChg);
                $totalVal   = $cleanNumeric($totalVal);
                $lossPct    = $cleanNumeric($lossPct);
                $minWt      = $cleanNumeric($minWt);
                $maxWt      = $cleanNumeric($maxWt);

                // Handle empty values
                $finding    = trim($finding) !== '' ? $finding : null;
                $size       = (strtoupper(trim($size)) === 'N/A' || trim($size) === '') ? null : trim($size);

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => trim($itemCode),
                    'design'        => trim($itemCode) ? substr(trim($itemCode), 2, 9) : null,
                    'description'   => trim($desc) ?: null,
                    'size'          => $size,
                    'finding'       => $finding,
                    'uom'           => $uom, // Use the UOM extracted via fixed position
                    'kt'            => trim($kt) ?: null,
                    'std_wt'        => $stdWt,
                    'conv_wt'       => $convWt,
                    'ord_qty'       => $ordQty,
                    'total_wt'      => $totalWt,
                    'lab_chg'       => $labChg,
                    'stone_chg'     => $stoneChg,
                    'add_l_chg'     => $addLChg,
                    'total_value'   => $totalVal,
                    'loss_percent'  => $lossPct,
                    'min_wt'        => $minWt,
                    'max_wt'        => $maxWt,
                    'ord'           => trim($ord) ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/




    /*public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
            //'type' => 'required|in:Customer,Regular',
            //'is_active' => 'sometimes|boolean'
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $insertedIds = [];

        // Extract company name from first non-empty line
        $lines = explode("\n", $content);
        $CompanyName = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !str_contains($line, '---') && !str_contains($line, 'CONSOLIDATED')) {
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => $request->is_active ?? true,
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows using regex pattern matching
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = rtrim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart) continue;

                // Skip empty lines or separator lines
                if (empty(trim($line)) || str_starts_with(trim($line), '---') || trim($line) === 'Total:') {
                    continue;
                }

                // ✅ Use fixed position for UOM extraction (columns 80-86)
                $uom = trim(substr($line, 80, 6));
                $uom = preg_replace('/[^A-Z]/', '', $uom); // Remove any non-alphabet characters
                $uom = $uom !== '' ? $uom : null;

                // ✅ Use regex to parse the data line (excluding UOM since we already extracted it)
                $pattern = '/^\s*(\d+)\s+([A-Z0-9]+)\s+(.*?)\s+([A-Z0-9\.\s\/MM]+|N\s*\/\s*A)\s+([A-Z\s]*?)\s+([0-9]+)\s+([0-9\.]+)\s+([0-9\.]*)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([A-Z])/';

                if (preg_match($pattern, $line, $matches)) {
                    $slNo       = $matches[1];
                    $itemCode   = $matches[2];
                    $desc       = trim($matches[3]);
                    $size       = trim($matches[4]);
                    $finding    = trim($matches[5]);
                    $kt         = $matches[6];
                    $stdWt      = $matches[7];
                    $convWt     = $matches[8] ?: 0;
                    $ordQty     = $matches[9];
                    $totalWt    = $matches[10];
                    $labChg     = $matches[11];
                    $stoneChg   = $matches[12];
                    $addLChg    = $matches[13];
                    $totalVal   = $matches[14];
                    $lossPct    = $matches[15];
                    $minWt      = $matches[16];
                    $maxWt      = $matches[17];
                    $ord        = $matches[18];
                } else {
                    // Alternative approach: split by multiple spaces (more flexible)
                    $parts = preg_split('/\s{2,}/', $line);
                    if (count($parts) >= 18 && is_numeric(trim($parts[0]))) {
                        $slNo       = trim($parts[0]);
                        $itemCode   = trim($parts[1]);
                        $desc       = trim($parts[2]);
                        $size       = trim($parts[3]);
                        $finding    = trim($parts[4]);
                        // UOM is already extracted using fixed position above
                        $kt         = trim($parts[5]);
                        $stdWt      = trim($parts[6]);
                        $convWt     = isset($parts[7]) ? trim($parts[7]) : 0;
                        $ordQty     = isset($parts[8]) ? trim($parts[8]) : 0;
                        $totalWt    = isset($parts[9]) ? trim($parts[9]) : 0;
                        $labChg     = isset($parts[10]) ? trim($parts[10]) : 0;
                        $stoneChg   = isset($parts[11]) ? trim($parts[11]) : 0;
                        $addLChg    = isset($parts[12]) ? trim($parts[12]) : 0;
                        $totalVal   = isset($parts[13]) ? trim($parts[13]) : 0;
                        $lossPct    = isset($parts[14]) ? trim($parts[14]) : 0;
                        $minWt      = isset($parts[15]) ? trim($parts[15]) : 0;
                        $maxWt      = isset($parts[16]) ? trim($parts[16]) : 0;
                        $ord        = isset($parts[17]) ? trim($parts[17]) : null;
                    } else {
                        // Skip if it doesn't look like a data row
                        continue;
                    }
                }

                // Skip header/separator rows or non-numeric SLNo
                if (!is_numeric($slNo)) continue;

                // ✅ Clean and convert values
                $cleanNumeric = function ($value) {
                    $value = trim($value);
                    // Remove any non-numeric characters except decimal point
                    $value = preg_replace('/[^0-9\.]/', '', $value);
                    return is_numeric($value) ? (float) $value : 0;
                };

                $stdWt      = $cleanNumeric($stdWt);
                $convWt     = $cleanNumeric($convWt);
                $ordQty     = $cleanNumeric($ordQty);
                $totalWt    = $cleanNumeric($totalWt);
                $labChg     = $cleanNumeric($labChg);
                $stoneChg   = $cleanNumeric($stoneChg);
                $addLChg    = $cleanNumeric($addLChg);
                $totalVal   = $cleanNumeric($totalVal);
                $lossPct    = $cleanNumeric($lossPct);
                $minWt      = $cleanNumeric($minWt);
                $maxWt      = $cleanNumeric($maxWt);

                // Handle empty values
                $finding    = trim($finding) !== '' ? $finding : null;
                $size       = (strtoupper(trim($size)) === 'N/A' || trim($size) === '') ? null : trim($size);

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => trim($itemCode),
                    'design'        => trim($itemCode) ? substr(trim($itemCode), 2, 9) : null,
                    'description'   => trim($desc) ?: null,
                    'size'          => $size,
                    'finding'       => $finding,
                    'uom'           => $uom, // Use the UOM extracted via fixed position
                    'kt'            => trim($kt) ?: null,
                    'std_wt'        => $stdWt,
                    'conv_wt'       => $convWt,
                    'ord_qty'       => $ordQty,
                    'total_wt'      => $totalWt,
                    'lab_chg'       => $labChg,
                    'stone_chg'     => $stoneChg,
                    'add_l_chg'     => $addLChg,
                    'total_value'   => $totalVal,
                    'loss_percent'  => $lossPct,
                    'min_wt'        => $minWt,
                    'max_wt'        => $maxWt,
                    'ord'           => trim($ord) ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/

    /*
    public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
            //'type' => 'required|in:Customer,Regular',
            //'is_active' => 'sometimes|boolean'
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $insertedIds = [];

        // Extract company name from first non-empty line
        $lines = explode("\n", $content);
        $CompanyName = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !str_contains($line, '---') && !str_contains($line, 'CONSOLIDATED')) {
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => $request->is_active ?? true,
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows using regex pattern matching
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = rtrim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart) continue;

                // Skip empty lines or separator lines
                if (empty(trim($line)) || str_starts_with(trim($line), '---') || trim($line) === 'Total:') {
                    continue;
                }

                // ✅ Use fixed position for UOM extraction (columns 80-86)
                $uom = trim(substr($line, 80, 6));
                $uom = preg_replace('/[^A-Z]/', '', $uom); // Remove any non-alphabet characters
                $uom = $uom !== '' ? $uom : null;

                // ✅ Use regex to parse the data line (excluding UOM since we already extracted it)
                $pattern = '/^\s*(\d+)\s+([A-Z0-9]+)\s+(.*?)\s+([A-Z0-9\.\s\/MM]+|N\s*\/\s*A)\s+([A-Z\s]*?)\s+([0-9]+)\s+([0-9\.]+)\s+([0-9\.]*)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([A-Z])/';

                if (preg_match($pattern, $line, $matches)) {
                    $slNo       = $matches[1];
                    $itemCode   = $matches[2];
                    $desc       = trim($matches[3]);
                    $size       = trim($matches[4]);
                    $finding    = trim($matches[5]);
                    $kt         = $matches[6];
                    $stdWt      = $matches[7];
                    $convWt     = $matches[8] ?: 0;
                    $ordQty     = $matches[9];
                    $totalWt    = $matches[10];
                    $labChg     = $matches[11];
                    $stoneChg   = $matches[12];
                    $addLChg    = $matches[13];
                    $totalVal   = $matches[14];
                    $lossPct    = $matches[15];
                    $minWt      = $matches[16];
                    $maxWt      = $matches[17];
                    $ord        = $matches[18];
                } else {
                    // Alternative approach: split by multiple spaces (more flexible)
                    $parts = preg_split('/\s{2,}/', $line);
                    if (count($parts) >= 18 && is_numeric(trim($parts[0]))) {
                        $slNo       = trim($parts[0]);
                        $itemCode   = trim($parts[1]);
                        $desc       = trim($parts[2]);
                        $size       = trim($parts[3]);
                        $finding    = trim($parts[4]);
                        // UOM is already extracted using fixed position above
                        $kt         = trim($parts[5]);
                        $stdWt      = trim($parts[6]);
                        $convWt     = isset($parts[7]) ? trim($parts[7]) : 0;
                        $ordQty     = isset($parts[8]) ? trim($parts[8]) : 0;
                        $totalWt    = isset($parts[9]) ? trim($parts[9]) : 0;
                        $labChg     = isset($parts[10]) ? trim($parts[10]) : 0;
                        $stoneChg   = isset($parts[11]) ? trim($parts[11]) : 0;
                        $addLChg    = isset($parts[12]) ? trim($parts[12]) : 0;
                        $totalVal   = isset($parts[13]) ? trim($parts[13]) : 0;
                        $lossPct    = isset($parts[14]) ? trim($parts[14]) : 0;
                        $minWt      = isset($parts[15]) ? trim($parts[15]) : 0;
                        $maxWt      = isset($parts[16]) ? trim($parts[16]) : 0;
                        $ord        = isset($parts[17]) ? trim($parts[17]) : null;
                    } else {
                        // Skip if it doesn't look like a data row
                        continue;
                    }
                }

                // Skip header/separator rows or non-numeric SLNo
                if (!is_numeric($slNo)) continue;

                // ✅ Clean and convert values
                $cleanNumeric = function ($value) {
                    $value = trim($value);
                    // Remove any non-numeric characters except decimal point
                    $value = preg_replace('/[^0-9\.]/', '', $value);
                    return is_numeric($value) ? (float) $value : 0;
                };

                $stdWt      = $cleanNumeric($stdWt);
                $convWt     = $cleanNumeric($convWt);
                $ordQty     = $cleanNumeric($ordQty);
                $totalWt    = $cleanNumeric($totalWt);
                $labChg     = $cleanNumeric($labChg);
                $stoneChg   = $cleanNumeric($stoneChg);
                $addLChg    = $cleanNumeric($addLChg);
                $totalVal   = $cleanNumeric($totalVal);
                $lossPct    = $cleanNumeric($lossPct);
                $minWt      = $cleanNumeric($minWt);
                $maxWt      = $cleanNumeric($maxWt);

                // Handle empty values
                $finding    = trim($finding) !== '' ? $finding : null;
                $size       = (strtoupper(trim($size)) === 'N/A' || trim($size) === '') ? null : trim($size);

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => trim($itemCode),
                    'design'        => trim($itemCode) ? substr(trim($itemCode), 2, 9) : null,
                    'description'   => trim($desc) ?: null,
                    'size'          => $size,
                    'finding'       => $finding,
                    'uom'           => $uom, // Use the UOM extracted via fixed position
                    'kt'            => trim($kt) ?: null,
                    'std_wt'        => $stdWt,
                    'conv_wt'       => $convWt,
                    'ord_qty'       => $ordQty,
                    'total_wt'      => $totalWt,
                    'lab_chg'       => $labChg,
                    'stone_chg'     => $stoneChg,
                    'add_l_chg'     => $addLChg,
                    'total_value'   => $totalVal,
                    'loss_percent'  => $lossPct,
                    'min_wt'        => $minWt,
                    'max_wt'        => $maxWt,
                    'ord'           => trim($ord) ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/

    /*
    public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
            //'type' => 'required|in:Customer,Regular',
            //'is_active' => 'sometimes|boolean'
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $insertedIds = [];

        // Extract company name from first non-empty line
        $lines = explode("\n", $content);
        $CompanyName = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !str_contains($line, '---') && !str_contains($line, 'CONSOLIDATED')) {
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => $request->is_active ?? true,
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows using regex pattern matching
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = rtrim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart) continue;

                // Skip empty lines or separator lines
                if (empty(trim($line)) || str_starts_with(trim($line), '---') || trim($line) === 'Total:') {
                    continue;
                }

                // ✅ Use fixed position for UOM extraction (columns 80-86)
                $uom = trim(substr($line, 80, 6));
                $uom = preg_replace('/[^A-Z]/', '', $uom); // Remove any non-alphabet characters
                $uom = $uom !== '' ? $uom : null;

                // ✅ Use regex to parse the data line (excluding UOM since we already extracted it)
                $pattern = '/^\s*(\d+)\s+([A-Z0-9]+)\s+(.*?)\s+([A-Z0-9\.\s\/MM]+|N\s*\/\s*A)\s+([A-Z\s]*?)\s+([0-9]+)\s+([0-9\.]+)\s+([0-9\.]*)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([A-Z])/';

                if (preg_match($pattern, $line, $matches)) {
                    $slNo       = $matches[1];
                    $itemCode   = $matches[2];
                    $desc       = trim($matches[3]);
                    $size       = trim($matches[4]);
                    $finding    = trim($matches[5]);
                    $kt         = $matches[6];
                    $stdWt      = $matches[7];
                    $convWt     = $matches[8] ?: 0;
                    $ordQty     = $matches[9];
                    $totalWt    = $matches[10];
                    $labChg     = $matches[11];
                    $stoneChg   = $matches[12];
                    $addLChg    = $matches[13];
                    $totalVal   = $matches[14];
                    $lossPct    = $matches[15];
                    $minWt      = $matches[16];
                    $maxWt      = $matches[17];
                    $ord        = $matches[18];
                } else {
                    // Alternative approach: split by multiple spaces (more flexible)
                    $parts = preg_split('/\s{2,}/', $line);
                    if (count($parts) >= 18 && is_numeric(trim($parts[0]))) {
                        $slNo       = trim($parts[0]);
                        $itemCode   = trim($parts[1]);
                        $desc       = trim($parts[2]);
                        $size       = trim($parts[3]);
                        $finding    = trim($parts[4]);
                        // UOM is already extracted using fixed position above
                        $kt         = trim($parts[5]);
                        $stdWt      = trim($parts[6]);
                        $convWt     = isset($parts[7]) ? trim($parts[7]) : 0;
                        $ordQty     = isset($parts[8]) ? trim($parts[8]) : 0;
                        $totalWt    = isset($parts[9]) ? trim($parts[9]) : 0;
                        $labChg     = isset($parts[10]) ? trim($parts[10]) : 0;
                        $stoneChg   = isset($parts[11]) ? trim($parts[11]) : 0;
                        $addLChg    = isset($parts[12]) ? trim($parts[12]) : 0;
                        $totalVal   = isset($parts[13]) ? trim($parts[13]) : 0;
                        $lossPct    = isset($parts[14]) ? trim($parts[14]) : 0;
                        $minWt      = isset($parts[15]) ? trim($parts[15]) : 0;
                        $maxWt      = isset($parts[16]) ? trim($parts[16]) : 0;
                        $ord        = isset($parts[17]) ? trim($parts[17]) : null;
                    } else {
                        // Skip if it doesn't look like a data row
                        continue;
                    }
                }

                // Skip header/separator rows or non-numeric SLNo
                if (!is_numeric($slNo)) continue;

                // ✅ Clean and convert values
                $cleanNumeric = function ($value) {
                    $value = trim($value);
                    // Remove any non-numeric characters except decimal point
                    $value = preg_replace('/[^0-9\.]/', '', $value);
                    return is_numeric($value) ? (float) $value : 0;
                };

                $stdWt      = $cleanNumeric($stdWt);
                $convWt     = $cleanNumeric($convWt);
                $ordQty     = $cleanNumeric($ordQty);
                $totalWt    = $cleanNumeric($totalWt);
                $labChg     = $cleanNumeric($labChg);
                $stoneChg   = $cleanNumeric($stoneChg);
                $addLChg    = $cleanNumeric($addLChg);
                $totalVal   = $cleanNumeric($totalVal);
                $lossPct    = $cleanNumeric($lossPct);
                $minWt      = $cleanNumeric($minWt);
                $maxWt      = $cleanNumeric($maxWt);

                // Handle empty values
                $finding    = trim($finding) !== '' ? $finding : null;
                $size       = (strtoupper(trim($size)) === 'N/A' || trim($size) === '') ? null : trim($size);

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => trim($itemCode),
                    'design'        => trim($itemCode) ? substr(trim($itemCode), 2, 9) : null,
                    'description'   => trim($desc) ?: null,
                    'size'          => $size,
                    'finding'       => $finding,
                    'uom'           => $uom, // Use the UOM extracted via fixed position
                    'kt'            => trim($kt) ?: null,
                    'std_wt'        => $stdWt,
                    'conv_wt'       => $convWt,
                    'ord_qty'       => $ordQty,
                    'total_wt'      => $totalWt,
                    'lab_chg'       => $labChg,
                    'stone_chg'     => $stoneChg,
                    'add_l_chg'     => $addLChg,
                    'total_value'   => $totalVal,
                    'loss_percent'  => $lossPct,
                    'min_wt'        => $minWt,
                    'max_wt'        => $maxWt,
                    'ord'           => trim($ord) ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }*/


    public function customerorderstempimporttxt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
            //'type' => 'required|in:Customer,Regular',
            //'is_active' => 'sometimes|boolean'
        ]);

        $filePath = $request->file('file')->getRealPath();
        $content = file_get_contents($filePath);

        // Split the file by JO sections
        $blocks = preg_split('/(?=\n.*?JO No\s*:\s*)/', $content);
        $insertedIds = [];

        // Extract company name from first non-empty line
        $lines = explode("\n", $content);
        $CompanyName = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !str_contains($line, '---') && !str_contains($line, 'CONSOLIDATED')) {
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

            // ✅ Flexible regex for headers
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block' => $block]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // ✅ JO Date parsing
            try {
                $carbonDate = Carbon::createFromFormat('d/m/Y h:i:s A', $joDate);
                $formattedDate = $carbonDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error("Failed to parse JO Date: " . $joDate);
                continue;
            }

            $deliveryDate = Carbon::createFromFormat('d/m/Y', $deliveryDateMatch[1])->format('Y-m-d');
            $vendorSite   = $vendorSiteMatch[1] ?? null;

            // Skip duplicates
            if (Customerordertemp::where('jo_no', $joNo)->exists()) continue;

            // Insert JO header
            $customerorder = Customerordertemp::create([
                'customer_id' => $GetCompanyId->id,
                'jo_no'       => $joNo,
                'jo_date'     => $formattedDate,
                'order_type'  => 'AutoUpload',
                'type'        => $request->type,
                'is_active'   => $request->is_active ?? true,
                'vendor_site' => $vendorSite,
                'created_by'  => Auth::user()->name
            ]);
            $insertedIds[] = $customerorder->id;

            // Process item rows using regex pattern matching
            $blockLines = explode("\n", $block);
            $dataStart = false;

            foreach ($blockLines as $line) {
                $line = rtrim($line);

                // Detect start of items
                if (!$dataStart && str_contains($line, 'SLNo') && str_contains($line, 'Item Code')) {
                    $dataStart = true;
                    continue;
                }

                if (!$dataStart) continue;

                // Skip empty lines or separator lines
                if (empty(trim($line)) || str_starts_with(trim($line), '---') || trim($line) === 'Total:') {
                    continue;
                }

                // ✅ Use fixed position for UOM extraction (columns 80-86)
                $uom = trim(substr($line, 80, 6));
                $uom = preg_replace('/[^A-Z]/', '', $uom); // Remove any non-alphabet characters
                $uom = $uom !== '' ? $uom : null;

                // ✅ Use regex to parse the data line (excluding UOM since we already extracted it)
                $pattern = '/^\s*(\d+)\s+([A-Z0-9]+)\s+(.*?)\s+([A-Z0-9\.\s\/MM]+|N\s*\/\s*A)\s+([A-Z\s]*?)\s+([0-9]+)\s+([0-9\.]+)\s+([0-9\.]*)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([A-Z])/';

                if (preg_match($pattern, $line, $matches)) {
                    $slNo       = $matches[1];
                    $itemCode   = $matches[2];
                    $desc       = trim($matches[3]);
                    $size       = trim($matches[4]);
                    $finding    = trim($matches[5]);
                    $kt         = $matches[6];
                    $stdWt      = $matches[7];
                    $convWt     = $matches[8] ?: 0;
                    $ordQty     = $matches[9];
                    $totalWt    = $matches[10];
                    $labChg     = $matches[11];
                    $stoneChg   = $matches[12];
                    $addLChg    = $matches[13];
                    $totalVal   = $matches[14];
                    $lossPct    = $matches[15];
                    $minWt      = $matches[16];
                    $maxWt      = $matches[17];
                    $ord        = $matches[18];
                } else {
                    // Alternative approach: split by multiple spaces (more flexible)
                    $parts = preg_split('/\s{2,}/', $line);
                    if (count($parts) >= 18 && is_numeric(trim($parts[0]))) {
                        $slNo       = trim($parts[0]);
                        $itemCode   = trim($parts[1]);
                        $desc       = trim($parts[2]);
                        $size       = trim($parts[3]);
                        $finding    = trim($parts[4]);
                        // UOM is already extracted using fixed position above
                        $kt         = trim($parts[5]);
                        $stdWt      = trim($parts[6]);
                        $convWt     = isset($parts[7]) ? trim($parts[7]) : 0;
                        $ordQty     = isset($parts[8]) ? trim($parts[8]) : 0;
                        $totalWt    = isset($parts[9]) ? trim($parts[9]) : 0;
                        $labChg     = isset($parts[10]) ? trim($parts[10]) : 0;
                        $stoneChg   = isset($parts[11]) ? trim($parts[11]) : 0;
                        $addLChg    = isset($parts[12]) ? trim($parts[12]) : 0;
                        $totalVal   = isset($parts[13]) ? trim($parts[13]) : 0;
                        $lossPct    = isset($parts[14]) ? trim($parts[14]) : 0;
                        $minWt      = isset($parts[15]) ? trim($parts[15]) : 0;
                        $maxWt      = isset($parts[16]) ? trim($parts[16]) : 0;
                        $ord        = isset($parts[17]) ? trim($parts[17]) : null;
                    } else {
                        // Skip if it doesn't look like a data row
                        continue;
                    }
                }

                // Skip header/separator rows or non-numeric SLNo
                if (!is_numeric($slNo)) continue;

                // ✅ Clean and convert values
                $cleanNumeric = function ($value) {
                    $value = trim($value);
                    // Remove any non-numeric characters except decimal point
                    $value = preg_replace('/[^0-9\.]/', '', $value);
                    return is_numeric($value) ? (float) $value : 0;
                };

                $stdWt      = $cleanNumeric($stdWt);
                $convWt     = $cleanNumeric($convWt);
                $ordQty     = $cleanNumeric($ordQty);
                $totalWt    = $cleanNumeric($totalWt);
                $labChg     = $cleanNumeric($labChg);
                $stoneChg   = $cleanNumeric($stoneChg);
                $addLChg    = $cleanNumeric($addLChg);
                $totalVal   = $cleanNumeric($totalVal);
                $lossPct    = $cleanNumeric($lossPct);
                $minWt      = $cleanNumeric($minWt);
                $maxWt      = $cleanNumeric($maxWt);

                // Handle empty values
                $finding    = trim($finding) !== '' ? $finding : null;
                $size       = (strtoupper(trim($size)) === 'N/A' || trim($size) === '') ? null : trim($size);

                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => $slNo,
                    'item_code'     => trim($itemCode),
                    'design'        => trim($itemCode) ? substr(trim($itemCode), 2, 9) : null,
                    'description'   => trim($desc) ?: null,
                    'size'          => $size,
                    'finding'       => $finding,
                    'uom'           => $uom, // Use the UOM extracted via fixed position
                    'kt'            => trim($kt) ?: null,
                    'std_wt'        => $stdWt,
                    'conv_wt'       => $convWt,
                    'ord_qty'       => $ordQty,
                    'total_wt'      => $totalWt,
                    'lab_chg'       => $labChg,
                    'stone_chg'     => $stoneChg,
                    'add_l_chg'     => $addLChg,
                    'total_value'   => $totalVal,
                    'loss_percent'  => $lossPct,
                    'min_wt'        => $minWt,
                    'max_wt'        => $maxWt,
                    'ord'           => trim($ord) ?: null,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar
                $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                Customerordertempitem::create($itemData);
            }
        }

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }



    public function savetempproducts(Request $request)
    {
        DB::beginTransaction();

        try {
            $items = Customerordertempitem::whereNull('kid')->get();

            foreach ($items as $item) {
                $vendorsite = Customerordertemp::where('id', $item->order_id)->first();
                // Validate item_code length
                $len = strlen($item->item_code);
                if ($len === 14) {
                    $company_id = 1; // TCL KOL
                } elseif ($len === 15) {
                    $company_id = 6; // NOVJL
                } else {
                    throw new \Exception("Invalid item_code length for {$item->item_code}");
                }

                // Extract codes
                $pCode = substr($item->item_code, 6, 1); // 7th char
                $size  = substr($item->item_code, 9, 1); // 10th char

                $getpcodeid = Pcode::where('code', $pCode)->first();
                $getsizeid  = Size::where('schar', $size)->where('pcode_id', $getpcodeid->id)->first();

                // Debug log for missing master data
                if (!$getpcodeid || !$getsizeid) {
                    \Log::warning("Missing master data for item_code={$item->item_code}, pCode={$pCode}, size={$size}");
                }

                // Purity mapping
                $purities = [
                    50 => 75,
                    51 => 91.6,
                ];
                $purity = $purities[$item->kt] ?? 0.00;

                // Create product
                $product = Product::create([
                    'company_id'     => $company_id,
                    'vendorsite'     => $vendorsite->vendor_site,
                    'item_code'      => $item->item_code,
                    'design_num'     => $item->design,
                    'description'    => $item->description,
                    'pcode_id'       => $getpcodeid?->id, // safe access
                    'size_id'        => $getsizeid?->id,  // safe access
                    'uom_id'         => 12, // TODO: replace with config/constant
                    'standard_wt'    => $item->std_wt ?? 0,
                    'kt'             => $item->kt . 'KT',
                    'kid'            => 32, // TODO: replace with config/constant
                    'purity'         => $purity,
                    'remarks'        => $item->remarks,
                    'customer_order' => 'Yes',
                    'stone_charge'   => $item->stone_chg,
                    'lab_charge'     => $item->lab_chg,
                    'loss'           => $item->loss_percent,
                    'pcodechar'      => $len,
                    'created_by'     => Auth::user()->name,
                ]);

                // Add default stone details
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

            return redirect()->route('products.index', [
                'customer_order' => 'Yes',
                'search' => ''
            ])->with('success', 'Products saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
