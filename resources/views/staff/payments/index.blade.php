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
                <span class="text-gray-400 mt-1 fw-semibold fs-6">Total {{ $payments instanceof \Illuminate\Pagination\LengthAwarePaginator ? $payments->total() : $payments->count() }} Payments</span>
            </h3>
            <div class="card-toolbar">
                <form method="GET" action="{{ route('staff.payments.index') }}" class="d-flex flex-stack flex-wrap gap-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="per_page" id="per_page" class="form-control form-control-solid" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 per page</option>
                                <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20 per page</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 per page</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 per page</option>
                                <option value="500" {{ request('per_page') == '500' ? 'selected' : '' }}>500 per page</option>
                                <option value="1000" {{ request('per_page') == '1000' ? 'selected' : '' }}>1000 per page</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control form-control-solid w-250px" data-control="select2">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Payment Status</label>
                            <select name="status" id="status" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="category_id" class="form-label">Customer Category</label>
                            <select name="category_id" id="category_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tariff_id" class="form-label">Customer Tariff</label>
                            <select name="tariff_id" id="tariff_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Tariffs</option>
                                @foreach ($tariffs as $tariff)
                                    <option value="{{ $tariff->id }}" {{ request('tariff_id') == $tariff->id ? 'selected' : '' }}>
                                        {{ $tariff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="lga_id" class="form-label">Local Government Area (LGA)</label>
                            <select name="lga_id" id="lga_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All LGAs</option>
                                @foreach ($lgas as $lga)
                                    <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>
                                        {{ $lga->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ward_id" class="form-label">Ward</label>
                            <select name="ward_id" id="ward_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Wards</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>
                                        {{ $ward->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="area_id" class="form-label">Area</label>
                            <select name="area_id" id="area_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Areas</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                   class="form-control form-control-solid w-200px" placeholder="Start Date" />
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                   class="form-control form-control-solid w-200px" placeholder="End Date" />
                        </div>
                        <div class="col-md-4">
                            <label for="method" class="form-label">Payment Method</label>
                            <select name="method" id="method" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Methods</option>
                                @foreach ($payments->pluck('method')->unique()->filter() as $method)
                                    <option value="{{ $method }}" {{ request('method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="{{ route('staff.payments.index') }}" class="btn btn-light btn-sm">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_payment_table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Customer Name</th>
                        <th class="text-end pe-3 min-w-100px">Payment Date</th>
                        <th class="text-end pe-3 min-w-100px">Bill ID</th>
                        <th class="text-end pe-3 min-w-100px">Payment Amount</th>
                        <th class="text-end pe-3 min-w-100px">Payment Method</th>
                        <th class="text-end pe-3 min-w-100px">Payment Status</th>
                        <th class="text-end pe-0 min-w-150px">Transaction Reference</th>
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
                                <span class="badge py-3 px-4 fs-7 badge-light-{{ $payment->payment_status === 'successful' ? 'success' : ($payment->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($payment->payment_status) }}
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
            @if ($payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-3">
                    {{ $payments->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @elseif ($payments instanceof \Illuminate\Database\Eloquent\Collection)
                <div class="mt-3">
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
                </div>
            @endif
        </div>
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
            const methodSelect = document.getElementById('method');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const perPageSelect = document.getElementById('per_page');
            let filterTimeout;

            function updateURL() {
                const customer = customerSelect.value;
                const status = statusSelect.value;
                const category = categorySelect.value;
                const tariff = tariffSelect.value;
                const lga = lgaSelect.value;
                const ward = wardSelect.value;
                const area = areaSelect.value;
                const method = methodSelect.value;
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const perPage = perPageSelect.value;
                const url = new URL('{{ route("staff.payments.index") }}');

                // Only add parameters if they have values
                if (customer) url.searchParams.set('customer_id', customer);
                if (status) url.searchParams.set('status', status);
                if (category) url.searchParams.set('category_id', category);
                if (tariff) url.searchParams.set('tariff_id', tariff);
                if (lga) url.searchParams.set('lga_id', lga);
                if (ward) url.searchParams.set('ward_id', ward);
                if (area) url.searchParams.set('area_id', area);
                if (method) url.searchParams.set('method', method);
                if (startDate) url.searchParams.set('start_date', startDate);
                if (endDate) url.searchParams.set('end_date', endDate);
                if (perPage && perPage !== '10') url.searchParams.set('per_page', perPage); // Only add if not default

                // If no filters, clear all parameters except per_page
                if (!customer && !status && !category && !tariff && !lga && !ward && !area && !method && !startDate && !endDate) {
                    const newUrl = new URL(url.origin + url.pathname);
                    if (perPage && perPage !== '10') newUrl.searchParams.set('per_page', perPage);
                    window.location.href = newUrl.toString();
                } else {
                    window.location.href = url.toString();
                }
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
            methodSelect.addEventListener('change', handleInput);
            startDateInput.addEventListener('change', handleInput);
            endDateInput.addEventListener('change', handleInput);
            perPageSelect.addEventListener('change', handleInput);

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
