@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Finished Product PDI List</h5>
                        <div id="fixed-social">
                            <div>
                                <a href="javasecript:void(0)">List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('finishedproductpdis.index') }}" id="FinishedproductpdisForm" name="FinishedproductpdisForm" enctype="multipart/form-data">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label class="col-form-label">Purity</label>
                                </div>
                                <div class="col-md-2">
                                    <select name="purity" class="form-select">
                                        <option value="">All</option>
                                        @foreach($purities as $p)
                                            <option value="{{ $p }}" {{ $purity == $p ? 'selected' : '' }}>
                                                {{ $p }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <label class="col-form-label">Select Karigar</label>
                                </div>
                                <div class="col-md-1">
                                    <select name="kid" class="form-select">
                                        <option value="all">All</option>
                                        @forelse($karigars as $karigar)
                                            <option value="{{ $karigar->id }}">{{ $karigar->kid }} - {{ $karigar->kname }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                </div>
                            </div>
                        </form>
                        <hr style="height: 3px; border: none; background: linear-gradient(to right, #dc3545, #ffc107); margin: 30px 0;">
                        <form class="row g-3" action="{{ route('finishedproductpdis.store') }}" method="POST" id="FinishedproductpdisForm" name="finishedproductpdis" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">


                                    <div class="table-responsive-xxl">
                                        @if(Session::has('success'))
                                            <div class="alert alert-success">
                                                {{ Session::get('success') }}
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

                                        <div class="row g-3 align-items-center">
                                            <div class="col-auto">
                                                <label class="col-form-label">Location ID <span style="color: red">*</span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="location_id" class="form-select rounded-0 " onchange="GetLocationWiseVoucherNo(this.value,'finished_product_pdi_list')">
                                                    <option value="">Choose...</option>
                                                    @forelse($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>

                                            <div class="col-auto">
                                                <label class="col-form-label">Voucher No <span style="color: red">*</span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="vou_no" id="voucher_no" value="" class="form-control rounded-0 " readonly="">
                                            </div>

                                            <div class="col-auto">
                                                <label class="col-form-label">Date <span style="color: red">*</span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control rounded-0 @error('date') is-invalid @enderror">
                                            </div>
                                        </div>
                                        <hr style="height: 3px; border: none; background: linear-gradient(to right, #dc3545, #ffc107); margin: 30px 0;">
                                        
                                        <table class="table mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Jo Number</th>
                                                    <th scope="col">Item Code</th>
                                                    <th scope="col">QTY</th>
                                                    <th scope="col">UOM</th>
                                                    <th scope="col">SIZE</th>
                                                    <th scope="col">NET WT</th>
                                                    <th scope="col">PURITY</th>
                                                    <th scope="col">RATE</th>
                                                    <th scope="col">A.LAB</th>
                                                    <th scope="col">STONE CHG</th>
                                                    <th scope="col">LOSS</th>
                                                    <th scope="col"><input type="checkbox" id="SelectAllStockoutPdiList" />SELECT ALL</th>
                                                    <th scope="col">KID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 1; @endphp
                                                @forelse($qualitycheckitems as $qualitycheckitem)
                                                    <tr>
                                                        <td scope="row">{{ $count }}</td>
                                                        <td scope="row">{{ $qualitycheckitem->job_no }}</td>
                                                        <td scope="row">{{ $qualitycheckitem->item_code }}</td>
                                                        <td scope="row">{{ $qualitycheckitem->receive_qty }}</td>
                                                        <td scope="row">{{ $qualitycheckitem->uom }}</td>
                                                        <td scope="row">{{ $qualitycheckitem->size }}</td>
                                                        <td scope="row">{{ $qualitycheckitem->gross_wt  }}</td>
                                                        <td scope="row">{{ $qualitycheckitem->purity }}</td>
                                                        <td scope="row">
                                                            @php
                                                                $getRate = GetItemcodeRate($qualitycheckitem->item_code);
                                                            @endphp
                                                            {{ @$getRate->lab_charge }}
                                                        </td>
                                                        <td scope="row">
                                                            @php
                                                                $getALab = GetItemcodeAlabStoneChg($qualitycheckitem->item_code);
                                                            @endphp
                                                            @if($getALab && $getALab->category != 'Stone')
                                                                {{ $getALab->pcs * $getALab->amount  }}
                                                            @else
                                                                0.00
                                                            @endif
                                                        </td>
                                                        <td scope="row">
                                                            @php
                                                                $getAStone = GetItemcodeAlabStoneChg($qualitycheckitem->item_code);
                                                            @endphp
                                                            @if($getAStone && $getAStone->category == 'Stone')
                                                                {{ $getAStone->pcs * $getAStone->amount  }}
                                                            @else
                                                                0.00
                                                            @endif
                                                        </td>
                                                        <td scope="row">
                                                            @php
                                                                $getLoss = GetItemcodeLoss($qualitycheckitem->item_code);
                                                            @endphp
                                                            {{ @$getLoss->loss }}
                                                        </td>
                                                        <td scope="row">
                                                            <input
                                                                type="checkbox"
                                                                name="selectstockout[]"
                                                                class="stockoutpdilist"
                                                                value="{{ $qualitycheckitem->id }},{{ $qualitycheckitem->receive_qty }},{{ $qualitycheckitem->gross_wt }},{{ $qualitycheckitem->purity }}"
                                                            >
                                                        </td>
                                                        <td>
                                                            {{ optional($qualitycheckitem->karigar)->kid }}
                                                        </td>
                                                    </tr>
                                                    @php $count++; @endphp
                                                @empty
                                                    <tr class="no-records">
                                                        <td colspan="14">No record found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <div class="d-flex justify-content-end">
                                            <div class="form-row">
                                                <div class="col">
                                                    Total Qty <input type="text" name="total_qty" id="total_qty" class="form-control" value="" placeholder="Total Qty" readonly />
                                                </div>
                                                <div class="col">
                                                    Total WT <input type="text" name="total_wt" id="total_wt" class="form-control" value="" placeholder="Total WT" readonly />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <input type="submit" name="submit" value="Submit" class="btn btn-grd-danger px-4 rounded-0" />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->



@include('include.footer')
