<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Bills</title>
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            min-height: 257mm;
            page-break-after: always;
            position: relative;
        }
        .bill {
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            margin-bottom: 5mm;
            padding: 4mm;
            page-break-inside: avoid;
            position: relative;
            min-height: 80mm;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            opacity: 0.05;
            z-index: -1;
            transform: translate(-50%, -50%);
        }

        .watermark img {
            height: 50px;
            width: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3mm;
        }

        .company-info {
            text-align: left;
        }

        .company-info h1 {
            color: #1e40af;
            font-size: 12px;
            margin: 0;
            font-weight: bold;
        }

        .company-info p {
            margin: 1px 0;
            font-size: 8px;
            color: #4b5563;
        }

        .logo {
            height: 15px;
        }

        .separator {
            border-top: 1px solid #2563eb;
            margin: 2mm 0;
        }

        .main-content {
            display: flex;
            font-size: 9px;
        }

        .left-section {
            flex: 1;
            padding-right: 2mm;
        }

        .right-section {
            width: 35%;
            background-color: #f1f5f9;
            border: 1px dashed #94a3b8;
            padding: 3mm;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2mm;
        }

        .info-item {
            margin-bottom: 1.5mm;
        }

        .info-item strong {
            color: #374151;
        }

        .status-approved {
            background-color: #dcfce7;
            color: #166534;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #dcfce7;
            color: #166534;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-overdue {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .receipt-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3mm;
            text-transform: uppercase;
        }

        .receipt-detail {
            margin-bottom: 1.5mm;
        }

        .receipt-detail strong {
            color: #374151;
        }

        .billing-summary {
            margin-top: 2mm;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5mm;
            font-size: 9px;
        }

        .summary-item strong {
            color: #374151;
        }

        .footer {
            text-align: center;
            margin-top: 3mm;
            padding-top: 2mm;
            border-top: 1px solid #d1d5db;
            font-size: 7px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    @foreach($bills as $index => $bill)
        @if($index % 3 == 0)
            <div class="page">
        @endif

        <div class="bill">
            <!-- Watermark -->
            <div class="watermark">
                <img src="{{ public_path('assets/media/logos/logo.png') }}" alt="Watermark" />
            </div>

            <!-- Header -->
            <div class="header">
                <div class="company-info">
                    <h1>Katsina State Water Board (KTSWB)</h1>
                    <p>123 Water Lane</p>
                    <p>City, State, Nigeria</p>
                </div>
                <img src="{{ public_path('assets/media/logos/logo.png') }}" alt="Logo" class="logo" />
            </div>

            <div class="separator"></div>

            <!-- Main Content: Horizontal Layout -->
            <div class="main-content">
                <!-- Left: Issued To, Description, Payment Details -->
                <div class="left-section">
                    <!-- Issued To and Description in table format -->
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="50%" valign="top">
                                <div class="recipient-info">
                                    <div class="section-title">Issued To:</div>
                                    <div class="info-item"><strong>{{ $bill->customer->first_name }} {{ $bill->customer->surname }}</strong></div>
                                    <div class="info-item">{{ $bill->customer->house_number }} {{ $bill->customer->street_name }}</div>
                                    <div class="info-item">{{ $bill->customer->area->name ?? 'N/A' }}, {{ $bill->customer->lga->name ?? 'N/A' }}</div>
                                </div>
                            </td>

                            <td width="50%" valign="top">
                                <div class="description-info">
                                    <div class="section-title">Description</div>
                                    <div class="info-item">{{ $bill->tariff->name ?? 'Water Usage' }}</div>
                                    <div style="margin-top: 1.5mm;">
                                        <span class="status-approved">{{ ucfirst($bill->approval_status) }}</span>
                                        @if($bill->status === 'paid')
                                            <span class="status-paid">Paid</span>
                                        @elseif($bill->status === 'overdue')
                                            <span class="status-overdue">Overdue</span>
                                        @else
                                            <span class="status-pending">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- Payment Details -->
                    <div style="background-color: #dbeafe; border: 1px solid #93c5fd; padding: 2mm; margin-top: 2mm;">
                        <div class="section-title" style="color: #1e40af; font-size: 9px;">Payment Details</div>
                        <div class="info-item">Payment Method: NABRoll / Account Balance</div>
                        <div class="info-item">Issued By: Katsina State Water Board (KTSWB)</div>
                    </div>
                </div>

                <!-- Right: Receipt Card -->
                <div class="right-section">
                    <div class="receipt-title">
                        {{ $bill->status === 'paid' ? 'WATER BILL RECEIPT' : 'WATER BILL' }}
                    </div>

                    <div class="receipt-details">
                        <div class="receipt-detail"><strong>Bill No:</strong> {{ $bill->billing_id }}</div>
                        <div class="receipt-detail"><strong>Issue Date:</strong> {{ $bill->billing_date->format('d M Y') }}</div>
                        <div class="receipt-detail">
                            <strong>Due Date:</strong> {{ $bill->due_date->format('d M Y') }}
                            @if($bill->status === 'overdue')
                                <span style="color: #ef4444; font-weight: bold;">Overdue</span>
                            @elseif($bill->due_date->isFuture())
                                <span style="color: #f59e0b; font-weight: bold;">Due in {{ $bill->due_date->diffInDays(now()) }} days</span>
                            @endif
                        </div>
                    </div>

                    <div class="billing-summary">
                        <div class="section-title">Billing Summary</div>
                        <div class="summary-item"><strong>Rate:</strong> ₦{{ number_format($bill->tariff->rate ?? 0, 2) }}</div>
                        <div class="summary-item"><strong>Units Used:</strong> {{ $bill->customer->meter_reading ?? 'N/A' }}</div>
                        <div class="summary-item"><strong>Payment Term:</strong> 30 days</div>
                        <div class="summary-item"><strong>Subtotal:</strong> ₦{{ number_format($bill->amount, 2) }}</div>
                        <div class="summary-item"><strong>VAT (0%):</strong> ₦0.00</div>
                        <div class="summary-item"><strong>Total Due:</strong> ₦{{ number_format($bill->amount, 2) }}</div>
                        <div class="summary-item">
                            <strong>Balance:</strong> ₦{{ number_format($bill->balance, 2) }}
                            <span style="color: {{ $bill->status === 'paid' ? '#16a34a' : '#dc2626' }}; font-weight: bold;">
                                {{ $bill->status === 'paid' ? 'Paid' : 'Unpaid' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                &copy; {{ date('Y') }} Powered by SteadFast Technologies Ltd. All rights reserved.
            </div>
        </div>

        @if(($index + 1) % 3 == 0 || $index + 1 == count($bills))
            </div> <!-- Close page div -->
        @endif
    @endforeach
</body>
</html>