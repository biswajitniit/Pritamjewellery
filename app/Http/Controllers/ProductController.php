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
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $customerOrder = $request->query('customer_order');

        $products = Product::with(['customer', 'pcode', 'size', 'uom', 'karigar'])
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
        $pcodes   = Pcode::where('is_active', 'Yes')->orderBy('description')->get();
        $sizes    = Size::where('is_active', 'Yes')->orderBy('ssize')->get();
        $uoms     = Uom::where('is_active', 'Yes')->orderBy('uomid')->get();
        $stones   = Stone::where('is_active', 'Yes')->orderBy('additional_charge_id')->get();
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kname')->get();
        $patterns = Pattern::where('is_active', 'Yes')->orderBy('pat_desc')->get();
        //$customers = Customer::where('is_active', 'Yes')->orderBy('cid')->get();
        // 👇 Include is_validation from the customers table
        $customers = Customer::where('is_active', 'Yes')
            ->select('id', 'cust_name', 'cid', 'is_validation')
            ->orderBy('cid')
            ->get();

        return view('products.add', compact('pcodes', 'sizes', 'uoms', 'stones', 'karigars', 'patterns', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate(
    //         [
    //             'company_id'               => 'required',
    //             'vendorsite'               => 'required',
    //             'item_code'                => 'required|min:14',
    //             'design_num'               => 'required',
    //             'description'              => 'required',
    //             'pattern'                  => 'required',
    //             'size'                     => 'required',
    //             'uom'                      => 'required',
    //             'kid'                      => 'required',
    //             'item_pic'                 => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //             'kt'                       => 'required',
    //             'lead_time_karigar'        => 'required',
    //             'product_lead_time'        => 'required',

    //         ],
    //         [
    //             //'company_id.required' => 'Company is Required', // custom message
    //             'item_code.required' => 'Itemcode is Required', // custom message
    //             'item_code.min' => 'Itemcode must be at least 14 characters',
    //             'design_num.required' => 'Design Name is Required', // custom message
    //             'description.required' => 'Description is Required', // custom message
    //             'item_pic.required' => 'Item picture is Required', // custom message
    //             'size.required'    => 'Size is Required', // custom message
    //             'uom.required' => 'UOM is Required', // custom message
    //             'kid.required' => 'KID Code is Required', // custom message
    //             'pattern.required' => 'Pattern Code is Required', // custom message
    //             'kt.required'     => 'KT is Required', // custom message
    //             'lead_time_karigar.required'     => 'Lead Time (Karigar) is Required', // custom message
    //             'kt.required'     => 'Product Lead Time is Required', // custom message
    //         ]
    //     );

    //     //Digital Signature Upload
    //     $item_pic_imageName = time() . '.' . $request->item_pic->extension();
    //     $request->item_pic->storeAs('Product', $item_pic_imageName, 'public');

    //     if (strlen($request->item_code) == 14) {
    //         $company_id = 1; // TCL KOL
    //     } elseif (strlen($request->item_code) == 15) {
    //         $company_id = 6; // NOVJL
    //     } else {
    //         return redirect()->back()->withErrors(['item_code' => 'Itemcode must be exactly 14 or 15 characters long.']);
    //     }

    //     $product = Product::create([
    //         'company_id'             => $company_id,
    //         'item_code'              => strip_tags($request->item_code),
    //         'design_num'             => strip_tags($request->design_num),
    //         'description'            => strip_tags($request->description),
    //         'pattern'                => strip_tags($request->pattern),
    //         'size'                   => strip_tags($request->size),
    //         'uom'                    => strip_tags($request->uom),
    //         'standard_wt'            => strip_tags($request->standard_wt),
    //         'kid'                    => strip_tags($request->kid),
    //         'lead_time_karigar'      => strip_tags($request->lead_time_karigar),
    //         'product_lead_time'      => strip_tags($request->product_lead_time),
    //         'stone_charge'           => strip_tags($request->stone_charge),
    //         'lab_charge'             => strip_tags($request->lab_charge),
    //         'loss'                   => strip_tags($request->loss),
    //         'purity'                 => strip_tags($request->purity),
    //         'item_pic'               => $item_pic_imageName,
    //         'kt'                     => strip_tags($request->kt),
    //         'pcodechar'              => strlen($request->item_code),
    //         'remarks'                => strip_tags($request->remarks),
    //         'created_by'             => Auth::user()->name
    //     ]);
    //     $lastInsertedId = $product->id;
    //     foreach ($request->stone_type as $key => $val) {
    //         Productstonedetails::create([
    //             'product_id'           => $lastInsertedId,
    //             'stone_id'             => strip_tags($request->stone_type[$key]),
    //             'category'             => strip_tags($request->category[$key]),
    //             'pcs'                  => strip_tags($request->pcs[$key]),
    //             'weight'               => strip_tags($request->weight[$key]),
    //             'rate'                 => strip_tags($request->rate[$key]),
    //             'amount'               => strip_tags($request->amount[$key]),
    //             'created_by'           => Auth::user()->name
    //         ]);
    //     }

    //     return redirect()->route('products.index')->withSuccess('Products record created successfully.');
    // }

    /*
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Find customer
            $customer = Customer::find($request->company_id);

            // Base validation (always required)
            $rules = [
                'company_id'  => 'required|exists:customers,id',
                'vendorsite'  => 'required|string|max:255',
                'item_code'   => 'required|string|min:14|max:15',
                'design_num'  => 'required|string|max:255',
                'description' => 'required|string|max:1000',
            ];

            // Apply strict rules only if customer requires validation
            if ($customer && $customer->is_validation == 1) {
                $rules = array_merge($rules, [
                    'pcode_id'               => 'required|exists:pcodes,id',
                    'size_id'                => 'required|exists:sizes,id',
                    'uom_id'                 => 'required|exists:uoms,id',
                    'kid'                    => 'required|exists:karigars,id',
                    'item_pic'               => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'kt'                     => 'required|string|max:10',
                    'lead_time_karigar'      => 'required|string|max:255',
                    'product_lead_time'      => 'required|string|max:255',
                    'stone_charge'           => 'nullable|string|max:255',
                    'lab_charge'             => 'nullable|string|max:255',
                    'additional_lab_charges' => 'nullable|string|max:255',
                    'loss'                   => 'nullable|string|max:255',
                    'purity'                 => 'nullable|numeric',
                    'remarks'                => 'nullable|string|max:500',
                ]);
            }

            // Validate
            $validatedData = $request->validate($rules);

            // Save image only if provided
            $item_pic_imageName = null;
            if ($request->hasFile('item_pic')) {
                $item_pic_imageName = time() . '.' . $request->item_pic->extension();
                $request->item_pic->storeAs('Product', $item_pic_imageName, 'public');
            }

            // Create product
            $product = Product::create([
                'company_id'        => $request->company_id,
                'vendorsite'        => $request->vendorsite,
                'item_code'         => $request->item_code,
                'design_num'        => $request->design_num,
                'description'       => $request->description,
                'pcode_id'          => $request->pcode_id ?? null,
                'size_id'           => $request->size_id ?? null,
                'uom_id'            => $request->uom_id ?? null,
                'standard_wt'       => $request->standard_wt,
                'kid'               => $request->kid ?? null,
                'lead_time_karigar' => $request->lead_time_karigar ?? null,
                'product_lead_time' => $request->product_lead_time ?? null,
                'stone_charge'      => $request->stone_charge ?? null,
                'lab_charge'        => $request->lab_charge ?? null,
                'additional_lab_charges' => $request->additional_lab_charges ?? null,
                'loss'              => $request->loss ?? null,
                'purity'            => $request->purity ?? null,
                'item_pic'          => $item_pic_imageName,
                'kt'                => $request->kt ?? null,
                'pcodechar'         => strlen($request->item_code),
                'remarks'           => $request->remarks ?? null,
                'created_by'        => Auth::user()->name
            ]);

            // Save additional stone details if provided
            if ($request->stone_type) {
                foreach ($request->stone_type as $key => $val) {
                    if (!empty($val)) {
                        Productstonedetails::create([
                            'product_id' => $product->id,
                            'stone_id'   => $val,
                            'category'   => $request->category[$key] ?? null,
                            'pcs'        => $request->pcs[$key] ?? null,
                            'weight'     => $request->weight[$key] ?? null,
                            'rate'       => $request->rate[$key] ?? null,
                            'amount'     => $request->amount[$key] ?? null,
                            'created_by' => Auth::user()->name
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->withSuccess('Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error for debugging
            Log::error('Product Store Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }*/


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Find customer
            $customer = Customer::find($request->company_id);

            // --- Base validation (always required) ---
            $rules = [
                'company_id'  => 'required|exists:customers,id',
                'vendorsite'  => 'required|string|max:255',
                'item_code'   => 'required|string|min:14|max:15',
                'design_num'  => 'required|string|max:255',
                'description' => 'required|string|max:1000',
            ];

            // --- Apply strict rules only if customer requires validation ---
            if ($customer && $customer->is_validation === 'Yes') {
                $rules = array_merge($rules, [
                    'pcode_id'               => 'required|exists:pcodes,id',
                    'size_id'                => 'required|exists:sizes,id',
                    'uom_id'                 => 'required|exists:uoms,id',
                    'kid'                    => 'required|exists:karigars,id',
                    'item_pic'               => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'kt'                     => 'required|string|max:10',
                    'lead_time_karigar'      => 'required|string|max:255',
                    'product_lead_time'      => 'required|string|max:255',
                    'stone_charge'           => 'nullable|string|max:255',
                    'lab_charge'             => 'nullable|string|max:255',
                    'additional_lab_charges' => 'nullable|string|max:255',
                    'loss'                   => 'nullable|string|max:255',
                    'purity'                 => 'nullable|numeric',
                    'remarks'                => 'nullable|string|max:500',
                ]);
            }

            // --- Validate request ---
            $validatedData = $request->validate($rules);

            // --- Handle file upload ---
            $item_pic_imageName = null;
            if ($request->hasFile('item_pic')) {
                $item_pic_imageName = time() . '.' . $request->item_pic->extension();
                $request->item_pic->storeAs('Product', $item_pic_imageName, 'public');
            }

            // --- Create Product ---
            $product = Product::create([
                'company_id'        => $request->company_id,
                'vendorsite'        => $request->vendorsite,
                'item_code'         => $request->item_code,
                'design_num'        => $request->design_num,
                'description'       => $request->description,
                'pcode_id'          => $request->pcode_id ?? null,
                'size_id'           => $request->size_id ?? null,
                'uom_id'            => $request->uom_id ?? null,
                'standard_wt'       => $request->standard_wt ?? 0,
                'kid'               => $request->kid ?? null,
                'lead_time_karigar' => $request->lead_time_karigar ?? null,
                'product_lead_time' => $request->product_lead_time ?? null,
                'stone_charge'      => $request->stone_charge ?? null,
                'lab_charge'        => $request->lab_charge ?? null,
                'additional_lab_charges' => $request->additional_lab_charges ?? null,
                'loss'              => $request->loss ?? null,
                'purity'            => $request->purity ?? null,
                'item_pic'          => $item_pic_imageName,
                'kt'                => $request->kt ?? null,
                'pcodechar'         => strlen($request->item_code),
                'remarks'           => $request->remarks ?? null,
                'created_by'        => Auth::user()->name
            ]);

            // --- Save additional stone details if provided ---
            if ($request->stone_type && is_array($request->stone_type)) {
                foreach ($request->stone_type as $key => $val) {
                    if (!empty($val)) {
                        Productstonedetails::create([
                            'product_id' => $product->id,
                            'stone_id'   => $val,
                            'category'   => $request->category[$key] ?? null,
                            'pcs'        => $request->pcs[$key] ?? null,
                            'weight'     => $request->weight[$key] ?? null,
                            'rate'       => $request->rate[$key] ?? null,
                            'amount'     => $request->amount[$key] ?? null,
                            'created_by' => Auth::user()->name
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->withSuccess('Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput();
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
        $pcodes   = Pcode::where('is_active', 'Yes')->orderBy('description')->get();
        $sizes    = Size::where('is_active', 'Yes')->orderBy('ssize')->get();
        $uoms     = Uom::where('is_active', 'Yes')->orderBy('uomid')->get();
        $stones   = Stone::where('is_active', 'Yes')->orderBy('additional_charge_id')->get();
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kname')->get();
        $patterns = Pattern::where('is_active', 'Yes')->orderBy('pat_desc')->get();
        $customers = Customer::where('is_active', 'Yes')->orderBy('cid')->get();
        $products = Product::findOrFail($id);
        $productstonedetails = Productstonedetails::where('product_id', $products->id)->get();

        return view('products.edit', compact('pcodes', 'sizes', 'uoms', 'stones', 'karigars', 'patterns', 'customers', 'products', 'productstonedetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    /*public function update(Request $request, string $id)
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
    */

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'company_id'         => 'required|integer',
            'vendorsite'         => 'required|string',
            'item_code'          => 'required|string|min:14|max:15',
            'design_num'         => 'required|string',
            'description'        => 'required|string',
            'pcode_id'           => 'required|integer',
            'size_id'            => 'required|integer',
            'uom_id'             => 'required|integer',
            'kid'                => 'required|integer',
            'lead_time_karigar'  => 'required|string',
            'product_lead_time'  => 'required|string',
            'item_pic'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kt'                 => 'required|string',

            // Optional fields
            'standard_wt'        => 'nullable|numeric',
            'stone_charge'       => 'nullable|numeric',
            'lab_charge'         => 'nullable|numeric',
            'additional_lab_charges' => 'nullable|numeric',
            'loss'               => 'nullable|numeric',
            'purity'             => 'nullable|numeric',
            'remarks'            => 'nullable|string',

            // Stone details
            'stone_type'         => 'nullable|array',
            'stone_type.*'       => 'nullable|integer',
            'category.*'         => 'nullable|string',
            'pcs.*'              => 'nullable|numeric',
            'weight.*'           => 'nullable|numeric',
            'rate.*'             => 'nullable|numeric',
            'amount.*'           => 'nullable|numeric',
        ]);

        try {
            $product = Product::findOrFail($id);

            // ✅ Handle image
            $item_pic = $product->item_pic;
            if ($request->hasFile('item_pic')) {
                $item_pic = time() . '.' . $request->item_pic->extension();
                $request->item_pic->storeAs('Product', $item_pic, 'public');
            }

            // ✅ Update product
            $product->update(array_merge($validated, [
                'company_id' => $request->company_id,
                'item_pic'   => $item_pic,
                'pcodechar'  => strlen($request->item_code),
            ]));

            // ✅ Update stone details
            if ($request->filled('stone_type')) {
                Productstonedetails::where('product_id', $id)->delete();

                foreach ($request->stone_type as $i => $stone) {
                    if ($stone) {
                        Productstonedetails::create([
                            'product_id' => $id,
                            'stone_id'   => $stone,
                            'category'   => $request->category[$i] ?? null,
                            'pcs'        => $request->pcs[$i] ?? null,
                            'weight'     => $request->weight[$i] ?? null,
                            'rate'       => $request->rate[$i] ?? null,
                            'amount'     => $request->amount[$i] ?? null,
                            'created_by' => Auth::user()->name
                        ]);
                    }
                }
            }

            // ✅ Update temp items kid
            DB::table('customerordertempitems')
                ->whereNull('kid')
                ->where('item_code', $request->item_code)
                ->update(['kid' => $request->kid]);

            return redirect()->route('products.index')
                ->withSuccess('Product record updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
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
        $sizes = Size::where('pcode_id', $request->pcode_id)->where('is_active', 'Yes')->orderBy('ssize')->get();
        $html = '<select name="size_id" id="size_id" class="form-select rounded-0">';
        $html .= '<option value="">Choose...</option>';
        foreach ($sizes as $size) {
            $html .= '<option value="' . $size->id . '">' . $size->schar . ' - ' . $size->item_name . ' - ' . $size->ssize . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }
}
