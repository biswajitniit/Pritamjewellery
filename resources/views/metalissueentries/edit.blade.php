@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Metal Issue Entry <div class="style_back"> <a href="{{ route('metalissueentries.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('metalissueentries.update', [$metalissueentries->metal_issue_entries_id]) }}" method="POST" name="editMetalreceiveentriesid">
                            @csrf
                            @method('PUT')

                            <div class="col-md-12">
                                <label class="form-label mb-2">Category <span style="color: red;">*</span></label>
                                <div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" value="Gold" class="form-check-input me-2" name="metal_category" onclick="return Get_metal_issue_category('Gold')" @if(@$metalissueentries->metal_category == "Gold") checked @endif required/>Gold </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" value="Finding" class="form-check-input me-2" name="metal_category" onclick="return Get_metal_issue_category('Finding')" @if(@$metalissueentries->metal_category == "Finding") checked @endif required/>Finding </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label"> <input type="radio" value="Alloy" class="form-check-input me-2" name="metal_category" onclick="return Get_metal_issue_category('Alloy')" @if(@$metalissueentries->metal_category == "Alloy") checked @endif required/>Alloy </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Voucher No <span style="color: red;">*</span></label>
                                <input type="text" name="voucher_no" class="form-control rounded-0 text-end" value="{{ @$metalissueentries->voucher_no }}" readonly/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date <span style="color: red;">*</span></label>
                                <input type="date" name="metal_issue_entries_date" class="form-control rounded-0" value="{{ old('metal_issue_entries_date', @$metalissueentries->metal_issue_entries_date) }}" required/>
                                @error('metal_issue_entries_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kid <span style="color: red;">*</span></label>
                                <select name="karigar_id" class="form-select rounded-0 @error('karigar_id') is-invalid @enderror" onchange="GetKarigarDetails(this.value)" required>
                                    <option value="">Choose...</option>
                                    @forelse($karigars as $karigar)
                                    <option value="{{ $karigar->id }}" @if($karigar->id == $metalissueentries->karigar_id) selected @endif >{{ $karigar->kid }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('karigar_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Karigar Name <span style="color: red;">*</span></label>
                                <input type="text" id="karigar_name" name="karigar_name" value="{{ @$metalissueentries->karigar_name }}" class="form-control rounded-0 @error('karigar_name') is-invalid @enderror" readonly required/>
                                @error('karigar_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Metal Name <span style="color: red;">*</span></label>
                                <select name="metal_id" id="metal_id" class="form-select rounded-0 @error('metal_id') is-invalid @enderror" onchange="GetMetalPurity(this.value)" required>
                                    <option value="">Choose...</option>
                                    @forelse($metals as $metal)
                                    <option value="{{ $metal->metal_id }}" @if($metal->metal_id == @$metalissueentries->metal_id) selected @endif>{{ $metal->metal_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('metal_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Source Purity <span style="color: red;">*</span></label>
                                <select name="purity_id" id="purity_id" class="form-select rounded-0 @error('purity_id') is-invalid @enderror" onchange="GetMetalPurityInfo(this.value)" required>
                                    <option value="">Choose...</option>
                                    @forelse($metalpurities as $metalpuritie)
                                    <option value="{{ $metalpuritie->purity_id }}" @if($metalpuritie->purity_id == @$metalissueentries->purity_id) selected @endif>{{ $metalpuritie->purity }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('purity_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Convert Purity <span style="color: red;">*</span></label>
                                <input type="text" name="converted_purity" id="converted_purity" class="form-control rounded-0 text-end  @error('converted_purity') is-invalid @enderror" value="{{ @$metalissueentries->converted_purity }}" readonly required/>
                                @error('converted_purity')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Weight <span style="color: red;">*</span></label>
                                <input type="text" name="weight" id="weight"  value="{{ old('weight',@$metalissueentries->weight) }}"  class="form-control rounded-0 text-end @error('weight') is-invalid @enderror" onkeyup="return GetAlloyandNetWeight()" required/>
                                @error('weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Alloy (gm) <span style="color: red;">*</span></label>
                                <input type="text" name="alloy_gm" id="alloy_gm" value="{{ old('alloy_gm',@$metalissueentries->alloy_gm) }}" class="form-control rounded-0 text-end @error('alloy_gm') is-invalid @enderror" required readonly/>
                                @error('alloy_gm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Net Weight <span style="color: red;">*</span></label>
                                <input type="text" name="netweight_gm" id="netweight_gm" value="{{ @$metalissueentries->netweight_gm }}"  class="form-control rounded-0 text-end @error('netweight_gm') is-invalid @enderror" required readonly/>
                                @error('netweight_gm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    {{-- <button type="button" class="btn btn-grd-info px-4 rounded-0">Edit</button> --}}
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
