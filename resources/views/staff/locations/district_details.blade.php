@extends('layouts.staff')

@section('content')
<div class="container-xxl flex-lg-row-fluid">
    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack my-5">
        <!--begin::Heading-->
        <h3 class="fw-bold me-5">District Details: {{ $district->name }}</h3>
        <!--end::Heading-->
        <!--begin::Controls-->
        <div class="d-flex flex-wrap my-5 my-md-0">
            <a href="{{ route('staff.districts.index') }}" class="btn btn-light me-2">Back to Districts</a>
        </div>
        <!--end::Controls-->
    </div>
    <!--end::Toolbar-->
    
    <!--begin::Summary Widgets-->
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        <!--begin::Total Staff Card-->
        <div class="col-sm-6 col-xl-3 mb-5 mb-xl-10">
            <div class="card card-flush h-md-50 mb-xl-10">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <h6 class="text-gray-400 fw-semibold mb-1">Total Staff</h6>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ count($staffs) }}</span>
                        </div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-primary">
                            <i class="ki-duotone ki-people fs-1 text-primary">
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
        
        <!--begin::Customers Card-->
        <div class="col-sm-6 col-xl-3 mb-5 mb-xl-10">
            <div class="card card-flush h-md-50 mb-xl-10">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <h6 class="text-gray-400 fw-semibold mb-1">Total Customers</h6>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ count($customers) }}</span>
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
        <!--end::Customers Card-->
        
        <!--begin::Active Staff Card-->
        <div class="col-sm-6 col-xl-3 mb-5 mb-xl-10">
            <div class="card card-flush h-md-50 mb-xl-10">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <h6 class="text-gray-400 fw-semibold mb-1">Active Staff</h6>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $staffs->where('status', 'approved')->count() }}</span>
                        </div>
                    </div>
                    <div class="symbol symbol-60px">
                        <div class="symbol-label bg-light-info">
                            <i class="ki-duotone ki-check-circle fs-1 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Active Staff Card-->
        
        <!--begin::Active Customers Card-->
        <div class="col-sm-6 col-xl-3 mb-5 mb-xl-10">
            <div class="card card-flush h-md-50 mb-xl-10">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="me-2">
                        <h6 class="text-gray-400 fw-semibold mb-1">Active Customers</h6>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $customers->where('status', 'approved')->count() }}</span>
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
        <!--end::Active Customers Card-->
    </div>
    <!--end::Summary Widgets-->
    
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        <!--begin::Col - Wards-->
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-title">Wards in District: {{ count($wards) }}</h3>
                    </div>
                </div>
                <div class="card-body py-4">
                    @if($wards->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted text-uppercase fw-bold fs-7 border-bottom-2 border-gray-200">
                                        <th class="min-w-125px">Name</th>
                                        <th class="min-w-125px">Code</th>
                                        <th class="min-w-125px">LGA</th>
                                        <th class="min-w-125px">Customers</th>
                                        <th class="min-w-125px">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach($wards as $ward)
                                        <tr>
                                            <td>{{ $ward->name }}</td>
                                            <td>{{ $ward->code }}</td>
                                            <td>{{ $ward->lga->name ?? 'N/A' }}</td>
                                            <td>{{ $ward->customers->count() }}</td>
                                            <td>
                                                <span class="badge @if($ward->status == 'approved') badge-success @elseif($ward->status == 'pending') badge-warning @elseif($ward->status == 'rejected') badge-danger @else badge-secondary @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $ward->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p>No wards assigned to this district</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Col - Wards-->
        
        <!--begin::Col - Staff-->
        <div class="col-12">
            <div class="card mt-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-title">Staff in District: {{ count($staffs) }}</h3>
                    </div>
                </div>
                <div class="card-body py-4">
                    @if($staffs->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted text-uppercase fw-bold fs-7 border-bottom-2 border-gray-200">
                                        <th class="min-w-125px">Name</th>
                                        <th class="min-w-125px">Email</th>
                                        <th class="min-w-125px">Phone</th>
                                        <th class="min-w-125px">Paypoint</th>
                                        <th class="min-w-125px">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach($staffs as $staff)
                                        <tr>
                                            <td>{{ $staff->first_name }} {{ $staff->surname }}</td>
                                            <td>{{ $staff->email }}</td>
                                            <td>{{ $staff->mobile_no ?? 'N/A' }}</td>
                                            <td>{{ $staff->paypoint ? $staff->paypoint->name : 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = '';
                                                    switch ($staff->status) {
                                                        case 'active':
                                                        case 'approved':
                                                            $badgeClass = 'badge-success';
                                                            break;
                                                        case 'inactive':
                                                        case 'rejected':
                                                        case 'terminated':
                                                            $badgeClass = 'badge-danger';
                                                            break;
                                                        case 'pending':
                                                        case 'suspended':
                                                            $badgeClass = 'badge-warning';
                                                            break;
                                                        case 'on_leave':
                                                            $badgeClass = 'badge-info';
                                                            break;
                                                        default:
                                                            $badgeClass = 'badge-secondary';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $staff->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p>No staff assigned to this district</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Col - Staff-->
        
        <!--begin::Col - Customers-->
        <div class="col-12">
            <div class="card mt-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-title">Customers in District: {{ count($customers) }}</h3>
                    </div>
                </div>
                <div class="card-body py-4">
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted text-uppercase fw-bold fs-7 border-bottom-2 border-gray-200">
                                        <th class="min-w-125px">Name</th>
                                        <th class="min-w-125px">Email</th>
                                        <th class="min-w-125px">Phone</th>
                                        <th class="min-w-125px">LGA</th>
                                        <th class="min-w-125px">Ward</th>
                                        <th class="min-w-125px">Category</th>
                                        <th class="min-w-125px">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->first_name }} {{ $customer->surname }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->phone_number }}</td>
                                            <td>{{ $customer->lga->name ?? 'N/A' }}</td>
                                            <td>{{ $customer->ward->name ?? 'N/A' }}</td>
                                            <td>{{ $customer->category->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge @if($customer->status == 'approved') badge-success @elseif($customer->status == 'pending') badge-warning @elseif($customer->status == 'rejected') badge-danger @else badge-secondary @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $customer->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p>No customers in this district</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Col - Customers-->
    </div>
</div>
@endsection