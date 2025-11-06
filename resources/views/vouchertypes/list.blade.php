@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Voucher Type</h5>
                        <div id="fixed-social">

                            <div>
                                <a href="{{ route('vouchertypes.create') }}">ADD</a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('vouchertypes.index') }}" id="VouchertypesSearchForm" name="searchvouchertypes" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-3">
                                    @php $currentYear = date('Y'); $startYear = $currentYear; $endYear = $currentYear - 2; @endphp

                                    <select name="applicable_year" class="form-select">
                                        <option value="">Select applicable year</option>
                                        @for ($year = $startYear; $year >= $endYear; $year--) @php $nextYear = $year + 1; $label = $year . '-' . substr($nextYear, -2); @endphp
                                        <option value="{{ $label }}" @if($search == $label) selected @endif>{{ $label }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                        <a href="{{ route('vouchertypes.index') }}" class="btn btn-grd-danger px-4 rounded-0">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>

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
                                                <th><i class="fa fa-cog style_cog"></i></th>
                                                <th scope="col">#</th>
                                                <th scope="col">Financial Year</th>
                                                <th scope="col">Voucher Type</th>
                                                <th scope="col">Voucher No</th>
                                                <th scope="col">Applicable Date</th>
                                                <th scope="col">Location</th>
                                                <th scope="col">Start No</th>
                                                <th scope="col">Prefix</th>
                                                <th scope="col">Suffix</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count=1; @endphp @forelse($vouchertypes as $vouchertype)
                                            <tr>
                                                <td>
                                                    <div class="dropdown dd__">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('vouchertypes.edit',[$vouchertype->id]) }}"><i class="fa fa-pencil"></i> Edit</a>
                                                            </li>

                                                            <li>
                                                                <form action="{{ route('vouchertypes.destroy', $vouchertype->id) }}" method="POST">
                                                                    @csrf @method("DELETE")
                                                                    <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td scope="row">{{ $count }}</td>
                                                <td>{{$vouchertype->financialYear->applicable_year}}</td>
                                                <td>
                                                    @if($vouchertype->voucher_type == 'gold_receipt_entry')
                                                    Gold Receipt Entry
                                                    @elseif($vouchertype->voucher_type == 'gold_issue_entry')
                                                    Gold Issue Entry
                                                    @elseif($vouchertype->voucher_type == 'quality_check')
                                                    Quality Check
                                                    @elseif($vouchertype->voucher_type == 'finished_goods_entry')
                                                    Finished Goods Entry
                                                    @elseif($vouchertype->voucher_type == 'return_gold_from_karigar')
                                                    Return Gold From Karigar
                                                    @elseif($vouchertype->voucher_type == 'stock_transfer')
                                                    Stock Transfer
                                                    @elseif($vouchertype->voucher_type == 'finished_product_pdi_list')
                                                    Finished Product PDI List
                                                    @elseif($vouchertype->voucher_type == 'delivery_challan_no')
                                                    Delivery Challan No
                                                    @else
                                                    @endif
                                                </td>
                                                <td>{{$vouchertype->prefix}}/{{$vouchertype->startno}}/{{$vouchertype->applicable_year}}</td>
                                                <td>{{$vouchertype->applicable_date}}</td>
                                                <td>{{ $vouchertype->location->location_name }}
                                                <td>{{$vouchertype->startno}}</td>
                                                <td>{{$vouchertype->prefix}}</td>
                                                <td>{{$vouchertype->suffix}}</td>
                                                <td>
                                                    @if($vouchertype->status == 'Active')
                                                    <span style="color: green">Active</span>
                                                    @else
                                                    <span style="color: rgb(243, 10, 57)">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $count++; @endphp @empty
                                            <tr class="no-records">
                                                <td colspan="18">No record found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $vouchertypes->withQueryString()->links("pagination::bootstrap-5") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->

@include('include.footer')
