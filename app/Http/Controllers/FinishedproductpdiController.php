<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finishedproductpdi;
use App\Models\Karigar;
use App\Models\Qualitycheckitem;

class FinishedproductpdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purity = $request->query('purity');
        $kid = $request->query('kid');

        $qualitycheckitems = Qualitycheckitem::with('karigar')
            ->when($purity, function ($query, $purity) {
                return $query->where('purity', $purity);
            })
            ->when($kid && $kid !== 'all', function ($query) use ($kid) {
                return $query->where('karigar_id', $kid);
            })
            ->where('pdi_list', 'No')
            ->paginate(100);

        // Active karigars with pending (pdi_list = No)
        $karigars = Karigar::where('is_active', 'Yes')
            ->whereHas('qualitycheckitems', function ($query) {
                $query->where('pdi_list', 'No');
            })
            ->orderBy('kname')
            ->get();

        // Distinct purity values where pdi_list = No
        $purities = Qualitycheckitem::where('pdi_list', 'No')
            ->select('purity')
            ->groupBy('purity')
            ->orderBy('purity')
            ->pluck('purity');

        return view('finishedproductpdis.list', compact('karigars', 'qualitycheckitems', 'purities', 'purity', 'kid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $items = $request->input('selectstockout'); // This is an array
        foreach ($items as $item) {

            list($id, $amount) = explode(',', $item);

            $qualitycheckitems = Qualitycheckitem::where('id', $id)->first();
            $karigars = Karigar::where('id', $qualitycheckitems->karigar_id)->first();

            $getRate = GetItemcodeRate($qualitycheckitems->item_code);
            $getALab = GetItemcodeAlabStoneChg($qualitycheckitems->item_code);
            if ($getALab && $getALab->category != 'Stone') {
                $getalabs = $getALab->pcs * $getALab->amount;
            } else {
                $getalabs = 0.00;
            }

            $getAStone = GetItemcodeAlabStoneChg($qualitycheckitems->item_code);
            if ($getAStone && $getAStone->category != 'Stone') {
                $getastones = $getAStone->pcs * $getAStone->amount;
            } else {
                $getastones = 0.00;
            }

            $getLoss = GetItemcodeLoss($qualitycheckitems->item_code);


            Finishedproductpdi::create([
                'job_no'            => strip_tags($qualitycheckitems->job_no),
                'item_code'            => strip_tags($qualitycheckitems->item_code),
                'qty'                  => strip_tags($qualitycheckitems->order_qty),
                'size'                 => strip_tags($qualitycheckitems->size),
                'uom'                  => strip_tags($qualitycheckitems->uom),
                'net_wt'               => $qualitycheckitems->qualitycheckitems,
                'purity'               => $qualitycheckitems->purity,
                'rate'                 => @$getRate->lab_charge,
                'a_lab'                => @$getalabs, // sum of amount product stone details
                'stone_chg'            => @$getastones,
                'loss'                 => @$getLoss->loss,
                'kid'                  => strip_tags($karigars->kid),
                'delivered_stock_out'  => 'No'
            ]);

            Qualitycheckitem::where('id', $id)
                ->update([
                    'pdi_list' => 'Yes'
                ]);
        }

        return redirect()->route('stockoutpdilists.create')->withSuccess('Finished product pdi successfully.');
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
