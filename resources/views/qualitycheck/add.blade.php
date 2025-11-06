@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">
                            Quality Check
                            <div class="style_back">
                                <a href="{{ route('qualitychecks.index') }}">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </h5>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(Session::has('success'))
                            <div class="alert alert-success">
                            {{ Session::get('success')}}
                            </div>
                        @endif

                    </div>

                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('qualitychecks.store') }}" method="POST" name="savequalitychecks">
                            @csrf

                            <div class="col-md-2">
                                <label class="form-label">Location ID <span style="color: red;">*</span></label>
                                <select name="location_id" class="form-select rounded-0 @error('location_id') is-invalid @enderror"
                                        onchange="GetLocationWiseVoucherNo(this.value,'quality_check')">
                                    <option value="">Choose...</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->location_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
<?php /* ?>

                            <div class="col-md-2">
                                <label class="form-label">KID <span style="color: red;">*</span></label>
                                <select name="karigar_id"
                                    onchange="return GetKarigarDetails(this.value), GetIssueToKarigarItemDetails(this.value), Getjoborderno(this)"
                                    class="form-select rounded-0 @error('karigar_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @foreach($karigars as $karigar)
                                        <option value="{{ $karigar->id }}" data-job-no="{{ $karigar->job_no }}" {{ old('karigar_id') == $karigar->id ? 'selected' : '' }}>
                                            {{ $karigar->kid }} - ({{ $karigar->kname }}) - {{ $karigar->job_no }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('karigar_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div> <?php */ ?>

                            <div class="col-md-2">
                                <label class="form-label">KID <span style="color: red;">*</span></label>
                                <select name="karigar_id"
                                    onchange="GetKarigarDetails(this.value);
                                            GetIssueToKarigarItemDetails(this.value);
                                            Getjoborderno(this);
                                            recalcAllRowsForKid();"
                                    class="form-select rounded-0 @error('karigar_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @foreach($karigars as $karigar)
                                        <option value="{{ $karigar->id }}" data-job-no="{{ $karigar->job_no }}" {{ old('karigar_id') == $karigar->id ? 'selected' : '' }}>
                                            {{ $karigar->kid }} - ({{ $karigar->kname }}) - {{ $karigar->job_no }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('karigar_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Name <span style="color: red;">*</span></label>
                                <input type="text" name="karigar_name" id="karigar_name" value="{{ old('karigar_name') }}"
                                    class="form-control rounded-0 @error('karigar_name') is-invalid @enderror" />
                                @error('karigar_name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Type <span style="color: red;">*</span></label>
                                <input type="text" name="type" id="type" value="{{ old('type') }}"
                                    class="form-control rounded-0 @error('type') is-invalid @enderror" />
                                @error('type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">QC Voucher <span style="color: red;">*</span></label>
                                <input type="text" name="qc_voucher" id="voucher_no" value="{{ old('qc_voucher') }}"
                                    class="form-control rounded-0 text-end @error('qc_voucher') is-invalid @enderror" />
                                @error('qc_voucher')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Date <span style="color: red;">*</span></label>
                                <input type="date" name="qualitycheck_date"
                                    value="{{ old('qualitycheck_date', now()->format('Y-m-d')) }}"
                                    class="form-control rounded-0 text-end @error('qualitycheck_date') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}" />
                                @error('qualitycheck_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="clearfix"></div>
                            <hr />

                            <div class="col-md-2">
                                <label class="form-label">Item Code <span style="color: red;">*</span></label>
                                <select name="item_code" id="item_code"
                                        onchange="return GetItemCodeDeatils(this.value)"
                                        class="form-select rounded-0 @error('item_code') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    {{-- You may want to populate with items --}}
                                </select>
                                @error('item_code')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Job No </label>
                                <input type="text" name="job_no" id="job_no" value="{{ old('job_no') }}"
                                    class="form-control rounded-0 @error('job_no') is-invalid @enderror" readonly/>
                                @error('job_no')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Design No. </label>
                                <input type="text" name="design" id="design" value="{{ old('design') }}"
                                    class="form-control rounded-0 @error('design') is-invalid @enderror" readonly/>
                                @error('design')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Description </label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}"
                                    class="form-control rounded-0 @error('description') is-invalid @enderror" readonly/>
                                @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Purity </label>
                                <input type="text" name="purity" id="purity" value="{{ old('purity') }}"
                                    class="form-control rounded-0 text-end @error('purity') is-invalid @enderror" readonly/>
                                @error('purity')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Size </label>
                                <input type="text" name="size" id="size" value="{{ old('size') }}"
                                    class="form-control rounded-0 @error('size') is-invalid @enderror" readonly/>
                                @error('size')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">UOM </label>
                                <input type="text" name="uom" id="uom" value="{{ old('uom') }}"
                                    class="form-control rounded-0 text-end @error('uom') is-invalid @enderror" readonly/>
                                @error('uom')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Order Qty </label>
                                <input type="text" name="order_qty" id="order_qty" value="{{ old('order_qty') }}"
                                    class="form-control rounded-0 text-end @error('order_qty') is-invalid @enderror" readonly/>
                                @error('order_qty')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Receive Qty </label>
                                <input type="text" name="receive_qty" id="receive_qty" value="{{ old('receive_qty') }}"
                                    class="form-control rounded-0 text-end @error('receive_qty') is-invalid @enderror" />
                                @error('receive_qty')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Bal Qty. </label>
                                <input type="text" name="bal_qty" id="bal_qty" value="{{ old('bal_qty') }}"
                                    class="form-control rounded-0 text-end @error('bal_qty') is-invalid @enderror" readonly/>
                                @error('bal_qty')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <input type="hidden" id="netwt" name="netwt" value=""> 

                            <div class="clearfix"></div>
                            <hr />

                            <div class="row mb-1">
                                <div class="col-md-1"><label class="form-label">Gross WT</label></div>
                                <div class="col-md-1"><label class="form-label">Net WT</label></div>
                                <div class="col-md-1"><label class="form-label">Design</label></div>
                                <div class="col-md-1"><label class="form-label">Solder</label></div>
                                <div class="col-md-2"><label class="form-label">Polish</label></div>
                                <div class="col-md-2"><label class="form-label">Finish</label></div>
                                <div class="col-md-2"><label class="form-label">Mina</label></div>
                                <div class="col-md-1"><label class="form-label">Other</label></div>
                                <div class="col-md-1"><label class="form-label">Remark</label></div>
                            </div>

                            <div id="qualitycheckitems"></div>

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
