@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Stock Effect Ledger</h5>
                    </div>
                    <div class="card-body p-4">
                        <form class="row g-3 align-items-end" action="{{ route('stockeffect.index') }}" id="StockeffectSearchForm" name="StockeffectSearchForm" method="GET">

                            <!-- 🔹 Ledger Type -->
                            <div class="col-md-3">
                                <label class="form-label d-block">Ledger Type</label>
                                @foreach (['Vendor', 'Customer', 'Karigar'] as $type)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="ledger_type"
                                            id="ledger_type_{{ $type }}"
                                            value="{{ $type }}"
                                            {{ request('ledger_type') == $type ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label" for="ledger_type_{{ $type }}">{{ $type }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 🔹 From Date -->
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" required>
                            </div>

                            <!-- 🔹 To Date -->
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" required>
                            </div>

                            <!-- 🔹 Buttons -->
                            <div class="col-md-4 d-grid d-md-flex gap-2">
                                {{-- Search Button --}}
                                <button type="submit" class="btn btn-primary rounded-0 px-4">
                                    <i class="fa fa-search"></i> Search
                                </button>

                                {{-- Reset Button --}}
                                <a href="{{ route('stockeffect.index') }}"
                                class="btn btn-secondary px-4 rounded-0">
                                <i class="fa fa-undo"></i> Reset
                                </a>

                                {{-- Excel Export Button (only shown if report exists) --}}
                                @if(isset($report) && $report->count() > 0)
                                    <a href="{{ route('stockeffect.index', array_merge(request()->query(), ['export' => 1])) }}"
                                    class="btn btn-success rounded-0 px-4">
                                    <i class="fa fa-file-excel"></i> Export
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        @if(isset($report) && $report->count() > 0)
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Voucher No</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Ledger Name</th>
                                        <th>Ledger Code</th>
                                        <th>Ledger Type</th>
                                        <th>Metal Category</th>
                                        <th>Metal Name</th>
                                        <th>Net Wt / Qty</th>
                                        <th>Purity</th>
                                        <th>Pure Wt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report as $row)
                                        <tr>
                                            <td>{{ $row->vou_no }}</td>
                                            <td>{{ $row->metal_receive_entries_date }}</td>
                                            <td>{{ $row->location_name }}</td>
                                            <td>{{ $row->ledger_name }}</td>
                                            <td>{{ $row->ledger_code }}</td>
                                            <td>{{ $row->ledger_type }}</td>
                                            <td>{{ $row->metal_category }}</td>
                                            <td>{{ $row->metal_name }}</td>
                                            <td>{{ $row->net_wt }}</td>
                                            <td>{{ $row->purity }}</td>
                                            <td>{{ $row->pure_wt }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @elseif(request()->has('from_date'))
                            <div class="alert alert-warning">No records found for the selected filters.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->


@include('include.footer')
