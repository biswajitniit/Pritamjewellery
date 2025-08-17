@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="card radius-10 col-lg-10 offset-lg-1">
            <div class="card-header py-3">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-lg-6">
                        <h5 class="mb-0">Issue To Karigar View</h5>
                    </div>
                    <div class="col-12 col-lg-6 text-md-end">
                        <div class="style_back"> <a href="{{ route('issuetokarigars.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped-columns">
                        <thead>
                            <tr>
                                <th class="text-left" style="width: 10%;">JOB NO.</th>
                                <th class="text-left" style="width: 10%;">Customer Name</th>
                                <th class="text-left border-0" style="width: 70%;"></th>
                                <th class="text-rigth" style="width: 10%;">Job Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left">{{ @$issuetokarigars->customerorder->jo_no }}</td>
                                <td class="text-left">
                                    @foreach($issuetokarigars->customer as $customers)
                                        {{$customers->cust_name}} ({{$customers->cid}})
                                    @endforeach
                                </td>
                                <td class="border-0"></td>
                                <td class="text-left">{{ @$issuetokarigars->customerorder->jo_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-invoice table-bordered mt-5">
                        <thead>
                            <tr>
                                <th class="text-left">Item Code</th>
                                {{-- <th class="text-left">Design</th> --}}
                                <th class="text-left">Description</th>
                                <th class="text-left">Size</th>
                                <th class="text-left">UOM</th>
                                <th class="text-left">St. Weight</th>
                                <th class="text-left">Min Weight</th>
                                <th class="text-left">Max Weight</th>
                                <th class="text-left">Qty.</th>
                                <th class="text-left">Karigar ID</th>
                                <th class="text-left">Dly Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issuetokarigaritems as $issuetokarigaritem)

                            <tr>
                                <td class="text-right">{{ $issuetokarigaritem->item_code }}</td>
                                {{-- <td class="text-left">{{ $issuetokarigaritem->design }}</td> --}}
                                <td class="text-left">{{ $issuetokarigaritem->description }}</td>
                                <td class="text-left">{{ $issuetokarigaritem->size }}</td>
                                <td class="text-left">{{ $issuetokarigaritem->uom }}</td>
                                <td class="text-right">{{ $issuetokarigaritem->st_weight }}</td>
                                <td class="text-right">{{ $issuetokarigaritem->min_weight }}</td>
                                <td class="text-right">{{ $issuetokarigaritem->max_weight }}</td>
                                <td class="text-right">{{ $issuetokarigaritem->qty }}</td>
                                <td class="text-right">{{ $issuetokarigaritem->kid }}</td>
                                <td class="text-right">{{ $issuetokarigaritem->delivery_date ? \Carbon\Carbon::parse($issuetokarigaritem->delivery_date)->format('d/m/Y') : '' }}</td>
                            </tr>

                            @empty
                            <tr class="no-records">
                                <td colspan="5">No record found.</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->

@include('include.footer')
