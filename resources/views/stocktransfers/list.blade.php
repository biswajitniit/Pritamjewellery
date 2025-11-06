@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Stock Transfer <div class="style_back"> <a
                                    href="{{ route('metalreceiveentries.index') }}"><i class="fa fa-chevron-left"></i>
                                    Back </a></div>
                        </h5>
                        @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
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
                            <form class="row g-3" action="{{ route('stock-transfers.store') }}" method="POST"
                                name="saveMetalReceiveEntry">
                                @csrf


                                <div class="col-md-4">
                                    <label class="form-label">Select From Location <span style="color: red">*</span></label>
                                    <select name="location_id" id="location_id" class="form-select rounded-0  @error('location_id') is-invalid @enderror" onchange="GetLocationWiseStockEffectItemName(this.value), GetToLocation(this.value)">
                                        <option value="">Choose...</option>
                                        @forelse($locations as $location)
                                        <option value="{{ $location->location_name }}">{{ $location->location_name }}</option>
                                        @empty
                                        @endforelse 
                                    </select>
                                    @error('location_id')
                                    <span class=" invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Select To Location <span style="color: red">*</span></label>
                                    <select name="to_location_id" id="to_location_id" class="form-select rounded-0">
                                        <option value="">Choose...</option>
                                    </select>
                                    @error('to_location_id')
                                    <span class=" invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label">Date <span style="color: red">*</span></label>
                                    <input type="date" name="metal_receive_entries_date"
                                        value="{{ old('metal_receive_entries_date', date('Y-m-d')) }}"
                                        max="{{ date('Y-m-d') }}"
                                        class="form-control rounded-0 @error('metal_receive_entries_date') is-invalid @enderror" />

                                    @error('metal_receive_entries_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Select Item <span style="color: red">*</span></label>
                                    <select name="metal_name" id="metal_name" class="form-select rounded-0" onchange="setStockAvailable(this.value)">
                                        <option value="">Choose...</option>
                                    </select>
                                    @error('location_id')
                                    <span class=" invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Stock Available <span style="color: red">*</span></label>
                                    <input type="text" name="stock_available" id="stock_available" value="{{ old('stock_available') }}"
                                        class="form-control rounded-0 @error('stock_available') is-invalid @enderror" readonly />
                                    @error('stock_available')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Qty <span style="color: red">*</span></label>
                                    <input type="text" name="qty" id="qty" value="{{ old('qty') }}"
                                        class="form-control rounded-0 @error('qty') is-invalid @enderror" />
                                    @error('qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <input type="submit" name="submit" value="submit"
                                            class="btn btn-grd-danger px-4 rounded-0">
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