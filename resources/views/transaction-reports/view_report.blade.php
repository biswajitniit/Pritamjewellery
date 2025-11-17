@include('include.header')

<style>
	.report-container {
		background: #fff;
		padding: 30px;
		font-size: 12px;
		font-family: "Arial", sans-serif;
	}
	table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 25px;
	}
	th,
	td {
		border: 1px solid #000;
		padding: 4px 6px;
		text-align: center;
		font-size: 11px;
	}
	th {
		background-color: #f3f3f3;
		font-weight: bold;
	}
	h3,
	h5 {
		text-align: center;
		margin: 5px 0;
		font-weight: bold;
	}
	h3 {
		font-size: 18px;
	}
	h5 {
		font-size: 14px;
	}
	.section-title {
		font-weight: bold;
		margin: 20px 0 5px;
		background-color: #fff;
		padding: 5px 0;
		font-size: 13px;
	}
	.text-right {
		text-align: right;
	}
	.text-left {
		text-align: left;
	}
	@media print {
		.no-print {
			display: none;
		}
	}
</style>

<main class="main-wrapper">
	<div class="main-content report-container mb-3">

		<div class="text-center mt-3 no-print">
			<a href="{{ route('new-report') }}" class="btn btn-secondary">Back</a>

			<form method="POST" action="{{ route('export.ledger.excel') }}" style="display: inline;">
				@csrf
				<input type="hidden" name="ledger_type" value="{{ $ledger_type }}">
				<input type="hidden" name="ledger_name" value="{{ $ledger_name }}">
				<input type="hidden" name="date_from" value="{{ $date_from }}">
				<input type="hidden" name="date_to" value="{{ $date_to }}">
				<button type="submit" class="btn btn-success">
					<i class="fa fa-file-excel"></i>
					Export to Excel
				</button>
			</form>

			<button class="btn btn-danger" onclick="window.print()">Print Report</button>
		</div>

        <div class="no-print mb-3">
            <form method="GET" action="{{ route('transaction.report.generate') }}" class="form-inline">
                <input type="hidden" name="ledger_type" value="{{ $ledger_type }}">
                <input type="hidden" name="ledger_name" value="{{ $ledger_name }}">
                <input type="hidden" name="date_from" value="{{ $date_from }}">
                <input type="hidden" name="date_to" value="{{ $date_to }}">

                <label for="vou_no" class="mr-2"><strong>Filter by Voucher No:</strong></label>
                <input type="text" name="vou_no" id="vou_no" value="{{ request('vou_no') }}"
                    class="form-control mr-2" placeholder="Enter Voucher No">

                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('transaction.report.generate', [
                    'ledger_type' => $ledger_type,
                    'ledger_name' => $ledger_name,
                    'date_from' => $date_from,
                    'date_to' => $date_to
                ]) }}" class="btn btn-secondary ml-2">Reset</a>
            </form>
        </div>


		<h3>{{ $company_name }}</h3>
		<h5>Ledger REPORT of -
			{{ $ledger_name }}
			({{ $ledger_type }})
						      for
			{{ \Carbon\Carbon::parse($date_from)->format('d.m.Y') }}
			to
			{{ \Carbon\Carbon::parse($date_to)->format('d.m.Y') }}
		</h5>

		<br>

		<!-- MAIN LEDGER TABLE -->
		<table>
			<thead>
				<tr>
					<th rowspan="2">SL NO</th>
					<th rowspan="2">Voucher No</th>
					<th rowspan="2">Date</th>
					<th rowspan="2">Issued Product</th>
					<th rowspan="2">Purity</th>
					<th rowspan="2">Wt/pcs</th>
					<th colspan="2">Opening</th>
					<th colspan="2">Issue</th>
					<th colspan="2">Receive</th>
					<th colspan="1">Loss</th>
					<th colspan="2">Closing</th>
				</tr>
				<tr>
					<th>GOLD<br>(100%)</th>
					<th>Other</th>
					<th>GOLD<br>(100%)</th>
					<th>Others</th>
					<th>GOLD<br>(100%)</th>
					<th>Others</th>
					<th>GOLD<br>(100%)</th>
					<th>GOLD<br>(100%)</th>
					<th>Others</th>
				</tr>
			</thead>
			<tbody>
				@php
                    $current_group = null;
                    $group_start = 0;
                @endphp

								        @forelse($transactions as $index => $t)
                                                                                    @php
                                                                                        // Group by voucher number
                                                                                        $vou_group = $t['vou_no'] . '_' . $t['date'];
                                                                                        if ($current_group !== $vou_group) {
                                                                                            $current_group = $vou_group;
                                                                                            $group_start = $index;
                                                                                        }
                                                                                        $is_first_in_group = ($index === $group_start);
                                                                                    @endphp

                                            <tr>
                                                @if($is_first_in_group)
                                                                <td rowspan="{{ collect($transactions)->filter(function ($item) use ($t) {
                                                        return $item['vou_no'] === $t['vou_no'] && $item['date'] === $t['date'];
                                                    })->count() }}">{{ $index + 1 }}</td>
                                                @endif

                                                <td>{{ $t['vou_no'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($t['date'])->format('d.m.Y') }}</td>
                                                <td>{{ $t['metal_name'] }}</td>
                                                <td>{{ $t['purity'] ? number_format($t['purity'], 1) : '' }}</td>
                                                <td>{{ number_format($t['wt_pcs'], 3) }}</td>
                                                <td>{{ $t['opening_gold'] > 0 ? number_format($t['opening_gold'], 3) : '' }}</td>
                                                <td>{{ $t['opening_other'] > 0 ? number_format($t['opening_other'], 3) : '' }}</td>
                                                <td>{{ $t['issue_gold'] > 0 ? number_format($t['issue_gold'], 3) : '' }}</td>
                                                <td>{{ $t['issue_other'] > 0 ? number_format($t['issue_other'], 3) : '' }}</td>
                                                <td>{{ $t['receive_gold'] > 0 ? number_format($t['receive_gold'], 3) : '' }}</td>
                                                <td>{{ $t['receive_other'] > 0 ? number_format($t['receive_other'], 3) : '' }}</td>
                                                <td>{{ $t['loss_gold'] > 0 ? number_format($t['loss_gold'], 3) : '' }}</td>
                                                <td>{{ number_format($t['closing_gold'], 3) }}</td>
                                                <td>{{ number_format($t['closing_other'], 3) }}</td>
                                            </tr>
                                        @empty
                    <tr>
                        <td colspan="15">No records found for the selected period.</td>
                    </tr>
                @endforelse
			</tbody>
		</table>

		<!-- GOLD SUMMARY TABLE -->
		@if(count($gold_summary_table) > 0)
            <table>
                <thead>
                    <tr>
                        <th>SL NO</th>
                        <th>DESCRIPTION</th>
                        <th colspan="2">Customer</th>
                        <th>PURITY</th>
                        <th>OP. BAL</th>
                        <th>RECD.</th>
                        <th>ISSUE</th>
                        <th>CLG BAL.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gold_summary_table as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $item['description'] }}</td>
                            <td colspan="2">{{ $item['customer'] }}</td>
                            <td>{{ $item['purity'] }}</td>
                            <td>{{ $item['opening'] }}</td>
                            <td>{{ $item['receive'] }}</td>
                            <td>{{ $item['issue'] }}</td>
                            <td>{{ $item['closing'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
		@if(count($other_summary_table) > 0)
                <div class="section-title"> Other articles</div>
            <table>
                <thead>
                    <tr>
                        <th>SL NO</th>
                        <th>DESCRIPTION</th>
                        <th>OP. BAL.</th>
                        <th>RECD. By Karigar</th>
                        <th>ISSUE BY Karigar</th>
                        <th>CLG BAL.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($other_summary_table as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $item['description'] }}</td>
                            <td>{{ $item['opening'] }}</td>
                            <td>{{ $item['receive'] }}</td>
                            <td>{{ $item['issue'] }}</td>
                            <td>{{ $item['closing'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

		<div class="text-center mt-3 no-print">
			<a href="{{ route('new-report') }}" class="btn btn-secondary">Back</a>
			<button class="btn btn-danger" onclick="window.print()">Print Report</button>
		</div>

	</div>
</main>

@include('include.footer')

