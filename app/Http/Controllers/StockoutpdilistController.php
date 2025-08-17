<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Karigar;
use App\Models\Stockoutpdilist;

use Illuminate\Http\Request;
use App\Models\Finishedproductpdi;
use App\Models\Stockoutpdilistitem;
use Illuminate\Support\Facades\Auth;

class StockoutpdilistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $stockoutpdilists = Stockoutpdilist::with('customers')
            ->when($search, function ($query, $search) {
                return $query->where('dc_ref_no', 'like', "%{$search}%");
            })
            ->paginate(100); // Optional: paginate results
        return view('stockoutpdilists.list', compact('stockoutpdilists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::whereIn('id', ['1', '2', '3', '4'])->get();
        $lastVoucher = Stockoutpdilist::orderBy('id', 'desc')->first();
        if ($lastVoucher) {
            $newNumber = $lastVoucher->id + 1;
            $newVoucherNo = $newNumber . '/' . date('y') . '-' . date('y', strtotime('+1 year'));
        } else {
            $newVoucherNo = '1/' . date('y') . '-' . date('y', strtotime('+1 year'));
        }

        $totalQty  = Finishedproductpdi::where('delivered_stock_out', 'No')->sum('qty');
        $totalStonechg  = Finishedproductpdi::where('delivered_stock_out', 'No')->sum('stone_chg');
        $totalNetwt  = Finishedproductpdi::where('delivered_stock_out', 'No')->sum('net_wt');
        $totalRate  = Finishedproductpdi::where('delivered_stock_out', 'No')->sum('rate');
        $totalAlab  = Finishedproductpdi::where('delivered_stock_out', 'No')->sum('a_lab');

        return view('stockoutpdilists.add', compact('customers', 'newVoucherNo', 'totalQty', 'totalStonechg', 'totalNetwt', 'totalRate', 'totalAlab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'customer_id'                => 'required',
                'customer_address'           => 'required'
            ],
            [
                'customer_id.required' => 'Selection of Customer is Required', // custom message
                'customer_address.required' => 'Customer address is Required', // custom message
            ]
        );

        $stockoutpdilist = Stockoutpdilist::create([
            'customer_id'          => strip_tags($request->customer_id),
            'customer_address'     => strip_tags($request->customer_address),
            'dc_ref_no'            => strip_tags($request->dc_ref_no),
            'dc_date'              => strip_tags($request->dc_date),
            'stock_out'            => 'Yes',
            'created_by'           => Auth::user()->name
        ]);
        $lastInsertedId = $stockoutpdilist->id;
        $finishedproductpdis = Finishedproductpdi::where('delivered_stock_out', 'No')->get();
        foreach ($finishedproductpdis as $finishedproductpdi) {
            Stockoutpdilistitem::create([
                'stockoutpdilist_id'      => $lastInsertedId,
                'finishedproductpdis_id'  => $finishedproductpdi->id,
                'purity'                  => (float) (@$finishedproductpdi->purity ?? 0),
                'qty'                     => (int) (@$finishedproductpdi->qty ?? 0),
                'net_weight'              => (float) (@$finishedproductpdi->net_wt ?? 0),
                'stone_chg'               => (float) (@$finishedproductpdi->stone_chg ?? 0),
                'lab_chg'                 => (float) (@$finishedproductpdi->rate ?? 0),
                'add_lab_chg'             => (float) (@$finishedproductpdi->a_lab ?? 0),
                'amount'                  => (@$finishedproductpdi->stone_chg + @$finishedproductpdi->a_lab + (@$finishedproductpdi->rate * @$finishedproductpdi->qty)),
            ]);

            Finishedproductpdi::where('id', $finishedproductpdi->id)
                ->update([
                    'delivered_stock_out' => 'Yes'
                ]);
        }

        return redirect()->route('stockoutpdilists.index')->withSuccess('Stock Out PDI List record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stockoutpdilists = Stockoutpdilist::with('customers')->where('id', $id)->first();
        $stockoutpdilistitems = Stockoutpdilistitem::with('finishedproductpdis')->where('stockoutpdilist_id', $id)->get();
        return view('stockoutpdilists.view', compact('stockoutpdilists', 'stockoutpdilistitems'));
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
}
