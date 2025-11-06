@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Day Wise Report</h5>
                        <div id="fixed-social">
                            <div>
                                <a href="javasecript:void(0)">List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        {{-- Filter Form --}}
                        <form method="GET" action="{{ route('daywisereport.index') }}">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Financial Year</label>
                                    <select name="financial_year_id" class="form-select">
                                        <option value="">-- Select --</option>
                                        @foreach ($financialyears as $year)
                                            <option value="{{ $year->id }}" 
                                                {{ request('financial_year_id') == $year->id ? 'selected' : '' }}>
                                                {{ $year->applicable_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" class="form-control"
                                        value="{{ request('from_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>To Date</label>
                                    <input type="date" name="to_date" class="form-control"
                                        value="{{ request('to_date') }}">
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Search</button>
                                    @if($issuetokarigaritems->isNotEmpty())
                                        <button type="submit" name="export" value="excel" class="btn btn-success">Export Excel</button>
                                    @endif
                                </div>
                            </div>
                        </form>

                    
                    {{-- @if($issuetokarigaritems->isNotEmpty())
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Karigar</th>
                                        <th>Item Name</th>
                                        <th>Purity</th>
                                        <th>Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($issuetokarigaritems as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                            <td>{{ $item->karigar_name ?? '' }}</td>
                                            <td>{{ $item->item_name ?? '' }}</td>
                                            <td>{{ $item->purity ?? '' }}</td>
                                            <td>{{ number_format($item->weight ?? 0, 3) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif(request()->filled(['from_date', 'to_date']))
                        <p class="text-center text-danger mt-4">No records found for selected date range.</p>
                    @endif --}}


                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->


@include('include.footer')
