@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Quality Check <div class="style_back"> <a href="{{ route('qualitychecks.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('qualitychecks.update', [$qualitychecks->id]) }}" method="POST" name="editQualityCheck">
                            @csrf
                            @method('PUT')

                            <div class="col-md-2">
                                <label class="form-label">KID <span style="color: red;">*</span></label>
                                <select name="karigar_id" onchange="return GetKarigarDetails(this.value), GetIssueToKarigarItemDetails(this.value)" class="form-select rounded-0 @error('karigar_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($karigars as $karigar)
                                    <option value="{{ $karigar->id }}">{{ $karigar->kid }}</option>
                                    @empty @endforelse
                                </select>
                                @error('karigar_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Name <span style="color: red;">*</span></label>
                                <input type="text" name="karigar_name" id="karigar_name" class="form-control rounded-0 @error('karigar_name') is-invalid @enderror" />
                                @error('karigar_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Type <span style="color: red;">*</span></label>
                                <input type="text" name="type" class="form-control rounded-0 @error('type') is-invalid @enderror" />
                                @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">QC Voucher <span style="color: red;">*</span></label>
                                <input type="text" name="qc_voucher" value="{{ @$newVoucherNo }}" class="form-control rounded-0 text-end  @error('qc_voucher') is-invalid @enderror" />
                                @error('qc_voucher')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date <span style="color: red;">*</span></label>
                                <input type="date" name="qualitycheck_date" class="form-control rounded-0 text-end @error('qualitycheck_date') is-invalid @enderror" max="{{ date('Y-m-d') }}" />
                                @error('qualitycheck_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="clearfix"></div>
                            <hr />
                            <div class="col-md-1">
                                <label class="form-label">Item Code <span style="color: red;">*</span></label>
                                <select name="item_code" id="item_code" onchange="return GetItemCodeDeatils(this.value)" class="form-select rounded-0 @error('item_code') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                </select>
                                @error('item_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Job Number </label>
                                <input type="text" name="job_no" id="job_no" class="form-control rounded-0 @error('job_no') is-invalid @enderror" readonly/>
                                @error('job_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Design No. </label>
                                <input type="text" name="design" id="design" class="form-control rounded-0 @error('design') is-invalid @enderror" readonly/>
                                @error('design')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Description </label>
                                <input type="text" name="description" id="description" class="form-control rounded-0 @error('description') is-invalid @enderror" readonly/>
                               @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Purity </label>
                                <input type="text" name="purity" id="purity" class="form-control rounded-0 text-end @error('purity') is-invalid @enderror" readonly/>
                                @error('purity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Size </label>
                                <input type="text" name="size" id="size" class="form-control rounded-0 @error('size') is-invalid @enderror" readonly/>
                                @error('size')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">UOM </label>
                                <input type="text" name="uom" id="uom" class="form-control rounded-0 text-end @error('uom') is-invalid @enderror" readonly/>
                                @error('uom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Order Qty </label>
                                <input type="text" name="order_qty" id="order_qty" class="form-control rounded-0 text-end @error('order_qty') is-invalid @enderror" readonly/>
                                @error('order_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Receive Qty </label>
                                <input type="text" name="receive_qty" id="receive_qty" class="form-control rounded-0 text-end @error('receive_qty') is-invalid @enderror" />
                                @error('receive_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Bal Qty. </label>
                                <input type="text" name="bal_qty" id="bal_qty" class="form-control rounded-0 text-end @error('bal_qty') is-invalid @enderror" readonly/>
                                @error('bal_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="clearfix"></div>
                            <hr />
                            <div class="row mb-1">
                                <div class="col-md-2">
                                    <label class="form-label">Design</label>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Solder</label>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Polish</label>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Finish</label>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Mina</label>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Other</label>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Remark</label>
                                </div>
                            </div>

                            <div id="qualitycheckitems"></div>




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
