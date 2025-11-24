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
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">District Management</h1>
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
                <li class="breadcrumb-item text-gray-900">Districts</li>
            </ul>
        </div>
        <!--end::Heading-->
        <!--begin::Controls-->
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <!--begin::Search & Filter-->
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center position-relative">
                    <i class="fas fa-search fs-3 text-gray-500 position-absolute start-0 ms-4 translate-middle-y"></i>
                    <input type="text" id="search_district" class="form-control form-control-solid w-250px ps-12" placeholder="Search Districts" value="{{ request('search_district') }}" />
                </div>
                <div class="position-relative">
                    <select id="zone_filter" class="form-select form-select-solid" data-control="select2" data-placeholder="Filter by zone">
                        <option value="">All Zones</option>
                        @foreach(\App\Models\Zone::all() as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_filter') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--end::Search & Filter-->
            <!--begin::Button-->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDistrictModal">
                <i class="fas fa-plus me-2"></i>
                Add District
            </button>
            <!--end::Button-->
        </div>
        <!--end::Controls-->
    </div>
    <!--end::Toolbar-->
    
    <!--begin::Stats Widgets-->
    @php
        $totalStaffCount = 0;
        $totalCustomerCount = 0;
        foreach($districts as $district) {
            $totalStaffCount += $district->staffs()->count();
            $totalCustomerCount += $district->customers()->count();
        }
    @endphp
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        <!--begin::Total Districts Card-->
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <div class="text-gray-400 fw-semibold fs-7 mb-1">Total Districts</div>
                        <div class="fw-bold text-gray-800 fs-2hx">{{ $districts->count() }}</div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-primary">
                            <i class="fas fa-map-marker-alt fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Total Districts Card-->

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

        <!--begin::Total Customers Card-->
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <div class="text-gray-400 fw-semibold fs-7 mb-1">Total Customers</div>
                        <div class="fw-bold text-gray-800 fs-2hx">{{ $totalCustomerCount }}</div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-success">
                            <i class="fas fa-user fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Total Customers Card-->

        <!--begin::Active Districts Card-->
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <div class="text-gray-400 fw-semibold fs-7 mb-1">Active Districts</div>
                        <div class="fw-bold text-gray-800 fs-2hx">{{ $districts->where('status', 'approved')->count() }}</div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-warning">
                            <i class="fas fa-check-circle fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Active Districts Card-->
    </div>
    <!--end::Stats Widgets-->

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Districts List</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage all districts in the system</span>
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted text-uppercase fw-bold fs-7 border-bottom border-gray-200">
                                    <th class="min-w-200px">District Details</th>
                                    <th class="min-w-100px">Code</th>
                                    <th class="min-w-150px">Zone</th>
                                    <th class="min-w-100px text-center">Status</th>
                                    <th class="min-w-200px text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($districts as $district)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-3">
                                                    <div class="symbol-label bg-light-primary">
                                                        <i class="ki-duotone ki-geolocation fs-2 text-primary">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('staff.districts.details', $district->id) }}" class="text-gray-800 text-hover-primary mb-1">{{ $district->name }}</a>
                                                    <span class="badge badge-light-primary">{{ $district->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold">{{ $district->code }}</span>
                                        </td>
                                        <td>
                                            @if($district->zone)
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-30px me-2">
                                                        <div class="symbol-label bg-light-info">
                                                            <i class="fas fa-flag text-info"></i>
                                                        </div>
                                                    </div>
                                                    <span class="text-gray-600">{{ $district->zone->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($district->status == 'approved')
                                                <span class="badge badge-light-success">Approved</span>
                                            @elseif($district->status == 'pending')
                                                <span class="badge badge-light-warning">Pending</span>
                                            @elseif($district->status == 'rejected')
                                                <span class="badge badge-light-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-light-secondary">{{ ucfirst(str_replace('_', ' ', $district->status)) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                <a href="#" class="btn btn-sm btn-light-info" data-bs-toggle="modal" data-bs-target="#viewDistrictModal{{ $district->id }}" title="Quick View">
                                                    <i class="fas fa-eye me-1"></i>
                                                    View
                                                </a>
                                                <a href="{{ route('staff.districts.details', $district->id) }}" class="btn btn-sm btn-light-primary" title="View Details">
                                                    <i class="fas fa-file-alt me-1"></i>
                                                    Details
                                                </a>
                                                <a href="{{ route('staff.districts.manage-wards', $district->id) }}" class="btn btn-sm btn-light-warning" title="Manage Wards">
                                                    <i class="fas fa-map me-1"></i>
                                                    Wards
                                                </a>
                                                @if($district->status != 'pending_delete')
                                                    <a href="#" class="btn btn-sm btn-light-success" data-bs-toggle="modal" data-bs-target="#editDistrictModal{{ $district->id }}" title="Edit">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Edit
                                                    </a>
                                                @endif
                                                @if($district->status == 'pending')
                                                    <button type="button" class="btn btn-sm btn-light-success" title="Approve" data-bs-toggle="modal" data-bs-target="#approveDistrictModal{{ $district->id }}">
                                                        <i class="fas fa-check me-1"></i>
                                                        Approve
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-light-danger" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectDistrictModal{{ $district->id }}">
                                                        <i class="fas fa-times me-1"></i>
                                                        Reject
                                                    </button>
                                                @elseif($district->status == 'pending_delete')
                                                    <button type="button" class="btn btn-sm btn-light-success" title="Approve Deletion" data-bs-toggle="modal" data-bs-target="#approveDistrictModal{{ $district->id }}">
                                                        <i class="fas fa-check me-1"></i>
                                                        Approve Del
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-light-warning" title="Reject Deletion" data-bs-toggle="modal" data-bs-target="#rejectDistrictModal{{ $district->id }}">
                                                        <i class="fas fa-times me-1"></i>
                                                        Reject Del
                                                    </button>
                                                @else
                                                    <a href="#" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#deleteDistrictModal{{ $district->id }}" title="Delete">
                                                        <i class="fas fa-trash me-1"></i>
                                                        Delete
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-10">
                                            <div class="text-gray-400 fw-semibold">No districts found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                    
                    <!--begin::Pagination-->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <select id="items_per_page" class="form-select form-select-solid me-3">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="all">All</option>
                            </select>
                            <span id="pagination_description"></span>
                        </div>
                        <nav>
                            <ul class="pagination" id="pagination_links">
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
</div>

<!--begin::Create District Modal-->
<div class="modal fade" id="createDistrictModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h3 class="modal-title">Create District</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body py-lg-10 px-lg-10">
                <form method="POST" action="{{ route('staff.districts.store') }}">
                    @csrf
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">District Name</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="name" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Code</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="code" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Zone</label>
                        <select name="zone_id" class="form-control form-control-solid" data-control="select2" required>
                            <option value="">Select Zone</option>
                            @foreach(\App\Models\Zone::all() as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
<!--end::Create District Modal-->

<!--begin::Edit District Modals for each district-->
@foreach($districts as $district)
<div class="modal fade" id="editDistrictModal{{ $district->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h3 class="modal-title">Edit District</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body py-lg-10 px-lg-10">
                <form method="POST" action="{{ route('staff.districts.update', $district->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">District Name</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="name" value="{{ $district->name }}" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Code</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="code" value="{{ $district->code }}" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Zone</label>
                        <select name="zone_id" class="form-control form-control-solid" data-control="select2" required>
                            <option value="">Select Zone</option>
                            @foreach(\App\Models\Zone::all() as $zone)
                                <option value="{{ $zone->id }}" {{ $district->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                            @endforeach
                        </select>
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
<!--end::Edit District Modals-->

<!--begin::View District Modal for each district-->
@foreach($districts as $district)
<div class="modal fade" id="viewDistrictModal{{ $district->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h3 class="modal-title">View District</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body py-lg-10 px-lg-10">
                <p><strong>Name:</strong> {{ $district->name }}</p>
                <p><strong>Code:</strong> {{ $district->code }}</p>
                <p><strong>Zone:</strong> {{ $district->zone ? $district->zone->name : 'N/A' }}</p>
                <h5>Wards</h5>
                @if($district->wards->count() > 0)
                    <ul>
                        @foreach($district->wards as $ward)
                            <li>{{ $ward->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No wards assigned to this district.</p>
                @endif
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
@endforeach
@endsection
<!--end::View District Modals-->
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search_district');
        const zoneFilter = document.getElementById('zone_filter');
        const itemsPerPageSelect = document.getElementById('items_per_page');
        const districtTableBody = document.querySelector('.table tbody');
        const allRows = Array.from(districtTableBody.querySelectorAll('tr'));
        const paginationLinks = document.getElementById('pagination_links');
        const paginationDescription = document.getElementById('pagination_description');

        let currentPage = 1;
        let itemsPerPage = parseInt(itemsPerPageSelect.value);
        let filteredRows = allRows;

        function renderTable() {
            districtTableBody.innerHTML = '';
            const start = (currentPage - 1) * itemsPerPage;
            const end = itemsPerPage === -1 ? filteredRows.length : start + itemsPerPage;
            const paginatedRows = filteredRows.slice(start, end);

            paginatedRows.forEach(row => districtTableBody.appendChild(row));

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
            const zoneId = zoneFilter.value;

            filteredRows = allRows.filter(row => {
                const districtName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const districtCode = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const rowZoneName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                const nameMatch = districtName.includes(searchTerm) || districtCode.includes(searchTerm);
                
                let zoneMatch = true;
                if(zoneId) {
                    const selectedZone = zoneFilter.options[zoneFilter.selectedIndex].text.toLowerCase();
                    zoneMatch = rowZoneName.includes(selectedZone)
                }

                return nameMatch && zoneMatch;
            });

            currentPage = 1;
            renderTable();
            renderPagination();
        }

        searchInput.addEventListener('input', filterAndPaginate);
        zoneFilter.addEventListener('change', filterAndPaginate);

        itemsPerPageSelect.addEventListener('change', function () {
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

        // New script for handling dynamic form submissions
        document.querySelectorAll('a[data-method]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                let method = e.target.getAttribute('data-method');
                let action = e.target.getAttribute('href');
                let confirmMessage = e.target.getAttribute('data-confirm');

                if (confirm(confirmMessage)) {
                    let form = document.createElement('form');
                    form.setAttribute('method', 'POST');
                    form.setAttribute('action', action);

                    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    let csrfInput = document.createElement('input');
                    csrfInput.setAttribute('type', 'hidden');
                    csrfInput.setAttribute('name', '_token');
                    csrfInput.setAttribute('value', csrfToken);
                    form.appendChild(csrfInput);

                    if (method.toLowerCase() !== 'post') {
                        let methodInput = document.createElement('input');
                        methodInput.setAttribute('type', 'hidden');
                        methodInput.setAttribute('name', '_method');
                        methodInput.setAttribute('value', method);
                        form.appendChild(methodInput);
                    }

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>

<!--begin::Approve District Modals for each district-->
@foreach($districts as $district)
<div class="modal fade" id="approveDistrictModal{{ $district->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                @if($district->status == 'pending')
                    Are you sure you want to approve this district ({{ $district->name }})?
                @elseif($district->status == 'pending_delete')
                    Are you sure you want to approve deletion of this district ({{ $district->name }})?
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('staff.districts.approve', $district->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectDistrictModal{{ $district->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Rejection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                @if($district->status == 'pending')
                    Are you sure you want to reject this district ({{ $district->name }})?
                @elseif($district->status == 'pending_delete')
                    Are you sure you want to reject deletion of this district ({{ $district->name }})?
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('staff.districts.reject', $district->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<!--end::Approve/Reject District Modals-->
<!--begin::Delete District Modals for each district-->
@foreach($districts as $district)
<div class="modal fade" id="deleteDistrictModal{{ $district->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this district ({{ $district->name }})? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('staff.districts.destroy', $district->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<!--end::Delete District Modals-->
@endsection