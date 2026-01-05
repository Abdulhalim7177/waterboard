<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill #{{ $bill->billing_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2563eb;
        }

        .logo {
            height: 60px;
        }

        .company-info {
            text-align: left;
        }

        .company-info h1 {
            color: #1e40af;
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        .company-info p {
            margin: 3px 0;
            font-size: 12px;
            color: #4b5563;
        }

        .container {
            border: 1px solid #d1d5db;
            padding: 20px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            opacity: 0.05;
            z-index: -1;
        }

        .watermark img {
            height: 160px;
            width: auto;
        }

        .main-content {
            display: flex;
            margin-bottom: 20px;
        }

        .left-section {
            flex: 1;
        }

        .right-section {
            width: 30%;
            background-color: #f1f5f9;
            border: 1px dashed #94a3b8;
            padding: 15px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .recipient-info, .description-info {
            margin-bottom: 15px;
        }

        .info-item {
            margin-bottom: 5px;
            font-size: 12px;
        }

        .info-item strong {
            color: #374151;
        }

        .status-paid {
            background-color: #dcfce7;
            color: #166534;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-overdue {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .receipt-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .receipt-details {
            margin-bottom: 15px;
        }

        .receipt-detail {
            margin-bottom: 5px;
            font-size: 12px;
        }

        .receipt-detail strong {
            color: #374151;
        }

        .billing-summary {
            margin-top: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .summary-item strong {
            color: #374151;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="watermark">
            <img src="{{ public_path('assets/media/logos/logo.png') }}" alt="Watermark" />
        </div>

        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>Katsina State Water Board (KTSWB)</h1>
                <p>No. 1 Olusegun Obasanjo Drive, P.M.B 2022, Katsina - Nigeria</p>
                <p>Email: katsinastatewaterboard@yahoo.com | Phone: +234 (0) 814 4689 489</p>
            </div>
            <img src="{{ public_path('assets/media/logos/logo.png') }}" alt="Logo" class="logo" />
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Left Section -->
            <div class="left-section">
                <!-- Recipient and Description -->
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
                                <div style="margin-top: 10px;">
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
                <div style="background-color: #dbeafe; border: 1px solid #93c5fd; padding: 10px; margin-top: 15px;">
                    <div class="section-title" style="color: #1e40af;">Payment Details</div>
                    <div class="info-item">Payment Method: NABRoll / Account Balance</div>
                    <div class="info-item">Issued By: Katsina State Water Board (KTSWB)</div>
                    @if($bill->status !== 'paid')
                        <div style="margin-top: 5px;">
                            <span style="background-color: #22c55e; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px;">Pay Now</span>
                        </div>
                    @else
                        <div style="margin-top: 5px;">
                            <span style="background-color: #22c55e; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px;">Paid</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Section -->
            <div class="right-section">
                <div class="receipt-title">
                    {{ $bill->status === 'paid' ? 'WATER BILL RECEIPT' : 'WATER BILL' }}
                </div>

                <div class="receipt-details">
                    <div class="receipt-detail"><strong>Bill No:</strong> {{ $bill->billing_id }}</div>
                    <div class="receipt-detail"><strong>Issue Date:</strong> {{ $bill->billing_date->format('d M Y') }}</div>
                    @if($bill->status === 'paid')
                        <div class="receipt-detail"><strong>Payment Date:</strong> {{ $bill->updated_at->format('d M Y') }}</div>
                    @else
                        <div class="receipt-detail">
                            <strong>Due Date:</strong> {{ $bill->due_date->format('d M Y') }}
                            @if($bill->status === 'overdue')
                                <span style="color: #ef4444; font-weight: bold;">Overdue</span>
                            @elseif($bill->due_date->isFuture())
                                <span style="color: #f59e0b; font-weight: bold;">Due in {{ $bill->due_date->diffInDays(now()) }} days</span>
                            @endif
                        </div>
                    @endif
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
</body>
</html>