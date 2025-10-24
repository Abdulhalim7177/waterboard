@extends('layouts.staff')

@section('page_title')
    Billing Management
@endsection

@section('page_description')
    Generate and manage customer bills
@endsection

@section('content')
<div class="container">
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

    <!-- Report Generation Form -->
    @can('view-report', App\Models\Bill::class)
        <div class="card card-flush mb-6">
            <div class="card-header border-0 pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Generate Reports</span>
                </h3>
            </div>
            <div class="card-body pt-0">
                <form id="report-form" class="d-flex flex-wrap align-items-end gap-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Report Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control form-control-solid w-200px">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Report End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control form-control-solid w-200px">
                        </div>
                        <div class="col-md-4">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Select Customer to Filter By</label>
                            <select name="customer_id" id="customer_id" class="form-control form-control-solid w-250px" data-control="select2">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Select Category to Filter By</label>
                            <select name="category_id" id="category_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tariff_id" class="block text-sm font-medium text-gray-700">Select Tariff to Filter By</label>
                            <select name="tariff_id" id="tariff_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Tariffs</option>
                                @foreach ($tariffs as $tariff)
                                    <option value="{{ $tariff->id }}">{{ $tariff->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="lga_id" class="block text-sm font-medium text-gray-700">Select LGA to Filter By</label>
                            <select name="lga_id" id="lga_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All LGAs</option>
                                @foreach ($lgas as $lga)
                                    <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ward_id" class="block text-sm font-medium text-gray-700">Select Ward to Filter By</label>
                            <select name="ward_id" id="ward_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Wards</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="area_id" class="block text-sm font-medium text-gray-700">Select Area to Filter By</label>
                            <select name="area_id" id="area_id" class="form-control form-control-solid w-200px" data-control="select2">
                                <option value="">All Areas</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="position-relative align-self-end">
                        <button type="submit" formaction="{{ route('staff.reports.combined') }}" class="btn btn-primary btn-sm">Generate Combined Financial Report</button>
                        <button type="submit" formaction="{{ route('staff.reports.billing') }}" class="btn btn-primary btn-sm ms-2">Generate Billing Summary Report</button>
                        <button type="submit" formaction="{{ route('staff.reports.payment') }}" class="btn btn-primary btn-sm ms-2">Generate Payment History Report</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    <!-- Bills Table -->
    <div class="card card-flush h-xl-100">
        <div class="card-header border-0 pt-7">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Manage Bills</span>
                <span class="text-gray-400 mt-1 fw-semibold fs-6">Total {{ $bills instanceof \Illuminate\Pagination\LengthAwarePaginator ? $bills->total() : $bills->count() }} Bills</span>
            </h3>
            <div class="card-toolbar">
                <div class="d-flex flex-stack flex-wrap gap-4">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('staff.bills.index') }}" class="d-flex flex-wrap align-items-end gap-4" id="filter-form">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select name="year_month" id="year_month" class="form-control form-control-solid w-200px" data-control="select2">
                                    <option value="">All Months</option>
                                    @foreach ($yearMonths as $ym)
                                        @if($ym)
                                            @php
                                                $label = $ym;
                                                try {
                                                    if (preg_match('/^\d{6}$/', $ym)) {
                                                        // Format like 202509
                                                        $label = \Carbon\Carbon::createFromFormat('Ym', $ym)->format('F Y');
                                                    } elseif (preg_match('/^\d{4}-\d{2}$/', $ym)) {
                                                        // Format like 2025-09
                                                        $label = \Carbon\Carbon::createFromFormat('Y-m', $ym)->format('F Y');
                                                    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $ym)) {
                                                        // Format like 2025-09-01
                                                        $label = \Carbon\Carbon::parse($ym)->format('F Y');
                                                    } else {
                                                        // Try a generic parse and fallback to raw value on failure
                                                        $label = \Carbon\Carbon::parse($ym)->format('F Y');
                                                    }
                                                } catch (\Exception $e) {
                                                    $label = $ym;
                                                }
                                            @endphp
                                            <option value="{{ $ym }}" {{ request('year_month') == $ym ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="customer_id" id="customer_id_filter" class="form-control form-control-solid w-250px" data-control="select2">
                                    <option value="">All Customers</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="category_id" id="category_id_filter" class="form-control form-control-solid w-200px" data-control="select2">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="tariff_id" id="tariff_id_filter" class="form-control form-control-solid w-200px" data-control="select2">
                                    <option value="">All Tariffs</option>
                                    @foreach ($tariffs as $tariff)
                                        <option value="{{ $tariff->id }}" {{ request('tariff_id') == $tariff->id ? 'selected' : '' }}>
                                            {{ $tariff->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="lga_id" id="lga_id_filter" class="form-control form-control-solid w-200px" data-control="select2">
                                    <option value="">All LGAs</option>
                                    @foreach ($lgas as $lga)
                                        <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>
                                            {{ $lga->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="ward_id" id="ward_id_filter" class="form-control form-control-solid w-200px" data-control="select2">
                                    <option value="">All Wards</option>
                                    @foreach ($wards as $ward)
                                        <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>
                                            {{ $ward->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="area_id" id="area_id_filter" class="form-control form-control-solid w-200px" data-control="select2">
                                    <option value="">All Areas</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="position-relative align-self-end">
                            <select name="per_page" id="per_page" class="form-select form-select-solid" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 per page</option>
                                <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20 per page</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 per page</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 per page</option>
                                <option value="500" {{ request('per_page') == '500' ? 'selected' : '' }}>500 per page</option>
                                <option value="1000" {{ request('per_page') == '1000' ? 'selected' : '' }}>1000 per page</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </div>
                        <div class="position-relative align-self-end">
                            <button type="submit" class="btn btn-primary btn-sm">Apply Billing Filters</button>
                            <a href="{{ route('staff.bills.index') }}" class="btn btn-light btn-sm ms-2">Clear Billing Filters</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body pt-0 mt-5">
            <!-- Action Buttons -->
            @can('create-bill', App\Models\Bill::class)
                <form action="{{ route('staff.bills.generate') }}" method="POST" class="d-inline-block me-2 mb-3">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Generate Monthly Customer Bills</button>
                </form>
            @can('approve-bill', App\Models\Bill::class)
                <form action="{{ route('staff.bills.approve-all') }}" method="POST" class="d-inline-block me-2 mb-3">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve all pending bills? This action cannot be undone.')">Approve All Pending Bills</button>
                </form>
                <form action="{{ route('staff.bills.download-bulk') }}" method="GET" class="d-inline-block mb-3">
                    @foreach (['year_month', 'customer_id', 'category_id', 'tariff_id', 'lga_id', 'ward_id', 'area_id'] as $filter)
                        @if (request($filter))
                            <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-primary btn-sm">Download Filtered Bills (PDF)</button>
                </form>
            @endcan
            @endcan

            <!-- Bills Table -->
            <div class="table-responsive position-relative">
                <!-- Table Top Bar -->
                <div class="d-flex justify-content-end mb-2">
                    <!-- Column Toggle Dropdown -->
                    <div>
                        <a href="#" class="btn btn-light btn-sm btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            Select Display Columns
                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-250px py-4" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content fs-6 fw-bold text-gray-900">Select Columns (6-10)</div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="customer" checked disabled />
                                        <label class="form-check-label">Customer</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="billing_id" checked disabled />
                                        <label class="form-check-label">Billing ID</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="amount" checked disabled />
                                        <label class="form-check-label">Amount</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="due_date" checked disabled />
                                        <label class="form-check-label">Due Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="status" checked disabled />
                                        <label class="form-check-label">Status</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="balance" checked />
                                        <label class="form-check-label">Balance</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="approval_status" checked />
                                        <label class="form-check-label">Approval Status</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="category" checked />
                                        <label class="form-check-label">Category</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="tariff" checked />
                                        <label class="form-check-label">Tariff</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="lga" checked />
                                        <label class="form-check-label">LGA</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="ward" checked />
                                        <label class="form-check-label">Ward</label>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item px-3">
                                <div class="menu-content">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input column-toggle" type="checkbox" value="area" checked />
                                        <label class="form-check-label">Area</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_bills_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px" data-column="customer">Customer</th>
                            <th class="text-end pe-3 min-w-100px" data-column="billing_id">Billing ID</th>
                            <th class="text-end pe-3 min-w-100px" data-column="amount">Amount</th>
                            <th class="text-end pe-3 min-w-100px" data-column="due_date">Due Date</th>
                            <th class="text-end pe-3 min-w-100px" data-column="status">Status</th>
                            <th class="text-end pe-3 min-w-100px" data-column="balance">Balance</th>
                            <th class="text-end pe-3 min-w-100px" data-column="approval_status">Approval Status</th>
                            <th class="text-end pe-3 min-w-100px" data-column="category">Category</th>
                            <th class="text-end pe-3 min-w-100px" data-column="tariff">Tariff</th>
                            <th class="text-end pe-3 min-w-100px" data-column="lga">LGA</th>
                            <th class="text-end pe-3 min-w-100px" data-column="ward">Ward</th>
                            <th class="text-end pe-3 min-w-100px" data-column="area">Area</th>
                            <th class="text-end pe-3 min-w-100px" data-column="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @forelse ($bills as $bill)
                            <tr>
                                <td data-column="customer">{{ $bill->customer->first_name }} {{ $bill->customer->surname }}</td>
                                <td class="text-end" data-column="billing_id">{{ $bill->billing_id }}</td>
                                <td class="text-end" data-column="amount">₦{{ number_format($bill->amount, 2) }}</td>
                                <td class="text-end" data-column="due_date">{{ \Carbon\Carbon::parse($bill->due_date)->format('Y-m-d') }}</td>
                                <td class="text-end" data-column="status">
                                    <span class="badge py-3 px-4 fs-7 badge-light-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'unpaid' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                                <td class="text-end" data-column="balance">₦{{ number_format($bill->balance, 2) }}</td>
                                <td class="text-end" data-column="approval_status">
                                    <span class="badge py-3 px-4 fs-7 badge-light-{{ $bill->approval_status === 'approved' ? 'success' : ($bill->approval_status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($bill->approval_status) }}
                                    </span>
                                </td>
                                <td class="text-end" data-column="category">{{ $bill->customer->category->name ?? 'N/A' }}</td>
                                <td class="text-end" data-column="tariff">{{ $bill->customer->tariff->name ?? 'N/A' }}</td>
                                <td class="text-end" data-column="lga">{{ $bill->customer->lga ? $bill->customer->lga->name : 'N/A' }}</td>
                                <td class="text-end" data-column="ward">{{ $bill->customer->ward ? $bill->customer->ward->name : 'N/A' }}</td>
                                <td class="text-end" data-column="area">{{ $bill->customer->area ? $bill->customer->area->name : 'N/A' }}</td>
                                <td class="text-end" data-column="actions">
                                    @can('view-bill', $bill)
                                        <a href="{{ route('staff.bills.download-pdf', $bill) }}" class="btn btn-sm btn-light btn-active-light-primary">Download PDF</a>
                                    @endcan
                                    @can('approve-bill', $bill)
                                        @if ($bill->approval_status === 'pending')
                                            <form action="{{ route('staff.bills.approve', $bill) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light btn-active-light-success me-2">Approve</button>
                                            </form>
                                            <form action="{{ route('staff.bills.reject', $bill) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light btn-active-light-danger">Reject</button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No Bills found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($bills instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-3">
                    {{ $bills->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
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
                    minimumResultsForSearch: 1
                });
            });

            // Debounced filter handling
            const yearMonthSelect = document.getElementById('year_month');
            const customerSelect = document.getElementById('customer_id_filter');
            const categorySelect = document.getElementById('category_id_filter');
            const tariffSelect = document.getElementById('tariff_id_filter');
            const lgaSelect = document.getElementById('lga_id_filter');
            const wardSelect = document.getElementById('ward_id_filter');
            const areaSelect = document.getElementById('area_id_filter');
            const perPageSelect = document.getElementById('per_page');
            const filterForm = document.getElementById('filter-form');
            let filterTimeout;

            function updateURL() {
                const yearMonth = yearMonthSelect.value;
                const customer = customerSelect.value;
                const category = categorySelect.value;
                const tariff = tariffSelect.value;
                const lga = lgaSelect.value;
                const ward = wardSelect.value;
                const area = areaSelect.value;
                const url = new URL('{{ route("staff.bills.index") }}');
                if (yearMonth) url.searchParams.set('year_month', yearMonth);
                if (customer) url.searchParams.set('customer_id', customer);
                if (category) url.searchParams.set('category_id', category);
                if (tariff) url.searchParams.set('tariff_id', tariff);
                if (lga) url.searchParams.set('lga_id', lga);
                if (ward) url.searchParams.set('ward_id', ward);
                if (area) url.searchParams.set('area_id', area);
                if (perPageSelect.value) url.searchParams.set('per_page', perPageSelect.value);
                window.location.href = url.toString();
            }

            function handleInput() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(updateURL, 500);
            }

            yearMonthSelect.addEventListener('change', handleInput);
            customerSelect.addEventListener('change', handleInput);
            categorySelect.addEventListener('change', handleInput);
            tariffSelect.addEventListener('change', handleInput);
            lgaSelect.addEventListener('change', handleInput);
            wardSelect.addEventListener('change', handleInput);
            areaSelect.addEventListener('change', handleInput);
            perPageSelect.addEventListener('change', handleInput);

            // Prevent Select2 keypress events from bubbling
            document.addEventListener('keydown', function (event) {
                if (event.target.classList.contains('select2-search__field')) {
                    event.stopPropagation();
                }
            });

            // Column toggle handling
            const table = document.getElementById('kt_bills_table');
            const columnToggles = document.querySelectorAll('.column-toggle');
            const MIN_COLUMNS = 6;
            const MAX_COLUMNS = 10;

            function updateColumnVisibility() {
                const checkedCount = Array.from(columnToggles).filter(cb => cb.checked).length;
                const selectableToggles = Array.from(columnToggles).filter(cb => !cb.disabled);
                const disableCheckboxes = checkedCount <= MIN_COLUMNS;

                selectableToggles.forEach(checkbox => {
                    if (!checkbox.checked && checkedCount >= MAX_COLUMNS) {
                        checkbox.disabled = true;
                    } else if (checkbox.checked && disableCheckboxes) {
                        checkbox.disabled = true;
                    } else {
                        checkbox.disabled = false;
                    }

                    const column = checkbox.value;
                    const elements = table.querySelectorAll(`[data-column="${column}"]`);
                    elements.forEach(el => {
                        el.style.display = checkbox.checked ? '' : 'none';
                    });
                });
            }

            columnToggles.forEach(checkbox => {
                checkbox.addEventListener('change', updateColumnVisibility);
            });

            // Initialize column visibility
            updateColumnVisibility();
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
        .table-responsive {
            overflow-x: auto;
        }
        .form-control-solid {
            border: 1px solid #e4e6ef;
            background-color: #f5f8fa;
        }
        .form-control-solid:focus {
            border-color: #009ef7;
            background-color: #ffffff;
        }
        .card-flush {
            border: 1px solid #e4e6ef;
            border-radius: 0.5rem;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
        }
        .badge-light-success {
            background-color: #e6f9e6;
            color: #28a745;
        }
        .badge-light-danger {
            background-color: #f9e6e6;
            color: #dc3545;
        }
        .badge-light-warning {
            background-color: #fff3cd;
            color: #ffc107;
        }
    </style>
@endsection