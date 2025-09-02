<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill #{{ $bill->billing_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-4">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl border {{ $bill->status === 'paid' ? 'border-green-200' : 'border-slate-200' }} relative">
        <!-- Watermark -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
            <img alt="Watermark" src="{{ asset('assets/media/logos/logo.png') }}" class="h-64 md:h-80 opacity-20" />
        </div>

        <div class="p-4 relative z-10">
            <!-- Heading -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="h-14 md:h-16 mr-2" />
                    <div>
                        <h1 class="text-2xl font-bold text-blue-800 tracking-wide">Katsina State Water Board (KSWB)</h1>
                        <p class="text-slate-600 text-xs">No. 1 Olusegun Obasanjo Drive, P.M.B 2022, Katsina - Nigeria</p>
                        <p class="text-slate-600 text-xs">Email: katsinastatewaterboard@yahoo.com | Phone: +234 (0) 814 4689 489</p>
                    </div>
                </div>
            </div>

            <!-- Main Content: Horizontal Layout -->
            <div class="flex flex-row gap-6">
                <!-- Left: Issued To, Description, Payment Details -->
                <div class="flex-1 space-y-3">
                    <!-- Issued To and Description side-by-side -->
                    <div class="flex gap-6">
                        <!-- Issued To -->
                        <div>
                            <h2 class="text-base font-semibold text-slate-800 mb-1">Issued To:</h2>
                            <p class="text-slate-900 font-medium text-xs">{{ $bill->customer->first_name }} {{ $bill->customer->surname }}</p>
                            <p class="text-slate-600 text-xs">{{ $bill->customer->house_number }} {{ $bill->customer->street_name }}</p>
                            <p class="text-slate-600 text-xs">{{ $bill->customer->area->name ?? 'N/A' }}, {{ $bill->customer->lga->name ?? 'N/A' }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <h2 class="text-base font-semibold text-slate-800 mb-1">Description</h2>
                            <ul class="list-disc list-inside text-slate-700 text-xs">
                                <li>{{ $bill->tariff->name ?? 'Water Usage' }}</li>
                            </ul>
                            <div class="mt-2 flex gap-2">
                                <span class="bg-{{ $bill->status === 'paid' ? 'green-100' : ($bill->status === 'overdue' ? 'red-100' : 'yellow-100') }} text-{{ $bill->status === 'paid' ? 'green-700' : ($bill->status === 'overdue' ? 'red-700' : 'yellow-700') }} text-xs font-medium px-2 py-0.5 rounded-full">
                                    {{ ucfirst($bill->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-blue-50 bg-opacity-75 border border-blue-200 px-3 py-2 rounded-lg">
                        <h2 class="text-sm font-semibold text-blue-800 mb-1">Payment Details</h2>
                        <p class="text-slate-700 text-xs">Payment Method: NABRoll / Account Balance</p>
                        <p class="text-slate-700 text-xs">Issued By: Katsina State Water Board (KSWB)</p>
                        @if($bill->status !== 'paid')
                            <a href="{{ route('customer.bills.pay') }}" class="inline-block mt-1 bg-green-500 text-white text-xs font-medium px-2 py-0.5 rounded-full hover:bg-green-600">Pay Now</a>
                        @else
                            <span class="inline-block mt-1 bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">Paid</span>
                        @endif
                    </div>
                </div>

                <!-- Right: Receipt Card -->
                <div class="w-[30%] bg-{{ $bill->status === 'paid' ? 'green-50' : 'slate-100' }} border {{ $bill->status === 'paid' ? 'border-green-300' : 'border-dashed border-slate-300' }} p-3 rounded-xl shadow-sm mt-[-4rem]">
                    <h2 class="text-xl font-bold {{ $bill->status === 'paid' ? 'text-green-700' : 'text-blue-700' }} mb-2 text-center tracking-wide">
                        {{ $bill->status === 'paid' ? 'WATER BILL RECEIPT' : 'WATER BILL' }}
                    </h2>
                    <div class="text-xs text-slate-700 space-y-0.5 mb-2">
                        <p><strong>Bill No:</strong> {{ $bill->billing_id }}</p>
                        <p><strong>Issue Date:</strong> {{ $bill->billing_date->format('d M Y') }}</p>
                        @if($bill->status === 'paid')
                            <p><strong>Payment Date:</strong> {{ $bill->updated_at->format('d M Y') }}</p>
                        @else
                            <p><strong>Due Date:</strong> {{ $bill->due_date->format('d M Y') }}
                                @if($bill->status === 'overdue')
                                    <span class="text-red-600 font-semibold">Overdue</span>
                                @elseif($bill->due_date->isFuture())
                                    <span class="text-yellow-600 font-semibold">Due in {{ $bill->due_date->diffInDays(now()) }} days</span>
                                @endif
                            </p>
                        @endif
                    </div>

                    <h3 class="text-base font-semibold text-slate-800 mb-1">Billing Summary</h3>
                    <div class="text-xs text-slate-700 space-y-0.5">
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
        <div class="text-center text-xs text-slate-500 py-2 border-t border-slate-200 relative z-10">
            &copy; {{ date('Y') }} Powered by PayFlow Systems Ltd. All rights reserved.
        </div>
    </div>
</body>
</html>