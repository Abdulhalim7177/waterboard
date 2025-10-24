@extends('layouts.staff')

@section('content')
<div class="container-xxl flex-lg-row-fluid">
    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack my-5">
        <!--begin::Heading-->
        <h3 class="fw-bold me-5">Paypoint Management</h3>
        <!--end::Heading-->
        <!--begin::Controls-->
        <div class="d-flex flex-wrap my-5 my-md-0">
            <!--begin::Button-->
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPaypointModal">Add Paypoint</a>
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
                        <h3 class="card-title">Paypoints List</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Summary Widgets-->
                    @php
                        // Calculate total staff for all paypoints
                        $totalStaffCount = 0;
                        foreach($paypoints as $paypoint) {
                            $totalStaffCount += $paypoint->staff()->count();
                        }
                    @endphp
                    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                        <!--begin::Total Paypoints Card-->
                        <div class="col-12 col-md-6 col-lg-3 mb-5 mb-xl-10">
                            <div class="card card-flush mb-xl-10">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="me-2">
                                        <h6 class="text-gray-400 fw-semibold mb-1">Total Paypoints</h6>
                                        <div class="d-flex flex-column">
                                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $paypoints->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="symbol symbol-60px">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-credit-cart fs-1 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Total Paypoints Card-->
                        
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
                        

                        
                        <!--begin::Active Paypoints Card-->
                        <div class="col-12 col-md-6 col-lg-3 mb-5 mb-xl-10">
                            <div class="card card-flush mb-xl-10">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="me-2">
                                        <h6 class="text-gray-400 fw-semibold mb-1">Active Paypoints</h6>
                                        <div class="d-flex flex-column">
                                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $paypoints->where('status', 'active')->count() }}</span>
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
                        <!--end::Active Paypoints Card-->
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
                                    <th class="min-w-125px">Type</th>
                                    <th class="min-w-125px">Location</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="min-w-125px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($paypoints as $paypoint)
                                    <tr>
                                        <td>{{ $paypoint->name }}</td>
                                        <td>{{ $paypoint->code }}</td>
                                        <td>{{ ucfirst($paypoint->type) }}</td>
                                        <td>
                                            @if($paypoint->type == 'zone')
                                                {{ $paypoint->zone->name ?? 'N/A' }}
                                            @elseif($paypoint->type == 'district')
                                                {{ $paypoint->district->name ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge @if($paypoint->status == 'active') badge-success @else badge-danger @endif">
                                                {{ ucfirst($paypoint->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.paypoints.details', $paypoint->id) }}" class="btn btn-sm btn-light-info me-2">View Details</a>
                                            <a href="#" class="btn btn-sm btn-light-primary me-2" data-bs-toggle="modal" data-bs-target="#editPaypointModal{{ $paypoint->id }}">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No paypoints found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>

<!--begin::Create Paypoint Modal-->
<div class="modal fade" id="createPaypointModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h3 class="modal-title">Create Paypoint</h3>
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
                <form method="POST" action="{{ route('staff.paypoints.store') }}">
                    @csrf
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Paypoint Name</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="name" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Code</label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="code" required>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Type</label>
                        <select name="type" class="form-control form-control-solid" data-control="select2" id="paypointType" required>
                            <option value="">Select Type</option>
                            <option value="zone">Zone</option>
                            <option value="district">District</option>
                        </select>
                    </div>
                    <div class="fv-row mb-10" id="zoneSelection" style="display:none;">
                        <label class="form-label fs-6 fw-bold">Zone</label>
                        <select name="zone_id" class="form-control form-control-solid" data-control="select2">
                            <option value="">Select Zone</option>
                            @foreach(\App\Models\Zone::all() as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10" id="districtSelection" style="display:none;">
                        <label class="form-label fs-6 fw-bold">District</label>
                        <select name="district_id" class="form-control form-control-solid" data-control="select2">
                            <option value="">Select District</option>
                            @foreach(\App\Models\District::all() as $district)
                                <option value="{{ $district->id }}">{{ $district->name }} ({{ $district->zone->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Description</label>
                        <textarea name="description" class="form-control form-control-lg form-control-solid"></textarea>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Status</label>
                        <select name="status" class="form-control form-control-solid" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
                    <div class="fv-row mb-10">
                        <label class="form-label fs-6 fw-bold">Status</label>
                        <select name="status" class="form-control form-control-solid" required>
                            <option value="active" {{ $paypoint->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $paypoint->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
<!--end::Edit Paypoint Modals-->

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    const editTypeSelect{{ $paypoint->id }} = document.getElementById('editPaypointType{{ $paypoint->id }}');
    if (editTypeSelect{{ $paypoint->id }}) {
        editTypeSelect{{ $paypoint->id }}.addEventListener('change', function() {
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