@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Metal Master <div class="style_back"> <a href="{{ route('metals.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('metals.update', [$metals->metal_id]) }}" method="POST" name="editMetals">
                            @csrf
                            @method('PUT')
                            <div class="col-md-2">
                                <label class="form-label">Metal Name <span style="color: red">*</span></label>
                                <input type="text" name="metal_name" value="{{ @$metals->metal_name }}" class="form-control rounded-0 text-end @error('metal_name') is-invalid @enderror" />
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
                                    <option value="Gold" @if($metals->metal_category == "Gold") selected @endif>Gold</option>
                                    <option value="Finding" @if($metals->metal_category == "Finding") selected @endif>Finding</option>
                                    <option value="Alloy" @if($metals->metal_category == "Alloy") selected @endif>Alloy</option>
                                </select>
                                @error('metal_category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">HSN </label>
                                <input type="text" name="metal_hsn" value="{{ old('metal_hsn', @$metals->metal_hsn) }}" class="form-control rounded-0 text-end" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">SAC</label>
                                <input type="text" name="metal_sac" value="{{ old('metal_sac', @$metals->metal_sac) }}" class="form-control rounded-0 text-end" />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Description <span style="color: red">*</span></label>
                                <input type="text" name="description" value="{{ @$metals->description }}" class="form-control rounded-0 @error('description') is-invalid @enderror" />
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
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" @if($metals->is_active == 'Yes') checked @endif name="is_active" value="Yes" required/>Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2"  @if($metals->is_active == 'No') checked @endif name="is_active" value="No" required/>No </label>
                                    </div>
                                </div>
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
