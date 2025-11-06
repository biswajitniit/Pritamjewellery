<?php

namespace App\Http\Controllers;

use App\Models\Customerorder;
use App\Models\Customerorderitem;
use App\Models\Karigar;
use Illuminate\Http\Request;
use App\Models\Qualitycheck;
use App\Models\Qualitycheckitem;
use App\Models\Issuetokarigaritem;
use App\Models\Location;
use App\Models\Product;
use App\Models\Productstonedetails;
use App\Models\Vouchertype;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QualitycheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $qualitychecks = Qualitycheck::with('karigar')
            ->when($search, function ($query, $search) {
                return $query->where('job_no', 'like', "%{$search}%");
            })
            ->paginate(100); // Optional: paginate results
        return view('qualitycheck.list', compact('qualitychecks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karigars = IssueToKarigarItem::join('karigars', 'issuetokarigaritems.kid', '=', 'karigars.kid')
            ->select(
                'issuetokarigaritems.job_no',
                'karigars.id',
                'karigars.kid',
                'karigars.kname'
            )
            ->where('quality_check', 'No')
            ->groupBy(
                'issuetokarigaritems.job_no',
                'karigars.id',
                'karigars.kid',
                'karigars.kname'
            )
            ->get();

        $locations = Location::get();

        return view('qualitycheck.add', compact('karigars', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 🔹 Lock voucher type row to prevent duplicate voucher numbers
            $voucherType = Vouchertype::where('voucher_type', 'quality_check')
                ->where('location_id', $request->location_id)
                ->lockForUpdate() // prevents race conditions
                ->first();

            if (!$voucherType) {
                return back()->withErrors(['Voucher type not found for this location.']);
            }

            // 🔹 Generate next voucher number (padded)
            $nextNo = (int) $voucherType->lastno + 1;
            $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

            // 🔹 Validation
            $validatedData = $request->validate(
                [
                    'karigar_id'        => 'required|string',
                    'karigar_name'      => 'required|string',
                    'type'              => 'required|string',
                    'qc_voucher'        => 'nullable|string',
                    'qualitycheck_date' => 'required|date',
                    'item_code'         => 'required|string',
                    'job_no'            => 'required|string',
                    'design'            => 'required|string',
                    'description'       => 'required|string',
                    'purity'            => 'required|string',
                    'size'              => 'required|string',
                    'uom'               => 'required|string',
                    'order_qty'         => 'required|numeric',
                    'receive_qty'       => 'required|numeric',
                    'bal_qty'           => 'required|numeric',

                    // Arrays
                    'gross_wt_items'      => 'required|array',
                    'gross_wt_items.*'    => 'nullable|string',
                    'design_items'      => 'required|array',
                    'design_items.*'    => 'nullable|string',
                    'solder_items'      => 'nullable|array',
                    'solder_items.*'    => 'nullable|string',
                    'polish_items'      => 'nullable|array',
                    'polish_items.*'    => 'nullable|string',
                    'finish_items'      => 'nullable|array',
                    'finish_items.*'    => 'nullable|string',
                    'mina_items'        => 'nullable|array',
                    'mina_items.*'      => 'nullable|string',
                    'other_items'       => 'nullable|array',
                    'other_items.*'     => 'nullable|string',
                    'remark_items'      => 'nullable|array',
                    'remark_items.*'    => 'nullable|string',
                ],
                [
                    'karigar_id.required'        => 'Selection of KID is Required',
                    'karigar_name.required'      => 'Karigar name is Required',
                    'type.required'              => 'Type is Required',
                    'qualitycheck_date.required' => 'Date is Required',
                    'item_code.required'         => 'Item Code is Required',
                    'job_no.required'            => 'Job Number is Required',
                    'design.required'            => 'Design No is Required',
                    'description.required'       => 'Description is Required',
                    'purity.required'            => 'Purity is Required',
                    'size.required'              => 'Size is Required',
                    'uom.required'               => 'UOM is Required',
                    'order_qty.required'         => 'Order Qty is Required',
                    'receive_qty.required'       => 'Receive Qty is Required',
                    'bal_qty.required'           => 'Bal Qty is Required',
                    'design_items.required'      => 'At least one design item is Required',
                ]
            );

            // 🔹 Create parent record
            $qualitycheck = Qualitycheck::create([
                'karigar_id'        => $validatedData['karigar_id'],
                'karigar_name'      => $validatedData['karigar_name'],
                'type'              => $validatedData['type'],
                'location_id'       => $request->location_id,
                'qc_voucher'        => $request->qc_voucher,
                'qualitycheck_date' => $validatedData['qualitycheck_date'],
                'item_code'         => $validatedData['item_code'],
                'job_no'            => $validatedData['job_no'],
                'design'            => $validatedData['design'],
                'description'       => $validatedData['description'],
                'purity'            => $validatedData['purity'],
                'size'              => $validatedData['size'],
                'uom'               => $validatedData['uom'],
                'order_qty'         => $validatedData['order_qty'],
                'receive_qty'       => $validatedData['receive_qty'],
                'bal_qty'           => $validatedData['bal_qty'],
                'created_by'        => Auth::user()->name
            ]);

            $lastInsertedId = $qualitycheck->id;

            $karigar = Karigar::where('id', $validatedData['karigar_id'])->first();
            if (!$karigar) {
                throw new \Exception("Karigar not found for ID: {$validatedData['karigar_id']}");
            }




            // 🔹 Loop through item arrays
            foreach ($validatedData['design_items'] as $key => $val) {
                $products = Product::where('item_code', $validatedData['item_code'])->first();

                if (!$products) {
                    throw new \Exception("Product with code {$validatedData['item_code']} not found.");
                }

                $productstonedetails = Productstonedetails::where('product_id', $products->id)->first();
                $totalStoneAmount = Productstonedetails::where('product_id', $products->id)->sum('amount');

                Qualitycheckitem::create([
                    'qualitychecks_id' => $lastInsertedId,
                    'karigar_id'       => $validatedData['karigar_id'],
                    'karigar_name'     => $validatedData['karigar_name'],
                    'job_no'           => $validatedData['job_no'],
                    'item_code'        => $validatedData['item_code'],
                    'design'           => $validatedData['design'],
                    'description'      => $validatedData['description'],
                    'purity'           => $validatedData['purity'],
                    'size'             => $validatedData['size'],
                    'uom'              => $validatedData['uom'],
                    'order_qty'        => $validatedData['order_qty'],
                    'receive_qty'      => $validatedData['receive_qty'],
                    'bal_qty'          => $validatedData['bal_qty'],
                    //'net_wt'           => @$products->standard_wt,
                    'net_wt'           => 0, // Changes as per discussion on date 01/11/2025 by KD
                    'rate'             => @$productstonedetails->rate,
                    'a_lab'            => $totalStoneAmount,
                    'loss'             => @$products->loss,
                    'gross_wt_items'   => $validatedData['gross_wt_items'][$key] ?? '',
                    'design_items'     => $validatedData['design_items'][$key] ?? '',
                    'solder_items'     => $validatedData['solder_items'][$key] ?? '',
                    'polish_items'     => $validatedData['polish_items'][$key] ?? '',
                    'finish_items'     => $validatedData['finish_items'][$key] ?? '',
                    'mina_items'       => $validatedData['mina_items'][$key] ?? '',
                    'other_items'      => $validatedData['other_items'][$key] ?? '',
                    'remark_items'     => $validatedData['remark_items'][$key] ?? '',
                    'pdi_list'         => 'No'
                ]);
            }

            // 🔹 Update the voucher type's lastno with padded value
            $voucherType->lastno = $voucherNo; // ✅ stores 001, 002, etc.
            $voucherType->save();

            $balQty = $validatedData['bal_qty'] ?? 0;

            $issueQuery = Issuetokarigaritem::where('job_no', $validatedData['job_no'])
                ->where('item_code', $validatedData['item_code'])
                ->where('kid', $karigar->kid);

            if ((int) $balQty === 0) {
                $issueQuery->update([
                    'quality_check' => 'Yes',
                    'bal_qty' => 0
                ]);
            } else {
                $issueQuery->update([
                    'bal_qty' => $balQty,
                    'quality_check' => 'No'
                ]);
            }

            DB::commit();

            // return redirect()->route('qualitychecks.index')
            //     ->withSuccess('Qualitychecks record created successfully.');

            return redirect()->route('qualitychecks.create')
                ->withSuccess('Qualitychecks record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error for debugging
            \Log::error('Qualitycheck Store Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
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
        $qualitychecks = Qualitycheck::findOrFail($id);
        $qualitycheckitems = Qualitycheckitem::where('qualitychecks_id', $qualitychecks->id)->get();
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kname')->get();
        return view('qualitycheck.edit', compact('qualitychecks', 'qualitycheckitems', 'karigars'));
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

    public function getissuetokarigaritemdetails(Request $request)
    {
        $karigars = Karigar::where('id', $request->karigar_id)->first();
        $issuetokarigaritems = Issuetokarigaritem::where('kid', $karigars->kid)->where('finish_product_received', 'No')->where('quality_check', 'No')->get();
        $html = '<select name="item_code" id="item_code" onchange="return GetItemCodeDeatils(this.value)" class="form-select rounded-0">';
        $html .= '<option value="">Choose...</option>';
        foreach ($issuetokarigaritems as $issuetokarigaritem) {
            $html .= '<option value="' . $issuetokarigaritem->item_code . '">' . $issuetokarigaritem->item_code . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function getissuetokarigaritemcodedetails(Request $request)
    {
        // Validate the input (optional but recommended)
        $request->validate([
            'item_code' => 'required|string'
        ]);

        // Fetch item details
        $issuetokarigaritems = Issuetokarigaritem::where('item_code', $request->item_code)->first();

        // Handle if not found
        if (!$issuetokarigaritems) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // Fetch net weight from customer order item
        $netwt = Customerorderitem::where('item_code', $request->item_code)->value('total_wt'); // <-- FIXED here

        return response()->json([
            "job_no"        => $issuetokarigaritems->job_no,
            "design"        => $issuetokarigaritems->design,
            "description"   => $issuetokarigaritems->description,
            "purity"        => 91.6, // fixed
            "size"          => $issuetokarigaritems->size,
            "uom"           => $issuetokarigaritems->uom, // <-- FIXED: size was repeated here
            "qty"           => $issuetokarigaritems->bal_qty,
            "netwt"         => $netwt
        ]);
    }

    public function  getordertype(Request $request)
    {
        $customerorders = Customerorder::where('jo_no', $request->jobNo)->first();

        return response()->json([
            "type"        => $customerorders->type,
        ]);
    }
}