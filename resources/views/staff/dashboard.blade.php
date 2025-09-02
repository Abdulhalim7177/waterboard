@extends('layouts.staff')

@section('content')
<div class="container-xxl">
    <div class="d-flex flex-wrap flex-stack mb-6">
        <h2 class="fw-bold my-2">Staff Dashboard</h2>
        <p class="text-muted my-2">Welcome, {{ Auth::guard('staff')->user()->name }}</p>
    </div>
    
    <div class="row g-5 g-xl-8">
        @can('manage-users')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.staff.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-user-edit text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Manage Staff</h3>
                        <p class="text-gray-600 fs-7 mt-2">Manage staff accounts, roles, and permissions</p>
                    </div>
                </a>
            </div>
        @endcan
        @can('view-locations')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.areas.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-geolocation text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Manage Locations</h3>
                        <p class="text-gray-600 fs-7 mt-2">Manage LGAs, Wards, and Areas</p>
                    </div>
                </a>
            </div>
        @endcan
        @can('create-category')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.categories.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-element-11 text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Manage Categories</h3>
                        <p class="text-gray-600 fs-7 mt-2">Manage water service categories</p>
                    </div>
                </a>
            </div>
        @endcan
        @can('create-tariff')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.tariffs.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-price-tag text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Manage Tariffs</h3>
                        <p class="text-gray-600 fs-7 mt-2">Manage pricing and tariffs</p>
                    </div>
                </a>
            </div>
        @endcan
        @can('view-customers')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.customers.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-profile-user text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Manage Customers</h3>
                        <p class="text-gray-600 fs-7 mt-2">Manage customer accounts and details</p>
                    </div>
                </a>
            </div>
        @endcan
        @can('view-payment')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.payments.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-dollar text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Payment History</h3>
                        <p class="text-gray-600 fs-7 mt-2">View and manage payment records</p>
                    </div>
                </a>
            </div>
        @endcan
        @can('view-bill')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.bills.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-file-down text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Customer Billing</h3>
                        <p class="text-gray-600 fs-7 mt-2">Manage customer bills and invoices</p>
                    </div>
                </a>
            </div>
        @endcan
        @can('view-complaints')
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('staff.complaints.index') }}" class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <i class="ki-duotone ki-information-2 text-primary fs-2x mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <h3 class="card-title fw-bold text-primary fs-5">Complaint Management</h3>
                        <p class="text-gray-600 fs-7 mt-2">Handle customer complaints and issues</p>
                    </div>
                </a>
            </div>
        @endcan
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize dashboard-specific components after the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Add any dashboard-specific JavaScript here
        
        // Ensure all cards are properly clickable
        const cards = document.querySelectorAll('.card.h-100');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // If the click target is the card itself (not a child element like a button)
                if (e.target === this || e.target === this.querySelector('.card-body')) {
                    const link = this.querySelector('a');
                    if (link) {
                        window.location.href = link.href;
                    }
                }
            });
        });
    });
</script>
@endsection