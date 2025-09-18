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

        <!--begin::Dashboard-->
        <div class="row g-5 mb-8">
            <!--begin::Welcome Card-->
            <div class="col-12">
                <div class="card card-flush h-md-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex flex-column">
                            <h3 class="fw-bold text-dark mb-5">Staff Management Dashboard</h3>
                            <p class="text-muted fs-6 mb-0">
                                Welcome to the Staff Management System. Here you can manage staff roles and permissions, 
                                as well as maintain comprehensive staff records.
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-3 mt-5">
                            <a href="{{ route('staff.staff.index') }}" class="btn btn-primary">Manage Staff Roles</a>
                            <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light-primary">Manage Staff Records</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Welcome Card-->
        </div>
        <!--end::Dashboard-->

        <!--begin::Quick Stats-->
        <div class="row g-5 mb-8">
            <!--begin::Total Staff-->
            <div class="col-md-4">
                <div class="card card-flush h-md-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex flex-column">
                            <h4 class="fw-bold text-dark mb-3">Total Staff</h4>
                            <div class="d-flex align-items-center">
                                <span class="fs-1 fw-bold text-primary">{{ $totalStaff ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-5">
                            <i class="ki-duotone ki-user fs-2x text-primary me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-muted fs-7">All registered staff members</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Total Staff-->

            <!--begin::Active Staff-->
            <div class="col-md-4">
                <div class="card card-flush h-md-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex flex-column">
                            <h4 class="fw-bold text-dark mb-3">Active Staff</h4>
                            <div class="d-flex align-items-center">
                                <span class="fs-1 fw-bold text-success">{{ $activeStaff ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-5">
                            <i class="ki-duotone ki-user-tick fs-2x text-success me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-muted fs-7">Currently active employees</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Active Staff-->

            <!--begin::Pending Changes-->
            <div class="col-md-4">
                <div class="card card-flush h-md-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex flex-column">
                            <h4 class="fw-bold text-dark mb-3">Pending Changes</h4>
                            <div class="d-flex align-items-center">
                                <span class="fs-1 fw-bold text-warning">{{ $pendingChanges ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-5">
                            <i class="ki-duotone ki-exclamation fs-2x text-warning me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-muted fs-7">Awaiting approval</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Pending Changes-->
        </div>
        <!--end::Quick Stats-->

        <!--begin::Recent Activity-->
        <div class="row g-5">
            <!--begin::Recent Staff Roles-->
            <div class="col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Recent Role Assignments</span>
                            <span class="text-muted fw-semibold fs-7">Latest staff role changes</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        @if(isset($recentRoleAssignments) && count($recentRoleAssignments) > 0)
                            <div class="timeline">
                                @foreach($recentRoleAssignments as $assignment)
                                    <div class="timeline-item">
                                        <div class="timeline-line w-40px"></div>
                                        <div class="timeline-icon symbol symbol-40px symbol-circle">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="ki-duotone ki-user fs-2 text-primary">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="timeline-content mb-5">
                                            <div class="overflow-auto pe-3">
                                                <div class="fs-5 fw-bold mb-2">
                                                    {{ $assignment->staff ? trim($assignment->staff->first_name . ' ' . ($assignment->staff->middle_name ?? '') . ' ' . ($assignment->staff->surname ?? '')) : 'N/A' }}
                                                </div>
                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                    <div class="text-muted me-2 fs-7">
                                                        {{ $assignment->created_at->diffForHumans() }}
                                                    </div>
                                                    <span class="badge badge-light-{{ $assignment->status == 'approved' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($assignment->status) }}
                                                    </span>
                                                </div>
                                                <div class="text-muted fs-7 mt-2">
                                                    Assigned roles: {{ $assignment->details['roles'] ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <i class="ki-duotone ki-user fs-3x text-muted mb-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div class="text-muted fs-6">No recent role assignments</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Recent Staff Roles-->

            <!--begin::Recent HR Changes-->
            <div class="col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Recent HR Updates</span>
                            <span class="text-muted fw-semibold fs-7">Latest staff record changes</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        @if(isset($recentHrUpdates) && count($recentHrUpdates) > 0)
                            <div class="timeline">
                                @foreach($recentHrUpdates as $update)
                                    <div class="timeline-item">
                                        <div class="timeline-line w-40px"></div>
                                        <div class="timeline-icon symbol symbol-40px symbol-circle">
                                            <div class="symbol-label bg-light-success">
                                                <i class="ki-duotone ki-user-edit fs-2 text-success">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="timeline-content mb-5">
                                            <div class="overflow-auto pe-3">
                                                <div class="fs-5 fw-bold mb-2">
                                                    {{ $update->staff ? trim($update->staff->first_name . ' ' . ($update->staff->middle_name ?? '') . ' ' . ($update->staff->surname ?? '')) : 'N/A' }}
                                                </div>
                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                    <div class="text-muted me-2 fs-7">
                                                        {{ $update->created_at->diffForHumans() }}
                                                    </div>
                                                    <span class="badge badge-light-{{ $update->status == 'approved' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($update->status) }}
                                                    </span>
                                                </div>
                                                <div class="text-muted fs-7 mt-2">
                                                    {{ $update->event }}: {{ $update->description ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <i class="ki-duotone ki-user-edit fs-3x text-muted mb-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div class="text-muted fs-6">No recent HR updates</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Recent HR Changes-->
        </div>
        <!--end::Recent Activity-->
    </div>
    <!--end::Container-->
@endsection