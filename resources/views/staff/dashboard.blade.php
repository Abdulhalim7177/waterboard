@extends('layouts.staff')

@section('page_title')
    Dashboard
@endsection

@section('page_description')
    Water Board Management System Overview
@endsection

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
                            <h3 class="fw-bold text-dark mb-5">Water Board Management Dashboard</h3>
                            <p class="text-muted fs-6 mb-0">
                                Welcome to the comprehensive Water Board Management System. Here you can monitor and manage all aspects of the system including staff, customers, billing, payments, and complaints.
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-3 mt-5">
                            <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-primary">Manage Staff Records</a>
                            <a href="{{ route('staff.customers.index') }}" class="btn btn-light-primary">Manage Customers</a>
                            <a href="{{ route('staff.bills.index') }}" class="btn btn-light-success">View Billing</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Welcome Card-->
        </div>
        <!--end::Dashboard-->

        <!--begin::Quick Stats-->
        <div class="row g-5 mb-8">
            <!-- Staff Management Stats -->
            <div class="col-xxl-4">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Staff Management</span>
                        </h3>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between pt-5">
                        <div class="row">
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-primary">{{ $totalStaff ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Total Staff</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-success">{{ $activeStaff ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Active Staff</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-warning">{{ $pendingChanges ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Pending Changes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Management Stats -->
            <div class="col-xxl-4">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Customer Management</span>
                        </h3>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between pt-5">
                        <div class="row">
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-primary">{{ $totalCustomers ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Total Customers</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-success">{{ $activeCustomers ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Active Customers</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-warning">{{ $pendingCustomerChanges ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Pending Changes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Financial Stats -->
            <div class="col-xxl-4">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Financial Overview</span>
                        </h3>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between pt-5">
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-primary">{{ $totalBills ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Total Bills</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-success">{{ $paidBills ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Paid Bills</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-warning">{{ $unpaidBills ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Unpaid Bills</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-info">{{ $totalPayments ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Total Payments</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Quick Stats-->

        <!--begin::Complaints and Activities-->
        <div class="row g-5 mb-8">
            <!-- Complaints Stats -->
            <div class="col-xxl-4">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Complaints Management</span>
                        </h3>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between pt-5">
                        <div class="row">
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-primary">{{ $totalComplaints ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Total</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-warning">{{ $openComplaints ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Open</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="fs-1 fw-bold text-success">{{ $resolvedComplaints ?? 0 }}</span>
                                    <span class="text-muted fs-7 mt-1">Resolved</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <a href="{{ route('staff.complaints.index') }}" class="btn btn-sm btn-light-primary">View All Complaints</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activities -->
            <div class="col-xxl-8">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Recent Activities</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#staff-activities">Staff</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#customer-activities">Customers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#billing-activities">Billing</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <!-- Staff Activities -->
                            <div class="tab-pane fade show active" id="staff-activities">
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
                            
                            <!-- Customer Activities -->
                            <div class="tab-pane fade" id="customer-activities">
                                @if(isset($recentCustomerActivities) && count($recentCustomerActivities) > 0)
                                    <div class="timeline">
                                        @foreach($recentCustomerActivities as $activity)
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
                                                            {{ $activity->auditable ? trim($activity->auditable->first_name . ' ' . ($activity->auditable->middle_name ?? '') . ' ' . ($activity->auditable->surname ?? '')) : 'N/A' }}
                                                        </div>
                                                        <div class="d-flex align-items-center mt-1 fs-6">
                                                            <div class="text-muted me-2 fs-7">
                                                                {{ $activity->created_at->diffForHumans() }}
                                                            </div>
                                                            <span class="badge badge-light-{{ $activity->event == 'created' ? 'primary' : ($activity->event == 'updated' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($activity->event) }}
                                                            </span>
                                                        </div>
                                                        <div class="text-muted fs-7 mt-2">
                                                            {{ $activity->description ?? 'N/A' }}
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
                                        <div class="text-muted fs-6">No recent customer activities</div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Billing Activities -->
                            <div class="tab-pane fade" id="billing-activities">
                                @if(isset($recentBillingActivities) && count($recentBillingActivities) > 0)
                                    <div class="timeline">
                                        @foreach($recentBillingActivities as $activity)
                                            <div class="timeline-item">
                                                <div class="timeline-line w-40px"></div>
                                                <div class="timeline-icon symbol symbol-40px symbol-circle">
                                                    <div class="symbol-label bg-light-info">
                                                        <i class="ki-duotone ki-document fs-2 text-info">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="timeline-content mb-5">
                                                    <div class="overflow-auto pe-3">
                                                        <div class="fs-5 fw-bold mb-2">
                                                            Bill #{{ $activity->auditable->billing_id ?? 'N/A' }}
                                                        </div>
                                                        <div class="d-flex align-items-center mt-1 fs-6">
                                                            <div class="text-muted me-2 fs-7">
                                                                {{ $activity->created_at->diffForHumans() }}
                                                            </div>
                                                            <span class="badge badge-light-{{ $activity->event == 'created' ? 'primary' : ($activity->event == 'updated' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($activity->event) }}
                                                            </span>
                                                        </div>
                                                        <div class="text-muted fs-7 mt-2">
                                                            {{ $activity->description ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <i class="ki-duotone ki-document fs-3x text-muted mb-5">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="text-muted fs-6">No recent billing activities</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Complaints and Activities-->

        <!--begin::Quick Actions-->
        <div class="row g-5">
            <div class="col-12">
                <div class="card card-flush h-md-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Quick Actions</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div class="row g-5">
                            <div class="col-md-3">
                                <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-flex btn-center btn-light-primary w-100 mb-5">
                                    <i class="ki-duotone ki-profile-user fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    Staff Management
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('staff.customers.index') }}" class="btn btn-flex btn-center btn-light-success w-100 mb-5">
                                    <i class="ki-duotone ki-user fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Customer Management
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('staff.bills.index') }}" class="btn btn-flex btn-center btn-light-info w-100 mb-5">
                                    <i class="ki-duotone ki-document fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Billing & Payments
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('staff.complaints.index') }}" class="btn btn-flex btn-center btn-light-warning w-100 mb-5">
                                    <i class="ki-duotone ki-message-programming fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    Complaints
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Quick Actions-->
    </div>
    <!--end::Container-->
@endsection