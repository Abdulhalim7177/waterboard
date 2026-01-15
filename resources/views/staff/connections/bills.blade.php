@extends('layouts.staff')

@section('page_title')
    Connection Bills Management
@endsection

@section('page_description')
    Manage all bills related to service connections
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

    <!-- Stats Cards -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-3">
            <div class="card bg-light-primary card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</span>
                    <h3 class="text-primary fw-bold mt-2">Total Bills</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card bg-light-success card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-success">{{ $stats['paid'] }}</span>
                    <h3 class="text-success fw-bold mt-2">Paid Bills</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card bg-light-danger card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-danger">{{ $stats['unpaid'] }}</span>
                    <h3 class="text-danger fw-bold mt-2">Unpaid Bills</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card bg-light-warning card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-warning">{{ $stats['pending_approval'] }}</span>
                    <h3 class="text-warning fw-bold mt-2">Pending Approval</h3>
                </div>
            </div>
        </div>
    </div>


    <!-- Bills Table -->
    <div class="card card-flush h-xl-100">
        <div class="card-header border-0 pt-7">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Connection Bills</span>
            </h3>
            <div class="card-toolbar">
                <div class="d-flex flex-wrap align-items-end gap-4">
                    <div class="d-flex align-items-center position-relative my-1">
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search by Name, Email, Billing ID" />
                    </div>
                    <form method="GET" action="{{ route('staff.connections.bills') }}" class="d-flex flex-wrap align-items-end gap-4" id="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                       class="form-control form-control-solid w-200px" placeholder="Start Date" />
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                       class="form-control form-control-solid w-200px" placeholder="End Date" />
                            </div>
                            <div class="col-md-3">
                                <label for="tariff_id" class="form-label">Connection Type</label>
                                <select name="tariff_id" id="tariff_id" class="form-select form-select-solid w-200px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach($tariffs as $tariff)
                                        <option value="{{ $tariff->id }}" {{ request('tariff_id') == $tariff->id ? 'selected' : '' }}>{{ $tariff->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="lga_id" class="form-label">LGA</label>
                                <select name="lga_id" id="lga_id" class="form-select form-select-solid w-200px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach($lgas as $lga)
                                        <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="ward_id" class="form-label">Ward</label>
                                <select name="ward_id" id="ward_id" class="form-select form-select-solid w-200px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="area_id" class="form-label">Area</label>
                                <select name="area_id" id="area_id" class="form-select form-select-solid w-200px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="position-relative align-self-end">
                            <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                            <a href="{{ route('staff.connections.bills') }}" class="btn btn-light btn-sm ms-2">Clear Filters</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body pt-0 mt-5">
            <!-- Bills Table -->
            <div class="table-responsive position-relative">
                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_bills_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Customer</th>
                            <th class="text-end pe-3 min-w-100px">Billing ID</th>
                            <th class="text-end pe-3 min-w-100px">Service Type</th>
                            <th class="text-end pe-3 min-w-100px">Amount</th>
                            <th class="text-end pe-3 min-w-100px">Due Date</th>
                            <th class="text-end pe-3 min-w-100px">Status</th>
                            <th class="text-end pe-3 min-w-100px">Approval Status</th>
                            <th class="text-end pe-3 min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600" id="bills-table-body">
                        @forelse ($bills as $bill)
                            <tr data-name="{{ $bill->customer->first_name }} {{ $bill->customer->surname }}"
                                data-billing-id="{{ $bill->billing_id }}"
                                data-email="{{ $bill->customer->email }}">
                                <td>{{ $bill->customer->first_name }} {{ $bill->customer->surname }}</td>
                                <td class="text-end">{{ $bill->billing_id }}</td>
                                <td class="text-end">{{ $bill->tariff->name }}</td>
                                <td class="text-end">₦{{ number_format($bill->amount, 2) }}</td>
                                <td class="text-end">{{ \Carbon\Carbon::parse($bill->due_date)->format('Y-m-d') }}</td>
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7 badge-light-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'unpaid' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7 badge-light-{{ $bill->approval_status === 'approved' ? 'success' : ($bill->approval_status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($bill->approval_status) }}
                                    </span>
                                </td>
                                <td class="text-end">
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
                                <td colspan="8" class="text-center">No Connection Bills found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2
        $('#tariff_id').select2();
        $('#lga_id').select2();
        $('#ward_id').select2();
        $('#area_id').select2();
        
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('bills-table-body');
        const tableRows = tableBody.getElementsByTagName('tr');

        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const name = row.dataset.name.toLowerCase();
                const billingId = row.dataset.billingId.toLowerCase();
                const email = row.dataset.email.toLowerCase();

                if (name.includes(searchTerm) ||
                    billingId.includes(searchTerm) ||
                    email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        searchInput.addEventListener('keyup', filterRows);
    });
</script>
@endsection
