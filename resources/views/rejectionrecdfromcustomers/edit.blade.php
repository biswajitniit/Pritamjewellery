@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Metal Receive Entry <div class="style_back"> <a href="{{ route('metalreceiveentries.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
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
                            <form class="row g-3" action="{{ route('metalreceiveentries.update', [$metalreceiveentries->metal_receive_entries_id]) }}" method="POST" name="saveMetalReceiveEntry">
                            @csrf
                            @method('PUT')
                                <div class="col-md-4">
                                    <label class="form-label">Location ID <span style="color: red">*</span></label>
                                    <select name="location_id" disabled class="form-select rounded-0 @error('location_id') is-invalid @enderror" onchange="GetLocationWiseVoucherNo(this.value,'gold_receipt_entry')">
                                        <option value="">Choose...</option>
                                        @forelse($locations as $location)
                                        <option value="{{ $location->id }}" @if($location->id == $metalreceiveentries->location_id) selected @endif>{{ $location->location_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('location_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Voucher No <span style="color: red">*</span></label>
                                    <input type="text" name="vou_no" id="voucher_no" value="{{ old('vou_no', $metalreceiveentries->vou_no) }}" class="form-control rounded-0 @error('vou_no') is-invalid @enderror" readonly/>
                                    @error('vou_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date <span style="color: red">*</span></label>
                                    <input type="date"
                                        name="metal_receive_entries_date"
                                        value="{{ old('metal_receive_entries_date', $metalreceiveentries->metal_receive_entries_date) }}"
                                        max="{{ date('Y-m-d') }}"
                                        class="form-control rounded-0 @error('metal_receive_entries_date') is-invalid @enderror" readonly/>

                                    @error('metal_receive_entries_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label">Customer ID <span style="color: red">*</span></label>
                                    <select name="customer_id" disabled class="form-select rounded-0 @error('customer_id') is-invalid @enderror" onchange="GetCustomerDetails(this.value)">
                                        <option value="">Choose...</option>
                                        @forelse($customers as $customer)
                                        <option value="{{ $customer->id }}" @if($customer->id == $metalreceiveentries->customer_id) selected @endif>{{ $customer->cust_name }} ({{ $customer->cid }})</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('customer_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Customer Name <span style="color: red">*</span></label>
                                    <input type="text" name="cust_name" id="cust_name" value="{{ old('cust_name', $metalreceiveentries->cust_name) }}" class="form-control rounded-0 @error('cust_name') is-invalid @enderror" readonly/>
                                    @error('cust_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>



                                <div class="col-md-6">
                                    <label class="form-label mb-2">Category <span style="color: red;">*</span></label>
                                    <div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label"> <input type="radio" value="Gold" class="form-check-input me-2" name="metal_category" onclick="return Get_metal_issue_category('Gold')" @if($metalreceiveentries->metal_category == 'Gold') checked @endif  required/>Gold </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label"> <input type="radio" value="Finding" class="form-check-input me-2" name="metal_category" onclick="return Get_metal_issue_category('Finding')" @if($metalreceiveentries->metal_category == 'Finding') checked @endif required/>Finding </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label"> <input type="radio" value="Alloy" class="form-check-input me-2" name="metal_category" onclick="return Get_metal_issue_category('Alloy')" @if($metalreceiveentries->metal_category == 'Alloy') checked @endif required/>Alloy </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label"> <input type="radio" value="Miscellaneous" class="form-check-input me-2" name="metal_category" onclick="return Get_metal_issue_category('Miscellaneous')" @if($metalreceiveentries->metal_category == 'Miscellaneous') checked @endif required/>Miscellaneous </label>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-3">
                                    <label class="form-label">Metal Name <span style="color: red;">*</span></label>
                                    <select name="metal_id" id="metal_id" class="form-select rounded-0 @error('metal_id') is-invalid @enderror" onchange="GetMetalPurity(this.value)" required>
                                        <option value="">Choose...</option>
                                    </select>
                                    @error('metal_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Purity<span style="color: red">*</span></label>
                                    <select name="purity_id" id="purity_id" class="form-select rounded-0 @error('purity_id') is-invalid @enderror">
                                        <option value="">Choose...</option>
                                    </select>
                                    @error('purity_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Weight in gm <span style="color: red">*</span></label>
                                    <input type="text" name="weight" value="{{ old('weight') }}" class="form-control rounded-0 @error('weight') is-invalid @enderror" />
                                    @error('weight')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">DV No. <span style="color: red">*</span></label>
                                    <input type="text" name="dv_no" value="{{ old('dv_no') }}" class="form-control rounded-0 @error('dv_no') is-invalid @enderror" />
                                    @error('dv_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">DV Date <span style="color: red">*</span></label>
                                    <input type="date" name="dv_date" value="{{ old('dv_date', now()->toDateString()) }}" class="form-control rounded-0 @error('dv_date') is-invalid @enderror" />
                                    @error('dv_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <input type="submit" name="submit" value="Edit" class="btn btn-grd-info px-4 rounded-0">
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
