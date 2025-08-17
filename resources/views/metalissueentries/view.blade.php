<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gold Issue Voucher</title>
            <style>
            {!! file_get_contents(public_path('assets/css/pace.min.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/metismenu/metisMenu.min.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/metismenu/mm-vertical.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/simplebar/css/simplebar.css')) !!}
            {!! file_get_contents(public_path('assets/css/font.awesome.css')) !!}
            {!! file_get_contents(public_path('assets/css/bootstrap.min.css')) !!}
            {!! file_get_contents(public_path('assets/css/pdf.css')) !!}
            {!! file_get_contents(public_path('assets/css/bootstrap-extended.css')) !!}
            {!! file_get_contents(public_path('assets/css/horizontal-menu.css')) !!}
            /* {!! file_get_contents(public_path('sass/bordered-theme.css')) !!} */
            {!! file_get_contents(public_path('sass/responsive.css')) !!}
            {!! file_get_contents(public_path('sass/main.css')) !!}
        </style>
        <style>
         body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .container {
            width: 100%;
            padding: 15px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .header-table td {
            padding: 5px 0;
        }
        .header-title {
            font-size: 22px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #ddd;
            padding: 5px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .info-table td {
            padding: 4px 6px;
            border: 1px solid #ddd;
        }
        .no-border td {
            border: none !important;
        }
        h5 {
            margin: 10px 0;
            font-size: 13px;
        }

    </style>
</head>
<body>
    <div class="container">


        <div class="subtitle">SHREE GANESHAYA NAMAH</div>
        <div class="subtitle">Gold Issue to Karigar Voucher</div>

        <table class="info-table" style="margin-top:10px;">
            <tr>
                <td width="50%">
                    <strong>Name:</strong> {{ $karigars->kname }}<br>
                    <strong>Address:</strong> {{ $karigars->address }}<br>
                    <strong>State Code:</strong> {{ $karigars->statecode }}<br>
                    <strong>GSTIN:</strong> {{ $karigars->gstin }}
                </td>
                <td width="50%">
                    <strong>Voucher No.:</strong> {{ $metalissueentries->voucher_no }}<br>
                    <strong>Voucher Date:</strong> {{ date('d/m/Y', strtotime($metalissueentries->metal_issue_entries_date)) }}<br>
                    <strong>GSTIN:</strong> 19AHVPS9192D1ZE<br>
                    <strong>State Code:</strong> 19
                </td>
            </tr>
        </table>

        <table class="table-bordered" style="margin-top:15px;">
            <thead>
                <tr class="text-center">
                    <th>Description</th>
                    <th>HSN</th>
                    <th>Purity</th>
                    <th>Add Alloy</th>
                    <th>Gross Weight (in Grams)</th>
                    <th>Net Weight (in Grams)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $metalissueentries->metal_category }}</td>
                    <td>{{ $metals->metal_hsn }}</td>
                    <td>{{ $metalpurities->purity }}</td>
                    <td>{{ $metalissueentries->alloy_gm }}</td>
                    <td>{{ $metalissueentries->weight }}</td>
                    <td>{{ $metalissueentries->netweight_gm }}</td>
                </tr>
            </tbody>
        </table>

        <h5>ISSUED FOR JOBWORK SAC: {{ $metals->metal_sac }}, HSN FOR ALLOY-7407/7106</h5>

        <table class="no-border" style="margin-top:20px; width:100%;">
            <tr>
                <td>Received By :</td>
                <td class="text-center">All Subject to Kolkata Jurisdiction</td>
                <td class="text-right">For Bhagya Laxmi Jewellers</td>
            </tr>
        </table>
    </div>
</body>
</html>
