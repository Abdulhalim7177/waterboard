@extends('layouts.customer')

@section('page-title', 'Customer Profile')
@section('breadcrumb', 'Customer Profile')

@section('content')

<!--begin::Alerts-->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="ki-duotone ki-check-circle fs-3 me-3">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!--begin::Modern Customer Profile Header-->
<div class="card card-flush shadow-sm mb-6">
    <div class="card-body p-6 p-lg-8">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-80px symbol-primary me-5">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-user fs-1 text-white">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <h1 class="fw-bold text-gray-800 mb-2">{{ $customer->first_name }} {{ $customer->surname }}</h1>
                        <div class="d-flex align-items-center mb-2">
                            <i class="ki-duotone ki-envelope fs-5 text-gray-500 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-600">{{ $customer->email }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge badge-light-primary fs-8">
                                <i class="ki-duotone ki-user-check fs-7 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Active Customer
                            </span>
                            <span class="badge badge-light-info fs-8">
                                ID: {{ $customer->billing_id ?? $customer->id }}
                            </span>
                            <span class="text-gray-500 fs-8">
                                Since {{ $customer->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <div class="bg-primary bg-opacity-10 rounded p-4">
                    <div class="fs-2 fw-bold text-primary mb-1">₦{{ number_format($customer->account_balance ?? 0, 2) }}</div>
                    <div class="text-gray-600 fs-7">Account Balance</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--begin::Modern Settings Navigation-->
<div class="card shadow-sm mb-6">
    <div class="card-body p-0">
        <ul class="nav nav-tabs nav-line-tabs mb-0 border-0" id="customerProfileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-semibold px-6 py-4"
                        id="customer-account-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#customer-account-pane"
                        type="button"
                        role="tab">
                    <i class="ki-duotone ki-user fs-4 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Account Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold px-6 py-4 @if($errors->has('current_password') || $errors->has('new_password')) active @endif"
                        id="customer-security-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#customer-security-pane"
                        type="button"
                        role="tab">
                   <i class="ki-duotone ki-lock-2 fs-4 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    Security
                </button>
            </li>
        </ul>
    </div>
</div>

<!--begin::Tab Content-->
<div class="tab-content" id="customerProfileTabContent">
    <!--begin::Account Tab Pane-->
    <div class="tab-pane fade show active @if(!$errors->has('current_password') && !$errors->has('new_password')) show active @endif"
         id="customer-account-pane"
         role="tabpanel"
         aria-labelledby="customer-account-tab">
        <!--begin::Email Update Card-->
        <div class="card shadow-sm mb-6">
            <div class="card-header bg-light-primary border-0 py-4">
                <div class="card-title d-flex align-items-center">
                    <div class="symbol symbol-45px bg-primary me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-envelope text-white fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">Email Address</h3>
                        <div class="text-muted fs-7">Update your account email address</div>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <form action="{{ route('customer.profile.update') }}" method="POST" class="form">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold fs-6 mb-2">
                            <i class="ki-duotone ki-envelope fs-4 me-2 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Email Address
                        </label>
                        <div class="input-group input-group-solid">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-mail fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <input type="email" class="form-control form-control-solid" name="email" id="email" value="{{ old('email', $customer->email) }}" placeholder="Enter your new email address" required />
                        </div>
                        @error('email')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text-muted fs-7 mt-2">We'll send a confirmation to your new email address</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-6">
                            <i class="ki-duotone ki-check fs-3 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Update Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                    <!--end::Account Tab Pane-->

    <!--begin::Security Tab Pane-->
    <div class="tab-pane fade @if($errors->has('current_password') || $errors->has('new_password')) show active @endif"
         id="customer-security-pane"
         role="tabpanel"
         aria-labelledby="customer-security-tab">
        <!--begin::Password Change Card-->
        <div class="card shadow-sm mb-6">
            <div class="card-header bg-light-warning border-0 py-4">
                <div class="card-title d-flex align-items-center">
                    <div class="symbol symbol-45px bg-warning me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-lock-2 text-white fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">Change Password</h3>
                        <div class="text-muted fs-7">Update your account password for security</div>
                    </div>
                </div>
            </div>
            <div class="card-body p-6">
                <form action="{{ route('customer.password.change') }}" method="POST" class="form">
                    @csrf
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-semibold fs-6 mb-2">
                            <i class="ki-duotone ki-lock-2 fs-4 me-2 text-warning">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Current Password
                        </label>
                        <div class="input-group input-group-solid">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-lock fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <input type="password" class="form-control form-control-solid" name="current_password" id="current_password" placeholder="Enter your current password" required />
                        </div>
                        @error('current_password')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="new_password" class="form-label fw-semibold fs-6 mb-2">
                                <i class="ki-duotone ki-shield-check fs-4 me-2 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                New Password
                            </label>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text">
                                    <i class="ki-duotone ki-lock fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <input type="password" class="form-control form-control-solid" name="new_password" id="new_password" placeholder="Enter new password" required />
                            </div>
                            @error('new_password')
                                <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                            @enderror
                            <div class="text-muted fs-7">Use 8+ characters, including uppercase, lowercase, and numbers</div>
                        </div>
                        <div class="col-md-6">
                            <label for="new_password_confirmation" class="form-label fw-semibold fs-6 mb-2">
                                <i class="ki-duotone ki-double-check fs-4 me-2 text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Confirm Password
                            </label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text">
                                    <i class="ki-duotone ki-lock fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <input type="password" class="form-control form-control-solid" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" required />
                            </div>
                            <div class="text-muted fs-7 mt-2">Re-enter your new password to confirm</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning px-6">
                            <i class="ki-duotone ki-key fs-3 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--begin::Quick Stats-->
<div class="row g-4 mb-6">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px symbol-light-primary me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-calendar text-primary fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <div class="fs-6 fw-semibold text-gray-600">Member Since</div>
                        <div class="fs-5 fw-bold text-gray-800">{{ $customer->created_at->format('M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px symbol-success me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-credit-card text-white fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <div class="fs-6 fw-semibold text-gray-600">Account Balance</div>
                        <div class="fs-5 fw-bold text-success">₦{{ number_format($customer->account_balance ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px symbol-light-info me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-geolocation text-info fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <div class="fs-6 fw-semibold text-gray-600">Billing ID</div>
                        <div class="fs-5 fw-bold text-gray-800">{{ $customer->billing_id ?? $customer->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Customer Profile Styles */
.nav-line-tabs .nav-link {
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 0;
    background: transparent;
    position: relative;
}

.nav-line-tabs .nav-link:hover {
    color: #0d6efd;
    border-bottom-color: rgba(13, 110, 253, 0.3);
}

.nav-line-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    font-weight: 600;
}

.symbol {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    position: relative;
}

.symbol-50px {
    width: 50px;
    height: 50px;
}

.symbol-80px {
    width: 80px;
    height: 80px;
}

.symbol-light-primary {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.symbol-light-success {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.symbol-light-info {
    background-color: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
}

.symbol-primary {
    background-color: #0d6efd;
    color: #ffffff;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.input-group-solid .input-group-text {
    background-color: #f5f8fa;
    border: 1px solid #e9ecef;
}

.form-control-solid {
    background-color: #f5f8fa;
    border: 1px solid #e9ecef;
}

.form-control-solid:focus {
    background-color: #ffffff;
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
}

.tab-pane {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.text-gray-600 { color: #6c757d !important; }
.text-gray-800 { color: #343a40 !important; }
.bg-primary { background-color: #0d6efd !important; }

@media (max-width: 768px) {
    .symbol-80px {
        width: 60px !important;
        height: 60px !important;
    }

    .fs-1 { font-size: 2rem !important; }
}
</style>

@endsection