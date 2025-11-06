@include('include.header')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 prod-master">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">
                            Add Financial Year
                            <div class="style_back">
                                <a href="{{ route('financial-years.index') }}"><i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </h5>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('financial-years.store') }}" method="POST">
                            @csrf

                            <div class="col-md-3">
                                <label class="form-label">Financial Year <span class="text-danger">*</span></label>
                                <input type="text" name="applicable_year" value="{{ old('applicable_year') }}" class="form-control rounded-0" placeholder="e.g. 2025-26" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control rounded-0" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control rounded-0" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-2">Status <span class="text-danger">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <input type="radio" class="form-check-input me-2" name="status" value="Active" checked> Active
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="radio" class="form-check-input me-2" name="status" value="Inactive"> Inactive
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" value="Submit" class="btn btn-grd-danger px-4 rounded-0">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('include.footer')
