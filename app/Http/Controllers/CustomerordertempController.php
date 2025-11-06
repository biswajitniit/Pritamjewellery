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
use App\Models\Uom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            //->leftJoin('karigars AS k', 'i.kid', '=', 'k.kid')
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

        // helper to clean numeric -> returns float or 0
        $numericOrZero = function ($value) {
            $v = trim($value);
            $v = preg_replace('/[^0-9\.]/', '', $v);
            return $v === '' ? 0.0 : (float)$v;
        };

        // helper to clean numeric -> returns float or null (for ConvWT)
        $numericOrNull = function ($value) {
            $v = trim($value);
            $v = preg_replace('/[^0-9\.]/', '', $v);
            return $v === '' ? null : (float)$v;
        };

        foreach ($blocks as $blockIndex => $block) {
            if (trim($block) === '') continue;

            // Extract JO header fields
            preg_match('/JO\s*No\s*:\s*([0-9]+)/i', $block, $joNoMatch);
            preg_match('/JO\s*Date\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4}\s+[0-9]{1,2}:[0-9]{2}:[0-9]{2}\s?(AM|PM))/i', $block, $joDateMatch);
            preg_match('/Vendor\s*Site\s*:\s*([A-Za-z0-9\-]+)/i', $block, $vendorSiteMatch);
            preg_match('/Delivery\s*Required\s*Before\s*:\s*([0-9]{2}\/[0-9]{2}\/[0-9]{4})/i', $block, $deliveryDateMatch);

            if (!isset($joNoMatch[1], $joDateMatch[1], $deliveryDateMatch[1])) {
                Log::warning("Missing JO header fields in block", ['block_index' => $blockIndex]);
                continue;
            }

            $joNo = trim($joNoMatch[1]);
            $joDate = trim($joDateMatch[1]);

            // Parse JO date
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

            // Split block lines and find header row inside the block
            $blockLines = explode("\n", $block);
            $headerIndex = null;
            foreach ($blockLines as $i => $ln) {
                if (stripos($ln, 'SLNo') !== false && stripos($ln, 'Item Code') !== false && stripos($ln, 'Descripton') !== false) {
                    $headerIndex = $i;
                    break;
                }
            }
            if ($headerIndex === null) {
                Log::warning("Header not found in JO block", ['jo_no' => $joNo]);
                continue;
            }

            $headerLine = $blockLines[$headerIndex];

            // Define the header tokens in order (we will compute their start positions)
            $tokens = [
                'SLNo',
                'Item Code',
                'Descripton',
                'Size',
                'Finding',
                'UOM',
                'Kt',
                'StdWT',
                'ConvWT',
                'Ord.Qty',
                'Total.Wt',
                'Lab.Chg',
                'StoneChg',
                'Add.L.Chg',
                'TotalValue',
                'Loss%',
                'MinWt',
                'MaxWt',
                'Ord'
            ];

            // compute column start positions based on header line
            $starts = [];
            foreach ($tokens as $token) {
                if ($token === 'Ord') {
                    // last 'Ord' might appear twice in header, use last occurrence
                    $pos = strripos($headerLine, $token);
                } else {
                    $pos = stripos($headerLine, $token);
                }
                if ($pos === false) {
                    // if any token not found, log and skip this block (you can choose fallback)
                    Log::warning("Header token not found", ['token' => $token, 'jo_no' => $joNo, 'header' => $headerLine]);
                    $pos = null;
                }
                $starts[$token] = $pos;
            }

            // Build an ordered list of tokens that we actually found (token => startIndex)
            $ordered = [];
            foreach ($tokens as $t) {
                if ($starts[$t] !== null) {
                    $ordered[$t] = $starts[$t];
                }
            }

            // If we have less than, say, 10 columns found, skip (parsing unreliable)
            if (count($ordered) < 10) {
                Log::warning("Insufficient header tokens found, skipping block", ['jo_no' => $joNo, 'found' => count($ordered)]);
                continue;
            }

            // Sort by start position (should already be in order but ensure it)
            asort($ordered);
            $fieldNames = array_keys($ordered);
            $fieldStarts = array_values($ordered);

            // iterate lines after headerIndex+ (the header row and the underline row)
            $firstDataLineIndex = $headerIndex + 2; // usually header then units then separator
            $parsedCount = 0;
            for ($li = $firstDataLineIndex; $li < count($blockLines); $li++) {
                $line = rtrim($blockLines[$li]);
                if ($line === '' || str_starts_with(trim($line), '---') || str_contains($line, 'Total:')) {
                    continue;
                }

                // quick check: does line start with numeric SLNo?
                $possibleSl = trim(substr($line, 0, 8));
                if ($possibleSl === '' || !is_numeric(trim(explode(' ', $possibleSl)[0] ?? ''))) {
                    // not a data line — skip
                    continue;
                }

                // extract fields by header starts
                $extracted = [];
                for ($k = 0; $k < count($fieldNames); $k++) {
                    $fname = $fieldNames[$k];
                    $startPos = $fieldStarts[$k];
                    $endPos = ($k < count($fieldNames) - 1) ? $fieldStarts[$k + 1] : null;
                    if ($endPos !== null) {
                        $len = $endPos - $startPos;
                        $part = $len > 0 ? substr($line, $startPos, $len) : '';
                    } else {
                        $part = substr($line, $startPos);
                    }
                    $extracted[$fname] = trim($part);
                }

                // Map extracted fields to variables (safely)
                $slNo     = $extracted['SLNo'] ?? null;
                $itemCode = $extracted['Item Code'] ?? null;
                $desc     = $extracted['Descripton'] ?? null;
                $size     = $extracted['Size'] ?? null;
                $finding  = $extracted['Finding'] ?? null;
                $uom      = $extracted['UOM'] ?? null;
                $kt       = $extracted['Kt'] ?? null;
                $stdWt    = $extracted['StdWT'] ?? null;
                $convWtRaw = $extracted['ConvWT'] ?? null;
                $ordQty   = $extracted['Ord.Qty'] ?? null;
                $totalWt  = $extracted['Total.Wt'] ?? null;
                $labChg   = $extracted['Lab.Chg'] ?? null;
                $stoneChg = $extracted['StoneChg'] ?? null;
                $addLChg  = $extracted['Add.L.Chg'] ?? null;
                $totalVal = $extracted['TotalValue'] ?? null;
                $lossPct  = $extracted['Loss%'] ?? null;
                $minWt    = $extracted['MinWt'] ?? null;
                $maxWt    = $extracted['MaxWt'] ?? null;
                $ord      = $extracted['Ord'] ?? null;

                if (!is_numeric(trim($slNo ?? ''))) {
                    // not a valid data row
                    continue;
                }

                // Handle empty text values -> 'N/A'
                $finding = trim($finding) !== '' ? $finding : 'N/A';
                $size    = trim($size) !== '' ? $size : 'N/A';
                $uom     = trim($uom) !== '' ? $uom : 'N/A';
                $kt      = trim($kt) !== '' ? $kt : 'N/A';
                $ord     = trim($ord) !== '' ? $ord : 'N/A';
                $desc    = trim($desc) !== '' ? $desc : 'N/A';
                $itemCode = trim($itemCode) ?: null;

                // Numeric cleaning
                $stdWtVal   = $numericOrZero($stdWt);
                $ordQtyVal  = $numericOrZero($ordQty);
                $totalWtVal = $numericOrZero($totalWt);
                $labChgVal  = $numericOrZero($labChg);
                $stoneChgVal = $numericOrZero($stoneChg);
                $addLChgVal = $numericOrZero($addLChg);
                $totalValNum = $numericOrZero($totalVal);
                $lossPctVal = $numericOrZero($lossPct);
                $minWtVal   = $numericOrZero($minWt);
                $maxWtVal   = $numericOrZero($maxWt);

                // ConvWT special: if empty => NULL, else numeric
                $convWtVal = $numericOrNull($convWtRaw);

                // build item data (note: conv_wt may be NULL to match decimal column)
                $itemData = [
                    'order_id'      => $customerorder->id,
                    'sl_no'         => trim($slNo),
                    'item_code'     => $itemCode,
                    'design'        => $itemCode ? substr($itemCode, 2, 9) : null,
                    'description'   => $desc,
                    'size'          => $size,
                    'finding'       => $finding,
                    'uom'           => $uom,
                    'kt'            => $kt,
                    'std_wt'        => $stdWtVal,
                    'conv_wt'       => $convWtVal,    // NULL if missing
                    'ord_qty'       => $ordQtyVal,
                    'total_wt'      => $totalWtVal,
                    'lab_chg'       => $labChgVal,
                    'stone_chg'     => $stoneChgVal,
                    'add_l_chg'     => $addLChgVal,
                    'total_value'   => $totalValNum,
                    'loss_percent'  => $lossPctVal,
                    'min_wt'        => $minWtVal,
                    'max_wt'        => $maxWtVal,
                    'ord'           => $ord,
                    'delivery_date' => $deliveryDate,
                ];

                // Karigar lookup (unchanged)
                // $getkid = Product::with('karigar')->where('item_code', $itemData['item_code'])->first();
                // $itemData['kid'] = $getkid && $getkid->karigar ? optional($getkid->karigar->first())->kid : null;

                $getkid = Product::with('karigar')
                    ->where('item_code', $itemData['item_code'])
                    ->first();

                // $itemData['kid'] = $getkid && $getkid->karigar
                //     ? $getkid->karigar->kid
                //     : 'XX';
                $itemData['kid'] = $getkid && $getkid->karigar
                    ? $getkid->karigar->kid
                    : '';


                // Insert
                Customerordertempitem::create($itemData);

                $parsedCount++;
                // optional: only log the first few parsed rows for debugging
                if ($parsedCount < 4) {
                    Log::debug("Parsed JO item", ['jo_no' => $joNo, 'item' => $itemData]);
                }
            } // end lines loop
        } // end blocks loop

        return !empty($insertedIds)
            ? redirect()->route('customerordertemps.show', end($insertedIds))->withSuccess('Customer orders uploaded successfully.')
            : back()->with('error', 'No valid JO orders were processed.');
    }




    /* public function savetempproducts(Request $request)
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
                    'uom_id'         => 16, // TODO: replace with config/constant
                    'standard_wt'    => $item->std_wt ?? 0,
                    'kt'             => $item->kt . 'KT',
                    'kid'            => 23, // TODO: replace with config/constant
                    'purity'         => $purity,
                    'remarks'        => $item->remarks,
                    'customer_order' => 'Yes',
                    'stone_charge'   => $item->stone_chg,
                    'lab_charge'     => $item->lab_chg,
                    'additional_lab_charges'     => $item->add_l_chg,
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
    }*/

    /*
    public function savetempproducts(Request $request)
    {
        DB::beginTransaction();

        try {
            $items = Customerordertempitem::where(function ($q) {
                $q->whereNull('kid')
                    ->orWhere('kid', '');
            })->get();

            //$items = Customerordertempitem::where('kid', 'XX')->get();

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

                // 🚨 Duplicate check
                $existingProduct = Product::where('item_code', $item->item_code)
                    ->where('company_id', $company_id)
                    ->first();

                if ($existingProduct) {
                    // Update existing product's customer_order to Yes
                    if ($existingProduct->customer_order !== 'Yes') {
                        $existingProduct->update([
                            'customer_order' => 'Yes',
                            'updated_by'     => Auth::user()->name,
                        ]);
                    }
                    continue;
                }

                // Extract codes
                $pCode = substr($item->item_code, 6, 1); // 7th char
                $size  = substr($item->item_code, 9, 1); // 10th char

                $getpcodeid = Pcode::where('code', $pCode)->first();
                $getsizeid  = Size::where('schar', $size)
                    ->where('pcode_id', $getpcodeid?->id)
                    ->first();

                // Debug log for missing master data
                if (!$getpcodeid || !$getsizeid) {
                    \Log::warning("Missing master data for item_code={$item->item_code}, pCode={$pCode}, size={$size}");
                }

                $firstTwoDigits = Str::substr($item->item_code, 0, 2); // "51"

                // Purity mapping
                $purities = [
                    22 => 91.6,
                    18 => 75,
                    14 => 58.3,
                    9  => 37.5,
                    24 => 99.9,
                ];
                $purity = $purities[$item->kt] ?? 0.00;

                // Create product
                $product = Product::create([
                    'company_id'     => $company_id,
                    'vendorsite'     => $vendorsite->vendor_site,
                    'item_code'      => $item->item_code,
                    'design_num'     => $item->design,
                    'description'    => $item->description,
                    'pcode_id'       => $getpcodeid?->id,
                    'size_id'        => $getsizeid?->id,
                    'uom_id'         => 12, // TODO: config/constant
                    'standard_wt'    => $item->std_wt ?? 0,
                    'kt'             => $item->kt . 'KT',
                    'kid'            => 23, // TODO: config/constant
                    'purity'         => $purity,
                    'remarks'        => $item->remarks,
                    'customer_order' => 'Yes',
                    'stone_charge'   => $item->stone_chg,
                    'lab_charge'     => $item->lab_chg,
                    'additional_lab_charges' => $item->add_l_chg,
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
    */

    public function savetempproducts(Request $request)
    {
        DB::beginTransaction();

        try {
            $items = Customerordertempitem::where(function ($q) {
                $q->whereNull('kid')
                    ->orWhere('kid', '');
            })->get();

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

                // 🚨 Duplicate check
                $existingProduct = Product::where('item_code', $item->item_code)
                    ->where('company_id', $company_id)
                    ->first();

                if ($existingProduct) {
                    // Update existing product's customer_order to Yes
                    if ($existingProduct->customer_order !== 'Yes') {
                        $existingProduct->update([
                            'customer_order' => 'Yes',
                            'updated_by'     => Auth::user()->name,
                        ]);
                    }
                    continue;
                }

                // Extract codes
                $pCode = substr($item->item_code, 6, 1); // 7th char
                $size  = substr($item->item_code, 9, 1); // 10th char

                $getpcodeid = Pcode::where('code', $pCode)->first();
                $getsizeid  = Size::where('schar', $size)
                    ->where('pcode_id', $getpcodeid?->id)
                    ->first();

                // Debug log for missing master data
                if (!$getpcodeid || !$getsizeid) {
                    \Log::warning("Missing master data for item_code={$item->item_code}, pCode={$pCode}, size={$size}");
                }

                $firstTwoDigits = Str::substr($item->item_code, 0, 2); // "51"

                // Purity mapping
                $purities = [
                    22 => 91.6,
                    18 => 75,
                    14 => 58.3,
                    9  => 37.5,
                    24 => 99.9,
                ];
                $purity = $purities[$item->kt] ?? 0.00;

                // ✅ Find UOM from master (fallback to 12 if not found)
                $uomRecord = Uom::where('uomid', $item->uom)->first();
                $uom_id = $uomRecord ? $uomRecord->id : 12;

                if (!$uomRecord) {
                    \Log::warning("Missing UOM for item_code={$item->item_code}, given_uom={$item->uom}. Defaulted to 12.");
                }

                // Create product
                $product = Product::create([
                    'company_id'     => $company_id,
                    'vendorsite'     => $vendorsite->vendor_site,
                    'item_code'      => $item->item_code,
                    'design_num'     => $item->design,
                    'description'    => $item->description,
                    'pcode_id'       => $getpcodeid?->id,
                    'size_id'        => $getsizeid?->id,
                    'uom_id'         => $uom_id, // ✅ dynamic check
                    'standard_wt'    => $item->std_wt ?? 0,
                    'kt'             => $item->kt . 'KT',
                    'kid'            => 44, // TODO: config/constant
                    'purity'         => $purity,
                    'remarks'        => $item->remarks,
                    'customer_order' => 'Yes',
                    'stone_charge'   => $item->stone_chg,
                    'lab_charge'     => $item->lab_chg,
                    'additional_lab_charges' => $item->add_l_chg,
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
