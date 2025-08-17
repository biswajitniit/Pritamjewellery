@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Metal Master <div class="style_back"> <a href="{{ route('metals.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                    </div>
                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('metals.store') }}" method="POST" name="saveMetals">
                            @csrf

                            <div class="col-md-2">
                                <label class="form-label">Metal Name <span style="color: red">*</span></label>
                                <input type="text" name="metal_name" value="{{ old('metal_name') }}" class="form-control rounded-0 text-end @error('metal_name') is-invalid @enderror" />
                                @error('metal_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Category <span style="color: red">*</span></label>
                                <select name="metal_category" id="metal_category" class="form-select rounded-0 @error('metal_category') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    <option value="Gold">Gold</option>
                                    <option value="Finding">Finding</option>
                                    <option value="Alloy">Alloy</option>
                                </select>
                                @error('metal_category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">HSN </label>
                                <input type="text" name="metal_hsn" value="{{ old('metal_hsn') }}" class="form-control rounded-0 text-end" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">SAC</label>
                                <input type="text" name="metal_sac" value="{{ old('metal_sac') }}" class="form-control rounded-0 text-end" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Description <span style="color: red">*</span></label>
                                <input type="text" name="description" value="{{ old('description') }}" class="form-control rounded-0 @error('description') is-invalid @enderror" />
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label mb-2">Active <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" checked name="is_active" value="Yes" />Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" name="is_active" value="No" />No </label>
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
