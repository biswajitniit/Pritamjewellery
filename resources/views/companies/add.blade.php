@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Company <div class="style_back"> <a href="{{ route('companies.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('companies.store') }}" method="POST" name="saveCompaniess">
                            @csrf

                            <div class="col-md-5">
                                <label class="form-label">CID <span style="color: red">*</span></label>
                                <input type="text" name="cid" value="{{ old('cid') }}" class="form-control rounded-0 @error('cid') is-invalid @enderror" required/>
                                @error('cid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Name <span style="color: red">*</span></label>
                                <input type="text" name="cust_name" value="{{ old('cust_name') }}" class="form-control rounded-0 @error('cust_name') is-invalid @enderror" required/>
                                @error('cust_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Customer Code </label>
                                <input type="text" name="cust_code" value="{{ old('cust_code') }}" class="form-control rounded-0"/>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address <span style="color: red">*</span></label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control rounded-0 @error('address') is-invalid @enderror" required/>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">City <span style="color: red">*</span></label>
                                <input type="text" name="city" value="{{ old('city') }}" class="form-control rounded-0 @error('city') is-invalid @enderror" required/>
                                @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">State <span style="color: red">*</span></label>
                                <input type="text" name="state" value="{{ old('state') }}" class="form-control rounded-0 @error('state') is-invalid @enderror" required/>
                                @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control rounded-0 text-end" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control rounded-0 text-end" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Contact Person <span style="color: red">*</span></label>
                                <input type="text" name="cont_person" value="{{ old('cont_person') }}" class="form-control rounded-0 @error('cont_person') is-invalid @enderror" required/>
                                @error('cont_person')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">GSTIN</label>
                                <input type="text" name="gstin" value="{{ old('gstin') }}" class="form-control rounded-0 text-end" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">State Code <span style="color: red">*</span></label>
                                <input type="text" name="statecode" value="{{ old('statecode') }}" class="form-control rounded-0 text-end @error('statecode') is-invalid @enderror" required/>
                                @error('statecode')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-2">Is Validation? <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" checked name="is_validation" value="Yes" required/>Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" name="is_validation" value="No" required/>No </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-2">Active <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" checked name="is_active" value="Yes" required/>Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" name="is_active" value="No" required/>No </label>
                                    </div>
                                </div>
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
