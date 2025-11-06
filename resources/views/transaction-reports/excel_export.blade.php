<table>
    <thead>
        {{-- Title Row --}}
        <tr>
            <th colspan="15" style="text-align: center; font-size: 16px; font-weight: bold;">
                {{ $company_name }}
            </th>
        </tr>
        {{-- Subtitle Row --}}
        <tr>
            <th colspan="15" style="text-align: center; font-size: 12px; font-weight: bold;">
                Ledger REPORT of - {{ $ledger_name }} ({{ $ledger_type }}) for {{ \Carbon\Carbon::parse($date_from)->format('d.m.Y') }} to {{ \Carbon\Carbon::parse($date_to)->format('d.m.Y') }}
            </th>
        </tr>
        {{-- Empty Row --}}
        <tr></tr>
        {{-- Main Header Row --}}
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
        {{-- Sub Header Row --}}
        <tr>
            <th>GOLD (100%)</th>
            <th>Other</th>
            <th>GOLD (100%)</th>
            <th>Others</th>
            <th>GOLD (100%)</th>
            <th>Others</th>
            <th>GOLD (100%)</th>
            <th>GOLD (100%)</th>
            <th>Others</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $index => $t)
        <tr>
            <td>{{ $index + 1 }}</td>
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
            <td colspan="15">No records found</td>
        </tr>
        @endforelse

        {{-- Empty Rows before Gold Summary --}}
        <tr></tr>
        <tr></tr>

        {{-- Gold Summary Table --}}
        @if(count($gold_summary_table) > 0)
        <tr>
            <th style="font-weight: bold; background-color: #f3f3f3;">SL NO</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">DESCRIPTION</th>
            <th colspan="2" style="font-weight: bold; background-color: #f3f3f3;">Customer</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">PURITY</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">OP. BAL</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">RECD.</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">ISSUE</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">CLG BAL.</th>
        </tr>
        @foreach($gold_summary_table as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item['description'] }}</td>
            <td colspan="2">{{ $item['customer'] }}</td>
            <td>{{ number_format($item['purity'], 3) }}</td>
            <td>{{ number_format($item['opening'], 3) }}</td>
            <td>{{ number_format($item['receive'], 3) }}</td>
            <td>{{ number_format($item['issue'], 3) }}</td>
            <td>{{ number_format($item['closing'], 3) }}</td>
        </tr>
        @endforeach
        @endif

        {{-- Empty Rows before Other Articles --}}
        <tr></tr>
        <tr></tr>

        {{-- Other Articles Section --}}
        @if(count($other_summary_table) > 0)
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: left;">Other Articles</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f3f3;">SL NO</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">DESCRIPTION</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">OP. BAL.</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">RECD. By Karigar</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">ISSUE BY Karigar</th>
            <th style="font-weight: bold; background-color: #f3f3f3;">CLG BAL.</th>
        </tr>
        @foreach($other_summary_table as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item['description'] }}</td>
            <td>{{ number_format($item['opening'], 3) }}</td>
            <td>{{ number_format($item['receive'], 3) }}</td>
            <td>{{ number_format($item['issue'], 3) }}</td>
            <td>{{ number_format($item['closing'], 3) }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
