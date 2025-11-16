<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Customerorder;
use App\Models\Customerorderitem;
use App\Models\Customerordertemp;
use App\Models\Customerordertempitem;
use App\Models\Karigar;
use App\Models\Product;
use App\Models\Productstonedetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CustomerorderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerorders = Customerorder::with('customer')->simplePaginate(25);
        $lastTempOrder = Customerordertemp::orderBy('id', 'desc')->first();

        if ($lastTempOrder) {
            // Record exists
            $lastTempOrderId = $lastTempOrder->id;
        } else {
            // No record found
            $lastTempOrderId = 0;
        }
        return view('customerorders.list', compact('customerorders', 'lastTempOrderId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //$customers    = Customer::where('is_active', 'Yes')->orderBy('cust_name')->get();
        $customers = Customer::where('is_active', 'Yes')
            ->select('id', 'cust_name', 'cid', 'is_validation')
            ->orderBy('cust_name')
            ->get();


        $products    = Product::orderBy('item_code')->get();

        $type = $request->query('type'); // 'bulk' or 'manual'
        if ($type === 'bulk') {
            // Load bulk upload view or logic
            return view('customerorders.add-bulk', compact('customers', 'products'));
        } elseif ($type === 'manual') {
            // Load manual entry view or logic
            return view('customerorders.add-manual', compact('customers', 'products'));
        } else {
            // Handle invalid or missing type
            abort(404, 'Invalid type provided.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /*
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            // Get all temp orders
            $tempOrders = Customerordertemp::with('customerordertempitems')->where('jo_no', $request->jo_no)->get();

            foreach ($tempOrders as $tempOrder) {

                // Check if jo_no already exists
                $existingOrder = Customerorder::where('jo_no', $tempOrder->jo_no)->first();
                if ($existingOrder) {
                    DB::rollBack(); // Ensure rollback before redirect
                    return back()->with('error', 'JO No already exists. Duplicate upload is not allowed.');
                }

                // Clone to customerorders
                $newOrder = Customerorder::create([
                    'jo_no'       => $tempOrder->jo_no,
                    'customer_id' => $tempOrder->customer_id,
                    'jo_date'     => $tempOrder->jo_date,
                    'order_type'  => $tempOrder->order_type,
                    'type'        => $tempOrder->type,
                    'created_by'  => $tempOrder->created_by,
                    'updated_by'  => $tempOrder->updated_by,
                    'created_at'  => $tempOrder->created_at,
                    'updated_at'  => $tempOrder->updated_at,
                ]);

                // Clone related items
                foreach ($tempOrder->customerordertempitems as $tempItem) {

                    $getkid = Product::with('karigar')
                        ->where('item_code', $tempItem->item_code)
                        ->first();
                    $kid = $getkid?->karigar?->kid ?? '';


                    Customerorderitem::create([
                        'order_id'      => $newOrder->id,
                        'sl_no'         => $tempItem->sl_no,
                        'item_code'     => $tempItem->item_code,
                        'kid'           => $kid,
                        'design'        => $tempItem->design,
                        'description'   => $tempItem->description,
                        'size'          => $tempItem->size,
                        'finding'       => $tempItem->finding,
                        'uom'           => $tempItem->uom,
                        'kt'            => $tempItem->kt,
                        'std_wt'        => $tempItem->std_wt,
                        'conv_wt'       => $tempItem->conv_wt,
                        'ord_qty'       => $tempItem->ord_qty,
                        'ord_qty_actual'       => $tempItem->ord_qty,
                        'total_wt'      => $tempItem->total_wt,
                        'lab_chg'       => $tempItem->lab_chg,
                        'stone_chg'     => $tempItem->stone_chg,
                        'add_l_chg'     => $tempItem->add_l_chg,
                        'total_value'   => $tempItem->total_value,
                        'loss_percent'  => $tempItem->loss_percent,
                        'min_wt'        => $tempItem->min_wt,
                        'max_wt'        => $tempItem->max_wt,
                        'ord'           => $tempItem->ord,
                        'delivery_date' => $tempItem->delivery_date,
                    ]);
                }

                // Delete related temp items
                $tempOrder->customerordertempitems()->delete();

                // Delete temp order
                $tempOrder->delete();
            }

            DB::commit();

            return redirect()->route('customerorders.index')->withSuccess('Customer orders record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }*/

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            // Get all temp orders
            $tempOrders = Customerordertemp::with('customerordertempitems')->where('jo_no', $request->jo_no)->get();

            foreach ($tempOrders as $tempOrder) {

                // Check if jo_no already exists
                $existingOrder = Customerorder::where('jo_no', $tempOrder->jo_no)->first();
                if ($existingOrder) {
                    DB::rollBack(); // Ensure rollback before redirect
                    return back()->with('error', 'JO No already exists. Duplicate upload is not allowed.');
                }

                // Clone to customerorders
                $newOrder = Customerorder::create([
                    'jo_no'       => $tempOrder->jo_no,
                    'customer_id' => $tempOrder->customer_id,
                    'jo_date'     => $tempOrder->jo_date,
                    'order_type'  => $tempOrder->order_type,
                    'type'        => $tempOrder->type,
                    'created_by'  => $tempOrder->created_by,
                    'updated_by'  => $tempOrder->updated_by,
                    'created_at'  => $tempOrder->created_at,
                    'updated_at'  => $tempOrder->updated_at,
                ]);

                // Clone related items
                foreach ($tempOrder->customerordertempitems as $tempItem) {

                    $getkid = Product::with('karigar')
                        ->where('item_code', $tempItem->item_code)
                        ->first();
                    $kid = $getkid?->karigar?->kid ?? '';


                    Customerorderitem::create([
                        'order_id'      => $newOrder->id,
                        'sl_no'         => $tempItem->sl_no,
                        'item_code'     => $tempItem->item_code,
                        'kid'           => $kid,
                        'design'        => $tempItem->design,
                        'description'   => $tempItem->description,
                        'size'          => $tempItem->size,
                        'finding'       => $tempItem->finding,
                        'uom'           => $tempItem->uom,
                        'kt'            => $tempItem->kt,
                        'std_wt'        => $tempItem->std_wt,
                        'conv_wt'       => $tempItem->conv_wt,
                        'ord_qty'       => $tempItem->ord_qty,
                        'ord_qty_actual' => $tempItem->ord_qty,
                        'total_wt'      => $tempItem->total_wt,
                        'lab_chg'       => $tempItem->lab_chg,
                        'stone_chg'     => $tempItem->stone_chg,
                        'add_l_chg'     => $tempItem->add_l_chg,
                        'total_value'   => $tempItem->total_value,
                        'loss_percent'  => $tempItem->loss_percent,
                        'min_wt'        => $tempItem->min_wt,
                        'max_wt'        => $tempItem->max_wt,
                        'ord'           => $tempItem->ord,
                        'delivery_date' => $tempItem->delivery_date,
                    ]);
                }

                // Delete related temp items
                $tempOrder->customerordertempitems()->delete();

                // Delete temp order
                $tempOrder->delete();
            }

            DB::commit();

            return redirect()->route('customerorders.index')->withSuccess('Customer orders record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Store a newly Manual created resource in storage.
     */
    public function storeManual(Request $request)
    {
        // ✅ Validation for parent + child arrays
        $validatedData = $request->validate(
            [
                'customer_id'         => 'required|integer|exists:customers,id',
                'job_no'              => 'required|string|max:50',
                'job_date'            => 'required|date',

                // Validate child arrays
                'item_code.*'         => 'required|string|max:50',
                'kid.*'               => 'nullable|string|max:50',
                'design.*'            => 'nullable|string|max:255',
                'description.*'       => 'nullable|string|max:255',
                'size.*'              => 'nullable|string|max:50',
                'finding.*'           => 'nullable|string|max:50',
                'uom.*'               => 'nullable|string|max:20',
                'kt.*'                => 'nullable|string|max:20',
                'std_wt.*'            => 'nullable|numeric',
                'conv_wt.*'           => 'nullable|numeric',
                'ord_qty.*'           => 'required|numeric|min:1',
                'total_wt.*'          => 'nullable|numeric',
                'lab_chg.*'           => 'nullable|numeric',
                'stone_chg.*'         => 'nullable|numeric',
                'add_l_chg.*'         => 'nullable|numeric',
                'total_value.*'       => 'nullable|numeric',
                'loss_percent.*'      => 'nullable|numeric',
                'min_wt.*'            => 'nullable|numeric',
                'max_wt.*'            => 'nullable|numeric',
                'ord.*'               => 'nullable|string|max:50',
                'delivery_date.*'     => 'nullable|date',
            ],
            [
                'customer_id.required' => 'Selection of Customer is Required',
                'job_no.required'      => 'Job no is Required',
                'job_date.required'    => 'Job Date is Required',
                'item_code.*.required' => 'Item code is required for all rows',
                'ord_qty.*.required'   => 'Order quantity is required for all rows',
            ]
        );

        DB::transaction(function () use ($request, &$customerorder) {
            // ✅ Create parent order
            $customerorder = Customerorder::create([
                'customer_id'  => strip_tags($request->customer_id),
                'jo_no'       => strip_tags($request->job_no),
                'jo_date'     => strip_tags($request->job_date),
                'order_type'   => 'ManualUpload',
                'is_active'    => $request->has('is_active') ? strip_tags($request->is_active) : 1,
                'created_by'   => Auth::user()->name,
            ]);

            $lastInsertedId = $customerorder->id;

            // ✅ Bulk prepare items
            $items = [];
            $count = 1;
            foreach ($request->item_code as $key => $val) {
                $items[] = [
                    'order_id'      => $lastInsertedId,
                    'sl_no'         => $count++,
                    'item_code'     => strip_tags($request->item_code[$key]),
                    'kid'           => strip_tags($request->kid[$key] ?? ''),
                    'design'        => strip_tags($request->design[$key] ?? ''),
                    'description'   => strip_tags($request->description[$key] ?? ''),
                    'size'          => strip_tags($request->size[$key] ?? ''),
                    'finding'       => strip_tags($request->finding[$key] ?? ''),
                    'uom'           => strip_tags($request->uom[$key] ?? ''),
                    'kt'            => strip_tags($request->kt[$key] ?? ''),
                    'std_wt'        => $request->std_wt[$key] ?? 0,
                    'conv_wt'       => $request->conv_wt[$key] ?? 0,
                    'ord_qty'       => $request->ord_qty[$key] ?? 0,
                    'ord_qty_actual'       => $request->ord_qty[$key] ?? 0,
                    'total_wt'      => $request->total_wt[$key] ?? 0,
                    'lab_chg'       => $request->lab_chg[$key] ?? 0,
                    'stone_chg'     => $request->stone_chg[$key] ?? 0,
                    'add_l_chg'     => $request->add_l_chg[$key] ?? 0,
                    'total_value'   => $request->total_value[$key] ?? 0,
                    'loss_percent'  => $request->loss_percent[$key] ?? 0,
                    'min_wt'        => $request->min_wt[$key] ?? 0,
                    'max_wt'        => $request->max_wt[$key] ?? 0,
                    'ord'           => strip_tags($request->ord[$key] ?? ''),
                    'remarks'       => strip_tags($request->remarks[$key] ?? ''),
                    'delivery_date' => $request->delivery_date[$key] ?? null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            // ✅ Bulk insert
            Customerorderitem::insert($items);
        });

        return redirect()->route('customerorders.index')->with('success', 'Customer order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customerorders = Customerorder::with('customer')->where('id', $id)->first();
        $customerorderitems = Customerorderitem::where('order_id', $id)->get();
        return view('customerorders.view', compact('customerorders', 'customerorderitems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customers          = Customer::where('is_active', 'Yes')->orderBy('cust_name')->get();
        $products           = Product::orderBy('item_code')->get();
        $customerorders     = Customerorder::findOrFail($id);
        $customerorderitems = Customerorderitem::where('order_id', $id)->get();
        return view('customerorders.edit', compact('customers', 'products', 'customerorders', 'customerorderitems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // ✅ Update main order
            $order = Customerorder::findOrFail($id);
            $order->update([
                'jo_no'       => $request->job_no,
                'customer_id' => $request->customer_id,
                'jo_date'     => $request->job_date,
            ]);

            // ✅ Start counter for sl_no
            $count = 1;

            foreach ($request->item_code as $index => $code) {
                $itemId = $request->item_id[$index] ?? null;

                $data = [
                    'sl_no'         => $count++,
                    'item_code'     => $code,
                    'design'        => $request->design[$index] ?? null,
                    'description'   => $request->description[$index] ?? null,
                    'size'          => $request->size[$index] ?? null,
                    'finding'       => $request->finding[$index] ?? null,
                    'uom'           => $request->uom[$index] ?? null,
                    'kt'            => $request->kt[$index] ?? null,
                    'std_wt'        => $request->std_wt[$index] ?? null,
                    'conv_wt'       => $request->conv_wt[$index] ?? null,
                    'ord_qty'       => $request->ord_qty[$index] ?? null,
                    'total_wt'      => $request->total_wt[$index] ?? null,
                    'lab_chg'       => $request->lab_chg[$index] ?? null,
                    'stone_chg'     => $request->stone_chg[$index] ?? null,
                    'add_l_chg'     => $request->add_l_chg[$index] ?? null,
                    'total_value'   => $request->total_value[$index] ?? null,
                    'loss_percent'  => $request->loss_percent[$index] ?? null,
                    'min_wt'        => $request->min_wt[$index] ?? null,
                    'max_wt'        => $request->max_wt[$index] ?? null,
                    'ord'           => $request->ord[$index] ?? null,
                    'kid'           => $request->kid[$index] ?? null,
                    'delivery_date' => $request->delivery_date[$index] ?? null,
                    'remarks'       => $request->remarks[$index] ?? null,
                ];

                if ($itemId) {
                    // ✅ Update existing item
                    Customerorderitem::where('id', $itemId)->update($data);
                } else {
                    // ✅ Insert new item
                    $data['order_id'] = $order->id;
                    Customerorderitem::create($data);
                }
            }

            DB::commit();
            return redirect()->route('customerorders.index')->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage() . ' (line ' . $e->getLine() . ')');
        }
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
        // return response()->json([
        //     "design"        => $product->design_num,
        //     "description"   => $product->description,
        //     "size"          => $product->size,
        //     "uom"           => $product->uom,
        //     "kt"            => $product->kt,
        //     "std_wt"        => $product->standard_wt,
        //     "kid"           => $karigar->kid,
        //     "add_l_chg"     => $productstonedetails_add_l_chg,
        //     "stone_charge"  => $product->stone_charge,
        //     "lab_charge"    => $product->lab_charge,
        //     "loss"          => $product->loss,
        //     "company_id"    => $product->company_id,
        // ]);
        return response()->json([
            "design"        => $product->design_num,
            "description"   => $product->description,
            "size"          => $product->size->ssize,
            "uom"           => $product->uom->description,
            "kt"            => $product->kt,
            "std_wt"        => $product->standard_wt,
            "kid"           => $karigar->kid,
            "add_l_chg"     => $productstonedetails_add_l_chg,
            "stone_charge"  => $product->stone_charge,
            "lab_charge"    => $product->lab_charge,
            "loss"          => $product->loss,
            "company_id"    => $product->company_id,
        ]);
    }

    public function customerordersimporttxt(Request $request)
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
                $existingOrder = Customerorder::where('jo_no', $joNo)->first();
                if ($existingOrder) {
                    return back()->with('error', 'JO No already exists. Duplicate upload is not allowed.');
                }

                $customerorder = Customerorder::create([
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

                        Customerorderitem::create([
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

                        Customerorderitem::create([
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
                        Customerorderitem::create([
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
            return redirect()->route('customerorders.index')->withSuccess('Successfully uploaded customer order.');
        } else {
            return back()->with('error', 'Company is not found in our system!');
        }
    }
}
