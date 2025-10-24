@extends('layouts.staff')

@section('content')
<div class="container-xxl flex-lg-row-fluid">
    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack my-5">
        <!--begin::Heading-->
        <h3 class="fw-bold me-5">District Management</h3>
        <!--end::Heading-->
        <!--begin::Controls-->
        <div class="d-flex flex-wrap my-5 my-md-0">
            <!--begin::Search-->
            <form method="GET" action="{{ route('staff.districts.index') }}" class="d-flex align-items-center me-5">
                <div class="position-relative me-2">
                    <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 ms-4 translate-middle-y">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" name="search_district" class="form-control w-250px ps-12" placeholder="Search Districts" value="{{ request('search_district') }}">
                </div>
                <div class="me-2">
                    <select name="zone_filter" class="form-control form-control-solid" data-control="select2">
                        <option value="">All Zones</option>
                        @foreach(\App\Models\Zone::all() as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_filter') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <!--end::Search-->
            <!--begin::Button-->
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDistrictModal">Add District</a>
            <!--end::Button-->
        </div>
        <!--end::Controls-->
    </div>
    <!--end::Toolbar-->
    
    <!--begin::Row-->
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        <!--begin::Col-->
        <div class="col-12">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="card-title">Districts List</h3>
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary active fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_5_tab_1">All</a>
                            </li>
                        </ul>
                    </div>
                    <!--begin::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Summary Widgets-->
                    @php
                        // Calculate total staff and customers for all districts
                        $totalStaffCount = 0;
                        $totalCustomerCount = 0;
                        foreach($districts as $district) {
                            $totalStaffCount += $district->staffs()->count();
                            $totalCustomerCount += $district->customers()->count();
                        }
                    @endphp
                    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                        <!--begin::Total Districts Card-->
                        <div class="col-12 col-md-6 col-lg-3 mb-5 mb-xl-10">
                            <div class="card card-flush mb-xl-10">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="me-2">
                                        <h6 class="text-gray-400 fw-semibold mb-1">Total Districts</h6>
                                        <div class="d-flex flex-column">
                                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $districts->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="symbol symbol-60px">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-geolocation fs-1 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Total Districts Card-->
                        
                        <!--begin::Total Staff Card-->
                        <div class="col-12 col-md-6 col-lg-3 mb-5 mb-xl-10">
                            <div class="card card-flush mb-xl-10">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="me-2">
                                        <h6 class="text-gray-400 fw-semibold mb-1">Total Staff</h6>
                                        <div class="d-flex flex-column">
                                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $totalStaffCount }}</span>
                                        </div>
                                    </div>
                                    <div class="symbol symbol-60px">
                                        <div class="symbol-label bg-light-info">
                                            <i class="ki-duotone ki-people fs-1 text-info">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Total Staff Card-->
                        
                        <!--begin::Total Customers Card-->
                        <div class="col-12 col-md-6 col-lg-3 mb-5 mb-xl-10">
                            <div class="card card-flush mb-xl-10">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="me-2">
                                        <h6 class="text-gray-400 fw-semibold mb-1">Total Customers</h6>
                                        <div class="d-flex flex-column">
                                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $totalCustomerCount }}</span>
                                        </div>
                                    </div>
                                    <div class="symbol symbol-60px">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-duotone ki-user fs-1 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Total Customers Card-->
                        
                        <!--begin::Active Districts Card-->
                        <div class="col-12 col-md-6 col-lg-3 mb-5 mb-xl-10">
                            <div class="card card-flush mb-xl-10">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="me-2">
                                        <h6 class="text-gray-400 fw-semibold mb-1">Active Districts</h6>
                                        <div class="d-flex flex-column">
                                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $districts->where('status', 'approved')->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="symbol symbol-60px">
                                        <div class="symbol-label bg-light-warning">
                                            <i class="ki-duotone ki-check-circle fs-1 text-warning">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Active Districts Card-->
                    </div>
                    <!--end::Summary Widgets-->
                    
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
                                <tr class="text-start text-muted text-uppercase fw-bold fs-7 border-bottom-2 border-gray-200">
                                    <th class="min-w-125px">Name</th>
                                    <th class="min-w-125px">Code</th>
                                    <th class="min-w-125px">Zone</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="min-w-125px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($districts as $district)
                                    <tr>
                                        <td>{{ $district->name }}</td>
                                        <td>{{ $district->code }}</td>
                                        <td>{{ $district->zone ? $district->zone->name : 'N/A' }}</td>
                                        <td>
                                            <span class="badge @if($district->status == 'approved') badge-success @elseif($district->status == 'pending') badge-warning @elseif($district->status == 'rejected') badge-danger @else badge-secondary @endif">
                                                {{ ucfirst(str_replace('_', ' ', $district->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.districts.details', $district->id) }}" class="btn btn-sm btn-light-info me-2">View Details</a>
                                            <a href="{{ route('staff.districts.manage-wards', $district->id) }}" class="btn btn-sm btn-light-info me-2">Manage Wards</a>
                                            <a href="#" class="btn btn-sm btn-light-primary me-2" data-bs-toggle="modal" data-bs-target="#editDistrictModal{{ $district->id }}">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No districts found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                    
                    <!--begin::Pagination-->
                    <div class="d-flex justify-content-center">
                        {{ $districts->links() }}
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
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
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
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
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
@endsection