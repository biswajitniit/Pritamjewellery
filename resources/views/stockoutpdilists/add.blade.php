@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Stock Out PDI List Add <div class="style_back"> <a href="{{ route('stockoutpdilists.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div>
                        </h5>
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
                            <form class="row g-3" action="{{ route('stockoutpdilists.store') }}" method="POST" name="saveStockoutpdilists">
                                @csrf

                                <div class="col-md-3">
                                    <label class="form-label">Name <span style="color: red;">*</span></label>
                                    <select name="customer_id" class="form-select rounded-0 @error('stone_chg') is-invalid @enderror" required onchange="return GetCustomerDetails(this.value)">
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
                                <div class="col-md-9">
                                    <label class="form-label">Address <span style="color: red;">*</span></label>
                                    <textarea name="customer_address" id="cust_address" class="form-control @error('customer_address') is-invalid @enderror""></textarea>
                                @error('customer_address')
                                <span class=" invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">D.C. Ref No. <span style="color: red;">*</span></label>
                                <input type="text" name="dc_ref_no" value="{{ $newVoucherNo }}" class="form-control rounded-0 text-end" readonly/>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">D.C Date <span style="color: red;">*</span></label>
                                <input type="date" name="dc_date" class="form-control rounded-0" required/>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Purity <span style="color: red;">*</span></label>
                                <input type="text" name="purity" value="91.6" readonly class="form-control rounded-0 text-end" />
                            </div>
                            <div class="col-md-6"></div>

                           <div class="col-md-2">
                                <label class="form-label">Quantity <span style="color: red;">*</span></label>
                                <input type="text"  name="qty" value="{{ @$totalQty }}" class="form-control rounded-0 text-end" readonly/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Net Weight <span style="color: red;">*</span></label>
                                <input type="text" name="net_weight" value="{{ @$totalNetwt }}" class="form-control rounded-0 text-end" readonly/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Stone Chg. <span style="color: red;">*</span></label>
                                <input type="text" name="stone_chg" value="{{ @$totalStonechg }}" class="form-control rounded-0 text-end" readonly/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Lab Chg. <span style="color: red;">*</span></label>
                                <input type="text" name="lab_chg" value="{{ @$totalRate }}" class="form-control rounded-0 text-end" readonly/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Add Lab. Chg. <span style="color: red;">*</span></label>
                                <input type="text" name="add_lab_chg" value="{{ @$totalAlab }}" class="form-control rounded-0 text-end" readonly/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Amount (Rs.) <span style="color: red;">*</span></label>
                                <input type="text" name="amount" value="{{ @$totalStonechg +  @$totalRate + @$totalAlab }}" class="form-control rounded-0 text-end" readonly/>
                            </div>


                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
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
