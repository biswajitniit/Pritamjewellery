@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 prod-master">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">
                           Edit Voucher Types
                            <div class="style_back">
                                <a href="{{ route('vouchertypes.index') }}"><i class="fa fa-chevron-left"></i> Back </a>
                            </div>
                        </h5>
                    </div>

                    @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                    @endif @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error')}}
                    </div>
                    @endif

                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('vouchertypes.update', [$vouchertypes->id]) }}" method="POST" name="editVouchertypes" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- <div class="col-md-2">
                                @php $currentYear = date('Y'); $startYear = $currentYear; $endYear = $currentYear - 2; @endphp
                                <label class="form-label">Applicable Year <span style="color: red;">*</span></label>
                                <select name="applicable_year" class="form-select" required>
                                    <option value="">Select applicable year</option>
                                    @for ($year = $startYear; $year >= $endYear; $year--) @php $nextYear = $year + 1; $label = $year . '-' . substr($nextYear, -2); @endphp
                                    <option value="{{ $label }}" @if($vouchertypes->applicable_year == $label) selected @endif>{{ $label }}</option>
                                    @endfor
                                </select>
                            </div> --}}

                            <div class="col-md-2">
                                 <label class="form-label">Financial Year <span style="color: red;">*</span></label>
                                <select name="financial_year_id" class="form-select" required>
                                    <option value="">Select financial year</option>
                                    @foreach ($financialyears as $financialyear)
                                     <option value="{{ $financialyear->id }}" @if($financialyear->id == $vouchertypes->financial_year_id) selected @endif>{{ $financialyear->applicable_year }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-2">
                                <label class="form-label">Voucher Type <span style="color: red;">*</span></label>
                                <select name="voucher_type" id="voucher_type" class="form-select rounded-0">
                                    <option value="">Choose...</option>
                                    <option value="finished_goods_entry" @if($vouchertypes->voucher_type == 'finished_goods_entry') selected @endif>Finished Goods Entry</option>
                                    <option value="finished_product_pdi_list" @if($vouchertypes->voucher_type == 'finished_product_pdi_list') selected @endif>Finished Product PDI List</option>
                                    <option value="gold_issue_entry" @if($vouchertypes->voucher_type == 'gold_issue_entry') selected @endif>Gold Issue Entry</option>
                                    <option value="gold_receipt_entry" @if($vouchertypes->voucher_type == 'gold_receipt_entry') selected @endif>Gold Receipt Entry</option>
                                    <option value="quality_check" @if($vouchertypes->voucher_type == 'quality_check') selected @endif>Quality Check</option>
                                    <option value="return_gold_from_karigar" @if($vouchertypes->voucher_type == 'return_gold_from_karigar') selected @endif>Return Gold From Karigar</option>
                                    <option value="stock_transfer" @if($vouchertypes->voucher_type == 'stock_transfer') selected @endif>Stock Transfer</option>
                                    <option value="delivery_challan_no" @if($vouchertypes->voucher_type == 'delivery_challan_no') selected @endif>Delivery Challan No</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Location <span style="color: red;">*</span></label>
                                <select name="location_id" id="location_id" class="form-select rounded-0">
                                    <option value="">Choose...</option>
                                    @forelse($locations as $location)
                                    <option value="{{ $location->id }}" @if($vouchertypes->location_id == $location->id) selected @endif >{{ $location->location_name }}</option>
                                    @empty @endforelse
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Applicable Date <span style="color: red;">*</span></label>
                                <input type="date" name="applicable_date" value="{{ old('applicable_date',$vouchertypes->applicable_date) }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Startno <span style="color: red;">*</span></label>
                                <input type="text" name="startno" value="{{ old('startno',$vouchertypes->startno) }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Prefix <span style="color: red;">*</span></label>
                                <input type="text" name="prefix" value="{{ old('prefix',$vouchertypes->prefix) }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Suffix <span style="color: red;">*</span></label>
                                <input type="text" name="suffix" value="{{ old('suffix',$vouchertypes->suffix) }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-2">Active <span style="color: red">*</span></label>
                                <div class="border p-2">
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" checked="" name="status" value="Active" @if($vouchertypes->status == 'Active') checked @endif>Active </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" class="form-check-input me-2" name="status" value="Inactive" @if($vouchertypes->status == 'Inactive') checked @endif>Inactive </label>
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
