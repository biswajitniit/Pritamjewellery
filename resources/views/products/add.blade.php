@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2 prod-master">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">
                            Add Product
                            <div class="style_back">
                                <a href="{{ route('products.index') }}"><i class="fa fa-chevron-left"></i> Back </a>
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
                        <form class="row g-3" action="{{ route('products.store') }}" method="POST" name="saveProducts" enctype="multipart/form-data">
                            @csrf
                            <h5>Product Details</h5>
                            <hr />

                            <div class="col-md-3">
                                <label class="form-label">Company <span style="color: red;">*</span></label>
                                <select name="company_id" class="form-select rounded-0 @error('company_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->cust_name }} ({{ $customer->cid }})</option>
                                    @empty @endforelse
                                </select>
                                @error('pattern')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Vendor Site <span style="color: red;">*</span></label>
                                <input type="text" name="vendorsite" id="vendorsite" value="{{ old('vendorsite') }}" class="form-control rounded-0 @error('vendorsite') is-invalid @enderror" required/>
                                @error('vendorsite')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Item Code <span style="color: red;">*</span></label>
                                <input type="text" name="item_code" id="item_code" value="{{ old('item_code') }}" class="form-control rounded-0 @error('item_code') is-invalid @enderror" required/>
                                @error('item_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Design Number <span style="color: red;">*</span></label>
                                <input type="text" name="design_num" id="design_num_product" value="{{ old('design_num') }}" class="form-control rounded-0 @error('design_num') is-invalid @enderror" />
                                @error('design_num')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Description <span style="color: red;">*</span></label>
                                <input type="text" name="description" id="product_description" value="{{ old('description') }}" class="form-control rounded-0 @error('description') is-invalid @enderror" />
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <?php /* ?>
                            <div class="col-md-2">
                                <label class="form-label">Patterns <span style="color: red;">*</span></label>
                                <select name="pattern_id" class="form-select rounded-0 @error('pattern_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($patterns as $pattern)
                                    <option value="{{ $pattern->id }}">{{ $pattern->pat_desc }}</option>
                                    @empty @endforelse
                                </select>
                                @error('pattern_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <?php */ ?>

                            <div class="col-md-2">
                                <label class="form-label">Pcode <span style="color: red;">*</span></label>
                                <select name="pcode_id" class="form-select rounded-0 @error('pcode_id') is-invalid @enderror" onchange="GetSizePcodeWise(this.value)">
                                    <option value="">Choose...</option>
                                    @forelse($pcodes as $pcode)
                                    <option value="{{ $pcode->id }}">{{ $pcode->description }}</option>
                                    @empty @endforelse
                                </select>
                                @error('pcode_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Size <span style="color: red;">*</span></label>
                                <select name="size_id" id="size_id" class="form-select rounded-0 @error('size_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                </select>
                            </div>



                            <div class="col-md-2">
                                <label class="form-label">UOM <span style="color: red;">*</span></label>
                                <select name="uom_id" class="form-select rounded-0 @error('uom_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($uoms as $uom)
                                    <option value="{{ $uom->id }}">{{ $uom->uomid }}</option>
                                    @empty @endforelse
                                </select>
                                @error('uom_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Standard WT</label>
                                <input type="text" name="standard_wt" value="{{ old('standard_wt') }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">KID <span style="color: red;">*</span></label>
                                <select name="kid" class="form-select rounded-0 @error('kid') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($karigars as $karigar)
                                    <option value="{{ $karigar->id }}">{{ $karigar->kid }} ({{ $karigar->kname }})</option>
                                    @empty @endforelse
                                </select>
                                @error('kid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Lead Time (Karigar) <span style="color: red;">*</span></label>
                                <input type="text" name="lead_time_karigar" value="{{ old('lead_time_karigar') }}" class="form-control rounded-0" required/>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Product Lead Time <span style="color: red;">*</span></label>
                                <input type="text" name="product_lead_time" value="{{ old('product_lead_time') }}" class="form-control rounded-0" required/>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Stone Charge</label>
                                <input type="text" name="stone_charge" value="{{ old('stone_charge') }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Lab Charge</label>
                                <input type="text" name="lab_charge" value="{{ old('lab_charge') }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Additional Lab Charges</label>
                                <input type="text" name="additional_lab_charges" value="{{ old('additional_lab_charges') }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Loss %</label>
                                <input type="text" name="loss" value="{{ old('loss') }}" class="form-control rounded-0" />
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Purity</label>
                                <input type="text" name="purity" value="{{ old('purity') }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Item picture upload <span style="color: red;">*</span></label>
                                <input type="file" name="item_pic" class="form-control rounded-0 @error('item_pic') is-invalid @enderror" accept="image/*"/>
                                @error('item_pic')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">KT <span style="color: red;">*</span></label>
                                <input type="kt" name="kt" class="form-control rounded-0 @error('kt') is-invalid @enderror" />
                                @error('kt')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <hr />
                            <h5 class="mt-1">Additional Charge Details</h5>
                            <hr />

                            <div class="col-md-2">
                                <label class="form-label">Additional Type</label>
                                <select name="stone_type[]" id="stone_type_1" class="form-select rounded-0">
                                    <option value="">Choose...</option>
                                    @forelse($stones as $stone)
                                    <option value="{{ $stone->id }}">{{ $stone->description }}</option>
                                    @empty @endforelse
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Category</label>
                                <select name="category[]" id="category_1" class="form-select rounded-0">
                                    <option value="">Choose...</option>
                                    <option value="Stone">Stone</option>
                                    <option value="Miscellaneous">Miscellaneous</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">PCS</label>
                                <input type="text" name="pcs[]" id="pcs_1" value="" class="form-control rounded-0 stone_pcs" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Weight</label>
                                <input type="text" name="weight[]" id="weight_1" value="" class="form-control rounded-0 stone_weight" />
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Rate</label>
                                <input type="text" name="rate[]" id="rate_1" value="" class="form-control rounded-0 stone_rate" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Amount</label>
                                <input type="text" name="amount[]" id="amount_1" value="" class="form-control rounded-0 stone_amount" />
                            </div>

                            <div class="newitemsproductstone"></div>
                            <div class="col-md-12">
                                <span class="btn btn-grd-info px-4 rounded-0 pull-right add_more_product_stone_items"><i class="fa fa-plus-circle"></i> ADD</span>
                            </div>

                            <hr class="mt-5 mb-1" />

                            <div class="col-md-12">
                                <label class="form-label">Remarks </label>
                                <input type="text" name="remarks" value="{{ old('remarks') }}" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0" />
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
