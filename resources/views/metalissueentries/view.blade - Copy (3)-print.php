<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gold Issue to Karigar Voucher</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header strong {
            font-size: 18px;
        }
        .address {
            text-align: center;
            font-size: 13px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-border td {
            border: none !important;
        }
        .footer-table td {
            border: none;
            font-size: 11px;
        }
        h5 {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <strong>SHREE GANESHAYA NAMAH</strong><br>
            <strong>Gold Issue to Karigar Voucher</strong>
        </div>

        <table class="no-border">
            <tr>
                <td>
                    <strong>Name : </strong>{{ $karigars->kname }} <br>
                    <strong>Address : </strong>{{ @$karigars->address }} <br>
                    <strong>State Code : </strong>{{ @$karigars->statecode }} <br>
                    <strong>GSTIN : </strong>{{ @$karigars->gstin }}
                </td>
                <td>
                    <strong>Voucher No. : </strong>{{ @$metalissueentries->voucher_no }} <br>
                    <strong>Voucher Date : </strong>{{ date('d/m/Y',strtotime(@$metalissueentries->metal_issue_entries_date)) }} <br>
                    <strong>GSTIN : </strong> 19AHVPS9192D1ZE <br>
                    <strong>State Code : </strong> 19
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>HSN</th>
                    <th>Purity</th>
                    <th>Add Alloy</th>
                    <th>Gross Weight (in Grams)</th>
                    <th>Net Weight (in Grams)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><u>{{ $metalissueentries->metal_category }}</u></strong></td>
                    <td>{{ @$metals->metal_hsn }}</td>
                    <td>{{ @$metalpurities->purity }}</td>
                    <td>{{ @$metalissueentries->alloy_gm }}</td>
                    <td>{{ @$metalissueentries->weight }}</td>
                    <td>{{ @$metalissueentries->netweight_gm }}</td>
                </tr>
            </tbody>
        </table>

        <h5>ISSUED FOR JOBWORK SAC: {{ @$metals->metal_sac }}, HSN FOR ALLOY - 7407/7106</h5>

        <table class="footer-table">
            <tr>
                <td>Received By :</td>
                <td style="text-align: center;">All Subject to Kolkata Jurisdiction</td>
                <td style="text-align: right;">For Bhagya Laxmi Jewellers</td>
            </tr>
        </table>
    </div>
</body>
</html>
