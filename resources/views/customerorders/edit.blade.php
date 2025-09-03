@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">

                        <h5 class="mb-0 text-danger">Edit Customer Order Manual
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
                            <form class="row g-3" action="{{ route('customerorders.update', [$customerorders->id]) }}" method="POST" name="saveCustomerorders" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                                    <div class="row m-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Selection of Customer <span style="color: red">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-select rounded-0 @error('stone_chg') is-invalid @enderror" required readonly>
                                                <option value="">Customer Selection</option>
                                                @forelse($customers as $customer)
                                                <option value="{{ $customer->id }}" @if($customer->id == $customerorders->customer_id) selected @endif>{{ $customer->cust_name }}({{ $customer->cid }})</option>
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
                                            <input type="text" name="job_no" value="{{ $customerorders->jo_no}}"  class="form-control rounded-0"  required/>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">JO Date <span style="color: red">*</span></label>
                                            <input type="date" name="job_date" value="{{ date('Y-m-d',strtotime($customerorders->jo_date)) }}" class="form-control rounded-0"  required/>
                                        </div>
                                    </div>

                                    @php
                                    $counter = 1;
                                    @endphp
                                    @foreach($customerorderitems as $key => $item)
                                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-2">
                                                <label class="form-label">Item Code <span style="color: red">*</span></label>
                                                <select name="item_code[]" id="item_code_{{$counter}}" onchange="Get_product_info(this.value,{{$counter}})" class="form-select ItemcodeSelect rounded-0 @error('item_code') is-invalid @enderror" required>
                                                    <option value="">Select Item Code</option>
                                                    @forelse($products as $product)
                                                    <option value="{{ $product->item_code }}" {{ $product->item_code == $item->item_code ? 'selected' : '' }}>{{ $product->item_code }}</option>
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
                                                <input type="text" id="design_{{$counter}}" name="design[]" value="{{ $item->design }}"  class="form-control rounded-0"  required readonly/>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Description <span style="color: red">*</span></label>
                                                <input type="text" id="description_{{$counter}}" name="description[]" value="{{ $item->description  }}"  class="form-control rounded-0" readonly required/>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Size <span style="color: red">*</span></label>
                                                <input type="text" id="size_{{$counter}}" name="size[]" value="{{ $item->size }}" class="form-control rounded-0 text-end" required readonly/>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Finding </label>
                                                <input type="text" id="finding_{{$counter}}" name="finding[]" value="{{ $item->finding }}" class="form-control rounded-0 text-end"/>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">UOM <span style="color: red">*</span></label>
                                                <input type="text" id="uom_{{$counter}}" name="uom[]" value="{{ $item->uom }}" class="form-control rounded-0 text-end" required readonly/>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">KT <span style="color: red">*</span></label>
                                                <input type="text" id="kt_{{$counter}}" name="kt[]"  value="{{ $item->kt }}" class="form-control rounded-0 text-end" required/>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">StdWT <span style="color: red">*</span></label>
                                                <input type="text" id="std_wt_{{$counter}}" name="std_wt[]" value="{{ $item->std_wt }}" class="form-control rounded-0 text-end" required readonly/>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">ConvWT </label>
                                                <input type="text" id="conv_wt_{{$counter}}" name="conv_wt[]" value="{{ $item->conv_wt }}" class="form-control rounded-0 text-end"/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Ord.Qty <span style="color: red">*</span></label>
                                                <input type="text" id="ord_qty_{{$counter}}" name="ord_qty[]" value="{{ $item->ord_qty }}" class="form-control rounded-0 text-end" required onchange="GetOrdQtyCalulationPart(1,this.value)" />
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Total.Wt<span style="color: red">*</span></label>
                                                <input type="text" id="total_wt_{{$counter}}" name="total_wt[]" value="{{ $item->total_wt }}" class="form-control rounded-0 text-end" required/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Lab.Chg <span style="color: red">*</span></label>
                                                <input type="text" id="lab_chg_{{$counter}}" name="lab_chg[]"  value="{{ $item->lab_chg }}" class="form-control rounded-0"  required/>
                                                <input type="hidden" id="lab_chg_hdn_{{$counter}}" value=""/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">StoneChg <span style="color: red">*</span></label>
                                                <input type="text" id="stone_chg_{{$counter}}" name="stone_chg[]" value="{{ $item->stone_chg }}" class="form-control rounded-0" required onkeyup="GetTotalValueCalulationPart(1,this.value)"/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Add.L.Chg <span style="color: red">*</span></label>
                                                <input type="text" id="add_l_chg_{{$counter}}" name="add_l_chg[]"   value="{{ $item->add_l_chg }}" class="form-control rounded-0"  required readonly/>
                                                <input type="hidden" id="add_l_chg_hdn_{{$counter}}" value=""/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">TotalValue</label>
                                                <input type="text" id="total_value_{{$counter}}" name="total_value[]"  value="{{ $item->total_value }}" class="form-control rounded-0"/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Loss% <span style="color: red">*</span></label>
                                                <input type="text" id="loss_percent_{{$counter}}" name="loss_percent[]"   value="{{ $item->loss_percent }}" class="form-control rounded-0"  required onchange="CalculateLossPercentage(1,this.value)"/>
                                            </div>


                                            <div class="col-md-1">
                                                <label class="form-label">MinWt <span style="color: red">*</span></label>
                                                <input type="text" id="min_wt_{{$counter}}" name="min_wt[]"  value="{{ $item->min_wt }}"  class="form-control rounded-0"  required/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">MaxWt <span style="color: red">*</span></label>
                                                <input type="text" id="max_wt_{{$counter}}" name="max_wt[]" value="{{ $item->max_wt }}" class="form-control rounded-0"  required/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Ord </label>
                                                <input type="text" id="ord_{{$counter}}" name="ord[]"  value="{{ $item->ord }}"  class="form-control rounded-0"/>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Kid <span style="color: red">*</span></label>
                                                <input type="text" id="kid_{{$counter}}" name="kid[]" value="{{ $item->kid }}" class="form-control rounded-0"  required readonly/>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Delivery Date <span style="color: red">*</span></label>
                                                <input type="date" name="delivery_date[]" value="{{ $item->delivery_date }}" class="form-control rounded-0 text-end" required/>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Remarks</label>
                                                <input type="text" id="remarks_{{$counter}}" name="remarks[]"  value="{{ $item->remarks }}" class="form-control rounded-0"/>
                                            </div>

                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm delete-item" data-id="{{ $item->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>


                                        </div>
                                    @php
                                    $counter++;
                                    @endphp
                                    @endforeach




                                    <!-- End-->
                                <div class="newitems"></div>
                                <div class="col-md-12">
                                    <input type="hidden" id="counterEdit" value="{{count($customerorderitems)}}">
                                    <span class="btn btn-grd-info px-4 rounded-0 pull-right add_more_order_items_edit"><i class="fa fa-plus-circle"></i> ADD NEW ITEMS</span>
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
