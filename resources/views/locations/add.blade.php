@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Location Master <div class="style_back"> <a href="{{ route('locations.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('locations.store') }}" method="POST" name="saveLocations">
                            @csrf

                            <div class="col-md-4">
                                <label class="form-label">Location Name <span style="color: red">*</span></label>
                                <input type="text" name="location_name" value="{{ old('location_name') }}" class="form-control rounded-0 @error('location_name') is-invalid @enderror" required/>
                                @error('location_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Location Address <span style="color: red">*</span></label>
                                <input type="text" name="location_address" value="{{ old('location_address') }}" class="form-control rounded-0 @error('location_address') is-invalid @enderror" required/>
                                @error('location_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
