<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11pt;
    }
    th, td {
        border: 1px solid #000;
        padding: 4px 6px;
        text-align: center;
        vertical-align: middle;
    }
    th {
        background-color: #f3f3f3;
        font-weight: bold;
    }
    .no-border td {
        border: none;
        padding: 2px 0;
    }
</style>

<table>
    {{-- HEADER SECTION --}}
    <tr class="no-border">
        <td colspan="14" align="center">
            <strong>TRANSACTION DETAILS FROM {{ $from_date }} TO {{ $to_date }}</strong>
        </td>
    </tr>
    <tr class="no-border">
        <td colspan="7">
            <strong>Vendor:</strong> {{ $vendor_name ?? 'Bhavya Laxmi Jewellers' }}
        </td>
        <td colspan="7">
            <strong>Vendor code:</strong> {{ $vendor_code ?? '1000684' }}
        </td>
    </tr>
    <tr class="no-border"><td colspan="14"></td></tr>

    {{-- SUB-HEADINGS --}}
    <tr>
        <th colspan="7" align="center">Receipts from TCL</th>
        <th colspan="7" align="center">Issues to TCL</th>
    </tr>

    {{-- COLUMN HEADERS --}}
    <tr>
        {{-- LEFT TABLE HEADERS --}}
        <th>DATE</th>
        <th>RV NO</th>
        <th>TIL DV NO</th>
        <th>WEIGHT</th>
        <th>PURITY</th>
        <th>PURE GOLD</th>
        <th>STATUS</th>

        {{-- RIGHT TABLE HEADERS --}}
        <th>DATE</th>
        <th>MB NO</th>
        <th>WEIGHT</th>
        <th>METAL CHG%</th>
        <th>PURE GOLD</th>
        <th>REMARK</th>
        <th>REJ WSTG</th>
    </tr>

    {{-- BODY DATA --}}
    @php
        $maxRows = max(count($receipts ?? []), count($issues ?? []));
    @endphp

    @for ($i = 0; $i < $maxRows; $i++)
        <tr>
            {{-- RECEIPTS FROM TCL --}}
            <td>{{ isset($receipts[$i]) ? \Carbon\Carbon::parse($receipts[$i]->date)->format('d-m-Y') : '' }}</td>
            <td>{{ $receipts[$i]->vou_no ?? '' }}</td>
            <td>{{ $receipts[$i]->dv_no ?? '' }}</td>
            <td>{{ $receipts[$i]->weight ?? '' }}</td>
            <td>{{ $receipts[$i]->metalpurity->purity ?? '' }}</td>
            <td>
                            @if(isset($receipts[$i]) && isset($receipts[$i]->metalpurity))
                {{ number_format(($receipts[$i]->weight * $receipts[$i]->metalpurity->purity) / 100, 3) }}
            @else
                {{ '' }}
            @endif
            </td>
            <td>{{ $receipts[$i]->item_type ?? '' }}</td>

            {{-- ISSUES TO TCL --}}
            <td>{{ isset($issues[$i]) ? \Carbon\Carbon::parse($issues[$i]->date)->format('d-m-Y') : '' }}</td>
            <td>{{ $issues[$i]->mb_no ?? '' }}</td>
            <td>{{ $issues[$i]->weight ?? '' }}</td>
            <td>{{ $issues[$i]->metal_chg ?? '' }}</td>
            <td>{{ $issues[$i]->pure_gold ?? '' }}</td>
            <td>{{ $issues[$i]->remark ?? '' }}</td>
            <td>{{ $issues[$i]->rej_wstg ?? '' }}</td>
        </tr>
    @endfor
</table>