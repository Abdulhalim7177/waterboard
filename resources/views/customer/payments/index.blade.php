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
                    <form id="payment_filter_form" action="{{ route('customer.payments') }}" method="GET" class="d-flex align-items-center">
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Show</div>
                            <select name="per_page" class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </div>
                    </form>
                    <form id="customer_payment_filters" method="GET" class="d-flex flex-stack flex-wrap gap-4 ms-4">
                        <!--begin::Method Filter-->
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Payment Method</div>
                            <select name="method" id="method_filter" class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="All Methods">
                                <option value="">All Methods</option>
                                @foreach ($payments->pluck('method')->unique()->filter() as $method)
                                    <option value="{{ $method }}" {{ request('method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Method Filter-->
                        <!--begin::Status Filter-->
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Payment Status</div>
                            <select name="status" id="status_filter" class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="All Statuses" data-kt-table-widget-5="filter_status">
                                <option value="">All Statuses</option>
                                <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <!--end::Status Filter-->
                        <div class="d-flex align-items-center">
                            <a href="{{ route('customer.payments') }}" class="btn btn-sm btn-light ms-2">Reset</a>
                        </div>
                    </form>
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
                            <th class="text-end pe-3 min-w-100px">Payment Amount</th>
                            <th class="text-end pe-3 min-w-100px">Payment Method</th>
                            <th class="text-end pe-3 min-w-100px">Payment Status</th>
                            <th class="text-end pe-0 min-w-150px">Transaction Reference</th>
                            <th class="text-end pe-0 min-w-150px">Payment Source</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s') }}</td>
                                <td class="text-end">{{ $payment->bill_id ? $payment->bill->billing_id : ($payment->bill_ids ? 'Multiple (' . $payment->bill_ids . ')' : 'N/A') }}</td>
                                <td class="text-end">₦{{ number_format($payment->amount, 2) }}</td>
                                <td class="text-end">{{ $payment->method }}</td>
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7
                                        {{ $payment->payment_status === 'successful' ? 'badge-light-primary' :
                                           ($payment->payment_status === 'failed' ? 'badge-light-danger' : 'badge-light-warning') }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-end">{{ $payment->transaction_ref ?? 'N/A' }}</td>
                                <td class="text-end">
                                    @if ($payment->channel === 'Vendor Payment')
                                        <span class="badge badge-light-success">Vendor Payment</span>
                                    @else
                                        <span class="badge badge-light-primary">Direct Payment</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
            <div class="card-footer">
                <div class="mt-4">
                    @if ($payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $payments->links('pagination::bootstrap-5') }}
                    @elseif ($payments instanceof \Illuminate\Database\Eloquent\Collection)
                        <nav>
                            <ul class="pagination">
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link">@lang('pagination.previous')</span>
                                </li>
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link">@lang('pagination.next')</span>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>

    <!-- Add JavaScript for customer payment filters -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2 for payment filters
            const methodSelect = document.getElementById('method_filter');
            const statusSelect = document.getElementById('status_filter');

            if (methodSelect) {
                $(methodSelect).select2({
                    placeholder: methodSelect.options[0].text,
                    allowClear: true,
                    minimumResultsForSearch: 10
                });
            }

            if (statusSelect) {
                $(statusSelect).select2({
                    placeholder: statusSelect.options[0].text,
                    allowClear: true,
                    minimumResultsForSearch: 10
                });
            }

            // Debounced filter handling for customer payments
            let customerPaymentFilterTimeout;

            function updateCustomerPaymentFilters() {
                const method = document.getElementById('method_filter').value;
                const status = document.getElementById('status_filter').value;
                const perPage = document.querySelector('select[name="per_page"]').value;

                const url = new URL('{{ route("customer.payments") }}');

                // Only add parameters if they have values
                if (method) url.searchParams.set('method', method);
                if (status) url.searchParams.set('status', status);
                if (perPage && perPage !== '10') url.searchParams.set('per_page', perPage);

                window.location.href = url.toString();
            }

            function handleCustomerPaymentInput() {
                clearTimeout(customerPaymentFilterTimeout);
                customerPaymentFilterTimeout = setTimeout(updateCustomerPaymentFilters, 500);
            }

            // Add event listeners for customer payment filters
            if (methodSelect) methodSelect.addEventListener('change', handleCustomerPaymentInput);
            if (statusSelect) statusSelect.addEventListener('change', handleCustomerPaymentInput);

            // Prevent Select2 keypress events from bubbling
            document.addEventListener('keydown', function (event) {
                if (event.target.classList.contains('select2-search__field')) {
                    event.stopPropagation();
                }
            });
        });
    </script>
@endsection