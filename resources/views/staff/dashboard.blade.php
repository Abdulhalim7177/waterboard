@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="d-flex flex-column flex-sm-row align-items-start justify-content-between mb-6">
        <div>
            <h1 class="fs-2x text-gray-900 mb-2">Dashboard Overview</h1>
            <div class="text-muted fw-semibold fs-6">Welcome to your Water Board management system</div>
        </div>
    </div>

    <!-- Stats Cards Row - Based on Permissions -->
    <div class="row g-4 mb-6">
        @can('view-staff', 'staff')
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
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
        @endcan

        @can('view-customers', 'staff')
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
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
        @endcan

        @can('view-bill', 'staff')
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
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
        @endcan

        @can('view-payment', 'staff')
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
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
        @endcan

        @can('manage-tickets', 'staff')
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
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
        @endcan

        @can('manage-tickets', 'staff')
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
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

    <!-- Secondary Stats Row - Based on Permissions -->
    <div class="row g-4 mb-6">
        @can('view-staff', 'staff')
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-primary">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-user-tick fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.staff.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $activeStaff }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Active Staff</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-customers', 'staff')
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
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
                            <a href="{{ route('staff.customers.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $activeCustomers }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Active Customers</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-bill', 'staff')
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-success">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-check-circle fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.bills.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $paidBills }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Paid Bills</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-bill', 'staff')
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0 bg-light-danger">
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="symbol symbol-40px mb-2">
                            <div class="symbol-label">
                                <i class="ki-duotone ki-close-circle fs-2x text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="mb-1">
                            <a href="{{ route('staff.bills.index') }}" class="text-gray-800 text-hover-primary fs-3 fw-bold">{{ $unpaidBills }}</a>
                        </div>
                        <span class="text-muted fw-semibold fs-7">Unpaid Bills</span>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-assets', 'staff')
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
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
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
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
    </div>

    <!-- Tertiary Stats Row - Based on Permissions -->
    <div class="row g-4 mb-6">
        @can('view-categories', 'staff')
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
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
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
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
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
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
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
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

    <!-- Performance Indicators Row - Based on Permissions -->
    @if(auth()->user()->can('view-payment', 'staff') && auth()->user()->can('view-bill', 'staff'))
    <div class="row g-4 mb-6">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="card-title fw-bold fs-3">Payment Performance</h3>
                        <div class="badge badge-light-success fs-6">{{ number_format(($successfulPayments / max(1, $totalPayments)) * 100, 1) }}% Success</div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="progress h-8px w-100 me-3 bg-light">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($successfulPayments / max(1, $totalPayments)) * 100 }}%"></div>
                        </div>
                        <div class="text-gray-800 fs-6 fw-bold">{{ $successfulPayments }}/{{ $totalPayments }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-8px symbol-bg-success me-2"></div>
                            <span class="text-gray-700 fs-7">Successful</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-8px symbol-bg-danger me-2"></div>
                            <span class="text-gray-700 fs-7">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="card-title fw-bold fs-3">Ticket Status</h3>
                        <div class="badge badge-light-info fs-6">{{ number_format(($closedTickets / max(1, $totalTickets)) * 100, 1) }}% Resolved</div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="progress h-8px w-100 me-3 bg-light">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($closedTickets / max(1, $totalTickets)) * 100 }}%"></div>
                        </div>
                        <div class="text-gray-800 fs-6 fw-bold">{{ $closedTickets }}/{{ $totalTickets }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-8px symbol-bg-info me-2"></div>
                            <span class="text-gray-700 fs-7">Closed</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-8px symbol-bg-warning me-2"></div>
                            <span class="text-gray-700 fs-7">Open</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions Section - Based on Permissions -->
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
                @can('create-customer', 'staff')
                <a href="{{ route('staff.customers.create.personal') }}" class="btn btn-sm btn-light-success">
                    <i class="ki-duotone ki-plus fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Add Customer
                </a>
                @endcan
                <!-- Additional Quick Actions Based on Permissions -->
                @can('view-locations', 'staff')
                <a href="{{ route('staff.lgas.index') }}" class="btn btn-sm btn-light-warning">
                    <i class="ki-duotone ki-geolocation fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    LGAs
                </a>
                @endcan
                @can('view-locations', 'staff')
                <a href="{{ route('staff.wards.index') }}" class="btn btn-sm btn-light-info">
                    <i class="ki-duotone ki-map fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Wards
                </a>
                @endcan
                @can('view-categories', 'staff')
                <a href="{{ route('staff.categories.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-tag fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Categories
                </a>
                @endcan
                @can('view-tariffs', 'staff')
                <a href="{{ route('staff.tariffs.index') }}" class="btn btn-sm btn-light-success">
                    <i class="ki-duotone ki-price-tag fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Tariffs
                </a>
                @endcan
                @can('manage-staff', 'staff')
                <a href="{{ route('staff.roles.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-shield-tick fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Roles
                </a>
                @endcan
                @can('create-staff', 'staff')
                <a href="{{ url('mngr-secure-9374/hr/staff/create') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-plus fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Add Staff
                </a>
                @endcan


                @can('view-report', 'staff')
                <a href="{{ route('staff.reports.combined') }}" class="btn btn-sm btn-light-secondary">
                    <i class="ki-duotone ki-chart-line fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Reports
                </a>
                @endcan
                @can('view-analytics', 'staff')
                <a href="{{ route('staff.analytics.index') }}" class="btn btn-sm btn-light-success">
                    <i class="ki-duotone ki-graph fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Analytics
                </a>
                @endcan
                @if(auth()->user()->can('view-audit-trail', 'staff') && auth()->user()->hasRole(['super-admin']))
                <a href="{{ route('staff.audits.index') }}" class="btn btn-sm btn-light-dark">
                    <i class="ki-duotone ki-security-user fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Audit Trail
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activities & New Customers Section - Based on Permissions -->
    <div class="row g-4">
        @if(auth()->user()->can('view-audit-trail', 'staff') || auth()->user()->hasRole(['super-admin', 'manager']))
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0 py-3">
                    <h3 class="card-title fw-bold fs-2">Recent Activities</h3>
                </div>
                <div class="card-body py-3">
                    @forelse($recentActivities as $activity)
                    <div class="d-flex flex-stack border-bottom border-gray-300 border-bottom-dashed pb-4 mb-4 last:border-bottom-0 last:pb-0 last:mb-0">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-3">
                                <div class="symbol-label bg-light-success">
                                    <i class="ki-duotone ki-user fs-2 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold fs-6">{{ $activity->user ? $activity->user->name : 'System/Unknown' }}</span>
                                <span class="text-muted fs-7">{{ $activity->event }}</span>
                            </div>
                        </div>
                        <span class="text-muted fw-semibold fs-7">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="symbol symbol-50px mx-auto mb-3">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-information fs-2 text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <p class="text-muted fs-7">No recent activities</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->can('view-customers', 'staff'))
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0 py-3">
                    <h3 class="card-title fw-bold fs-2">Newly Registered Customers</h3>
                </div>
                <div class="card-body py-3">
                    @forelse($newCustomers as $customer)
                    <div class="d-flex flex-stack border-bottom border-gray-300 border-bottom-dashed pb-4 mb-4 last:border-bottom-0 last:pb-0 last:mb-0">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-3">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-profile-user fs-2 text-primary">
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
                    @empty
                    <div class="text-center py-5">
                        <div class="symbol symbol-50px mx-auto mb-3">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-profile-user fs-2 text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                            </div>
                        </div>
                        <p class="text-muted fs-7">No new customers</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Additional dashboard scripts can be added here
    document.addEventListener('DOMContentLoaded', function() {
        // Add any specific dashboard functionality here
        
        // Add smooth hover effects to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection