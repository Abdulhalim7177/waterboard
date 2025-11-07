@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Staff Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Total Customers">Total Customers</h5>
                            <h3 class="my-2 py-1">{{ $totalCustomers }}</h3>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="ki-duotone ki-profile-user fs-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Total Tickets">Total Tickets</h5>
                            <h3 class="my-2 py-1">{{ $totalTickets }}</h3>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="ki-duotone ki-message-text-2 fs-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Open Tickets">Open Tickets</h5>
                            <h3 class="my-2 py-1">{{ $openTickets }}</h3>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="ki-duotone ki-message-edit fs-3x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-deck">
                <div class="card quick-action-btn">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-profile-user fs-2x mb-3"></i>
                        <h5 class="card-title">Manage Customers</h5>
                        <p class="card-text">Manage customer accounts and information.</p>
                        <a href="{{ route('staff.customers.index') }}" class="btn btn-primary">Go to Customer Management</a>
                    </div>
                </div>
                <div class="card quick-action-btn">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-message-text-2 fs-2x mb-3"></i>
                        <h5 class="card-title">Manage Tickets</h5>
                        <p class="card-text">Manage customer tickets and support requests.</p>
                        <a href="{{ route('staff.tickets.index') }}" class="btn btn-primary">Go to Ticket Management</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
