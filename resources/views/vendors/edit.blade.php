@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Vendor <div class="style_back"> <a href="{{ route('vendors.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('vendors.update', [$vendors->id]) }}" method="POST" name="editCustomers">
                            @csrf
                            @method('PUT')

                            <div class="col-md-5">
                                <label class="form-label">VID <span style="color: red">*</span></label>
                                <input type="text" name="vid" value="{{ @$vendors->vid }}" class="form-control rounded-0 @error('vid') is-invalid @enderror" required/>
                                @error('vid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Name <span style="color: red">*</span></label>
                                <input type="text" name="name" value="{{ @$vendors->name }}" class="form-control rounded-0 @error('name') is-invalid @enderror" required/>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Vendor Code </label>
                                <input type="text" name="vendor_code" value="{{ old('vendor_code', @$vendors->vendor_code) }}" class="form-control rounded-0"/>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address <span style="color: red">*</span></label>
                                <input type="text" name="address" value="{{ @$vendors->address }}" class="form-control rounded-0 @error('address') is-invalid @enderror" required/>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">City <span style="color: red">*</span></label>
                                <input type="text" name="city" value="{{ @$vendors->city }}" class="form-control rounded-0 @error('city') is-invalid @enderror" required/>
                                @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">State <span style="color: red">*</span></label>
                                <input type="text" name="state" value="{{ @$vendors->state }}" class="form-control rounded-0 @error('state') is-invalid @enderror" required/>
                                @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ @$vendors->phone }}" class="form-control rounded-0 text-end" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" value="{{ @$vendors->mobile }}" class="form-control rounded-0 text-end" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Contact Person <span style="color: red">*</span></label>
                                <input type="text" name="contact_person" value="{{ @$vendors->contact_person }}" class="form-control rounded-0 @error('contact_person') is-invalid @enderror" required/>
                                @error('contact_person')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">GSTIN</label>
                                <input type="text" name="gstin" value="{{ @$vendors->gstin }}" class="form-control rounded-0 text-end" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">State Code <span style="color: red">*</span></label>
                                <input type="text" name="statecode" value="{{ @$vendors->statecode }}" class="form-control rounded-0 text-end @error('statecode') is-invalid @enderror" required/>
                                @error('statecode')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-2">Active <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" @if($vendors->is_active == 'Yes') checked @endif name="is_active" value="Yes" required/>Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2"  @if($vendors->is_active == 'No') checked @endif name="is_active" value="No" required/>No </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="Save" class="btn btn-grd-info px-4 rounded-0">
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
