@include('include.header')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">

                        <h5 class="mb-0 text-danger">Add Customer Order Manual
                            <div class="style_back"> <a href="{{ route('customerorders.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div>
                        </h5>
                    </div>

                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if(Session::has('error'))
                        <div class="alert alert-danger">
                        {{ Session::get('error')}}
                        </div>
                    @endif


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body p-4">
                        <div class="tabcontent">
                            <form class="row g-3" action="{{ route('customerorders.store.manual') }}" method="POST" name="saveCustomerorders" enctype="multipart/form-data">
                                @csrf
                                    <div class="row m-3">
                                        {{-- <div class="col-md-3">
                                            <label class="form-label">Selection of Customer <span style="color: red">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-select rounded-0 @error('stone_chg') is-invalid @enderror" required readonly>
                                                <option value="">Customer Selection</option>
                                                @forelse($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->cust_name }}({{ $customer->cid }})</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div> --}}
                                        <div class="col-md-3">
                                            <label class="form-label">Selection of Customer <span style="color: red">*</span></label>
                                            
                                        <select name="customer_id" id="customer_id" 
                                                class="form-select rounded-0 @error('stone_chg') is-invalid @enderror" required>
                                            <option value="">Customer Selection</option>
                                            @forelse($customers as $customer)
                                                <option value="{{ $customer->id }}" data-validation="{{ $customer->is_validation }}">
                                                    {{ $customer->cust_name }}({{ $customer->cid }})
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                            @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>


                                        <div class="col-md-2">
                                            <label class="form-label">JO No <span style="color: red">*</span></label>
                                            <input type="text" name="job_no"  class="form-control rounded-0"  required/>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">JO Date <span style="color: red">*</span></label>
                                            <input type="date" name="job_date"  class="form-control rounded-0"  required/>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-2">
                                            <label class="form-label">Item Code <span style="color: red">*</span></label>
                                            <select name="item_code[]" id="item_code_1" onchange="Get_product_info(this.value,1)" class="form-select ItemcodeSelect rounded-0 @error('item_code') is-invalid @enderror" required>
                                                <option value="">Select Item Code</option>
                                                @forelse($products as $product)
                                                <option value="{{ $product->item_code }}">{{ $product->item_code }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>



                                        <div class="col-md-1">
                                            <label class="form-label">Design <span style="color: red">*</span></label>
                                            <input type="text" id="design_1" name="design[]"  class="form-control rounded-0"  required readonly/>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Description <span style="color: red">*</span></label>
                                            <input type="text" id="description_1" name="description[]" class="form-control rounded-0" readonly required/>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">Size <span style="color: red">*</span></label>
                                            <input type="text" id="size_1" name="size[]" class="form-control rounded-0 text-end" required readonly/>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">Finding </label>
                                            <input type="text" id="finding_1" name="finding[]" class="form-control rounded-0 text-end"/>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">UOM <span style="color: red">*</span></label>
                                            <input type="text" id="uom_1" name="uom[]" class="form-control rounded-0 text-end" required readonly/>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">KT <span style="color: red">*</span></label>
                                            <input type="text" id="kt_1" name="kt[]" class="form-control rounded-0 text-end" required/>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">StdWT <span style="color: red">*</span></label>
                                            <input type="text" id="std_wt_1" name="std_wt[]" class="form-control rounded-0 text-end" required readonly/>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">ConvWT </label>
                                            <input type="text" id="conv_wt_1" name="conv_wt[]" class="form-control rounded-0 text-end"/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Ord.Qty <span style="color: red">*</span></label>
                                            <input type="text" id="ord_qty_1" name="ord_qty[]" class="form-control rounded-0 text-end" required onchange="GetOrdQtyCalulationPart(1,this.value)" />
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Total.Wt<span style="color: red">*</span></label>
                                            <input type="text" id="total_wt_1" name="total_wt[]" class="form-control rounded-0 text-end" required/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Lab.Chg <span style="color: red">*</span></label>
                                            <input type="text" id="lab_chg_1" name="lab_chg[]"  class="form-control rounded-0"  required/>
                                            <input type="hidden" id="lab_chg_hdn_1" value=""/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">StoneChg <span style="color: red">*</span></label>
                                            <input type="text" id="stone_chg_1" name="stone_chg[]"  class="form-control rounded-0" required onkeyup="GetTotalValueCalulationPart(1,this.value)"/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Add.L.Chg <span style="color: red">*</span></label>
                                            <input type="text" id="add_l_chg_1" name="add_l_chg[]"  class="form-control rounded-0"  required readonly/>
                                            <input type="hidden" id="add_l_chg_hdn_1" value=""/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">TotalValue</label>
                                            <input type="text" id="total_value_1" name="total_value[]"  class="form-control rounded-0"/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Loss% <span style="color: red">*</span></label>
                                            <input type="text" id="loss_percent_1" name="loss_percent[]"  class="form-control rounded-0"  required onchange="CalculateLossPercentage(1,this.value)"/>
                                        </div>


                                        <div class="col-md-1">
                                            <label class="form-label">MinWt <span style="color: red">*</span></label>
                                            <input type="text" id="min_wt_1" name="min_wt[]"  class="form-control rounded-0"  required/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">MaxWt <span style="color: red">*</span></label>
                                            <input type="text" id="max_wt_1" name="max_wt[]"  class="form-control rounded-0"  required/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Ord </label>
                                            <input type="text" id="ord_1" name="ord[]"  class="form-control rounded-0"/>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Kid <span style="color: red">*</span></label>
                                            <input type="text" id="kid_1" name="kid[]"  class="form-control rounded-0"  required readonly/>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Delivery Date <span style="color: red">*</span></label>
                                            <input type="date" name="delivery_date[]" class="form-control rounded-0 text-end" required/>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Remarks</label>
                                            <input type="text" id="remarks_1" name="remarks[]"  class="form-control rounded-0"/>
                                        </div>

                                    </div>
                                    <!-- End-->
                                <div class="newitems"></div>
                                <div class="col-md-12">
                                    <span class="btn btn-grd-info px-4 rounded-0 pull-right add_more_order_items"><i class="fa fa-plus-circle"></i> ADD NEW ITEMS</span>
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3 mt-4">
                                        <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->
@include('include.footer')
