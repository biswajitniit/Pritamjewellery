@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">

                        <h5 class="mb-0 text-danger">Add Customer Order
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


                    <div class="card-body p-4">

                        <div class="tab">
                            <button class="tablinks" onclick="openCustomerOrder(event, 'AutoUpload')">Customer Order Auto Upload</button>
                            <button class="tablinks" onclick="openCustomerOrder(event, 'Manual')">Customer Order Manual</button>
                        </div>

                        <div id="AutoUpload" class="tabcontent">
                            <h3>London</h3>
                            <p>London is the capital city of England.</p>
                        </div>

                        <div id="Manual" class="tabcontent">
                            <h3>Paris</h3>
                            <p>Paris is the capital of France.</p>
                        </div>

                        <form class="row g-3" action="{{ route('customerordersimporttxt') }}" method="POST" name="saveCustomerorders" enctype="multipart/form-data">
                            @csrf
                            <label>Select TXT File:</label>
                            <input type="file" name="file" required accept=".txt">

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3 mt-4">
                                    <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0">
                                </div>
                            </div>
                        </form>


                        <form class="row g-3" action="{{ route('customerorders.store') }}" method="POST" name="saveCustomerorders" enctype="multipart/form-data">
                            @csrf
                                <div class="row m-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Selection of Customer <span style="color: red">*</span></label>
                                        <select name="customer_id" class="form-select rounded-0 @error('stone_chg') is-invalid @enderror" required>
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
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Job No <span style="color: red">*</span></label>
                                        <input type="text" id="job_no_1" name="job_no[]"  class="form-control rounded-0"  required/>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Job Date <span style="color: red">*</span></label>
                                        <input type="date" id="job_date_1" name="job_date[]"  class="form-control rounded-0"  required/>
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-1">
                                        <label class="form-label">Item Code <span style="color: red">*</span></label>
                                        <select name="item_code[]" id="item_code_1" onchange="Get_product_info(this.value,1)" class="form-select rounded-0 @error('item_code') is-invalid @enderror" required>
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

                                    <div class="col-md-2">
                                        <label class="form-label">Description <span style="color: red">*</span></label>
                                        <input type="text" id="description_1" name="description[]" class="form-control rounded-0" readonly required/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Size <span style="color: red">*</span></label>
                                        <input type="text" id="size_1" name="size[]" class="form-control rounded-0 text-end" required readonly/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">UOM <span style="color: red">*</span></label>
                                        <input type="text" id="uom_1" name="uom[]" class="form-control rounded-0 text-end" required readonly/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">St Wt <span style="color: red">*</span></label>
                                        <input type="text" id="st_weight_1" name="st_weight[]" class="form-control rounded-0 text-end" required readonly/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Kid <span style="color: red">*</span></label>
                                        <input type="text" id="kid_1" name="kid[]"  class="form-control rounded-0"  required readonly/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Loss <span style="color: red">*</span></label>
                                        <input type="text" id="loss_1" name="loss[]"  class="form-control rounded-0"  required/>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Qty. <span style="color: red">*</span></label>
                                        <input type="text" id="qty_1" name="qty[]" class="form-control rounded-0 text-end" required/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Tot Wt<span style="color: red">*</span></label>
                                        <input type="text" id="total_weight_1" name="total_weight[]" class="form-control rounded-0 text-end" required/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Lb.Ch <span style="color: red">*</span></label>
                                        <input type="text" id="lb_ch_1" name="lb_ch[]"  class="form-control rounded-0"  required/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Adl.Ch <span style="color: red">*</span></label>
                                        <input type="text" id="adl_ch_1" name="adl_ch[]"  class="form-control rounded-0"  required/>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Dly Dt <span style="color: red">*</span></label>
                                        <input type="date" name="delivery_date[]" class="form-control rounded-0 text-end" required/>
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
</main>
<!--end main wrapper-->
@include('include.footer')
