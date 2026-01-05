<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #374151;
            background-color: #ffffff;
            width: 210mm;
            height: 297mm;
            margin: 0;
        }
        
        .container {
            width: 190mm;
            margin: 10mm auto;
            padding: 10px;
        }
        
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background-color: #ffffff;
            padding: 15px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .logo {
            height: 40px;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-size: 9px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 11px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .table-container {
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .report-table th {
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            color: #6b7280;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .report-table th:nth-child(2),
        .report-table th:nth-child(3),
        .report-table th:nth-child(4),
        .report-table th:nth-child(5) {
            text-align: right;
        }
        
        .report-table td {
            padding: 10px 0;
            font-size: 11px;
            font-weight: 700;
            color: #374151;
        }
        
        .report-table td:nth-child(2),
        .report-table td:nth-child(3),
        .report-table td:nth-child(4),
        .report-table td:nth-child(5) {
            text-align: right;
        }
        
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        
        .totals-box {
            max-width: 200px;
            width: 100%;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .total-label {
            font-size: 9px;
            font-weight: 600;
            color: #6b7280;
            padding-right: 20px;
        }
        
        .total-value {
            font-size: 11px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 12px;
        }
        
        @media print {
            .container {
                padding: 0;
                margin: 0;
                width: 210mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <img alt="Logo" src="{{ public_path('assets/media/logos/logo.png') }}" class="logo" />
                <div class="info-item">
                    <div class="info-label">Generated On:</div>
                    <div class="info-value">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
            
            <div class="report-title">Payment Report</div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Date Range:</div>
                    <div class="info-value">
                        {{ $filters['start_date'] ? \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') : 'All' }}
                        to
                        {{ $filters['end_date'] ? \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') : 'All' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Generated By:</div>
                    <div class="info-value">Katsina State Water Board (KTSWB)</div>
                </div>
            </div>
            
            <div class="table-container">
                <h6 class="section-title">PAYMENT SUMMARY</h6>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction Ref</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->first_name }} {{ $payment->surname }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y H:i:s') }}</td>
                                <td>₦{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->method }}</td>
                                <td>{{ $payment->transaction_ref ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="totals-section">
                <div class="totals-box">
                    <div class="total-row">
                        <div class="total-label">Total Payments:</div>
                        <div class="total-value">{{ $total_payments }}</div>
                    </div>
                    <div class="total-row">
                        <div class="total-label">Total Paid:</div>
                        <div class="total-value">₦{{ number_format($total_paid, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>