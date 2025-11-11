@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="row">
        @if(auth()->user()->hasRole('super-admin'))
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Staff</h5>
                            <h3 class="font-size-24">{{ $totalStaff }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-user fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole(['super-admin', 'manager']))
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Customers</h5>
                            <h3 class="font-size-24">{{ $totalCustomers }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-profile-user fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('super-admin'))
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Bills</h5>
                            <h3 class="font-size-24">{{ $totalBills }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-file-down fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('super-admin'))
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Payments</h5>
                            <h3 class="font-size-24">{{ $totalPayments }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-dollar fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole(['super-admin', 'manager', 'staff']))
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Tickets</h5>
                            <h3 class="font-size-24">{{ $totalTickets }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-message-text-2 fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @can('manage-tickets', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">My Tickets</h5>
                            <h3 class="font-size-24">{{ $myTickets }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-message-text-2 fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-assets', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Assets</h5>
                            <h3 class="font-size-24">{{ $totalAssets }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-briefcase fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-vendors', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Vendors</h5>
                            <h3 class="font-size-24">{{ $totalVendors }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-shop fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-categories', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-light text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Categories</h5>
                            <h3 class="font-size-24">{{ $totalCategories }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-tag fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-tariffs', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-light text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Tariffs</h5>
                            <h3 class="font-size-24">{{ $totalTariffs }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-price-tag fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-locations', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-light text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total LGAs</h5>
                            <h3 class="font-size-24">{{ $totalLgas }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-geolocation fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-locations', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-light text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Wards</h5>
                            <h3 class="font-size-24">{{ $totalWards }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-geolocation fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-locations', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-light text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Total Areas</h5>
                            <h3 class="font-size-24">{{ $totalAreas }}</h3>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-geolocation fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-analytics', 'staff')
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-size-16 text-uppercase mb-1">Analytics</h5>
                            <a href="{{ route('staff.analytics.index') }}" class="btn btn-sm btn-light">View Analytics</a>
                        </div>
                        <div class="text-end">
                            <i class="ki-duotone ki-graph fs-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Quick Actions</h4>

                    <div class="row">
                        @can('manage-staff', 'staff')
                        <div class="col-lg-3">
                            <a href="{{ route('staff.staff.index') }}" class="btn btn-lg btn-light-primary w-100 mb-3">Manage Staff</a>
                        </div>
                        @endcan
                        @can('view-customers', 'staff')
                        <div class="col-lg-3">
                            <a href="{{ route('staff.customers.index') }}" class="btn btn-lg btn-light-success w-100 mb-3">Manage Customers</a>
                        </div>
                        @endcan
                        @can('view-bill', 'staff')
                        <div class="col-lg-3">
                            <a href="{{ route('staff.bills.index') }}" class="btn btn-lg btn-light-warning w-100 mb-3">Manage Billing</a>
                        </div>
                        @endcan
                        @can('view-payment', 'staff')
                        <div class="col-lg-3">
                            <a href="{{ route('staff.payments.index') }}" class="btn btn-lg btn-light-danger w-100 mb-3">Manage Payments</a>
                        </div>
                        @endcan
                        @can('manage-tickets', 'staff')
                        <div class="col-lg-3">
                            <a href="{{ route('staff.tickets.index') }}" class="btn btn-lg btn-light-info w-100 mb-3">Manage Tickets</a>
                        </div>
                        @endcan
                        @can('manage-assets', 'staff')
                        <div class="col-lg-3">
                            <a href="{{ route('staff.assets.index') }}" class="btn btn-lg btn-light-secondary w-100 mb-3">Manage Assets</a>
                        </div>
                        @endcan
                        @can('manage-vendors', 'staff')
                        <div class="col-lg-3">
                            <a href="{{ route('staff.vendors.index') }}" class="btn btn-lg btn-light-dark w-100 mb-3">Manage Vendors</a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @can('view-audit-trail', 'staff')
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Recent Activities</h4>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->user->name }}</td>
                                    <td>{{ $activity->event }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('view-customers', 'staff')
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Newly Registered Customers</h4>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($newCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->first_name }} {{ $customer->surname }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection