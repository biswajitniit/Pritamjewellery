<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header, .customer, .summary, .footer {
            margin-bottom: 10px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        .bordered th, .bordered td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right !important;
        }
        .no-border {
            border: none;
        }
        .footer-note {
            text-align: right;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">PRITAM JEWELLERS PRIVATE LIMITED</div>
        <div class="title">6G/1A/5, GOPAL CHANDRA BOSE LANE</div>
        <div class="title">Kolkata, West Bengal, 700050</div>
        <div class="title">GSTIN : 19AAGCM5399F1ZZ</div>
    </div>

    <div class="customer">
        <table>
            <tr>
                <td><strong>Invoice No.:</strong> {{ $sale->invoice_no }}</td>
            </tr>
            <tr></tr>
            <tr>
                <td><strong>Bill to:</strong></td>
            </tr>
            <tr>
                <td>Customer Name: {{ $sale->customer->cust_name ?? '' }}</td>
            </tr>
            <tr>
                <td>Customer Code: {{ $sale->customer->cust_code ?? '' }}</td>
            </tr>
            <tr>
                <td>Address: {{ $sale->customer->address ?? '' }}, {{ $sale->customer->city ?? '' }}, {{ $sale->customer->state ?? '' }}</td>
            </tr>
            <tr>
                <td>GSTIN: {{ $sale->customer->cust_name ?? '' }}</td>
            </tr>
        </table>
    </div>

    <table class="bordered">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>ITEM with HSN No.</th>
                <th>Purity</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>GST %</th>
                <th>GST Amt</th>
                <th>Subtotal Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotalAmount = 0;
                $gstAmount = 0;
                $totalAmount = 0;
            @endphp
            @foreach ($sale->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->name ?? '' }}<br>{{ $item->hsn ?? '' }}</td>
                    <td>{{ isset($item->purity->purity) ? $item->purity->purity : '' }}</td>
                    <td>{{ $item->quantity ?? '' }}</td>
                    <td class="text-right">{{ number_format($item->rate, 2) ?? '' }}</td>
                    <td class="text-right">{{ number_format($item->subtotal_amount, 2) ?? '' }}</td>
                    <td>{{ $item->gstin_percent ?? '' }}</td>
                    <td class="text-right">{{ number_format($item->gstin_amount, 2) ?? '' }}</td>
                    <td class="text-right">{{ number_format($item->total_amount, 2) ?? '' }}</td>
                </tr>
                @php
                    $subtotalAmount = $subtotalAmount + (float) $item->subtotal_amount;
                    $gstAmount = $gstAmount + (float) $item->gstin_amount;
                    $totalAmount = $totalAmount + (float) $item->total_amount;
                @endphp
            @endforeach

            <tr>
                <td colspan="2"><strong>TOTAL</strong></td>
                <td colspan="3"></td>
                <td class="text-right"><strong>{{ number_format($subtotalAmount, 2) ?? '' }}</strong></td>
                <td></td>
                <td class="text-right"><strong>{{ number_format($gstAmount, 2) ?? '' }}</strong></td>
                <td class="text-right"><strong>{{ number_format($totalAmount, 2) ?? 0 }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <strong>Rupees in Words:</strong> {{ convertToIndianCurrencyWords($totalAmount) ?? '' }}
    </div>

    <div class="footer-note">
        <p>For : PRITAM JEWELLERS PRIVATE LIMITED</p>
        <br><br>
        <p><strong>E&amp;OE</strong></p>
    </div>

</body>
</html>
