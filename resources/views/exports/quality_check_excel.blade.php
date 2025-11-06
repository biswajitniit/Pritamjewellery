<!-- resources/views/exports/quality_check_excel.blade.php -->

<table>
    <thead>
        {{-- Row 1: Title (Company Name) - BOLD --}}
        <tr>
            <th colspan="13" style="text-align: center; font-size: 14px; font-weight: bold; padding: 10px;">
                {{ $company_name }}
            </th>
        </tr>

        {{-- Row 2: Subtitle - BOLD --}}
        <tr>
            <th colspan="13" style="text-align: center; font-size: 11px; font-weight: bold; padding: 8px;">
                Job Wise Delivery Details for {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}
            </th>
        </tr>

        {{-- Row 3: Empty Row --}}
        <tr>
            <td colspan="13"></td>
        </tr>

        {{-- Row 4: Header - BOLD with gray background --}}
        <tr>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">S. NO</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">QC VOUCHER</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">DATE</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">KARIGAR NAME</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">JOB NO</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">ITEM CODE</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">DESIGN</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">SOLDER ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">POLISH ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">FINISH ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">MINA ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">OTHER ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">REMARK</td>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $item)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: left;">{{ $item->qc_voucher }}</td>
            <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->qualitycheck_date)->format('d.m.Y') }}</td>
            <td style="text-align: left;">{{ $item->karigar_name }}</td>
            <td style="text-align: right;">{{ $item->job_no }}</td>
            <td style="text-align: centre;">{{ $item->item_code }}</td>
            <td style="text-align: centre;">{{ $item->design }}</td>
            <td style="text-align: center;">{{ $item->solder_items }}</td>
            <td style="text-align: center;">{{ $item->polish_items }}</td>
            <td style="text-align: center;">{{ $item->finish_items }}</td>
            <td style="text-align: center;">{{ $item->mina_items }}</td>
            <td style="text-align: center;">{{ $item->other_items }}</td>
            <td style="text-align: left;">{{ $item->remark_items }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="13" style="text-align: center; border: 1px solid #000; padding: 10px;">No records found</td>
        </tr>
        @endforelse
    </tbody>
</table>
