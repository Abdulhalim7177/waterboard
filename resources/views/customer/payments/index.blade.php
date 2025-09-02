@extends('layouts.customer')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <!--begin::Card-->
        <div class="card card-flush shadow-md h-xl-100">
            <!--begin::Card header-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Payment History</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">View all your payment transactions</span>
                </h3>
                <!--end::Title-->
                <!--begin::Actions-->
                <div class="card-toolbar">
                    <div class="d-flex flex-stack flex-wrap gap-4">
                        <!--begin::Method Filter-->
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Method</div>
                            <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                <option></option>
                                <option value="Show All" selected>Show All</option>
                                @foreach ($payments->pluck('method')->unique() as $method)
                                    <option value="{{ $method }}">{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Method Filter-->
                        <!--begin::Status Filter-->
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Status</div>
                            <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-5="filter_status">
                                <option></option>
                                <option value="Show All" selected>Show All</option>
                                <option value="SUCCESSFUL">Successful</option>
                                <option value="FAILED">Failed</option>
                                <option value="PENDING">Pending</option>
                            </select>
                        </div>
                        <!--end::Status Filter-->
                    </div>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Payment Date</th>
                            <th class="text-end pe-3 min-w-100px">Bill ID</th>
                            <th class="text-end pe-3 min-w-100px">Amount</th>
                            <th class="text-end pe-3 min-w-100px">Method</th>
                            <th class="text-end pe-3 min-w-100px">Status</th>
                            <th class="text-end pe-0 min-w-150px">Transaction Ref</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s') }}</td>
                                <td class="text-end">{{ $payment->bill_id ? $payment->bill->billing_id : ($payment->bill_ids ? 'Multiple (' . $payment->bill_ids . ')' : 'N/A') }}</td>
                                <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                                <td class="text-end">{{ $payment->method }}</td>
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7 
                                        {{ $payment->payment_status === 'SUCCESSFUL' ? 'badge-light-primary' : 
                                           ($payment->payment_status === 'FAILED' ? 'badge-light-danger' : 'badge-light-warning') }}">
                                        {{ $payment->payment_status }}
                                    </span>
                                </td>
                                <td class="text-end">{{ $payment->transaction_ref ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
            <div class="card-footer">
                  <div class="mt-4">
                {{ $payments->links('pagination::bootstrap-5') }}
            </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection