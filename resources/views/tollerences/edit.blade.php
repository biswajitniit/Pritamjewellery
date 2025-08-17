@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Tolerance <div class="style_back"> <a href="{{ route('tollerences.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('tollerences.update', [$tollerences->id]) }}" method="POST" name="editUoms">
                            @csrf
                            @method('PUT')
                            <div class="col-md-3">
                                <label class="form-label">Weight Min <span style="color: red">*</span></label>
                                <input type="text" name="weight_min"   value="{{ @$tollerences->weight_min }}"  class="form-control rounded-0 text-end @error('weight_min') is-invalid @enderror" />
                                @error('weight_min')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Weight Max <span style="color: red">*</span></label>
                                <input type="text" name="weight_max"  value="{{ @$tollerences->weight_max }}"  class="form-control rounded-0 text-end @error('weight_max') is-invalid @enderror" />
                                @error('weight_max')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tolerance (+) <span style="color: red">*</span></label>
                                <input type="tolerance_plus" name="tolerance_plus"  value="{{ @$tollerences->tolerance_plus }}"  class="form-control rounded-0 text-end @error('tolerance_plus') is-invalid @enderror" />
                                @error('tolerance_plus')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tolerance (-) <span style="color: red">*</span></label>
                                <input type="text" name="tolerance_minus"  value="{{ @$tollerences->tolerance_minus }}"  class="form-control rounded-0 text-end @error('tolerance_minus') is-invalid @enderror" />
                                @error('tolerance_minus')
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
