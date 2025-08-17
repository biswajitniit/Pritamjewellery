@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 ">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Check Uplods Items <div class="style_back"> <a href="{{ route('customerorders.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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



                    <div class="card-body p-4">

                        @php
                            $hasItemNotFound = $customerordertempitems->contains('status', 'Item Not Found');
                        @endphp

                        <form class="row g-3" action="{{ route('customerorders.store') }}" method="POST" name="saveRolePermissions">
                            @csrf


                            <div class="col-md-12">
                                <table class="table mb-0 table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Jo No</th>
                                            <th scope="col">Item Code</th>
                                            <th scope="col">Design</th>
                                            <th scope="col">Size</th>
                                            <th scope="col">Ord Qty</th>
                                            <th scope="col">StdWT</th>
                                            <th scope="col">Lab.Chg</th>
                                            <th scope="col">StoneChg</th>
                                            <th scope="col">Add.L.Chg</th>
                                            <th scope="col">Total Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {{-- @foreach($customerordertempitems as $customerordertempitem)
                                            <tr>
                                                @foreach (['jo_no','item_code', 'design_num', 'size', 'ord_qty', 'std_wt', 'lab_chg', 'stone_chg', 'add_l_chg'] as $field)
                                                    <td @if($customerordertempitem->status == 'Item Not Found') style="background-color: #ffcccc" @endif>
                                                        {{ $customerordertempitem->$field }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach --}}
                                        @php
                                            $lastJoNo = null;
                                        @endphp

                                        @foreach($customerordertempitems as $index => $item)
                                            @php
                                                $bgColor = ($item->status == 'Item Not Found') ? ' style=background-color:#ffcccc;' : '';
                                            @endphp

                                            <tr>
                                                <td {!! $bgColor !!}>{{ $item->jo_no }}</td>
                                                <td {!! $bgColor !!}>{{ $item->item_code }}</td>
                                                <td {!! $bgColor !!}>{{ $item->design_num }}</td>
                                                <td {!! $bgColor !!}>{{ $item->size }}</td>
                                                <td {!! $bgColor !!}>{{ $item->ord_qty }}</td>
                                                <td {!! $bgColor !!}>{{ $item->std_wt }}</td>
                                                <td {!! $bgColor !!}>{{ $item->lab_chg }}</td>
                                                <td {!! $bgColor !!}>{{ $item->stone_chg }}</td>
                                                <td {!! $bgColor !!}>{{ $item->add_l_chg }}</td>
                                                <td {!! $bgColor !!}>{{ $item->total_value }}</td>

                                            </tr>

                                            @php
                                                // Detect if the next row is a new JO No
                                                $nextJoNo = $customerordertempitems[$index + 1]->jo_no ?? null;

                                                // Collect all items with same JO No
                                                $joItems = $customerordertempitems->where('jo_no', $item->jo_no);

                                                // If at least one status is NOT "Found", we show delete button
                                                $showDelete = $joItems->contains(function ($itm) {
                                                    return $itm->status != 'Found';
                                                });
                                            @endphp

                                            @if ($item->jo_no !== $nextJoNo)
                                                <tr>
                                                    <td colspan="10" style="text-align: left;">
                                                        <form action="" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="jo_no" value="{{ $item->jo_no }}">
                                                            <button type="submit" class="btn btn-grd-danger px-4 rounded-0">Submit</button>

                                                            @if($showDelete)
                                                                <button type="submit" class="btn btn-grd-danger px-4 rounded-0">Delete</button>
                                                            @endif
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>


                            {{-- <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0">
                                </div>
                            </div> --}}


                            <div class="col-md-12">
                                {{-- <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="Submit" class="btn btn-grd-danger px-4 rounded-0" @if($hasItemNotFound) disabled @endif>
                                </div> --}}
                                @if($hasItemNotFound)
                                    <small class="text-danger">Cannot submit: One or more items were not found.</small>
                                @endif
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
