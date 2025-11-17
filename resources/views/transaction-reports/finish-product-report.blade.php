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

    th, td {
        border: 1px solid #000;
        padding: 8px 6px;
        text-align: center;
        font-size: 11px;
    }

    th {
        background-color: #f3f3f3;
        font-weight: bold;
        color: #000;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .header-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .header-section h2 {
        margin: 5px 0;
        font-size: 16px;
        font-weight: bold;
    }

    .header-section p {
        margin: 3px 0;
        font-size: 12px;
    }

    .date-info {
        text-align: right;
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 12px;
    }

    .action-buttons {
        text-align: center;
        margin: 20px 0;
        no-print: true;
    }

    .action-buttons button, .action-buttons a {
        margin: 0 5px;
        padding: 8px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 12px;
    }

    .action-buttons .btn-success {
        background-color: #28a745;
    }

    .action-buttons .btn-danger {
        background-color: #dc3545;
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #666;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    @media print {
        .action-buttons {
            display: none;
        }
        body {
            margin: 0;
            padding: 0;
        }
    }
</style>

<div class="report-container">
    <div class="action-buttons">
        <button class="btn-success" onclick="exportToExcel()">
            <i class="fa fa-file-excel"></i> Export to Excel
        </button>
        <button class="btn-danger" onclick="window.print()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>

    <div class="date-info">
        Date: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
    </div>

    <div class="header-section">
        <h2>Item Code Wise Details - Karigar</h2>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</p>
    </div>

    <div class="action-buttons">
        <form method="GET" action="">
            <input type="date" name="date" value="{{ $date }}" hidden required>

            <select name="karigar_name"
                style="padding:6px; border:1px solid #ccc; border-radius:4px;">
                <option value="">-- Select Karigar --</option>

                @foreach($karigarList as $name)
                    <option value="{{ $name }}"
                        {{ ($karigar_name == $name) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-success">
                <i class="fa fa-search"></i> Search
            </button>
        </form>
    </div>

    @if($data->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>S. NO</th>
                    <th>JOB NO</th>
                    <th>ITEM CODE</th>
                    <th>KARIGAR NAME</th>
                    <th>GROSS WT</th>
                    <th>STONE WT</th>
                    <th>NET WT</th>
                    <th>MBILL NO</th>
                    <th>MBDATE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">{{ $item->job_no }}</td>
                        <td class="text-left">{{ $item->item_code }}</td>
                        <td class="text-left">{{ $item->karigar_name }}</td>
                        <td class="text-right">{{ number_format($item->gross_wt, 3) }}</td>
                        <td class="text-right">{{ number_format($item->st_weight, 3) }}</td>
                        <td class="text-right">{{ number_format($item->net, 3) }}</td>
                        <td class="text-left">{{ $item->voucher_no }}</td>
                        <td class="text-centre">{{ \Carbon\Carbon::parse($item->voucher_date)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No records found for the selected date.</p>
        </div>
    @endif

    <div class="action-buttons" style="margin-top: 30px;">
        <a href="{{ route('karigar.itemcodes') }}" class="btn btn-secondary">Back</a>
        <form method="POST" action="{{ route('karigar.itemcodes.export') }}" style="display: inline;">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <button type="submit" class="btn-success">
                <i class="fa fa-file-excel"></i> Export to Excel
            </button>
        </form>
        <button class="btn-danger" onclick="window.print()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>

</div>

@include('include.footer')

