<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Invoice {{ $invoice->invoice_number }}</title>
        <style>
            body {
                font-family: 'DejaVu Sans', sans-serif;
                font-size: 11px;
                color: #333;
                line-height: 1.5;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                display: table;
                width: 100%;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 2px solid #000;
            }
            .header-left {
                display: table-cell;
                width: 50%;
                vertical-align: top;
            }
            .header-right {
                display: table-cell;
                width: 50%;
                text-align: right;
                vertical-align: top;
            }
            .logo {
                max-height: 80px;
                max-width: 200px;
                margin-bottom: 10px;
            }
            h1 {
                font-size: 32px;
                font-weight: bold;
                margin: 0 0 10px 0;
                color: #000;
            }
            .amount-due {
                font-size: 11px;
                color: #666;
                text-transform: uppercase;
                margin-bottom: 5px;
            }
            .amount-value {
                font-size: 28px;
                font-weight: bold;
                color: #000;
            }
            .details {
                display: table;
                width: 100%;
                margin-bottom: 30px;
            }
            .details-left,
            .details-right {
                display: table-cell;
                width: 50%;
                vertical-align: top;
            }
            .details-right {
                text-align: right;
            }
            .detail-section {
                margin-bottom: 20px;
            }
            .detail-label {
                font-size: 10px;
                font-weight: bold;
                color: #666;
                text-transform: uppercase;
                margin-bottom: 5px;
            }
            .detail-value {
                font-weight: 500;
                color: #000;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table thead {
                background-color: #000;
                color: #fff;
            }
            table th {
                padding: 10px 8px;
                font-size: 10px;
                font-weight: bold;
                text-transform: uppercase;
                text-align: left;
            }
            table th.text-center {
                text-align: center;
            }
            table th.text-right {
                text-align: right;
            }
            table tbody tr {
                border-bottom: 1px solid #ddd;
            }
            table td {
                padding: 12px 8px;
            }
            table td.text-center {
                text-align: center;
            }
            table td.text-right {
                text-align: right;
            }
            .summary {
                width: 50%;
                margin-left: auto;
                margin-top: 20px;
            }
            .summary-row {
                display: table;
                width: 100%;
                padding: 5px 0;
            }
            .summary-label,
            .summary-value {
                display: table-cell;
            }
            .summary-label {
                text-align: left;
                font-weight: 500;
            }
            .summary-value {
                text-align: right;
            }
            .summary-total {
                padding-top: 10px;
                border-top: 1px solid #000;
                font-size: 16px;
                font-weight: bold;
            }
            .summary-balance {
                background-color: #f5f5f5;
                padding: 8px;
                margin-top: 5px;
                font-weight: bold;
            }
            .notes-section {
                display: table;
                width: 100%;
                margin-top: 40px;
            }
            .notes,
            .terms {
                display: table-cell;
                width: 48%;
                padding: 15px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
            }
            .notes {
                margin-right: 4%;
            }
            .notes h3,
            .terms h3 {
                font-size: 10px;
                font-weight: bold;
                color: #666;
                text-transform: uppercase;
                margin: 0 0 10px 0;
            }
            .discount {
                color: #e53e3e;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    @if ($invoice->logo_path)
                        @php
                            $logoPath = storage_path('app/public/' . $invoice->logo_path);
                        @endphp

                        @if (file_exists($logoPath))
                            @php
                                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                                $data = file_get_contents($logoPath);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            @endphp
                            <img
                                src="{{ $base64 }}"
                                alt="Logo"
                                class="logo"
                            />
                        @endif
                    @endif

                    <h1>INVOICE</h1>
                </div>
                <div class="header-right">
                    <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">INVOICE</div>
                    <div style="font-size: 14px; color: #666;">
                        #{{ $invoice->invoice_number }}
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="details">
                <div class="details-left">
                    <div class="detail-section">
                        <div class="detail-label">Bill To</div>
                        <div class="detail-value">
                            {!! nl2br(e($invoice->bill_to ?? 'N/A')) !!}
                        </div>
                    </div>
                    @if ($invoice->ship_to)
                        <div class="detail-section">
                            <div class="detail-label">Ship To</div>
                            <div class="detail-value">
                                {!! nl2br(e($invoice->ship_to)) !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="details-right">
                    <div class="detail-section">
                        <div class="detail-label">Invoice Number</div>
                        <div class="detail-value">
                            {{ $invoice->invoice_number }}
                        </div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">Invoice Date</div>
                        <div class="detail-value">
                            {{ $invoice->date ? $invoice->date->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">Due Date</div>
                        <div class="detail-value">
                            {{ $invoice->due_date ? $invoice->due_date->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>
                    @if ($invoice->po_number)
                        <div class="detail-section">
                            <div class="detail-label">P.O. Number</div>
                            <div class="detail-value">
                                {{ $invoice->po_number }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Rate</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($invoice->items)
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>{{ $item['Item'] ?? 'Untitled Item' }}</td>
                                <td class="text-center">
                                    {{ $item['Quantity'] ?? 0 }}
                                </td>
                                <td class="text-center">
                                    {{ number_format($item['Rate'] ?? 0, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($item['Amount'] ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <!-- Summary -->
            <div class="summary">
                <div class="summary-row">
                    <div class="summary-label">Subtotal</div>
                    <div class="summary-value">
                        {{ number_format($invoice->subtotal, 2) }}
                    </div>
                </div>
                @if ($invoice->discount > 0)
                    <div class="summary-row discount">
                        <div class="summary-label">
                            Discount ({{ $invoice->discount_rate }}%)
                        </div>
                        <div class="summary-value">
                            -{{ number_format($invoice->discount, 2) }}
                        </div>
                    </div>
                @endif

                @if ($invoice->tax > 0)
                    <div class="summary-row">
                        <div class="summary-label">
                            Tax ({{ $invoice->tax_rate }}%)
                        </div>
                        <div class="summary-value">
                            {{ number_format($invoice->tax, 2) }}
                        </div>
                    </div>
                @endif

                @if ($invoice->shipping > 0)
                    <div class="summary-row">
                        <div class="summary-label">Shipping</div>
                        <div class="summary-value">
                            {{ number_format($invoice->shipping, 2) }}
                        </div>
                    </div>
                @endif

                <div class="summary-row summary-total">
                    <div class="summary-label">Total</div>
                    <div class="summary-value">
                        {{ $invoice->currency }}
                        {{ number_format($invoice->total, 2) }}
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Amount Paid</div>
                    <div class="summary-value">
                        {{ number_format($invoice->amount_paid, 2) }}
                    </div>
                </div>
                <div class="summary-row summary-balance">
                    <div class="summary-label">Balance Due</div>
                    <div class="summary-value">
                        {{ $invoice->currency }}
                        {{ number_format($invoice->balance_due, 2) }}
                    </div>
                </div>
            </div>

            <!-- Notes & Terms -->
            <div class="notes-section">
                <div class="notes">
                    <h3>Notes</h3>
                    <div>
                        {!! nl2br(e($invoice->notes ?: 'No notes provided')) !!}
                    </div>
                </div>
                <div class="terms">
                    <h3>Terms & Conditions</h3>
                    <div>
                        {!! nl2br(e($invoice->terms ?: 'No terms provided')) !!}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
