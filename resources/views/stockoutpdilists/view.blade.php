@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="card radius-10 col-lg-10 offset-lg-1">
            <div class="card-header py-3">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-lg-6">
                        <h5 class="mb-0">PDI LIST</h5>
                    </div>
                    <div class="col-12 col-lg-6 text-md-end">
                        <div class="style_back"> <a href="{{ route('stockoutpdilists.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped-columns">
                        <thead>
                            <tr>
                                <th class="text-left" style="width: 10%;">D.C Ref No</th>
                                <th class="text-left" style="width: 10%;">Customer Name</th>
                                <th class="text-left border-0" style="width: 70%;"></th>
                                <th class="text-rigth" style="width: 10%;">D.C Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left">{{ @$stockoutpdilists->dc_ref_no }}</td>
                                <td class="text-left">
                                    @foreach($stockoutpdilists->customers as $customer)
                                        {{ $customer->cust_name }}
                                    @endforeach
                                </td>
                                <td class="border-0"></td>
                                <td class="text-left">{{ @$stockoutpdilists->dc_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-invoice table-bordered mt-5">
                        <thead>
                            <tr>
                                <th class="text-left">J.O No</th>
                                <th class="text-left">Item Code</th>
                                <th class="text-left">Size</th>
                                <th class="text-left">Qty</th>
                                <th class="text-left">G.Weight</th>
                                <th class="text-left">N.Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockoutpdilistitems as $stockoutpdilistitem)

                            <tr>
                                <td class="text-right">
                                    @foreach($stockoutpdilistitem->finishedproductpdis as $finishedproductpdi)
                                        {{ $finishedproductpdi->jo_number }}
                                    @endforeach
                                </td>
                                <td class="text-left">
                                    @foreach($stockoutpdilistitem->finishedproductpdis as $finishedproductpdi)
                                        {{ $finishedproductpdi->item_code }}
                                    @endforeach
                                </td>
                                <td class="text-left">
                                    @foreach($stockoutpdilistitem->finishedproductpdis as $finishedproductpdi)
                                        {{ $finishedproductpdi->size }}
                                    @endforeach
                                </td>
                                <td class="text-left">{{ $stockoutpdilistitem->qty }}</td>
                                <td class="text-right">{{ $stockoutpdilistitem->net_weight }}</td>
                                <td class="text-right">{{ $stockoutpdilistitem->net_weight }}</td>
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
