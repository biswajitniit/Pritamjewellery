@include('include.header')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">

                        <h5 class="mb-0 text-danger">Add Customer Order Bulk
                            <div class="style_back"> <a href="{{ route('customerorders.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div>
                        </h5>
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


                    @if ($errors->has('file'))
                        <span class="text-danger">{{ $errors->first('file') }}</span>
                    @endif

                    <div class="card-body p-4">
                        <div class="tabcontent">

                            <!-- Loader and message -->
                            <div id="loader" style="display: none; margin-top: 10px;">
                                <img src="{{ asset('assets/images/loading.gif') }}" alt="Loading..." width="150">
                                <p id="customMessage" style="margin-top: 5px;">Uploading customer order, please wait...</p>
                            </div>

                            {{-- <form id="myForm" class="row g-3" action="{{ route('customerordersimporttxt') }}" method="POST" name="saveCustomerorders" enctype="multipart/form-data"> --}}
                            <form id="myForm" class="row g-3" action="{{ route('customerorderstempimporttxt') }}" method="POST" name="saveCustomerorderstemp" enctype="multipart/form-data">
                                @csrf
                                <label class="mb-1">Order Type:</label>
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="regularOrder" value="Regular" required>
                                        <label class="form-check-label" for="regularOrder">Regular Order</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="customerOrder" value="Customer" required>
                                        <label class="form-check-label" for="customerOrder">Customer Order</label>
                                    </div>
                                </div>

                                <label>Select TXT File:</label>
                                <input type="file" name="file" required accept=".txt">

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3 mt-4">
                                        <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->
@include('include.footer')
