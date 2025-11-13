@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start justify-content-between mb-6">
        <h1 class="fs-2x text-gray-900 mb-3">Dashboard Overview</h1>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-6">
        @if(auth()->user()->hasRole('super-admin'))
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-primary">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-user fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.staff.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalStaff }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Staff</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole(['super-admin', 'manager']))
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-success">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-profile-user fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.customers.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalCustomers }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Customers</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('super-admin'))
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-warning">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-file-down fs-2x text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.bills.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalBills }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Bills</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('super-admin'))
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-danger">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-dollar fs-2x text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.payments.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalPayments }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Payments</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole(['super-admin', 'manager', 'staff']))
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-info">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-message-text-2 fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.tickets.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalTickets }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Tickets</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @can('manage-tickets', 'staff')
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-info">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-message-text-2 fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.tickets.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $myTickets }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">My Tickets</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <!-- Secondary Stats Row -->
    <div class="row g-4 mb-6">
        @can('view-assets', 'staff')
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-secondary">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-briefcase fs-2x text-secondary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.assets.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalAssets }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Assets</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-vendors', 'staff')
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-dark">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-shop fs-2x text-dark">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.vendors.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalVendors }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Vendors</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-categories', 'staff')
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-primary">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-tag fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.categories.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalCategories }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Categories</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-tariffs', 'staff')
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-success">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-price-tag fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.tariffs.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalTariffs }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Tariffs</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-locations', 'staff')
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-warning">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-geolocation fs-2x text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.lgas.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalLgas }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">LGAs</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-locations', 'staff')
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-info">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-geolocation fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.wards.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $totalWards }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Wards</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <!-- Quick Actions Section -->
    <div class="card mb-6">
        <div class="card-body p-6">
            <h3 class="card-title fw-bold fs-2 mb-5">Quick Actions</h3>
            <div class="d-flex flex-wrap gap-3">
                @can('manage-staff', 'staff')
                <a href="{{ route('staff.staff.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-user fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Staff
                </a>
                @endcan
                @can('view-customers', 'staff')
                <a href="{{ route('staff.customers.index') }}" class="btn btn-sm btn-light-success">
                    <i class="ki-duotone ki-profile-user fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    Customers
                </a>
                @endcan
                @can('view-bill', 'staff')
                <a href="{{ route('staff.bills.index') }}" class="btn btn-sm btn-light-warning">
                    <i class="ki-duotone ki-file fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Billing
                </a>
                @endcan
                @can('view-payment', 'staff')
                <a href="{{ route('staff.payments.index') }}" class="btn btn-sm btn-light-danger">
                    <i class="ki-duotone ki-dollar fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Payments
                </a>
                @endcan
                @can('manage-tickets', 'staff')
                <a href="{{ route('staff.tickets.index') }}" class="btn btn-sm btn-light-info">
                    <i class="ki-duotone ki-message-text-2 fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Tickets
                </a>
                @endcan
                @can('manage-assets', 'staff')
                <a href="{{ route('staff.assets.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-briefcase fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Assets
                </a>
                @endcan
                @can('manage-vendors', 'staff')
                <a href="{{ route('staff.vendors.index') }}" class="btn btn-sm btn-light-dark">
                    <i class="ki-duotone ki-shop fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Vendors
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Recent Activities & New Customers Section -->
    <div class="row g-4">
        @can('view-audit-trail', 'staff')
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0 py-3">
                    <h3 class="card-title fw-bold fs-2">Recent Activities</h3>
                </div>
                <div class="card-body py-3">
                    <div class="timeline-label">
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item d-flex mb-4">
                            <div class="timeline-badge me-3 mt-1">
                                <i class="fa fa-genderless text-success fs-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-gray-800 fs-7 mb-1">{{ $activity->created_at->format('M d, Y h:i A') }}</div>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-20px me-2">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-duotone ki-user fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800">
                                            <strong>{{ $activity->user ? $activity->user->name : 'System/Unknown' }}</strong> - {{ $activity->event }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-customers', 'staff')
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0 py-3">
                    <h3 class="card-title fw-bold fs-2">Newly Registered Customers</h3>
                </div>
                <div class="card-body py-3">
                    @foreach($newCustomers as $customer)
                    <div class="d-flex flex-stack border-bottom border-gray-300 border-bottom-dashed pb-4 mb-4 last:border-bottom-0 last:pb-0 last:mb-0">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-3">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-profile-user fs-3 text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold fs-6">{{ $customer->first_name }} {{ $customer->surname }}</span>
                                <span class="text-muted fs-7">{{ $customer->email }}</span>
                            </div>
                        </div>
                        <span class="text-muted fw-semibold fs-7">{{ $customer->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Additional dashboard scripts can be added here
    document.addEventListener('DOMContentLoaded', function() {
        // Add any specific dashboard functionality here
    });
</script>
@endsection