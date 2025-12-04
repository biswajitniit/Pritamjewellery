<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Metal Receive Entry</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />

<style>

/* --- EXACT A5 LANDSCAPE PAGE --- */
@page { size: A5 landscape; margin: 0; }

html, body {
    margin: 0;
    padding: 0;
    width: 210mm;
    height: 148mm;
    background: #fff;
    -webkit-print-color-adjust: exact;
    font-family: Arial, sans-serif;
    font-size: 11px;
}

/* MAIN PAGE */
.page {
    width: 210mm;
    height: 148mm;
    border: 1px solid #000;
    padding: 6mm;
    box-sizing: border-box;
    position: relative; /* so footer can stick to bottom */
}

/* HEADER */
.header {
    text-align: center;
    margin-bottom: 4px;
}
.header img {
    height: 60px;
}
.header-title {
    font-size: 16px;
    font-weight: bold;
    color: #8B0000;
}
.header-address {
    font-size: 10px;
    margin-top: 2px;
}
.ganesh {
    text-align: center;
    font-weight: bold;
    margin: 4px 0 6px 0;
    font-size: 12px;
}

/* INFO TABLE */
.info-table {
    width: 100%;
    border-collapse: collapse;
}
.info-table td {
    border: 1px solid #000;
    padding: 6px;
    vertical-align: top;
    font-size: 10px;
}
.title-cell {
    font-weight: bold;
    text-align: center;
    font-size: 11px;
    padding: 4px;
}

/* ITEMS TABLE */
.items {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
}
.items th,
.items td {
    border: 1px solid #000;
    padding: 6px;
    font-size: 10px;
    vertical-align: top;
}
.items thead th {
    background: #f0f0f0;
    font-weight: bold;
}

/* Remove bottom border under description row */
.items tr.remove-desc td {
    border-bottom-width: 0 !important;
}

/* Remove top border from blank row */
.items tr.blank-row td {
    border-top-width: 0 !important;
}

/* COLUMN WIDTHS */
.col-desc   { width: 56%; }
/* .col-hsn    { width: 8%;  text-align: center; } */
.col-purity { width: 8%;  text-align: center; }
.col-gross  { width: 18%; text-align: center; }
.col-net    { width: 18%; text-align: right; }

/* DESCRIPTION BLOCK */
.desc-wrapper {
    display: flex;
    width: 100%;
}
.desc-left {
    width: 70%;
    padding-top: 4px;
    line-height: 14px;
}
.desc-indent {
    padding-left: 15px;
}
.desc-right {
    width: 30%;
    font-weight: bold;
    text-align: left;
}

/* TOTAL ROW — top + bottom border + sides */
.total-row td {
    border-left: 1px solid #000 !important;
    border-right: 1px solid #000 !important;
    border-bottom: 1px solid #000 !important;
    border-top: 2px solid #000 !important;
    font-weight: bold;
    padding: 8px 6px;
}

/* FOOTER TOP TEXT (ISSUED FOR JOBWORK ...) */
.issued {
    margin-top: 10px;
    font-weight: bold;
    font-size: 10px;
}

/* FOOTER SIGNATURE ROW STUCK TO BOTTOM */
.footer {
    position: absolute;
    left: 6mm;
    right: 6mm;
    bottom: 3.5mm; /* very close to bottom border */
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    font-size: 10px;
}

.footer div {
    text-align: center;
}

@media print {
    html, body { width: 210mm; height: 148mm; }
}

</style>
</head>

<body>
<div class="page">

    <!-- HEADER -->
    <div class="header">
        <img src="{{ asset('assets/images/logo1.png') }}">
        <div class="header-title">Bhagya Laxmi Jewellers</div>
        <div class="header-address">{{ $company->address }} {{ $company->city }}</div>
    </div>

    <div class="ganesh">॥ श्री गणेशाय नमः ॥</div>

    <!-- INFO TABLE -->
    <table class="info-table">
        <tr>
            <td class="title-cell" colspan="2">DELIVERY CHALLAN</td>
        </tr>
        <tr>
            <td>
                <strong>Name:</strong> {{ $customer->cust_name }}<br><br>
                <strong>Address:</strong> {{ $customer->address }}<br><br>
                <strong>GSTIN:</strong> {{ $customer->gstin }}<br><br>
                <strong>State code:</strong> {{ $customer->statecode }}
            </td>

            <td>
                <strong>Voucher No:</strong> {{ $metalreceiveentries->dv_no }}<br><br>
                <strong>Voucher Date:</strong> {{ date('d/m/Y', strtotime($metalreceiveentries->dv_no)) }}<br><br>
                <strong>GSTIN:</strong> {{ $company->gstin }}<br><br>
                <strong>State code:</strong> {{ $company->statecode }}
            </td>
        </tr>
    </table>

    <!-- ITEMS TABLE -->
    <table class="items">
        <thead>
            <tr>
                <th class="col-desc">Description</th>
                <th class="col-purity">Purity</th>
                <th class="col-gross">Gross Weight (Grams)</th>
                <th class="col-net">Net Weight (Grams)</th>
            </tr>
        </thead>
        <tbody>

            <!-- DESCRIPTION ROW -->
            <tr class="remove-desc">
                <td class="col-desc" >
                    <div class="desc-wrapper">

                        <div class="desc-right">GOLD</div><br><br><br><br><br>


                    </div>
                </td>
                <td class="col-purity">{{ $metalpurities->purity }}</td>
                <td class="col-gross">{{ number_format($metalreceiveentries->weight, 3, '.', '') }}</td>
                <td class="col-net">
                    {{ number_format($metalreceiveentries->weight, 3, '.', '') }}
                </td>
            </tr>

            <!-- BLANK ROW (no top border) -->
            <tr class="blank-row" style="height: 32mm;">
                <td style="position: relative;">
                    <div style="
                        position: absolute;
                        bottom: 2px;
                        left: 5px;
                        line-height: 14px;
                        font-size: 10px;
                        text-align: left;
                    ">
                        Receive GOLD for Job Work <br>
                        Against DV No: 500537999 &nbsp;&nbsp; Dated : {{ date('d/m/Y', strtotime($metalreceiveentries->dv_no)) }}
                    </div>    
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <!-- TOTAL ROW -->
            {{-- <tr class="total-row">
                <td colspan="2"></td>
                <td style="text-align:right;">Total</td>
                <td style="text-align:right;">{{ number_format($metalreceiveentries->weight,3, '.', '') }}</td>
            </tr> --}}

        </tbody>
    </table>

    <!-- FOOTER TOP LINE -->
    <div class="issued">
        JOB WORK – SAC : {{ $metal->metal_sac }} / HSN : {{ $metal->metal_hsn }}
    </div>

    <!-- FOOTER STUCK TO BOTTOM BORDER -->
    <div class="footer">
        <div style="text-align:left;">
            <strong>Received By :</strong><br>
            ____________________
        </div>

        <div style="text-align:center;">
            All Subject to Kolkata Jurisdiction
        </div>

        <div style="text-align:right;">
            <strong>For Bhagya Laxmi Jewellers</strong><br>
            ____________________
        </div>
    </div>

</div>
</body>
</html>
