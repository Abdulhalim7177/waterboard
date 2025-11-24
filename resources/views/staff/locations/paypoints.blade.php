@extends('layouts.staff')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQZKbw/kjIcgNk6unEJQaZI8W5Ba4I2QlUZrFf+9npJdXbAw99G6xJgNnc" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div id="kt_content_container" class="container-xxl">
    <!--begin::Toolbar-->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-4 my-6">
        <!--begin::Heading-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Paypoint Management</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('staff.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Locations</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-gray-900">Paypoints</li>
            </ul>
        </div>
        <!--end::Heading-->
        <!--begin::Controls-->
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative">
                <i class="fas fa-search fs-3 text-gray-500 position-absolute start-0 ms-4 translate-middle-y"></i>
                <input type="text" id="search_paypoint" class="form-control form-control-solid w-250px ps-12" placeholder="Search Paypoints" />
            </div>
            <!--end::Search-->
            <!--begin::Button-->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPaypointModal">
                <i class="fas fa-plus me-2"></i>
                Add Paypoint
            </button>
            <!--end::Button-->
        </div>
        <!--end::Controls-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Stats Widgets-->
    @php
    $totalStaffCount = 0;
    foreach($paypoints as $paypoint) {
    $totalStaffCount += $paypoint->staff()->count();
    }
    @endphp
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        <!--begin::Total Paypoints Card-->
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <div class="text-gray-400 fw-semibold fs-7 mb-1">Total Paypoints</div>
                        <div class="fw-bold text-gray-800 fs-2hx">{{ $paypoints->count() }}</div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-primary">
                            <i class="fas fa-credit-card fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Total Paypoints Card-->

        <!--begin::Total Staff Card-->
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <div class="text-gray-400 fw-semibold fs-7 mb-1">Total Staff</div>
                        <div class="fw-bold text-gray-800 fs-2hx">{{ $totalStaffCount }}</div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-info">
                            <i class="fas fa-users fs-1 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Total Staff Card-->

        <!--begin::Approved Paypoints Card-->
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <div class="text-gray-400 fw-semibold fs-7 mb-1">Approved Paypoints</div>
                        <div class="fw-bold text-gray-800 fs-2hx">{{ $paypoints->where('status', 'approved')->count() }}</div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-success">
                            <i class="fas fa-check-circle fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Approved Paypoints Card-->

        <!--begin::Rejected Paypoints Card-->
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <div class="text-gray-400 fw-semibold fs-7 mb-1">Rejected Paypoints</div>
                        <div class="fw-bold text-gray-800 fs-2hx">{{ $paypoints->where('status', 'rejected')->count() }}</div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-danger">
                            <i class="fas fa-times-circle fs-1 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Inactive Paypoints Card-->
    </div>
    <!--end::Stats Widgets-->

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Paypoints List</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage all paypoints in the system</span>
                </h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_paypoint_table">
                    <thead>
                        <tr class="text-start text-muted text-uppercase fw-bold fs-7 border-bottom border-gray-200">
                            <th class="min-w-200px">Paypoint Details</th>
                            <th class="min-w-100px">Code</th>
                            <th class="min-w-100px">Type</th>
                            <th class="min-w-150px">Location</th>
                            <th class="min-w-100px text-center">Status</th>
                            <th class="min-w-150px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        @forelse($paypoints as $paypoint)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="fas fa-credit-card fs-2 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('staff.paypoints.details', $paypoint->id) }}" class="text-gray-800 text-hover-primary mb-1">{{ $paypoint->name }}</a>
                                        <span class="badge badge-light-primary">{{ $paypoint->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-gray-800 fw-bold">{{ $paypoint->code }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-info">{{ ucfirst($paypoint->type) }}</span>
                            </td>
                            <td>
                                @if($paypoint->type == 'zone')
                                <span class="text-gray-600">{{ $paypoint->zone->name ?? 'N/A' }}</span>
                                @elseif($paypoint->type == 'district')
                                <span class="text-gray-600">{{ $paypoint->district->name ?? 'N/A' }}</span>
                                @else
                                <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($paypoint->status == 'approved')
                                <span class="badge badge-light-success">Approved</span>
                                @elseif($paypoint->status == 'rejected')
                                <span class="badge badge-light-danger">Rejected</span>
                                @elseif($paypoint->status == 'pending')
                                <span class="badge badge-light-warning">Pending</span>
                                @else
                                <span class="badge badge-light-danger">Pending Delete</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('staff.paypoints.details', $paypoint->id) }}" class="btn btn-sm btn-icon btn-light-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($paypoint->status != 'pending_delete')
                                    <a href="#" class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editPaypointModal{{ $paypoint->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if($paypoint->status == 'pending')
                                    <form method="POST" action="{{ route('staff.paypoints.approve', $paypoint->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-success" title="Approve" onclick="return confirm('Are you sure you want to approve this paypoint?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('staff.paypoints.reject', $paypoint->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Reject" onclick="return confirm('Are you sure you want to reject this paypoint?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @elseif($paypoint->status == 'pending_delete')
                                    <form method="POST" action="{{ route('staff.paypoints.approve', $paypoint->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-success" title="Approve Deletion" onclick="return confirm('Are you sure you want to approve deletion of this paypoint?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('staff.paypoints.reject', $paypoint->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-warning" title="Reject Deletion" onclick="return confirm('Are you sure you want to reject deletion of this paypoint?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @else
                                    <a href="#" class="btn btn-sm btn-icon btn-light-danger" data-bs-toggle="modal" data-bs-target="#deletePaypointModal{{ $paypoint->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10">
                                <div class="text-gray-400 fw-semibold">No paypoints found</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
            <!--begin::Pagination-->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <select id="items_per_page" class="form-select form-select-solid w-auto">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="all">All</option>
                    </select>
                    <span class="text-gray-600" id="pagination_description"></span>
                </div>
                <nav>
                    <ul class="pagination mb-0" id="pagination_links">
                    </ul>
                </nav>
            </div>
            <!--end::Pagination-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
<!--end::Col-->
</div>
<!--end::Row-->

<!--begin::Create Paypoint Modal-->
<div class="modal fade" id="createPaypointModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3">
            <!--begin::Modal header-->
            <div class="modal-header border-0 pb-0">
                <h2 class="modal-title fw-bold">Create New Paypoint</h2>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times fs-2x"></i>
                </button>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y px-10 py-8">
                <form method="POST" action="{{ route('staff.paypoints.store') }}">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="fv-row mb-8">
                        <label class="required form-label fs-6 fw-semibold mb-2">Paypoint Name</label>
                        <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" name="name" placeholder="Enter paypoint name" required value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-8">
                        <label class="required form-label fs-6 fw-semibold mb-2">Code</label>
                        <input type="text" class="form-control form-control-solid @error('code') is-invalid @enderror" name="code" placeholder="Enter paypoint code" required value="{{ old('code') }}">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-8">
                        <label class="required form-label fs-6 fw-semibold mb-2">Type</label>
                        <select name="type" class="form-select form-select-solid @error('type') is-invalid @enderror" data-control="select2" data-placeholder="Select type" id="paypointType" required>
                            <option value="" {{ old('type') ? '' : 'selected' }}>Select Type</option>
                            <option value="zone" {{ old('type') == 'zone' ? 'selected' : '' }}>Zone</option>
                            <option value="district" {{ old('type') == 'district' ? 'selected' : '' }}>District</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-8" id="zoneSelection" style="display:none;">
                        <label class="form-label fs-6 fw-semibold mb-2">Zone</label>
                        <select name="zone_id" class="form-select form-select-solid @error('zone_id') is-invalid @enderror" data-control="select2" data-placeholder="Select zone">
                            <option value="" {{ old('zone_id') ? '' : 'selected' }}>Select Zone</option>
                            @foreach(\App\Models\Zone::all() as $zone)
                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        @error('zone_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-8" id="districtSelection" style="display:none;">
                        <label class="form-label fs-6 fw-semibold mb-2">District</label>
                        <select name="district_id" class="form-select form-select-solid @error('district_id') is-invalid @enderror" data-control="select2" data-placeholder="Select district">
                            <option value="" {{ old('district_id') ? '' : 'selected' }}>Select District</option>
                            @foreach(\App\Models\District::all() as $district)
                            <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }} ({{ $district->zone->name }})</option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-8">
                        <label class="form-label fs-6 fw-semibold mb-2">Description</label>
                        <textarea name="description" class="form-control form-control-solid @error('description') is-invalid @enderror" rows="3" placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center pt-5">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">
                                <i class="fas fa-check me-2"></i>
                                Create Paypoint
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
<!--end::Create Paypoint Modal-->

<!--begin::Edit Paypoint Modals for each paypoint-->
@foreach($paypoints as $paypoint)
<div class="modal fade" id="editPaypointModal{{ $paypoint->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h3 class="modal-title">Edit Paypoint</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body py-lg-10 px-lg-10">
                <form method="POST" action="{{ route('staff.paypoints.update', $paypoint->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Paypoint Name</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="name" value="{{ $paypoint->name }}" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Code</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="code" value="{{ $paypoint->code }}" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Type</label>
                        <select name="type" class="form-control form-control-solid" data-control="select2" id="editPaypointType{{ $paypoint->id }}" required>
                            <option value="zone" {{ $paypoint->type == 'zone' ? 'selected' : '' }}>Zone</option>
                            <option value="district" {{ $paypoint->type == 'district' ? 'selected' : '' }}>District</option>
                        </select>
                    </div>
                    <div class="fv-row mb-10" id="editZoneSelection{{ $paypoint->id }}" style="{{ $paypoint->type == 'zone' ? 'display:block;' : 'display:none;' }}">
                        <label class="form-label fs-6 fw-bold">Zone</label>
                        <select name="zone_id" class="form-control form-control-solid" data-control="select2">
                            <option value="">Select Zone</option>
                            @foreach(\App\Models\Zone::all() as $zone)
                            <option value="{{ $zone->id }}" {{ $paypoint->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10" id="editDistrictSelection{{ $paypoint->id }}" style="{{ $paypoint->type == 'district' ? 'display:block;' : 'display:none;' }}">
                        <label class="form-label fs-6 fw-bold">District</label>
                        <select name="district_id" class="form-control form-control-solid" data-control="select2">
                            <option value="">Select District</option>
                            @foreach(\App\Models\District::all() as $district)
                            <option value="{{ $district->id }}" {{ $paypoint->district_id == $district->id ? 'selected' : '' }}>{{ $district->name }} ({{ $district->zone->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Description</label>
                        <textarea name="description" class="form-control form-control-lg form-control-solid">{{ $paypoint->description }}</textarea>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">Update</span>
                        </button>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
@endforeach

<!--begin::Delete Paypoint Modals for each paypoint-->
@foreach($paypoints as $paypoint)
<div class="modal fade" id="deletePaypointModal{{ $paypoint->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Deletion</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this paypoint ({{ $paypoint->name }})? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('staff.paypoints.destroy', $paypoint->id) }}"
                    class="btn btn-danger"
                    onclick="event.preventDefault();
                                    document.getElementById('delete-paypoint-form-{{ $paypoint->id }}').submit();">
                    Delete
                </a>
                <form id="delete-paypoint-form-{{ $paypoint->id }}"
                    action="{{ route('staff.paypoints.destroy', $paypoint->id) }}"
                    method="POST"
                    class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
<!--end::Delete Paypoint Modals-->
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search_paypoint');
        const itemsPerPageSelect = document.getElementById('items_per_page');
        const paypointTableBody = document.querySelector('#kt_paypoint_table tbody');
        const allRows = Array.from(paypointTableBody.querySelectorAll('tr'));
        const paginationLinks = document.getElementById('pagination_links');
        const paginationDescription = document.getElementById('pagination_description');

        let currentPage = 1;
        let itemsPerPage = parseInt(itemsPerPageSelect.value);
        let filteredRows = allRows;

        function renderTable() {
            paypointTableBody.innerHTML = '';
            const start = (currentPage - 1) * itemsPerPage;
            const end = itemsPerPage === -1 ? filteredRows.length : start + itemsPerPage;
            const paginatedRows = filteredRows.slice(start, end);

            paginatedRows.forEach(row => paypointTableBody.appendChild(row));

            const totalFiltered = filteredRows.length;
            const startEntry = totalFiltered > 0 ? start + 1 : 0;
            const endEntry = itemsPerPage === -1 ? totalFiltered : Math.min(start + itemsPerPage, totalFiltered);

            paginationDescription.textContent = `Showing ${startEntry} to ${endEntry} of ${totalFiltered} entries`;
        }

        function renderPagination() {
            paginationLinks.innerHTML = '';
            if (itemsPerPage === -1) return;

            const totalPages = Math.ceil(filteredRows.length / itemsPerPage);

            if (totalPages <= 1) return;

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            const prevA = document.createElement('a');
            prevA.className = 'page-link';
            prevA.href = '#';
            prevA.textContent = 'Previous';
            prevA.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                    renderPagination();
                }
            });
            prevLi.appendChild(prevA);
            paginationLinks.appendChild(prevLi);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = i;
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = i;
                    renderTable();
                    renderPagination();
                });
                li.appendChild(a);
                paginationLinks.appendChild(li);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            const nextA = document.createElement('a');
            nextA.className = 'page-link';
            nextA.href = '#';
            nextA.textContent = 'Next';
            nextA.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                    renderPagination();
                }
            });
            nextLi.appendChild(nextA);
            paginationLinks.appendChild(nextLi);
        }

        function filterAndPaginate() {
            const searchTerm = searchInput.value.toLowerCase();
            filteredRows = allRows.filter(row => {
                const paypointName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const paypointCode = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                return paypointName.includes(searchTerm) || paypointCode.includes(searchTerm);
            });
            currentPage = 1;
            renderTable();
            renderPagination();
        }

        searchInput.addEventListener('input', filterAndPaginate);

        itemsPerPageSelect.addEventListener('change', function() {
            const value = this.value;
            if (value === 'all') {
                itemsPerPage = -1;
            } else {
                itemsPerPage = parseInt(value);
            }
            currentPage = 1;
            renderTable();
            renderPagination();
        });

        // Initial render
        filterAndPaginate();

        // Handle paypoint type selection for create form
        const createTypeSelect = document.getElementById('paypointType');
        if (createTypeSelect) {
            createTypeSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const zoneSel = document.getElementById('zoneSelection');
                const districtSel = document.getElementById('districtSelection');

                if (selectedValue === 'zone') {
                    zoneSel.style.display = 'block';
                    districtSel.style.display = 'none';
                } else if (selectedValue === 'district') {
                    zoneSel.style.display = 'none';
                    districtSel.style.display = 'block';
                } else {
                    zoneSel.style.display = 'none';
                    districtSel.style.display = 'none';
                }
            });
        }

        // Handle paypoint type selection for edit forms
        @foreach($paypoints as $paypoint)
        const editTypeSelect {
            {
                $paypoint - > id
            }
        } = document.getElementById('editPaypointType{{ $paypoint->id }}');
        if (editTypeSelect {
                {
                    $paypoint - > id
                }
            }) {
            editTypeSelect {
                {
                    $paypoint - > id
                }
            }.addEventListener('change', function() {
                const selectedValue = this.value;
                const zoneSel = document.getElementById('editZoneSelection{{ $paypoint->id }}');
                const districtSel = document.getElementById('editDistrictSelection{{ $paypoint->id }}');

                if (selectedValue === 'zone') {
                    zoneSel.style.display = 'block';
                    districtSel.style.display = 'none';
                } else if (selectedValue === 'district') {
                    zoneSel.style.display = 'none';
                    districtSel.style.display = 'block';
                } else {
                    zoneSel.style.display = 'none';
                    districtSel.style.display = 'none';
                }
            });
        }
        @endforeach

    });
</script>
@endsection