@extends('layouts.staff')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="container mx-auto px-4 py-8">
     <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Payment History Table -->
    <div class="card card-flush h-xl-100">
        <div class="card-header border-0 pt-7">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">All Payment Transactions</span>
                <span class="text-gray-400 mt-1 fw-semibold fs-6">Total {{ $payments->total() }} Payments</span>
            </h3>
            <div class="card-toolbar">
                <form method="GET" action="{{ route('staff.payments.index') }}" class="d-flex flex-stack flex-wrap gap-4">
                    <div class="d-flex align-items-center position-relative my-1">
                        <select name="customer_id" id="customer_id" class="form-control form-control-solid w-250px" data-control="select2">
                            <option value="">All Customers</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <select name="status" id="status" class="form-control form-control-solid w-200px" data-control="select2">
                            <option value="">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <select name="category_id" id="category_id" class="form-control form-control-solid w-200px" data-control="select2">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <select name="tariff_id" id="tariff_id" class="form-control form-control-solid w-200px" data-control="select2">
                            <option value="">All Tariffs</option>
                            @foreach ($tariffs as $tariff)
                                <option value="{{ $tariff->id }}" {{ request('tariff_id') == $tariff->id ? 'selected' : '' }}>
                                    {{ $tariff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <select name="lga_id" id="lga_id" class="form-control form-control-solid w-200px" data-control="select2">
                            <option value="">All LGAs</option>
                            @foreach ($lgas as $lga)
                                <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>
                                    {{ $lga->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <select name="ward_id" id="ward_id" class="form-control form-control-solid w-200px" data-control="select2">
                            <option value="">All Wards</option>
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>
                                    {{ $ward->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <select name="area_id" id="area_id" class="form-control form-control-solid w-200px" data-control="select2">
                            <option value="">All Areas</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                               class="form-control form-control-solid w-200px" placeholder="Start Date" />
                    </div>
                    <div class="d-flex align-items-center position-relative my-1">
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                               class="form-control form-control-solid w-200px" placeholder="End Date" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_payment_table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Customer</th>
                        <th class="text-end pe-3 min-w-100px">Payment Date</th>
                        <th class="text-end pe-3 min-w-100px">Bill ID</th>
                        <th class="text-end pe-3 min-w-100px">Amount</th>
                        <th class="text-end pe-3 min-w-100px">Method</th>
                        <th class="text-end pe-3 min-w-100px">Status</th>
                        <th class="text-end pe-0 min-w-150px">Transaction Ref</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                    @forelse ($payments as $payment)
                        <tr>
                            <td>
                                {{ $payment->customer ? $payment->customer->first_name . ' ' . $payment->customer->surname : 'N/A' }}
                            </td>
                            <td class="text-end">{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s') }}</td>
                            <td class="text-end">{{ $payment->bill_id ? $payment->bill->billing_id : ($payment->bill_ids ? 'Multiple (' . $payment->bill_ids . ')' : 'N/A') }}</td>
                            <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                            <td class="text-end">{{ $payment->method }}</td>
                            <td class="text-end">
                                <span class="badge py-3 px-4 fs-7 badge-light-{{ $payment->payment_status === 'SUCCESSFUL' ? 'success' : ($payment->payment_status === 'FAILED' ? 'danger' : 'warning') }}">
                                    {{ $payment->payment_status }}
                                </span>
                            </td>
                            <td class="text-end">{{ $payment->transaction_ref ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($payments->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $payments->previousPageUrl() }}&customer_id={{ request('customer_id') }}&status={{ request('status') }}&category_id={{ request('category_id') }}&tariff_id={{ request('tariff_id') }}&lga_id={{ request('lga_id') }}&ward_id={{ request('ward_id') }}&area_id={{ request('area_id') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}">Previous</a>
                        </li>
                    @endif
                    @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $payments->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&customer_id={{ request('customer_id') }}&status={{ request('status') }}&category_id={{ request('category_id') }}&tariff_id={{ request('tariff_id') }}&lga_id={{ request('lga_id') }}&ward_id={{ request('ward_id') }}&area_id={{ request('area_id') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    @if ($payments->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $payments->nextPageUrl() }}&customer_id={{ request('customer_id') }}&status={{ request('status') }}&category_id={{ request('category_id') }}&tariff_id={{ request('tariff_id') }}&lga_id={{ request('lga_id') }}&ward_id={{ request('ward_id') }}&area_id={{ request('area_id') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2 for filter dropdowns
            document.querySelectorAll('select[data-control="select2"]').forEach(select => {
                $(select).select2({
                    placeholder: select.options[0].text,
                    allowClear: true,
                    minimumResultsForSearch: 1 // Enable search for dropdowns
                });
            });

            // Debounced filter handling
            const customerSelect = document.getElementById('customer_id');
            const statusSelect = document.getElementById('status');
            const categorySelect = document.getElementById('category_id');
            const tariffSelect = document.getElementById('tariff_id');
            const lgaSelect = document.getElementById('lga_id');
            const wardSelect = document.getElementById('ward_id');
            const areaSelect = document.getElementById('area_id');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            let filterTimeout;

            function updateURL() {
                const customer = customerSelect.value;
                const status = statusSelect.value;
                const category = categorySelect.value;
                const tariff = tariffSelect.value;
                const lga = lgaSelect.value;
                const ward = wardSelect.value;
                const area = areaSelect.value;
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const url = new URL('{{ route("staff.payments.index") }}');
                if (customer) url.searchParams.set('customer_id', customer);
                if (status) url.searchParams.set('status', status);
                if (category) url.searchParams.set('category_id', category);
                if (tariff) url.searchParams.set('tariff_id', tariff);
                if (lga) url.searchParams.set('lga_id', lga);
                if (ward) url.searchParams.set('ward_id', ward);
                if (area) url.searchParams.set('area_id', area);
                if (startDate) url.searchParams.set('start_date', startDate);
                if (endDate) url.searchParams.set('end_date', endDate);
                window.location.href = url.toString();
            }

            function handleInput() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(updateURL, 500);
            }

            customerSelect.addEventListener('change', handleInput);
            statusSelect.addEventListener('change', handleInput);
            categorySelect.addEventListener('change', handleInput);
            tariffSelect.addEventListener('change', handleInput);
            lgaSelect.addEventListener('change', handleInput);
            wardSelect.addEventListener('change', handleInput);
            areaSelect.addEventListener('change', handleInput);
            startDateInput.addEventListener('change', handleInput);
            endDateInput.addEventListener('change', handleInput);

            // Prevent Select2 keypress events from bubbling
            document.addEventListener('keydown', function (event) {
                if (event.target.classList.contains('select2-search__field')) {
                    event.stopPropagation();
                }
            });
        });
    </script>
    <style>
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        .pagination .page-link {
            color: #007bff;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
        }
        .select2-container--open {
            z-index: 9999;
        }
        .select2-container .select2-search__field {
            width: 100% !important;
        }
    </style>
@endsection
