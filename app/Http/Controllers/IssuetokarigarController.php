<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issuetokarigar;
use App\Models\Issuetokarigaritem;
use App\Models\Karigar;
use App\Models\Customerorder;
use App\Models\Customerorderitem;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Carbon\Carbon;

class IssuetokarigarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*$issuetokarigars = Issuetokarigar::with('customer', 'customerorder')->get();
        //dd($issuetokarigars);
        return view('issuetokarigars.list', compact('issuetokarigars'));*/


        $issuetokarigars = DB::table('customerorders as co')
            ->join('issuetokarigaritems as ik', 'co.jo_no', '=', 'ik.job_no')
            ->join('karigars', 'ik.kid', '=', 'karigars.kid')
            ->select(
                'ik.kid',
                'karigars.kname',
                DB::raw('MIN(ik.issue_to_karigar_id) as issue_to_karigar_id'),
                DB::raw('MIN(co.jo_no) as jo_no'),
                DB::raw('MIN(co.jo_date) as jo_date')
            )
            ->where('co.jo_date', '<=', Carbon::now())
            ->groupBy('ik.kid', 'karigars.kname')
            ->get();


        return view('issuetokarigars.list', compact('issuetokarigars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $customerorders = Customerorder::orderBy('jo_no')->get();
        return view('issuetokarigars.add', compact('customerorders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /*public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'customer_id'                => 'required',
                'order_id'                   => 'required'
            ],
            [
                'customer_id.required' => 'Selection of Customer is Required', // custom message
                'order_id.required' => 'Order No Selection is Required', // custom message
            ]
        );

        $issuetokarigar = Issuetokarigar::create([
            'order_id'             => strip_tags($request->order_id),
            'customer_id'          => strip_tags($request->customer_id),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);
        $lastInsertedId = $issuetokarigar->id;
        $customerorder = Customerorder::where('id', $request->order_id)->first();
        foreach ($request->item_code as $key => $val) {
            //DB::enableQueryLog();
            Issuetokarigaritem::create([
                'job_no'               => $customerorder->jo_no,
                'issue_to_karigar_id'  => $lastInsertedId,
                'item_code'            => strip_tags(@$request->item_code[$key]),
                'design'               => strip_tags(@$request->design[$key]),
                'description'          => strip_tags(@$request->description[$key]),
                'size'                 => strip_tags(@$request->size[$key]),
                'uom'                  => strip_tags(@$request->uom[$key]),
                'st_weight'            => strip_tags(@$request->st_weight[$key]),
                'min_weight'           => strip_tags(@$request->min_weight[$key]),
                'max_weight'           => strip_tags(@$request->max_weight[$key]),
                'qty'                  => strip_tags(@$request->qty[$key]),
                'kid'                  => @$request->kid[$key],
                'delivery_date'        => @$request->delivery_date[$key],
                'finish_product_received' => 'No'
            ]);
            //dd(DB::getQueryLog());
        }
        return redirect()->route('issuetokarigars.index')->withSuccess('Issue to Karigar record created successfully.');
    }*/

    /*
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'order_id'    => 'required',
        ], [
            'customer_id.required' => 'Selection of Customer is Required',
            'order_id.required'    => 'Order No Selection is Required',
        ]);

        DB::beginTransaction();
        try {
            // Create Issue to Karigar entry
            $issuetokarigar = Issuetokarigar::create([
                'order_id'    => strip_tags($request->order_id),
                'customer_id' => strip_tags($request->customer_id),
                'is_active'   => strip_tags($request->is_active),
                'created_by'  => Auth::user()->name,
            ]);

            $lastInsertedId = $issuetokarigar->id;
            $customerorder  = Customerorder::findOrFail($request->order_id);

            foreach ($request->item_code as $key => $itemCode) {

                $financialYearId = getFinancialYearIdByDate($request->delivery_date[$key]);

                if (! $financialYearId) {
                    throw new \Exception('No financial year found for the given date: ' . $request->delivery_date[$key]);
                }

                $issuedQty = (int) strip_tags($request->qty[$key]);

                // Create Issued Item
                Issuetokarigaritem::create([
                    'financial_year_id'    => $financialYearId,
                    'job_no'               => $customerorder->jo_no,
                    'issue_to_karigar_id'  => $lastInsertedId,
                    'item_code'            => strip_tags($itemCode),
                    'design'               => strip_tags($request->design[$key]),
                    'description'          => strip_tags($request->description[$key]),
                    'size'                 => strip_tags($request->size[$key]),
                    'uom'                  => strip_tags($request->uom[$key]),
                    'st_weight'            => strip_tags($request->st_weight[$key]),
                    'min_weight'           => strip_tags($request->min_weight[$key]),
                    'max_weight'           => strip_tags($request->max_weight[$key]),
                    'qty'                  => $issuedQty,
                    'bal_qty'              => $issuedQty,
                    'kid'                  => $request->kid[$key],
                    'delivery_date'        => $request->delivery_date[$key],
                    'finish_product_received' => 'No',
                ]);

                // Fetch and update Customer Order Item
                $orderItem = Customerorderitem::where('order_id', $request->order_id)
                    ->where('item_code', $itemCode)
                    ->first();

                if ($orderItem) {
                    $orderItem->ord_qty = max(0, $orderItem->ord_qty - $issuedQty);

                    if ($orderItem->ord_qty <= 0) {
                        $orderItem->issue_to_karigar_status = 'Complete';
                    }

                    $orderItem->save();
                }
            }

            // Check if all items are completed
            $remaining = Customerorderitem::where('order_id', $request->order_id)
                ->where('issue_to_karigar_status', '!=', 'Complete')
                ->count();

            if ($remaining === 0) {
                $customerorder->issue_to_karigar = 'Complete';
                $customerorder->save();
            }

            DB::commit();

            return redirect()->route('issuetokarigars.index')
                ->withSuccess('Issued to Karigar successfully and quantities updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }
*/
    public function store(Request $request)
    {
        // ✅ Validate all required fields including arrays
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'order_id'    => 'required',
            'item_code'   => 'required|array|min:1',
            'item_code.*' => 'required|string',
            'design.*'    => 'nullable|string',
            'description.*' => 'nullable|string',
            'size.*'      => 'nullable|string',
            'uom.*'       => 'nullable|string',
            'st_weight.*' => 'nullable|numeric',
            'min_weight.*' => 'nullable|numeric',
            'max_weight.*' => 'nullable|numeric',
            'qty.*'       => 'required|numeric|min:1',
            'kid.*'       => 'nullable|string',
            'delivery_date.*' => 'required|date',
        ], [
            'customer_id.required' => 'Selection of Customer is Required',
            'order_id.required'    => 'Order No Selection is Required',
            'item_code.required'   => 'At least one item must be selected',
            'item_code.*.required' => 'Each item must have a valid item code',
            'qty.*.required'       => 'Quantity is required for each item',
            'delivery_date.*.required' => 'Delivery date is required for each item',
        ]);

        DB::beginTransaction();

        try {
            // ✅ Create main Issue to Karigar record
            $issuetokarigar = Issuetokarigar::create([
                'order_id'    => strip_tags($request->order_id),
                'customer_id' => strip_tags($request->customer_id),
                'is_active'   => strip_tags($request->is_active ?? 1),
                'created_by'  => Auth::user()->name,
            ]);

            $lastInsertedId = $issuetokarigar->id;
            $customerorder  = Customerorder::findOrFail($request->order_id);

            // ✅ Get the minimum count to avoid array size mismatch
            $itemCount = min(
                count($request->item_code ?? []),
                count($request->qty ?? []),
                count($request->delivery_date ?? [])
            );

            if ($itemCount === 0) {
                throw new \Exception('No valid items found to process.');
            }

            // ✅ Loop through each item and save
            for ($key = 0; $key < $itemCount; $key++) {
                // Skip if essential data is missing
                if (
                    empty($request->item_code[$key]) ||
                    empty($request->qty[$key]) ||
                    empty($request->delivery_date[$key])
                ) {
                    continue;
                }

                $financialYearId = getFinancialYearIdByDate($request->delivery_date[$key]);

                if (!$financialYearId) {
                    throw new \Exception('No financial year found for date: ' . $request->delivery_date[$key]);
                }

                $issuedQty = (int) strip_tags($request->qty[$key]);

                // Skip if quantity is zero or negative
                if ($issuedQty <= 0) {
                    continue;
                }

                // ✅ Create issued item record
                Issuetokarigaritem::create([
                    'financial_year_id'    => $financialYearId,
                    'job_no'               => $customerorder->jo_no,
                    'issue_to_karigar_id'  => $lastInsertedId,
                    'item_code'            => strip_tags($request->item_code[$key]),
                    'design'               => strip_tags($request->design[$key] ?? ''),
                    'description'          => strip_tags($request->description[$key] ?? ''),
                    'size'                 => strip_tags($request->size[$key] ?? ''),
                    'uom'                  => strip_tags($request->uom[$key] ?? ''),
                    'st_weight'            => strip_tags($request->st_weight[$key] ?? 0),
                    'min_weight'           => strip_tags($request->min_weight[$key] ?? 0),
                    'max_weight'           => strip_tags($request->max_weight[$key] ?? 0),
                    'qty'                  => $issuedQty,
                    'bal_qty'              => $issuedQty,
                    'kid'                  => strip_tags($request->kid[$key] ?? ''),
                    'delivery_date'        => $request->delivery_date[$key],
                    'finish_product_received' => 'No',
                ]);

                // ✅ Update the corresponding Customer Order Item
                $orderItem = Customerorderitem::where('order_id', $request->order_id)
                    ->where('item_code', $request->item_code[$key])
                    ->first();

                if ($orderItem) {
                    $orderItem->ord_qty = max(0, $orderItem->ord_qty - $issuedQty);

                    if ($orderItem->ord_qty <= 0) {
                        $orderItem->issue_to_karigar_status = 'Complete';
                    }

                    $orderItem->save();
                }
            }

            // ✅ Check if all order items are completed
            $remaining = Customerorderitem::where('order_id', $request->order_id)
                ->where('issue_to_karigar_status', '!=', 'Complete')
                ->count();

            if ($remaining === 0) {
                $customerorder->issue_to_karigar = 'Complete';
                $customerorder->save();
            }

            DB::commit();

            return redirect()
                ->route('issuetokarigars.index')
                ->with('success', 'Issued to Karigar successfully and quantities updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $issuetokarigars = Issuetokarigar::with('customer', 'customerorder')->where('id', $id)->first();
        $issuetokarigaritems = Issuetokarigaritem::where('issue_to_karigar_id', $id)->get();
        return view('issuetokarigars.view', compact('issuetokarigars', 'issuetokarigaritems'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function view($jo_no, $kid, $issue_to_karigar_id)
    {
        $issuetokarigars = Issuetokarigar::with('customer', 'customerorder')->where('id', $issue_to_karigar_id)->first();
        $issuetokarigaritems = Issuetokarigaritem::where('job_no', $jo_no)->where('kid', $kid)->get();
        return view('issuetokarigars.view', compact('issuetokarigars', 'issuetokarigaritems'));
    }

    /**
     * Display the specified resource.
     */
    public function print($jo_no, $kid, $issue_to_karigar_id)
    {
        // Load IssueToKarigar with related data
        $issuetokarigar = Issuetokarigar::with([
            'customer',
            'customerorder',
            'issuetokarigaritems' => function ($query) use ($jo_no, $kid) {
                $query->where('job_no', $jo_no)
                    ->where('kid', $kid)
                    ->orderBy('id', 'asc');
            }
        ])->findOrFail($issue_to_karigar_id);

        // dd($issuetokarigar);
        // Get filtered items
        $issuetokarigaritems = $issuetokarigar->issuetokarigaritems;

        return view('issuetokarigars.print', compact('issuetokarigar', 'issuetokarigaritems'));
    }


    public function getorderno(Request $request)
    {
        $customerorder = Customerorder::where('customer_id', $request->customerid)->where('issue_to_karigar', 'Processing')->orderBy('jo_no')->get();
        $html = '<select name="order_id" id="selection_order_no" onchange="Get_order_items(this.value)" class="form-select rounded-0" required>';
        $html .= '<option value="">Order No Selection</option>';
        foreach ($customerorder as $customerorder) {
            $html .= '<option value="' . $customerorder->id . '">' . $customerorder->jo_no . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    /*public function getorderitems(Request $request)
    {
        $customerorderitems = Customerorderitem::where('order_id', $request->order_id)->orderBy('item_code')->get();
        $karigars = Karigar::where('is_active', 'Yes')->get();

        $customerorder = Customerorder::where('id', $request->order_id)->first();
        $dateString = $customerorder->jo_date;


        $html = '';
        $count = 1;
        foreach ($customerorderitems as $customerorderitem) {
            $get_lead_time_karigar = Product::where('item_code', $customerorderitem->item_code)->first();
            $formattedDate = Carbon::createFromFormat('d/m/Y h:i:s A', $dateString)->addDays((int) $get_lead_time_karigar->lead_time_karigar)->format('Y-m-d');
            $dlydate =  $formattedDate;


            $karigarhtml = '<select name="kid[]" class="form-select rounded-0"><option value="">Select</option>';
            foreach ($karigars as $karigar) {
                $selected = $customerorderitem->kid == $karigar->kid ? 'selected' : '';
                $karigarhtml .= '<option value="' . $karigar->kid . '"  ' . $selected . '>' . $karigar->kid . '</option>';
            }
            $karigarhtml .= '</select>';

            $html .= '<div class="row g-3 mb-3 row_id_' . $count . '"><div class="col-md-2"><input type="text" name="item_code[]" id="item_code_' . $count . '" placeholder="Item Code" class="form-control rounded-0" readonly value="' . $customerorderitem->item_code . '"></div><div class="col-md-2"><input type="hidden" name="design[]" id="design_' . $count . '" class="form-control rounded-0" readonly value="' . substr($customerorderitem->item_code, 2, 9) . '"><input type="text" name="description[]" id="description_' . $count . '" class="form-control rounded-0" readonly value="' . $customerorderitem->description . '"></div><div class="col-md-1"><input type="text" id="size_' . $count . '" name="size[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->size . '"></div><div class="col-md-1"><input type="text" id="uom_' . $count . '" name="uom[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->uom . '"></div><div class="col-md-1"><input type="text" id="st_weight_' . $count . '" name="st_weight[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->std_wt . '"></div><div class="col-md-1"><input type="text" id="min_weight_' . $count . '" name="min_weight[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->min_wt . '"></div><div class="col-md-1"><input type="text" id="max_weight_' . $count . '" name="max_weight[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->max_wt . '"></div><div class="col-md-1"><input type= "hidden" id="qty_hidden' . $count . '" value="' . $customerorderitem->qty . '"><input type="text" id="qty_' . $count . '" name="qty[]" onblur="Clone_order_items(' . $count . ')" class="form-control rounded-0 text-end" value="' . $customerorderitem->ord_qty . '"></div><div class="col-md-1">' . $karigarhtml . '</div><div class="col-md-1"><input type="date" id="delivery_date_' . $count . '" name="delivery_date[]" value="' . $dlydate . '" class="form-control rounded-0 text-end" ></div></div>';

            $count++;
        }
        echo $html;
    }*/

    public function getorderitems(Request $request)
    {
        $customerorderitems = Customerorderitem::where('order_id', $request->order_id)
            ->where('issue_to_karigar_status', 'Processing')
            ->orderBy('item_code')
            ->get();

        $karigars = Karigar::where('is_active', 'Yes')->get();

        $customerorder = Customerorder::where('id', $request->order_id)->first();
        $dateString = $customerorder->jo_date;

        $html = '';
        $count = 1;

        foreach ($customerorderitems as $customerorderitem) {
            $get_lead_time_karigar = Product::where('item_code', $customerorderitem->item_code)->first();

            // Fix: Handle different date format and add lead time safely
            try {
                $baseDate = Carbon::createFromFormat('Y-m-d h:i:s a', strtolower($dateString));
            } catch (\Exception $e) {
                $baseDate = Carbon::parse($dateString); // Fallback if format doesn't match
            }

            $formattedDate = $baseDate->addDays((int) $get_lead_time_karigar->lead_time_karigar)->format('Y-m-d');
            $dlydate = $formattedDate;

            // Build karigar select dropdown
            $karigarhtml = '<select name="kid[]" class="form-select rounded-0"><option value="">Select</option>';
            foreach ($karigars as $karigar) {
                $selected = $customerorderitem->kid == $karigar->kid ? 'selected' : '';
                $karigarhtml .= '<option value="' . $karigar->kid . '" ' . $selected . '>' . $karigar->kid . '-(' . $karigar->kname . ')' . '</option>';
            }
            $karigarhtml .= '</select>';

            // Build item row HTML
            $html .= '<div class="row g-3 mb-3 row_id_' . $count . '">
                <div class="col-md-2">
                    <input type="text" name="item_code[]" id="item_code_' . $count . '" placeholder="Item Code" class="form-control rounded-0" readonly value="' . $customerorderitem->item_code . '">
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="design[]" id="design_' . $count . '" class="form-control rounded-0" readonly value="' . substr($customerorderitem->item_code, 2, 9) . '">
                    <input type="text" name="description[]" id="description_' . $count . '" class="form-control rounded-0" readonly value="' . $customerorderitem->description . '">
                </div>
                <div class="col-md-1">
                    <input type="text" id="size_' . $count . '" name="size[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->size . '">
                </div>
                <div class="col-md-1">
                    <input type="text" id="uom_' . $count . '" name="uom[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->uom . '">
                </div>
                <div class="col-md-1">
                    <input type="text" id="st_weight_' . $count . '" name="st_weight[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->std_wt . '">
                </div>
                <div class="col-md-1">
                    <input type="text" id="min_weight_' . $count . '" name="min_weight[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->min_wt . '">
                </div>
                <div class="col-md-1">
                    <input type="text" id="max_weight_' . $count . '" name="max_weight[]" class="form-control rounded-0 text-end" readonly value="' . $customerorderitem->max_wt . '">
                </div>
                <div class="col-md-1">
                    <input type="hidden" id="qty_hidden' . $count . '" value="' . $customerorderitem->qty . '">
                    <input type="text" id="qty_' . $count . '" name="qty[]" onblur="Clone_order_items(' . $count . ')" class="form-control rounded-0 text-end" value="' . $customerorderitem->ord_qty . '">
                </div>
                <div class="col-md-1">
                    ' . $karigarhtml . '
                </div>
                <div class="col-md-1">
                    <input type="date" id="delivery_date_' . $count . '" name="delivery_date[]" value="' . $dlydate . '" class="form-control rounded-0 text-end">
                </div>
            </div>';

            $count++;
        }

        echo $html;
    }
}
