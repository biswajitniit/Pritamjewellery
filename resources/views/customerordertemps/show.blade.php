@include('include.header')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 ">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Check Uplods Items
                            <div class="style_back">
                                <a href="{{ route('customerorders.index') }}">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </h5>
                    </div>

                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-danger">
                       The Karigar ID XX Won't be processed in Order, Please edit Karigar ID in Product Master.
                    </div>

                    <form action="{{ route('savetempproducts')}}" name="savetempproducts" method="POST">
                        @csrf
                        <div class="d-md-flex d-grid align-items-center gap-3 mt-10">
                            <button type="submit" class="btn btn-grd-info px-4 rounded-0 mt-2 ms-4">Processing</button>
                        </div>
                    </form>

                    <div class="card-body p-4 mt-10">
                        @php
                            $hasItemNotFound = $customerordertempitems->contains('status', 'Item Not Found');
                        @endphp

                        <div class="col-md-12">
                            <table class="table mb-0 table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Jo No</th>
                                        <th scope="col">Item Code</th>
                                        <th scope="col">KID</th>
                                        <th scope="col">Design</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Ord Qty</th>
                                        <th scope="col">StdWT</th>
                                        <th scope="col">Lab.Chg</th>
                                        <th scope="col">StoneChg</th>
                                        <th scope="col">Add.L.Chg</th>
                                        <th scope="col">Total Value</th>
                                        <th scope="col">Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $lastJoNo = null; @endphp

                                    @foreach($customerordertempitems as $index => $item)
                                        @php
                                            $isRed = ($item->kid == 'XX' || $item->status == 'Item Not Found');
                                            $bgColor = $isRed ? ' style=background-color:#ffcccc;' : '';
                                        @endphp

                                        <tr>
                                            <td {!! $bgColor !!}>{{ $item->jo_no }}</td>
                                            <td {!! $bgColor !!}>{{ $item->item_code }}</td>
                                            <td {!! $bgColor !!}>{{ $item->kid }}</td>
                                            <td {!! $bgColor !!}>{{ $item->design }}</td>
                                            <td {!! $bgColor !!}>{{ $item->size }}</td>
                                            <td {!! $bgColor !!}>{{ $item->ord_qty }}</td>
                                            <td {!! $bgColor !!}>{{ $item->std_wt }}</td>
                                            <td {!! $bgColor !!}>{{ $item->lab_chg }}</td>
                                            <td {!! $bgColor !!}>{{ $item->stone_chg }}</td>
                                            <td {!! $bgColor !!}>{{ $item->add_l_chg }}</td>
                                            <td {!! $bgColor !!}>{{ $item->total_value }}</td>
                                            <td {!! $bgColor !!}>{{ $item->type }}</td>
                                        </tr>

                                        @php
                                            $nextJoNo = $customerordertempitems[$index + 1]->jo_no ?? null;

                                            $joItems = $customerordertempitems->where('jo_no', $item->jo_no);

                                            $showDelete = $joItems->contains(function ($itm) {
                                                return $itm->status == 'Item Not Found' && in_array($itm->type, ['Regular', 'Customer']);
                                            });

                                            $disableSubmit = $joItems->contains(function ($itm) {
                                                return $itm->kid == 'XX' || $itm->status == 'Item Not Found';
                                            });
                                        @endphp

                                        @if ($item->jo_no !== $nextJoNo)
                                            <tr>
                                                <td colspan="12">
                                                    <div class="row g-2">
                                                        <div class="col-auto">
                                                            <form action="{{ route('customerorders.store') }}" method="POST" name="saveCustomerorders">
                                                                @csrf
                                                                <input type="hidden" name="jo_no" value="{{ $item->jo_no }}">
                                                                <button type="submit" class="btn btn-grd-danger px-4 rounded-0" {{ $disableSubmit ? 'disabled' : '' }}>
                                                                    Submit
                                                                </button>
                                                            </form>
                                                        </div>

                                                        @if($showDelete)
                                                            <div class="col-auto">
                                                                <form action="{{ route('customerordertemps.delete', $item->jo_no) }}" method="POST" name="DeleteOrder">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-grd-danger px-4 rounded-0">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->

@include('include.footer')
