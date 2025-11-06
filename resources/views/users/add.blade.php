@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Admin Master <div class="style_back"> <a href="{{ route('users.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('users.store') }}" method="POST" name="saveAdmin">
                            @csrf
                            <div class="col-md-3">
                                <label class="form-label">Name <span style="color: red">*</span></label>
                                <input type="text" name="name" class="form-control rounded-0 @error('name') is-invalid @enderror" required/>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email <span style="color: red">*</span></label>
                                <input type="email" name="email" class="form-control rounded-0 @error('email') is-invalid @enderror" required/>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Password <span style="color: red">*</span></label>
                                <input type="password" name="password" class="form-control rounded-0 @error('password') is-invalid @enderror" required/>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" class="form-control rounded-0 text-end" name="mobile"/>
                            </div>
                            {{-- <div class="col-md-6">
                                <label class="form-label mb-2">Role Type <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" checked name="user_type" value="admin" required/>Admin </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" name="user_type" value="super_admin" required/>Super Admin </label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <label class="form-label mb-2">Active <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" checked name="status" value="Yes" required/>Yes </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" name="status" value="No" required/>No </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    {{-- <button type="button" class="btn btn-grd-danger px-4 rounded-0">Submit</button> --}}
                                    <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0">
                                    {{-- <button type="button" class="btn btn-grd-info px-4 rounded-0">Edit</button> --}}
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
