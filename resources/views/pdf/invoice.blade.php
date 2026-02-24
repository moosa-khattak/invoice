<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Invoice {{ $invoice->invoice_number }}</title>
        <style>
            @page {
                margin: 0cm 0cm;
            }
            body {
                font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
                font-size: 14px;
                color: #374151; /* text-gray-700 */
                line-height: 1.5;
                margin-top: 2cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
                background-color: #ffffff;
            }

            /* Typography */
            h1, h2, h3, h4, h5, h6 {
                margin: 0;
                color: #111827; /* text-gray-900 */
            }
            .text-sm { font-size: 12px; }
            .text-xs { font-size: 10px; }
            .text-lg { font-size: 18px; }
            .text-xl { font-size: 20px; }
            .text-2xl { font-size: 24px; }
            
            .font-bold { font-weight: bold; }
            .font-semibold { font-weight: 600; }
            .font-medium { font-weight: 500; }
            .text-gray-500 { color: #6b7280; }
            .text-gray-600 { color: #4b5563; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .uppercase { text-transform: uppercase; }
            .tracking-wide { letter-spacing: 0.05em; }

            /* Utilities */
            .mb-2 { margin-bottom: 0.5rem; }
            .mb-4 { margin-bottom: 1rem; }
            .mb-6 { margin-bottom: 1.5rem; }
            .mb-8 { margin-bottom: 2rem; }
            
            .mt-2 { margin-top: 0.5rem; }
            .mt-4 { margin-top: 1rem; }
            .mt-8 { margin-top: 2rem; }
            
            .pb-4 { padding-bottom: 1rem; }
            .pt-4 { padding-top: 1rem; }
            .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
            .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
            .px-4 { padding-left: 1rem; padding-right: 1rem; }

            /* Layout */
            table {
                width: 100%;
                border-collapse: collapse;
            }
            .w-full { width: 100%; }
            .w-half { width: 50%; }
            
            /* Specific Components */
            .header-table {
                margin-bottom: 2rem;
            }
            .logo-container img {
                max-width: 150px;
                max-height: 80px;
                object-fit: contain;
            }
            
            .invoice-title {
                font-size: 2.5rem;
                font-weight: 800;
                color: #2563eb; /* text-blue-600 */
                letter-spacing: -0.025em;
                margin-bottom: 0.25rem;
            }

            .badge {
                display: inline-block;
                padding: 4px 12px;
                border-radius: 9999px;
                font-size: 11px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                background-color: #dbeafe; /* bg-blue-100 */
                color: #1e40af; /* text-blue-800 */
            }

            /* Info grid */
            .info-box {
                background-color: #f9fafb; /* bg-gray-50 */
                border-radius: 0.5rem;
                padding: 1.5rem;
                margin-bottom: 2rem;
                border: 1px solid #e5e7eb;
            }

            .info-table td {
                vertical-align: top;
            }

            /* Items Table */
            .items-table {
                width: 100%;
                margin-bottom: 2rem;
            }
            .items-table th {
                background-color: #f3f4f6; /* bg-gray-100 */
                color: #374151; /* text-gray-700 */
                font-size: 11px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 12px 16px;
                border-top: 1px solid #e5e7eb;
                border-bottom: 1px solid #e5e7eb;
            }
            .items-table td {
                padding: 16px;
                border-bottom: 1px solid #e5e7eb;
                color: #111827;
                font-size: 13px;
            }
            .items-table tr:last-child td {
                border-bottom: none;
            }

            /* Totals Section */
            .totals-table {
                width: 100%;
            }
            .totals-container {
                width: 45%;
                float: right;
                background-color: #f9fafb;
                border-radius: 0.5rem;
                padding: 1.5rem;
                border: 1px solid #e5e7eb;
            }
            .totals-row td {
                padding: 8px 0;
                font-size: 13px;
                color: #4b5563;
            }
            .totals-row td.amount {
                text-align: right;
                font-weight: 500;
                color: #111827;
            }
            .totals-divider {
                border-top: 1px solid #e5e7eb;
                margin: 8px 0;
            }
            .total-final td {
                padding-top: 12px;
                font-size: 18px;
                font-weight: bold;
                color: #111827;
            }
            .total-final td.amount {
                color: #2563eb; /* text-blue-600 */
            }

            /* Notes */
            .footer-notes {
                clear: both;
                padding-top: 3rem;
            }
            .note-box {
                margin-bottom: 1.5rem;
            }
            .note-title {
                font-size: 11px;
                font-weight: bold;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 8px;
            }
            .note-content {
                font-size: 12px;
                color: #4b5563;
                background-color: #f9fafb;
                padding: 12px;
                border-left: 4px solid #d1d5db;
                border-radius: 0 4px 4px 0;
            }
            
            /* Clearfix */
            .clearfix::after {
                content: "";
                display: table;
                clear: both;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <table class="header-table w-full">
            <tr>
                <td class="w-half logo-container" style="vertical-align: top;">
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
                            <img src="{{ $base64 }}" alt="Company Logo" />
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                    @else
                        <!-- Placeholder for alignment if no logo -->
                        <div style="height: 60px;"></div>
                    @endif
                </td>
                <td class="w-half text-right" style="vertical-align: top;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="text-gray-500 font-medium mb-2">#{{ $invoice->invoice_number }}</div>
                    @if($invoice->balance_due == 0)
                        <div class="badge" style="background-color: #dcfce7; color: #166534;">PAID IN FULL</div>
                    @elseif($invoice->balance_due < $invoice->total)
                        <div class="badge" style="background-color: #fef9c3; color: #854d0e;">PARTIALLY PAID</div>
                    @else
                        <div class="badge" style="background-color: #fee2e2; color: #991b1b;">UNPAID</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Info Grid -->
        <div class="info-box block">
            <table class="info-table w-full">
                <tr>
                    <!-- Left Column (Bill To) -->
                    <td style="width: 35%;">
                        <div class="text-xs font-bold text-gray-500 uppercase   tracking-wide mb-2">Billed To</div>
                        <div class="font-semibold text-lg mb-1">{{ explode("\n", $invoice->bill_to)[0] ?? 'N/A' }}</div>
                        <div class="text-gray-600 text-sm">
                            {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->bill_to), 1)))) !!}
                        </div>
                    </td>

                    <!-- Middle Column (Ship To - Optional) -->
                    <td style="width: 35%;">
                        @if ($invoice->ship_to)
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Shipped To</div>
                            <div class="font-semibold text-lg mb-1">{{ explode("\n", $invoice->ship_to)[0] ?? 'N/A' }}</div>
                            <div class="text-gray-600 text-sm">
                                {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->ship_to), 1)))) !!}
                            </div>
                        @endif
                    </td>

                    <!-- Right Column (Dates & PO) -->
                    <td style="width: 30%; text-align: right;">
                        <table class="w-full text-right" style="font-size: 13px;">
                            <tr>
                                <td class="text-gray-500 pb-2">Invoice Date:</td>
                                <td class="font-medium pb-2 text-gray-900">{{ $invoice->date ? $invoice->date->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-gray-500 pb-2">Due Date:</td>
                                <td class="font-medium pb-2 text-gray-900">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            @if ($invoice->po_number)
                            <tr>
                                <td class="text-gray-500">P.O. Number:</td>
                                <td class="font-medium text-gray-900">{{ $invoice->po_number }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left; width: 45%;">Description</th>
                    <th style="text-align: center; width: 15%;">Quantity</th>
                    <th style="text-align: right; width: 20%;">Rate</th>
                    <th style="text-align: right; width: 20%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if ($invoice->items)
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td class="font-medium">{{ $item['Item'] ?? 'Untitled Item' }}</td>
                            <td class="text-center text-gray-600">{{ $item['Quantity'] ?? 0 }}</td>
                            <td class="text-right text-gray-600">{{ $invoice->currency }} {{ number_format($item['Rate'] ?? 0, 0) }}</td>
                            <td class="text-right font-medium text-gray-900">{{ $invoice->currency }} {{ number_format($item['Amount'] ?? 0, 0) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center text-gray-500" style="padding: 2rem;">No items added to this invoice.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals & Notes Area -->
        <div class="clearfix">
            <!-- Totals Box (Right floated) -->
            <div class="totals-container">
                <table class="totals-table">
                    <tr class="totals-row">
                        <td>Subtotal</td>
                        <td class="amount">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 0) }}</td>
                    </tr>
                    
                    @if ($invoice->discount > 0)
                        <tr class="totals-row">
                            <td>Discount ({{ $invoice->discount_rate }}%)</td>
                            <td class="amount" style="color: #ef4444;">-{{ $invoice->currency }} {{ number_format($invoice->discount, 0) }}</td>
                        </tr>
                    @endif

                    @if ($invoice->tax > 0)
                        <tr class="totals-row">
                            <td>Tax ({{ $invoice->tax_rate }}%)</td>
                            <td class="amount">{{ $invoice->currency }} {{ number_format($invoice->tax, 0) }}</td>
                        </tr>
                    @endif

                    @if ($invoice->shipping > 0)
                        <tr class="totals-row">
                            <td>Shipping</td>
                            <td class="amount">{{ $invoice->currency }} {{ number_format($invoice->shipping, 0) }}</td>
                        </tr>
                    @endif

                    <tr><td colspan="2"><div class="totals-divider"></div></td></tr>

                    <tr class="total-final">
                        <td>Total</td>
                        <td class="amount">{{ $invoice->currency }} {{ number_format($invoice->total, 0) }}</td>
                    </tr>

                    @if ($invoice->amount_paid > 0)
                        <tr><td colspan="2"><div class="totals-divider"></div></td></tr>
                        <tr class="totals-row">
                            <td style="padding-top: 8px;">Amount Paid</td>
                            <td class="amount" style="padding-top: 8px; color: #10b981;">{{ $invoice->currency }} {{ number_format($invoice->amount_paid, 0) }}</td>
                        </tr>
                        <tr class="total-final" style="font-size: 16px;">
                            <td style="color: #374151;">Balance Due</td>
                            <td class="amount" style="color: #111827;">{{ $invoice->currency }} {{ number_format($invoice->balance_due, 0) }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="footer-notes">
            @if ($invoice->notes)
                <div class="note-box" style="page-break-inside: avoid;">
                    <div class="note-title">Notes</div>
                    <div class="note-content">
                        {!! nl2br(e($invoice->notes)) !!}
                    </div>
                </div>
            @endif

            @if ($invoice->terms)
                <div class="note-box" style="page-break-inside: avoid;">
                    <div class="note-title">Terms & Conditions</div>
                    <div class="note-content">
                        {!! nl2br(e($invoice->terms)) !!}
                    </div>
                </div>
            @endif
        </div>
    </body>
</html>
