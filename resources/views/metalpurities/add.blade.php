@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Metal Purities <div class="style_back"> <a href="{{ route('metalpurities.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('metalpurities.store') }}" method="POST" name="saveMetalpurities">
                            @csrf


                            <div class="col-md-5">
                                <label class="form-label">Metal Name <span style="color: red">*</span></label>
                                <select name="metal_id" class="form-select rounded-0 @error('metal_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($metals as $metal)
                                    <option value="{{ $metal->metal_id }}">{{ $metal->metal_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('metal_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Purity <span style="color: red">*</span></label>
                                <input type="text" name="purity" value="{{ old('purity') }}" class="form-control rounded-0 @error('purity') is-invalid @enderror" />
                                @error('purity')
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
