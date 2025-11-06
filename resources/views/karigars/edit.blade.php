@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Karigar <div class="style_back"> <a href="{{ route('karigars.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
                    </div>

                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('karigars.update', [$karigars->id]) }}" method="POST" name="editKarigars">
                            @csrf
                            @method('PUT')

                            <div class="col-md-2">
                                <label class="form-label">KID <span style="color: red">*</span></label>
                                <input type="text" name="kid" value="{{ @$karigars->kid }}" class="form-control rounded-0 @error('kid') is-invalid @enderror" required />
                                @error('kid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Karigar Name <span style="color: red">*</span></label>
                                <input type="text" name="kname" value="{{ @$karigars->kname }}" class="form-control rounded-0 @error('kname') is-invalid @enderror" required/>
                                @error('kname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Father Name <span style="color: red">*</span></label>
                                <input type="text" name="kfather" value="{{ @$karigars->kfather }}" class="form-control rounded-0 @error('kfather') is-invalid @enderror" required/>
                                @error('kfather')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Address <span style="color: red">*</span></label>
                                <input type="text" name="address" value="{{ @$karigars->address }}" class="form-control rounded-0 @error('address') is-invalid @enderror" required/>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ @$karigars->phone }}" class="form-control rounded-0" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mobile <span style="color: red">*</span></label>
                                <input type="text" name="mobile" value="{{ @$karigars->mobile }}" class="form-control rounded-0 @error('mobile') is-invalid @enderror" required/>
                                @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="pan" value="{{ @$karigars->pan }}" class="form-control rounded-0" />

                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Introducer</label>
                                <input type="text" name="introducer" value="{{ @$karigars->introducer }}" class="form-control rounded-0" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Karigar Loss</label>
                                <input type="text" name="karigar_loss" value="{{ @$karigars->karigar_loss }}" class="form-control rounded-0" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Remark</label>
                                <input type="text" name="remark" value="{{ @$karigars->remark }}" class="form-control rounded-0" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">GSTIN</label>
                                <input type="text" name="gstin" value="{{ @$karigars->gstin }}"  class="form-control rounded-0" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">State Code </label>
                                <input type="text" name="statecode" value="{{ @$karigars->statecode }}"  class="form-control rounded-0" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-2">Active <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" @if($karigars->is_active == 'Yes') checked @endif name="is_active" value="Yes" required/>Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2"  @if($karigars->is_active == 'No') checked @endif name="is_active" value="No" required/>No </label>
                                    </div>
                                </div>
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
