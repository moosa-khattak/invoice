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
            font-size: 13px;
            color: #374151;
            margin: 40px;
            background-color: #ffffff;
        }

        /* Headers & Typography */
        h1,
        h2,
        h3,
        h4 {
            margin: 0;
            color: #111827;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .text-gray-900 {
            color: #111827;
        }

        .font-bold {
            font-weight: bold;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-sm {
            font-size: 11px;
        }

        .text-xs {
            font-size: 10px;
        }

        .text-lg {
            font-size: 16px;
        }

        .text-2xl {
            font-size: 24px;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .tracking-wider {
            letter-spacing: 0.05em;
        }

        /* Layout Utilities */
        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Specific Elements */
        .brand-band {
            background-color: #2563eb;
            height: 6px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .invoice-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.025em;
            text-transform: uppercase;
        }

        .logo-container {
            height: 80px;
            text-align: left;
        }

        .logo-container img {
            max-width: 160px;
            max-height: 70px;
            object-fit: contain;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-paid {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-partial {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .badge-unpaid {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Info Boxes */
        .info-grid {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .info-box {
            padding: 15px;
            background-color: #f8fafc;
            border-left: 3px solid #2563eb;
            border-radius: 4px;
        }

        .info-label {
            font-size: 10px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 13px;
            color: #334155;
            line-height: 1.4;
        }

        /* Items Table */
        .items-header {
            background-color: #1e293b;
            color: #ffffff;
        }

        .items-header th {
            padding: 12px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
        }

        .items-row td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        .items-row:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Totals Section */
        .totals-wrapper {
            width: 100%;
            margin-top: 20px;
        }

        .qr-section {
            width: 45%;
            float: left;
            padding: 20px 0;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            border: 1px solid #e2e8f0;
            padding: 5px;
            border-radius: 4px;
        }

        .totals-section {
            width: 50%;
            float: right;
        }

        .totals-table td {
            padding: 6px 0;
        }

        .totals-label {
            text-align: left;
            color: #64748b;
            font-size: 12px;
        }

        .totals-value {
            text-align: right;
            color: #1e293b;
            font-weight: 500;
            font-size: 13px;
        }

        .totals-divider {
            border-top: 1px solid #cbd5e1;
            margin: 8px 0;
        }

        .total-final .totals-label {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            padding-top: 8px;
        }

        .total-final .totals-value {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            padding-top: 8px;
        }

        .balance-due {
            background-color: #f1f5f9;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        /* Footer Notes */
        .footer-notes {
            clear: both;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .note-block {
            margin-bottom: 20px;
        }

        .note-title {
            font-size: 10px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .note-content {
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="brand-band"></div>

    <!-- Header Section -->
    <table class="w-full mb-6" style="margin-top: 20px;">
        <tr>
            <td class="w-half logo-container" style="vertical-align: top;">
                @if (isset($logoBase64) && $logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo" />
                @else
                <div style="height: 40px;"></div>
                @endif
            </td>
            <td class="w-half text-right" style="vertical-align: top;">
                <div class="invoice-title">INVOICE</div>
                <div class="text-gray-500 font-bold tracking-wider mb-2">#{{ $invoice->invoice_number }}</div>

                @if($invoice->balance_due == 0)
                <div class="badge badge-paid">Paid In Full</div>
                @elseif($invoice->balance_due < $invoice->total)
                    <div class="badge badge-partial">Partially Paid</div>
                    @else
                    <div class="badge badge-unpaid">Payment Pending</div>
                    @endif
            </td>
        </tr>
    </table>

    <!-- Info Grid -->
    <table class="info-grid">
        <tr>
            <!-- Billed To -->
            <td style="width: 32%; padding-right: 15px; vertical-align: top;">
                <div class="info-box border-left">
                    <div class="info-label">Billed To</div>
                    <div class="info-value">
                        <span style="font-weight: bold; font-size: 14px; display: block; margin-bottom: 3px;">{{ explode("\n", $invoice->bill_to)[0] ?? 'N/A' }}</span>
                        {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->bill_to), 1)))) !!}
                    </div>
                </div>
            </td>

            <!-- Shipped To -->
            <td style="width: 32%; padding-right: 15px; vertical-align: top;">
                @if ($invoice->ship_to)
                <div class="info-box">
                    <div class="info-label">Shipped To</div>
                    <div class="info-value">
                        <span style="font-weight: bold; font-size: 14px; display: block; margin-bottom: 3px;">{{ explode("\n", $invoice->ship_to)[0] ?? 'N/A' }}</span>
                        {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->ship_to), 1)))) !!}
                    </div>
                </div>
                @endif
            </td>

            <!-- Invoice Details -->
            <td style="width: 36%; vertical-align: top;">
                <div class="info-box">
                    <table class="w-full text-right">
                        <tr>
                            <td class="info-label text-right" style="padding-bottom: 5px;">Invoice Date:</td>
                            <td class="info-value text-right" style="font-weight: bold; padding-bottom: 5px;">{{ $invoice->date ? $invoice->date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label text-right" style="padding-bottom: 5px;">Due Date:</td>
                            <td class="info-value text-right" style="font-weight: bold; color: #ef4444; padding-bottom: 5px;">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @if ($invoice->po_number)
                        <tr>
                            <td class="info-label text-right">P.O. Number:</td>
                            <td class="info-value text-right" style="font-weight: bold;">{{ $invoice->po_number }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="w-full mb-6" style="border-radius: 4px; overflow: hidden;">
        <thead class="items-header">
            <tr>
                <th style="text-align: left; width: 45%;">Item Description</th>
                <th style="text-align: center; width: 15%;">Qty</th>
                <th style="text-align: right; width: 20%;">Price</th>
                <th style="text-align: right; width: 20%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice->items as $item)
            <tr class="items-row">
                <td>
                    <div class="font-bold">{{ $item->name ?? 'Untitled Item' }}</div>
                    <div class="text-xs text-gray-500" style="margin-top: 2px;">Product / Service</div>
                </td>
                <td class="text-center font-medium">{{ $item->quantity ?? 0 }}</td>
                <td class="text-right">{{ $invoice->currency }} {{ number_format($item->rate ?? 0, 0) }}</td>
                <td class="text-right font-bold">{{ $invoice->currency }} {{ number_format($item->amount ?? 0, 0) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-gray-500" style="padding: 30px;">No line items found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Totals Area -->
    <div class="totals-wrapper clearfix">

        <!-- QR Code -->
        <div class="qr-section">
            @if($invoice->status !== 'Paid')
            <div class="info-label text-left mb-2">Scan To Pay</div>
            <img class="qr-code" src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(100)->generate(route('invoice.payment', $invoice->invoice_number))) }}" alt="Payment QR Code">
            @endif
        </div>

        <!-- Calculations -->
        <div class="totals-section">
            <table class="totals-table w-full">
                <tr>
                    <td class="totals-label">Subtotal</td>
                    <td class="totals-value">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 0) }}</td>
                </tr>
                @if ($invoice->discount > 0)
                <tr>
                    <td class="totals-label">Discount ({{ $invoice->discount_rate }}%)</td>
                    <td class="totals-value" style="color: #ef4444;">-{{ $invoice->currency }} {{ number_format($invoice->discount, 0) }}</td>
                </tr>
                @endif
                @if ($invoice->tax > 0)
                <tr>
                    <td class="totals-label">Tax ({{ $invoice->tax_rate }}%)</td>
                    <td class="totals-value">{{ $invoice->currency }} {{ number_format($invoice->tax, 0) }}</td>
                </tr>
                @endif
                @if ($invoice->shipping > 0)
                <tr>
                    <td class="totals-label">Shipping</td>
                    <td class="totals-value">{{ $invoice->currency }} {{ number_format($invoice->shipping, 0) }}</td>
                </tr>
                @endif

                <tr>
                    <td colspan="2">
                        <div class="totals-divider"></div>
                    </td>
                </tr>

                <tr class="total-final">
                    <td class="totals-label">Grand Total</td>
                    <td class="totals-value">{{ $invoice->currency }} {{ number_format($invoice->total, 0) }}</td>
                </tr>

                @if ($invoice->amount_paid > 0)
                <tr>
                    <td colspan="2">
                        <div class="totals-divider"></div>
                    </td>
                </tr>
                <tr>
                    <td class="totals-label" style="color: #10b981;">Amount Paid</td>
                    <td class="totals-value" style="color: #10b981;">{{ $invoice->currency }} {{ number_format($invoice->amount_paid, 0) }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="balance-due">
                            <table class="w-full">
                                <tr>
                                    <td class="info-label" style="margin: 0;">Balance Due</td>
                                    <td class="text-right" style="font-size: 16px; font-weight: bold; color: #0f172a;">{{ $invoice->currency }} {{ number_format($invoice->balance_due, 0) }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Footer Notes -->
    <div class="footer-notes">
        @if ($invoice->notes)
        <div class="note-block" style="page-break-inside: avoid;">
            <div class="note-title">Additional Notes</div>
            <div class="note-content">
                {!! nl2br(e($invoice->notes)) !!}
            </div>
        </div>
        @endif
        @if ($invoice->terms)
        <div class="note-block" style="page-break-inside: avoid;">
            <div class="note-title">Terms & Conditions</div>
            <div class="note-content">
                {!! nl2br(e($invoice->terms)) !!}
            </div>
        </div>
        @endif
    </div>
</body>

</html>