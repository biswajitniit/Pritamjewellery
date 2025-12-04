@include('include.header')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">

                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">
                            Rejection Recd From Customers
                            <div class="style_back">
                                <a href="{{ route('rejection-recd-from-customers.index') }}">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </h5>

                        {{-- Success Message --}}
                        @if (session()->has('success'))
                            <div class="alert alert-success mt-2">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        {{-- Error Messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- FORM START --}}
                    <div class="card-body p-4">
                        <form class="row g-3"
                              action="{{ route('rejection-recd-from-customers.store') }}"
                              method="POST">
                            @csrf

                            <!-- Location -->
                            <div class="col-md-4">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <select name="location_id"
                                        class="form-select rounded-0 @error('location_id') is-invalid @enderror"
                                        onchange="GetLocationWiseVoucherNo(this.value,'rejection_recd_from_customers')"
                                        required>
                                    <option value="">Choose...</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id') <span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <!-- Voucher No -->
                            <div class="col-md-4">
                                <label class="form-label">Voucher No <span class="text-danger">*</span></label>
                                <input type="text" name="vou_no"
                                       value="{{ old('vou_no') }}"
                                       class="form-control rounded-0 @error('vou_no') is-invalid @enderror"
                                       id="voucher_no"
                                       required>
                                @error('vou_no') <span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date <span style="color: red">*</span></label>
                                <input type="date" name="vou_date"
                                    value="{{ old('vou_date', date('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="form-control rounded-0 @error('vou_date') is-invalid @enderror" />

                                @error('vou_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Job No -->
                            <div class="col-md-4">
                                <label class="form-label">Job No <span class="text-danger">*</span></label>
                                <select name="job_no"
                                        class="form-select rounded-0 @error('job_no') is-invalid @enderror"
                                        id="job_no"
                                        onchange="GetKIDjobnowise(this.value)"
                                        required>
                                    <option value="">Choose...</option>
                                    @foreach($finishedproductpdis as $finishedproductpdi)
                                        <option value="{{ $finishedproductpdi->job_no }}">{{ $finishedproductpdi->job_no }}</option>
                                    @endforeach
                                </select>
                                @error('job_no') <span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <!-- KID -->
                            <div class="col-md-4">
                                <label class="form-label">KID <span class="text-danger">*</span></label>
                                <select name="kid"
                                        class="form-select rounded-0 @error('kid') is-invalid @enderror"
                                        id="jobno_kid"
                                        onchange="GetItemCodeKIDJobNoWise(this.value)"
                                        required>
                                    <option value="">Choose...</option>
                                </select>
                                @error('kid') <span class="invalid-feedback">{{ $message }}</span>@enderror  
                            </div>

                            <!-- Item Code -->
                            <div class="col-md-4">
                                <label class="form-label">Item Code <span class="text-danger">*</span></label>
                                <select name="item_code"
                                        class="form-select rounded-0 @error('item_code') is-invalid @enderror"
                                        id="item_code"
                                        onchange="GetItemCodeKIDJobNoWiseQtyGrosswtNetwt(this.value)"
                                        required>
                                    <option value="">Choose...</option>
                                </select>
                                @error('item_code') <span class="invalid-feedback">{{ $message }}</span>@enderror                                
                            </div>

                            <!-- Qty -->
                            <div class="col-md-4">
                                <label class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="qty" id="qty"
                                       value="{{ old('qty') }}"
                                       class="form-control rounded-0 @error('qty') is-invalid @enderror"
                                       required>
                                @error('qty') <span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <!-- Gross Weight -->
                            <div class="col-md-4">
                                <label class="form-label">Gross Wt <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="gross_wt" id="gross_wt"
                                       value="{{ old('gross_wt') }}"
                                       class="form-control rounded-0 @error('gross_wt') is-invalid @enderror"
                                       required>
                                @error('gross_wt') <span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <!-- Net Weight -->
                            <div class="col-md-4">
                                <label class="form-label">Net Wt <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="net_wt" id="net_wt"
                                       value="{{ old('net_wt') }}"
                                       class="form-control rounded-0 @error('net_wt') is-invalid @enderror"
                                       required>
                                @error('net_wt') <span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <!-- Reason -->
                            <div class="col-md-6">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <select name="reason"
                                        class="form-select rounded-0 @error('reason') is-invalid @enderror"
                                        required>
                                    <option value="">Choose...</option>
                                    @foreach($reasons as $reason)
                                        <option value="{{ $reason->reason }}">{{ $reason->reason }}</option>
                                    @endforeach
                                </select>
                                @error('reason') <span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6"></div>
                            
                            <!-- Extra: Lab Charge -->
                            <div class="col-md-3">
                                <label class="form-label">Lab Charge</label>
                                <input type="text" name="rej_lab_chg"
                                       value="{{ old('rej_lab_chg') }}"
                                       class="form-control rounded-0">
                            </div>

                            <!-- Extra: Stone Charge -->
                            <div class="col-md-3">
                                <label class="form-label">Stone Charge</label>
                                <input type="text" name="rej_st_chg"
                                       value="{{ old('rej_st_chg') }}"
                                       class="form-control rounded-0">
                            </div>

                            <!-- Extra: Additional Labour -->
                            <div class="col-md-3">
                                <label class="form-label">Additional Labour Charge</label>
                                <input type="text" name="rej_add_lab"
                                       value="{{ old('rej_add_lab') }}"
                                       class="form-control rounded-0">
                            </div>

                            <!-- Submit -->
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-grd-danger px-4 rounded-0">
                                    Submit
                                </button>
                            </div>

                        </form>
                    </div>
                    {{-- FORM END --}}

                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->

@include('include.footer')
