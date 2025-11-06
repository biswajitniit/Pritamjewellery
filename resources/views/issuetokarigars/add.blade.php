@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">

                        <h5 class="mb-0 text-danger">Add Issue to Karigar
                            <div class="style_back"> <a href="{{ route('issuetokarigars.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div>
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
                        <form class="row g-3" action="{{ route('issuetokarigars.store') }}" method="POST" name="saveIssuetokarigars" enctype="multipart/form-data">
                            @csrf
                                <div class="row m-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Selection Customer Name <span style="color: red">*</span></label>
                                        <select name="customer_id" onchange="Get_order_info(this.value)" class="form-select rounded-0 @error('stone_chg') is-invalid @enderror" required>
                                            <option value="">Customer Selection</option>
                                            @forelse($customerorders as $customerorder)
                                            <option value="{{ $customerorder->customer_id }}">{{ $customerorder->customer->cust_name }} ({{ $customerorder->customer->cid }})</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Selection Job Order No <span style="color: red">*</span></label>
                                        <select name="order_id" id="selection_order_no" onchange="Get_order_items(this.value)" class="form-select rounded-0" required>
                                            <option value="">Order No Selection</option>
                                        </select>
                                        @error('order_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>


                                <div class="row g-3 mb-1">
                                    <div class="col-md-2"><label class="form-label">Item Code</label></div>
                                    {{-- <div class="col-md-2"><label class="form-label">Design</label></div> --}}
                                    <div class="col-md-2"><label class="form-label">Description</label></div>
                                    <div class="col-md-1"><label class="form-label">Size</label></div>
                                    <div class="col-md-1"><label class="form-label">UOM</label></div>
                                    <div class="col-md-1"><label class="form-label">St. Weight</label></div>
                                    <div class="col-md-1"><label class="form-label">Min Weight</label></div>
                                    <div class="col-md-1"><label class="form-label">Max Weight</label></div>
                                    <div class="col-md-1">
                                        <label class="form-label">Qty.</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Karigar ID</label>
                                    </div>
                                    <div class="col-md-1"><label class="form-label">Dly Date</label></div>
                                </div>


                                <div class="orderitems_issue_to_karigar"></div>
                                <!-- End-->
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
