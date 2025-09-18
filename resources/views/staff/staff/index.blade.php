@extends('layouts.staff')

@section('content')
    <!--begin::Container-->
    <div class="container-xxl">
        <!--begin::Staff Management Navigation-->
        @include('staff.partials.navigation')
        <!--end::Staff Management Navigation-->
        
        <!--begin::Alerts-->
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
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!--end::Alerts-->

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="fw-bold text-dark">Staff Management Systems</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="row g-5">
                    <!--begin::Role Management Card-->
                    <div class="col-md-6">
                        <div class="card card-flush h-md-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold fs-3 mb-1">Role & Permission Management</span>
                                    <span class="text-muted fw-semibold fs-7">Manage staff roles and permissions</span>
                                </h3>
                            </div>
                            <div class="card-body pt-5">
                                <div class="d-flex flex-column gap-4">
                                    <p class="text-muted">
                                        This system is used to assign roles and permissions to existing staff members. 
                                        You can view staff members, assign roles, and manage their access levels.
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-shield fs-2x text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold fs-6">Total Staff: {{ $totalStaff ?? 0 }}</span>
                                            <span class="text-muted fs-7">With roles assigned</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-user-tick fs-2x text-success me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold fs-6">Active Roles: {{ $activeRoles ?? 0 }}</span>
                                            <span class="text-muted fs-7">Currently defined</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer pt-0">
                                <a href="{{ route('staff.staff.roles') }}" class="btn btn-primary w-100">Manage Staff Roles</a>
                            </div>
                        </div>
                    </div>
                    <!--end::Role Management Card-->
                    
                    <!--begin::HR Management Card-->
                    <div class="col-md-6">
                        <div class="card card-flush h-md-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold fs-3 mb-1">HR Staff Management</span>
                                    <span class="text-muted fw-semibold fs-7">Manage comprehensive staff records</span>
                                </h3>
                            </div>
                            <div class="card-body pt-5">
                                <div class="d-flex flex-column gap-4">
                                    <p class="text-muted">
                                        This system is used for comprehensive staff management including personal information, 
                                        employment details, location data, and HR records.
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-profile-user fs-2x text-primary me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold fs-6">Total Records: {{ $totalHrStaff ?? 0 }}</span>
                                            <span class="text-muted fs-7">Complete staff profiles</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-user-edit fs-2x text-success me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold fs-6">Departments: {{ $totalDepartments ?? 0 }}</span>
                                            <span class="text-muted fs-7">Organizational units</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer pt-0">
                                <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-primary w-100">Manage Staff Records</a>
                            </div>
                        </div>
                    </div>
                    <!--end::HR Management Card-->
                </div>
                
                <!--begin::Unified Stats-->
                <div class="row g-5 mt-8">
                    <div class="col-12">
                        <div class="card card-flush">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold fs-3 mb-1">Staff Management Overview</span>
                                    <span class="text-muted fw-semibold fs-7">Combined statistics from both systems</span>
                                </h3>
                            </div>
                            <div class="card-body pt-5">
                                <div class="row g-5">
                                    <div class="col-md-3">
                                        <div class="border border-dashed border-gray-300 rounded p-5">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-user fs-2x text-primary me-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-4">{{ $totalStaffCombined ?? 0 }}</span>
                                                    <span class="text-muted fs-7">Total Staff</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border border-dashed border-gray-300 rounded p-5">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-user-tick fs-2x text-success me-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-4">{{ $activeStaffCombined ?? 0 }}</span>
                                                    <span class="text-muted fs-7">Active Staff</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border border-dashed border-gray-300 rounded p-5">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-user-edit fs-2x text-warning me-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-4">{{ $pendingStaffCombined ?? 0 }}</span>
                                                    <span class="text-muted fs-7">Pending Changes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border border-dashed border-gray-300 rounded p-5">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-shield fs-2x text-info me-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-4">{{ $totalRolesCombined ?? 0 }}</span>
                                                    <span class="text-muted fs-7">Total Roles</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Unified Stats-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection