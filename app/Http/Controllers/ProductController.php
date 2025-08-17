<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Productstonedetails;
use App\Models\Size;
use App\Models\Uom;
use App\Models\Stone;
use App\Models\Karigar;
use App\Models\Pattern;
use App\Models\Customer;
use App\Models\Customerordertempitem;
use App\Models\Itemdescriptionheader;
use App\Models\Pcode;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $customerOrder = $request->query('customer_order');

        $products = Product::with('karigar')
            ->when($search, function ($query, $search) {
                $query->where('item_code', 'like', "%{$search}%");
            })
            ->when($customerOrder, function ($query, $customerOrder) {
                $query->where('customer_order', $customerOrder);
            })
            ->paginate(100);

        return view('products.list', compact('products', 'search', 'customerOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pcodes   = Pcode::where('is_active', 'Yes')->orderBy('code')->get();
        $sizes    = Size::where('is_active', 'Yes')->orderBy('ssize')->get();
        $uoms     = Uom::where('is_active', 'Yes')->orderBy('uomid')->get();
        $stones   = Stone::where('is_active', 'Yes')->orderBy('additional_charge_id')->get();
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kname')->get();
        $patterns = Pattern::where('is_active', 'Yes')->orderBy('pat_desc')->get();
        $customers = Customer::where('is_active', 'Yes')->orderBy('cid')->get();

        return view('products.add', compact('pcodes', 'sizes', 'uoms', 'stones', 'karigars', 'patterns', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                //'company_id'               => 'required',
                'item_code'                => 'required|min:14',
                'design_num'               => 'required',
                'description'              => 'required',
                'pattern'                  => 'required',
                'size'                     => 'required',
                'uom'                      => 'required',
                'kid'                      => 'required',
                'item_pic'                 => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'kt'                       => 'required',
                'lead_time_karigar'        => 'required',
                'product_lead_time'        => 'required',

            ],
            [
                //'company_id.required' => 'Company is Required', // custom message
                'item_code.required' => 'Itemcode is Required', // custom message
                'item_code.min' => 'Itemcode must be at least 14 characters',
                'design_num.required' => 'Design Name is Required', // custom message
                'description.required' => 'Description is Required', // custom message
                'item_pic.required' => 'Item picture is Required', // custom message
                'size.required'    => 'Size is Required', // custom message
                'uom.required' => 'UOM is Required', // custom message
                'kid.required' => 'KID Code is Required', // custom message
                'pattern.required' => 'Pattern Code is Required', // custom message
                'kt.required'     => 'KT is Required', // custom message
                'lead_time_karigar.required'     => 'Lead Time (Karigar) is Required', // custom message
                'kt.required'     => 'Product Lead Time is Required', // custom message
            ]
        );

        //Digital Signature Upload
        $item_pic_imageName = time() . '.' . $request->item_pic->extension();
        $request->item_pic->storeAs('Product', $item_pic_imageName, 'public');

        if (strlen($request->item_code) == 14) {
            $company_id = 1; // TCL KOL
        } elseif (strlen($request->item_code) == 15) {
            $company_id = 6; // NOVJL
        } else {
            return redirect()->back()->withErrors(['item_code' => 'Itemcode must be exactly 14 or 15 characters long.']);
        }

        $product = Product::create([
            'company_id'             => $company_id,
            'item_code'              => strip_tags($request->item_code),
            'design_num'             => strip_tags($request->design_num),
            'description'            => strip_tags($request->description),
            'pattern'                => strip_tags($request->pattern),
            'size'                   => strip_tags($request->size),
            'uom'                    => strip_tags($request->uom),
            'standard_wt'            => strip_tags($request->standard_wt),
            'kid'                    => strip_tags($request->kid),
            'lead_time_karigar'      => strip_tags($request->lead_time_karigar),
            'product_lead_time'      => strip_tags($request->product_lead_time),
            'stone_charge'           => strip_tags($request->stone_charge),
            'lab_charge'             => strip_tags($request->lab_charge),
            'loss'                   => strip_tags($request->loss),
            'purity'                 => strip_tags($request->purity),
            'item_pic'               => $item_pic_imageName,
            'kt'                     => strip_tags($request->kt),
            'pcodechar'              => strlen($request->item_code),
            'remarks'                => strip_tags($request->remarks),
            'created_by'             => Auth::user()->name
        ]);
        $lastInsertedId = $product->id;
        foreach ($request->stone_type as $key => $val) {
            Productstonedetails::create([
                'product_id'           => $lastInsertedId,
                'stone_id'             => strip_tags($request->stone_type[$key]),
                'category'             => strip_tags($request->category[$key]),
                'pcs'                  => strip_tags($request->pcs[$key]),
                'weight'               => strip_tags($request->weight[$key]),
                'rate'                 => strip_tags($request->rate[$key]),
                'amount'               => strip_tags($request->amount[$key]),
                'created_by'           => Auth::user()->name
            ]);
        }

        return redirect()->route('products.index')->withSuccess('Products record created successfully.');
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
        $sizes    = Size::where('is_active', 'Yes')->orderBy('ssize')->get();
        $uoms     = Uom::where('is_active', 'Yes')->orderBy('uomid')->get();
        $stones   = Stone::where('is_active', 'Yes')->orderBy('additional_charge_id')->get();
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kname')->get();
        $patterns = Pattern::where('is_active', 'Yes')->orderBy('pat_desc')->get();
        $customers = Customer::where('is_active', 'Yes')->orderBy('cid')->get();
        $products = Product::findOrFail($id);
        $productstonedetails = Productstonedetails::where('product_id', $products->id)->get();

        return view('products.edit', compact('sizes', 'uoms', 'stones', 'karigars', 'patterns', 'customers', 'products', 'productstonedetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                //'company_id'               => 'required',
                'item_code'                => 'required|min:14',
                'design_num'               => 'required',
                'description'              => 'required',
                'pattern'                  => 'required',
                'size'                     => 'required',
                'uom'                      => 'required',
                'kid'                      => 'required',
                'item_pic'                 => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'kt'                       => 'required',
                'lead_time_karigar'        => 'required',
                'product_lead_time'        => 'required',

            ],
            [
                //'company_id.required' => 'Company is Required', // custom message
                'item_code.required' => 'Itemcode is Required', // custom message
                'item_code.min' => 'Itemcode must be at least 14 characters',
                'design_num.required' => 'Design Name is Required', // custom message
                'description.required' => 'Description is Required', // custom message
                'item_pic.required' => 'Item picture is Required', // custom message
                'size.required'    => 'Size is Required', // custom message
                'uom.required' => 'UOM is Required', // custom message
                'kid.required' => 'KID Code is Required', // custom message
                'pattern.required' => 'Pattern Code is Required', // custom message
                'kt.required'     => 'KT is Required', // custom message
                'lead_time_karigar.required'     => 'Lead Time (Karigar) is Required', // custom message
                'kt.required'     => 'Product Lead Time is Required', // custom message
            ]
        );

        try {

            if ($request->file('item_pic')) {
                //Digital Signature Upload
                $item_pic_imageName = time() . '.' . $request->item_pic->extension();
                $request->item_pic->storeAs('Product', $item_pic_imageName, 'public');
            } else {
                $productimage = Product::findOrFail($id);
                $item_pic_imageName = $productimage->item_pic;
            }


            if (strlen($request->item_code) == 14) {
                $company_id = 1; // TCL KOL
            } elseif (strlen($request->item_code) == 15) {
                $company_id = 6; // NOVJL
            } else {
                return redirect()->back()->withErrors(['item_code' => 'Itemcode must be exactly 14 or 15 characters long.']);
            }


            $product                           = Product::find($id);

            $product->company_id               = $company_id;
            $product->item_code                = $request->input('item_code');
            $product->design_num               = $request->input('design_num');
            $product->description              = $request->input('description');
            $product->pattern                  = $request->input('pattern');
            $product->size                     = $request->input('size');
            $product->uom                      = $request->input('uom');
            $product->standard_wt              = $request->input('standard_wt');
            $product->kid                      = $request->input('kid');
            $product->lead_time_karigar        = $request->input('lead_time_karigar');
            $product->product_lead_time        = $request->input('product_lead_time');
            $product->stone_charge             = $request->input('stone_charge');
            $product->lab_charge               = $request->input('lab_charge');
            $product->loss                     = $request->input('loss');
            $product->purity                   = $request->input('purity');
            $product->item_pic                 = $item_pic_imageName;
            $product->kt                       = $request->input('kt');
            $product->remarks                  = $request->input('remarks');
            $product->pcodechar                = strlen($request->item_code);
            $product->update();

            // Check if 'items' is a non-empty array and contains at least one non-empty value
            if (is_array($request->stone_type) && count(array_filter($request->stone_type)) > 0) {
                // Delete Productstonedetails where stone_type is not empty
                Productstonedetails::where('product_id', $id)->delete();

                foreach ($request->stone_type as $key => $val) {
                    Productstonedetails::create([
                        'product_id'           => $id,
                        'stone_id'             => $request->stone_type[$key],
                        'category'             => $request->category[$key],
                        'pcs'                  => $request->pcs[$key],
                        'weight'               => $request->weight[$key],
                        'rate'                 => $request->rate[$key],
                        'amount'               => $request->amount[$key],
                        'created_by'           => Auth::user()->name
                    ]);
                }
            }

            // Check if customerordertempitems.kid is empty then update
            if ($request->input('kid')) {
                DB::table('customerordertempitems')
                    ->whereNull('kid')
                    ->where('item_code', $request->input('item_code'))
                    ->update([
                        'kid' => $request->input('kid')
                    ]);
            }


            return redirect()->route('products.index')->withSuccess('Product record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getitemheaderdescription(Request $request)
    {
        $itemdescriptionheader = Itemdescriptionheader::where('value', $request->headercode)->first();
        return response()->json([
            "description" => $itemdescriptionheader->description,
        ]);
    }

    public function getpcode(Request $request)
    {
        $pcode = Pcode::where('code', $request->code)->first();
        return response()->json([
            "description" => $pcode->description,
        ]);
    }

    public function getsize(Request $request)
    {
        $size = Size::where('schar', $request->schar)->first();
        return response()->json([
            "ssize" => $size->ssize,
        ]);
    }

    public function deleteproductstone(Request $request)
    {
        $item = Productstonedetails::findOrFail($request->productstoneid);
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Product stone details deleted successfully.']);
    }

    public function getsizepcodewise(Request $request)
    {
        $sizes = Size::where('pcode_id', $request->pcode_id)->where('is_active', 'Yes')->get();
        $html = '<select name="size" id="size" class="form-select rounded-0">';
        $html .= '<option value="">Choose...</option>';
        foreach ($sizes as $size) {
            $html .= '<option value="' . $size->id . '">' . $size->ssize . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }
}
