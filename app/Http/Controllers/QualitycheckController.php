<?php

namespace App\Http\Controllers;

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

class QualitycheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $products = Product::with('karigar')->simplePaginate(25);
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
        //$karigars = Karigar::where('is_active', 'Yes')->orderBy('kname')->get();

        $karigars = IssueToKarigarItem::join('karigars', 'issuetokarigaritems.kid', '=', 'karigars.kid')
            ->select('karigars.id', 'karigars.kid', 'karigars.kname')
            ->get();

        // $lastVoucher = Qualitycheck::orderBy('id', 'desc')->first();

        // if ($lastVoucher) {
        //     $newNumber = $lastVoucher->id + 1;
        //     $newVoucherNo = $newNumber . '/' . date('y') . '-' . date('y', strtotime('+1 year'));
        // } else {
        //     $newVoucherNo = '1/' . date('y') . '-' . date('y', strtotime('+1 year'));
        // }

        $locations = Location::get();
        //$issuetokarigaritems = Issuetokarigaritem::where('finish_product_received', 'no')->get();

        return view('qualitycheck.add', compact('karigars', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /* public function store(Request $request)
    {
        // Get voucher type FIRST (before validation to auto-generate vou_no)
        $voucherType = Vouchertype::where('voucher_type', 'quality_check')
            ->where('location_id', $request->location_id)
            ->first();

        if (!$voucherType) {
            return back()->withErrors(['Voucher type not found for this location.']);
        }

        // Generate next voucher number
        $nextNo = (int)$voucherType->lastno + 1;
        $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

        // Final formatted voucher number
        $voucherNoPadded = $voucherType->prefix . '/' . $voucherNo . '/' . $voucherType->applicable_year;

        $validatedData = $request->validate(
            [
                'karigar_id'                     => 'required',
                'karigar_name'                   => 'required',
                'type'                           => 'required',
                'qc_voucher'                     => 'required',
                'qualitycheck_date'              => 'required',
                'item_code'                      => 'required',
                'job_no'                         => 'required',
                'design'                         => 'required',
                'description'                    => 'required',
                'purity'                         => 'required',
                'size'                           => 'required',
                'uom'                            => 'required',
                'order_qty'                      => 'required',
                'receive_qty'                    => 'required',
                'bal_qty'                        => 'required',

            ],
            [
                'karigar_id.required'        => 'Selection of KID is Required', // custom message
                'karigar_name.required'      => 'Karigar name is Required',
                'type.required'              => 'Type is Required',
                'qc_voucher.required'        => 'QC Voucher is Required',
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
            ]
        );

        $qualitycheck = Qualitycheck::create([
            'karigar_id'           => strip_tags($request->karigar_id),
            'karigar_name'         => strip_tags($request->karigar_name),
            'type'                 => strip_tags($request->type),
            'location_id'          => $request->location_id,
            'qc_voucher'           => $voucherNoPadded,
            'qualitycheck_date'    => $request->qualitycheck_date,
            'item_code'            => strip_tags($request->item_code),
            'job_no'               => strip_tags($request->job_no),
            'design'               => strip_tags($request->design),
            'description'          => strip_tags($request->description),
            'purity'               => strip_tags($request->purity),
            'size'                 => strip_tags($request->size),
            'uom'                  => strip_tags($request->uom),
            'order_qty'            => strip_tags($request->order_qty),
            'receive_qty'          => strip_tags($request->receive_qty),
            'bal_qty'              => strip_tags($request->bal_qty),
            'created_by'           => Auth::user()->name
        ]);
        $lastInsertedId = $qualitycheck->id;

        foreach ($request->design_items as $key => $val) {
            //DB::enableQueryLog();
            $products = Product::where('item_code', $request->item_code)->first();
            $productstonedetails = Productstonedetails::where('product_id', $products->id)->first();

            Qualitycheckitem::create([
                'qualitychecks_id'     => $lastInsertedId,
                'karigar_id'           => strip_tags(@$request->karigar_id),
                'karigar_name'         => strip_tags(@$request->$request->karigar_name),
                'job_no'               => strip_tags($request->job_no),
                'item_code'            => strip_tags($request->item_code),
                'design'               => strip_tags($request->design),
                'description'          => strip_tags($request->description),
                'purity'               => strip_tags($request->purity),
                'size'                 => strip_tags($request->size),
                'uom'                  => strip_tags($request->uom),
                'order_qty'            => strip_tags($request->order_qty),
                'receive_qty'          => strip_tags($request->receive_qty),
                'bal_qty'              => strip_tags($request->bal_qty),
                'net_wt'               => @$products->standard_wt,
                'rate'                 => @$productstonedetails->rate,
                'a_lab'                => @$productstonedetails->amount, // sum of amount product stone details
                //'stone_chg'            => @$products->standard_wt,
                'loss'                 => @$products->loss,
                'design_items'         => strip_tags(@$request->design_items[$key]),
                'solder_items'         => strip_tags(@$request->solder_items[$key]),
                'polish_items'         => strip_tags(@$request->polish_items[$key]),
                'finish_items'         => strip_tags(@$request->finish_items[$key]),
                'mina_items'           => strip_tags(@$request->mina_items[$key]),
                'other_items'          => strip_tags(@$request->other_items[$key]),
                'remark_items'         => strip_tags(@$request->remark_items[$key]),
                'pdi_list'             => 'No'
            ]);
            //dd(DB::getQueryLog());
        }

        // Update the voucher type's lastno (as INTEGER, not padded)
        $voucherType->lastno = $nextNo;
        $voucherType->save();

        return redirect()->route('qualitychecks.index')->withSuccess('Qualitychecks record created successfully.');
    }*/
    public function store(Request $request)
    {
        try {
            // Get voucher type FIRST (before validation to auto-generate vou_no)
            $voucherType = Vouchertype::where('voucher_type', 'quality_check')
                ->where('location_id', $request->location_id)
                ->first();

            if (!$voucherType) {
                return back()->withErrors(['Voucher type not found for this location.']);
            }

            // Generate next voucher number
            $nextNo = (int)$voucherType->lastno + 1;
            $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

            // Final formatted voucher number
            $voucherNoPadded = $voucherType->prefix . '/' . $voucherNo . '/' . $voucherType->applicable_year;

            // 🔹 Validation
            $validatedData = $request->validate(
                [
                    'karigar_id'        => 'required|string',
                    'karigar_name'      => 'required|string',
                    'type'              => 'required|string',
                    'qc_voucher'        => 'nullable|string', // auto-generated, so nullable
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
                'karigar_id'        => strip_tags($validatedData['karigar_id']),
                'karigar_name'      => strip_tags($validatedData['karigar_name']),
                'type'              => strip_tags($validatedData['type']),
                'location_id'       => $request->location_id,
                'qc_voucher'        => $voucherNoPadded,
                'qualitycheck_date' => $validatedData['qualitycheck_date'],
                'item_code'         => strip_tags($validatedData['item_code']),
                'job_no'            => strip_tags($validatedData['job_no']),
                'design'            => strip_tags($validatedData['design']),
                'description'       => strip_tags($validatedData['description']),
                'purity'            => strip_tags($validatedData['purity']),
                'size'              => strip_tags($validatedData['size']),
                'uom'               => strip_tags($validatedData['uom']),
                'order_qty'         => strip_tags($validatedData['order_qty']),
                'receive_qty'       => strip_tags($validatedData['receive_qty']),
                'bal_qty'           => strip_tags($validatedData['bal_qty']),
                'created_by'        => Auth::user()->name
            ]);

            $lastInsertedId = $qualitycheck->id;

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
                    'karigar_id'       => strip_tags($validatedData['karigar_id']),
                    'karigar_name'     => strip_tags($validatedData['karigar_name']),
                    'job_no'           => strip_tags($validatedData['job_no']),
                    'item_code'        => strip_tags($validatedData['item_code']),
                    'design'           => strip_tags($validatedData['design']),
                    'description'      => strip_tags($validatedData['description']),
                    'purity'           => strip_tags($validatedData['purity']),
                    'size'             => strip_tags($validatedData['size']),
                    'uom'              => strip_tags($validatedData['uom']),
                    'order_qty'        => strip_tags($validatedData['order_qty']),
                    'receive_qty'      => strip_tags($validatedData['receive_qty']),
                    'bal_qty'          => strip_tags($validatedData['bal_qty']),
                    'net_wt'           => @$products->standard_wt,
                    'rate'             => @$productstonedetails->rate,
                    'a_lab'            => $totalStoneAmount,
                    'loss'             => @$products->loss,
                    'design_items'     => strip_tags($validatedData['design_items'][$key] ?? ''),
                    'solder_items'     => strip_tags($validatedData['solder_items'][$key] ?? ''),
                    'polish_items'     => strip_tags($validatedData['polish_items'][$key] ?? ''),
                    'finish_items'     => strip_tags($validatedData['finish_items'][$key] ?? ''),
                    'mina_items'       => strip_tags($validatedData['mina_items'][$key] ?? ''),
                    'other_items'      => strip_tags($validatedData['other_items'][$key] ?? ''),
                    'remark_items'     => strip_tags($validatedData['remark_items'][$key] ?? ''),
                    'pdi_list'         => 'No'
                ]);
            }

            // 🔹 Update the voucher type's lastno
            $voucherType->lastno = $nextNo;
            $voucherType->save();

            return redirect()->route('qualitychecks.index')
                ->withSuccess('Qualitychecks record created successfully.');
        } catch (\Exception $e) {
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
        $issuetokarigaritems = Issuetokarigaritem::where('kid', $karigars->kid)->where('finish_product_received', 'No')->get();
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

        $issuetokarigaritems = Issuetokarigaritem::where('item_code', $request->item_code)->first();

        return response()->json([
            "job_no"        => $issuetokarigaritems->job_no,
            "design"        => $issuetokarigaritems->design,
            "description"   => $issuetokarigaritems->description,
            "purity"        => 91.6, //fixed
            "size"          => $issuetokarigaritems->size,
            "uom"           => $issuetokarigaritems->size,
            "qty"           => $issuetokarigaritems->qty,
        ]);
    }
}
