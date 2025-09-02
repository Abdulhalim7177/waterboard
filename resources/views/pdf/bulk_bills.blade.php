<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Bills</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
        }
        .page {
            width: 190mm;
            min-height: 277mm;
            page-break-after: always;
        }
        .bill-container {
            display: flex;
            flex-direction: column;
            gap: 5mm; /* 5mm spacing between bills */
            height: 277mm;
        }
        .bill {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
            height: calc(277mm / 3 - 5mm); /* Each bill takes ~1/3 of page height, minus spacing */
            box-sizing: border-box;
            overflow: hidden;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    @foreach($bills->chunk(3) as $billChunk)
        <div class="page">
            <div class="bill-container">
                @foreach($billChunk as $bill)
                    <div class="bill relative">
                        <!-- Watermark -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                            <img alt="Watermark" src="{{ asset('assets/media/logos/logo.png') }}" class="h-32 opacity-20" />
                        </div>

                        <div class="p-2 relative z-10">
                            <!-- Heading and Top Separator -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-base font-bold text-blue-800 tracking-wide">Water Board Inc.</h1>
                                    <p class="text-slate-600 text-[8px]">123 Water Lane</p>
                                    <p class="text-slate-600 text-[8px]">City, State, Nigeria</p>
                                </div>
                                <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="h-8 me-1" />
                            </div>
                            <div class="border-t-2 border-blue-700 my-1"></div>

                            <!-- Main Content: Horizontal Layout -->
                            <div class="flex flex-row gap-3 items-start">
                                <!-- Left: Issued To, Description, Payment Details -->
                                <div class="flex-1 space-y-1">
                                    <!-- Issued To and Description side-by-side -->
                                    <div class="flex gap-3">
                                        <!-- Issued To -->
                                        <div>
                                            <h2 class="text-[9px] font-semibold text-slate-800 mb-0.5">Issued To:</h2>
                                            <p class="text-slate-900 font-medium text-[8px]">{{ $bill->customer->first_name }} {{ $bill->customer->surname }}</p>
                                            <p class="text-slate-600 text-[8px]">{{ $bill->customer->house_number }} {{ $bill->customer->street_name }}</p>
                                            <p class="text-slate-600 text-[8px]">{{ $bill->customer->area->name ?? 'N/A' }}, {{ $bill->customer->lga->name ?? 'N/A' }}</p>
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <h2 class="text-[9px] font-semibold text-slate-800 mb-0.5">Description</h2>
                                            <ul class="list-disc list-inside text-slate-700 text-[8px]">
                                                <li>{{ $bill->tariff->name ?? 'Water Usage' }}</li>
                                            </ul>
                                            <div class="mt-0.5 flex gap-1">
                                                <span class="bg-{{ $bill->approval_status === 'approved' ? 'green-100' : 'yellow-100' }} text-{{ $bill->approval_status === 'approved' ? 'green-700' : 'yellow-700' }} text-[8px] font-medium px-1 py-0.25 rounded-full">
                                                    {{ ucfirst($bill->approval_status) }}
                                                </span>
                                                <span class="bg-{{ $bill->status === 'paid' ? 'green-100' : ($bill->status === 'overdue' ? 'red-100' : 'yellow-100') }} text-{{ $bill->status === 'paid' ? 'green-700' : ($bill->status === 'overdue' ? 'red-700' : 'yellow-700') }} text-[8px] font-medium px-1 py-0.25 rounded-full">
                                                    {{ ucfirst($bill->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Details -->
                                    <div class="bg-blue-50 bg-opacity-75 border border-blue-200 px-1 py-0.5 rounded-md">
                                        <h2 class="text-[9px] font-semibold text-blue-800 mb-0.5">Payment Details</h2>
                                        <p class="text-slate-700 text-[8px]">Payment Method: NABRoll / Account Balance</p>
                                        <p class="text-slate-700 text-[8px]">Issued By: Water Board Inc.</p>
                                    </div>
                                </div>

                                <!-- Right: Receipt Card -->
                                <div class="w-[30%] bg-slate-100 border border-dashed border-slate-300 p-1 rounded-md shadow-sm">
                                    <h2 class="text-base font-bold text-blue-700 mb-0.5 text-center tracking-wide">
                                        {{ $bill->status === 'paid' ? 'WATER BILL RECEIPT' : 'WATER BILL' }}
                                    </h2>
                                    <div class="text-[8px] text-slate-700 space-y-0.25 mb-0.5">
                                        <p><strong>Bill No:</strong> {{ $bill->billing_id }}</p>
                                        <p><strong>Issue Date:</strong> {{ $bill->billing_date->format('d M Y') }}</p>
                                        <p><strong>Due Date:</strong> {{ $bill->due_date->format('d M Y') }}
                                            @if($bill->status === 'overdue')
                                                <span class="text-red-600 font-semibold">Overdue</span>
                                            @elseif($bill->due_date->isFuture())
                                                <span class="text-yellow-600 font-semibold">Due in {{ $bill->due_date->diffInDays(now()) }} days</span>
                                            @endif
                                        </p>
                                    </div>

                                    <h3 class="text-[9px] font-semibold text-slate-800 mb-0.5">Billing Summary</h3>
                                    <div class="text-[8px] text-slate-700 space-y-0.25">
                                        <p><strong>Rate:</strong> ₦{{ number_format($bill->tariff->rate ?? 0, 2) }}</p>
                                        <p><strong>Units Used:</strong> {{ $bill->customer->meter_reading ?? 'N/A' }}</p>
                                        <p><strong>Payment Term:</strong> 30 days</p>
                                        <p><strong>Subtotal:</strong> ₦{{ number_format($bill->amount, 2) }}</p>
                                        <p><strong>VAT (0%):</strong> ₦0.00</p>
                                        <p><strong>Total Due:</strong> ₦{{ number_format($bill->amount, 2) }}</p>
                                        <p><strong>Balance:</strong> ₦{{ number_format($bill->balance, 2) }}
                                            <span class="text-{{ $bill->status === 'paid' ? 'green-600' : 'red-600' }} font-semibold">{{ $bill->status === 'paid' ? 'Paid' : 'Unpaid' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center text-[8px] text-slate-500 py-0.5 border-t border-slate-200 relative z-10">
                            &copy; {{ date('Y') }} Powered by PayFlow Systems Ltd. All rights reserved.
                        </div>
                    </div>
                @endforeach
                @if($billChunk->count() < 3)
                    @for($i = $billChunk->count(); $i < 3; $i++)
                        <div class="bill" style="visibility: hidden;"></div>
                    @endfor
                @endif
            </div>
        </div>
    @endforeach
</body>
</html>