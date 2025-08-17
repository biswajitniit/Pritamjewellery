@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-6 offset-xl-3">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Size <div class="style_back"> <a href="{{ route('sizes.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('sizes.store') }}" method="POST" name="saveSize">
                            @csrf

                            <div class="col-md-2">
                                <label class="form-label">schar <span style="color: red">*</span></label>
                                <input type="text" name="schar" value="{{ old('schar') }}" class="form-control rounded-0 @error('schar') is-invalid @enderror" required/>
                                @error('schar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Item Name <span style="color: red">*</span></label>
                                <input type="text" name="item_name" value="{{ old('item_name') }}" class="form-control rounded-0 @error('item_name') is-invalid @enderror" required/>
                                @error('item_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ssize <span style="color: red">*</span></label>
                                <input type="text" name="ssize" value="{{ old('ssize') }}" class="form-control rounded-0 @error('ssize') is-invalid @enderror" required/>
                                @error('ssize')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">pcode <span style="color: red">*</span></label>
                                <select name="pcode_id" class="form-select rounded-0 @error('pcode_id') is-invalid @enderror" required>
                                    <option value="">Choose...</option>
                                    @forelse($pcodes as $pcode)
                                    <option value="{{ $pcode->id }}">{{ $pcode->code }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('pcode_id')
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
