<table>
    <thead>
        {{-- Subtitle Row (Row 1) - BOLD --}}
        <tr>
            <th colspan="9" style="text-align: center; font-size: 14px; font-weight: bold; padding: 10px;">
                {{ $company_name }}
            </th>
        </tr>

        <tr>
            <th colspan="9" style="text-align: center; font-size: 11px; font-weight: bold; padding: 8px;">
                Item Code Wise Details - Karigar for {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}
            </th>
        </tr>
        {{-- Empty Row (Row 2) --}}
        <tr>
            <td colspan="9"></td>
        </tr>
        {{-- Main Header Row (Row 3) - BOLD with gray background --}}
        <tr>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">S. NO</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">JOB NO</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">ITEM CODE</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">MBILL NO</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">MBDATE</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">KARIGAR NAME</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">GROSS WT</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">STONE WT</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">NET WT</td>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $item)
        <tr>
            <td style="text-align: center; border: 1px solid #000; padding: 6px;">{{ $index + 1 }}</td>
            <td style="text-align: left; border: 1px solid #000; padding: 6px;">{{ $item->job_no }}</td>
            <td style="text-align: left; border: 1px solid #000; padding: 6px;">{{ $item->item_code }}</td>
            <td style="text-align: left; border: 1px solid #000; padding: 6px;">{{ $item->voucher_no }}</td>
            <td style="text-align: center; border: 1px solid #000; padding: 6px;">{{ \Carbon\Carbon::parse($item->voucher_date)->format('d.m.Y') }}</td>
            <td style="text-align: left; border: 1px solid #000; padding: 6px;">{{ $item->karigar_name }}</td>
            <td style="text-align: right; border: 1px solid #000; padding: 6px;">{{ number_format($item->gross_wt, 3) }}</td>
            <td style="text-align: right; border: 1px solid #000; padding: 6px;">{{ number_format($item->st_weight, 3) }}</td>
            <td style="text-align: right; border: 1px solid #000; padding: 6px;">{{ number_format($item->net, 3) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align: center; border: 1px solid #000; padding: 10px;">No records found</td>
        </tr>
        @endforelse
    </tbody>
</table>
