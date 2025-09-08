@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-6 offset-xl-3">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Miscellaneous <div class="style_back"> <a href="{{ route('miscellaneouses.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('miscellaneouses.update', [$miscellaneous->id]) }}" method="POST" name="editStones">
                            @csrf
                            @method('PUT')

                            <div class="col-md-4">
                                <label class="form-label">Product Code <span style="color: red">*</span></label>
                                <input type="text" name="product_code" value="{{ @$miscellaneous->product_code }}" class="form-control rounded-0 text-end @error('product_code') is-invalid @enderror" required/>
                                @error('product_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Product Name <span style="color: red">*</span></label>
                                <input type="text" name="product_name" value="{{ @$miscellaneous->product_name }}" class="form-control rounded-0 text-end @error('product_name') is-invalid @enderror" required/>
                                @error('product_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">UOM <span style="color: red">*</span></label>
                                <select name="uom" class="form-select rounded-0 @error('uom') is-invalid @enderror" required>
                                    <option value="">Choose...</option>
                                    {{-- @foreach ($uoms as $uom)
                                        <option value="{{ $uom->uomid }}" @if($miscellaneous->uom == $uom->uomid) selected @endif>{{ $uom->uomid }}</option>
                                    @endforeach --}}
                                    <option value="GM" @if($miscellaneous->uom == 'GM') selected @endif>GM</option>
                                    <option value="PCS" @if($miscellaneous->uom == 'PCS') selected @endif>PCS</option>
                                </select>
                                @error('uom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-4">
                                <label class="form-label">Size <span style="color: red">*</span></label>
                                <input type="text" name="size" value="{{ @$miscellaneous->size }}" class="form-control rounded-0 text-end @error('size') is-invalid @enderror" required/>
                                @error('size')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div> --}}

                            <div class="col-md-4">
                                <label class="form-label mb-2">Active <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" @if($miscellaneous->is_active == 'Yes') checked @endif name="is_active" value="Yes" required/>Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2"  @if($miscellaneous->is_active == 'No') checked @endif name="is_active" value="No" required/>No </label>
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
