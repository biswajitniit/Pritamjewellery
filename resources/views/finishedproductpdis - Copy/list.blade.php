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
                                <div class="col-md-1">
                                    <input type="text" name="purity" value="91.6" class="form-control" />
                                </div>

                                <div class="col-auto">
                                    <label class="col-form-label">Filter By</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="job_no" placeholder="Job No" value="" class="form-control" />
                                </div>

                                <div class="col-auto">
                                    <label class="col-form-label">Select Karigar</label>
                                </div>
                                <div class="col-md-1">
                                    <select name="kid" class="form-select">
                                        <option value="all">All</option>
                                        @forelse($karigars as $karigar)
                                        <option value="{{ $karigar->id }}">{{ $karigar->kid }}</option>
                                        @empty @endforelse
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                </div>
                            </div>
                        </form>

                        <form class="row g-3" action="{{ route('finishedproductpdis.store') }}" method="POST" id="FinishedproductpdisForm" name="finishedproductpdis" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive-xxl">
                                        @if(Session::has('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success')}}
                                        </div>
                                        @endif

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
                                                @php
                                                $count=1;
                                                @endphp @forelse($qualitycheckitems as $qualitycheckitem)
                                                <tr>
                                                    <td scope="row">{{ $count }}</td>
                                                    <td scope="row">{{ $qualitycheckitem->job_no }}</td>
                                                    <td scope="row">{{ $qualitycheckitem->item_code }}</td>
                                                    <td scope="row">{{ $qualitycheckitem->receive_qty }}</td>
                                                    <td scope="row">{{ $qualitycheckitem->uom }}</td>
                                                    <td scope="row">{{ $qualitycheckitem->size }}</td>
                                                    <td scope="row">{{ $qualitycheckitem->net_wt }}</td>
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
                                                        {{-- {{ $qualitycheckitem->loss }} --}}
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
                                                        value="{{ $qualitycheckitem->receive_qty }},{{ $qualitycheckitem->net_wt }}"
                                                        >
                                                    </td>
                                                    <td>
                                                        @foreach($qualitycheckitem->karigar as $karigars) {{ $karigars->kid }} @endforeach
                                                    </td>
                                                </tr>
                                                @php
                                                $count++;
                                                @endphp
                                                @empty
                                                <tr class="no-records">
                                                    <td colspan="14">No record found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>


                                        <div class="d-flex justify-content-end">
                                            <div class="form-row">
                                                <div class="col">Total Qty <input type="text" id="total_qty" class="form-control" value="" placeholder="Total Qty" readonly /></div>
                                                <div class="col">Total WT <input type="text" id="total_wt" class="form-control" value="" placeholder="Total WT" readonly /></div>
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
