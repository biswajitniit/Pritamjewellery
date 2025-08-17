@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Metal Receive Entry <div class="style_back"> <a href="{{ route('metalreceiveentries.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('metalreceiveentries.update', [$metalreceiveentries->metal_receive_entries_id]) }}" method="POST" name="editMetalreceiveentriesid">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6">
                                <label class="form-label">Date <span style="color: red">*</span></label>
                                <input type="date" name="metal_receive_entries_date" value="{{ @$metalreceiveentries->metal_receive_entries_date }}" class="form-control rounded-0 @error('metal_receive_entries_date') is-invalid @enderror" />
                                @error('metal_receive_entries_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-6">
                                <label class="form-label">Company ID <span style="color: red">*</span></label>
                                <select name="customer_id" class="form-select rounded-0 @error('customer_id') is-invalid @enderror" onchange="GetCustomerDetails(this.value)">
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

                            <div class="col-md-6">
                                <label class="form-label">Company Name <span style="color: red">*</span></label>
                                <input type="text" name="cust_name" id="cust_name" value="{{ @$metalreceiveentries->cust_name }}" class="form-control rounded-0 @error('cust_name') is-invalid @enderror" readonly/>
                                @error('cust_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Company Address <span style="color: red">*</span></label>
                                <textarea name="cust_address" id="cust_address" class="form-control rounded-0 @error('cust_address') is-invalid @enderror" readonly>{{ @$metalreceiveentries->cust_address }}</textarea>
                                @error('cust_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Metal Name <span style="color: red">*</span></label>
                                <select name="metal_id" class="form-select rounded-0 @error('metal_id') is-invalid @enderror" onchange="GetMetalPurity(this.value)">
                                    <option value="">Choose...</option>
                                    @forelse($metals as $metal)
                                    <option value="{{ $metal->metal_id }}" @if($metal->metal_id == $metalreceiveentries->metal_id) selected @endif >{{ $metal->metal_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('metal_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Purity<span style="color: red">*</span></label>
                                <select name="purity_id" id="purity_id" class="form-select rounded-0 @error('purity_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($metalpurity as $metalpuriti)
                                    <option value="{{ $metalpuriti->purity_id }}" @if($metalpuriti->purity_id == $metalreceiveentries->purity_id) selected @endif >{{ $metalpuriti->purity }}</option>
                                    @empty
                                    @endforelse

                                    @error('purity_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </select>
                                @error('purity_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Weight <span style="color: red">*</span></label>
                                <input type="text" name="weight" value="{{ @$metalreceiveentries->weight }}" class="form-control rounded-0 @error('weight') is-invalid @enderror" />
                                @error('weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">DV No. <span style="color: red">*</span></label>
                                <input type="text" name="dv_no" value="{{ @$metalreceiveentries->dv_no }}" class="form-control rounded-0 @error('dv_no') is-invalid @enderror" />
                                @error('dv_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">DV Date <span style="color: red">*</span></label>
                                <input type="date" name="dv_date" value="{{ @$metalreceiveentries->dv_date }}" class="form-control rounded-0 @error('dv_date') is-invalid @enderror" />
                                @error('dv_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    {{-- <button type="button" class="btn btn-grd-info px-4 rounded-0">Edit</button> --}}
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
