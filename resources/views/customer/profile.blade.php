@extends('layouts.customer')

@section('page-title', 'Account Settings')
@section('breadcrumb', 'Account Settings')

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

<!--begin::Account Settings Card-->
<div class="card card-flush">
    <div class="card-body p-6 p-lg-8">
        <div class="row g-4 g-lg-8">
            <!--begin::Sidebar Navigation-->
            <div class="col-lg-3">
                <div class="bg-light-primary rounded p-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-60px symbol-light-primary me-4">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-user fs-2 text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-2 text-dark fw-bold">{{ $customer->first_name }} {{ $customer->middle_name ?? '' }} {{ $customer->surname }}</h4>
                            <div class="d-flex align-items-center mb-2">
                                <i class="ki-duotone ki-envelope fs-5 text-muted me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div class="text-muted fs-7">{{ $customer->email }}</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="badge badge-primary fs-8 me-2">
                                        <i class="ki-duotone ki-user-check fs-7 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Active Customer
                                    </div>
                                    @if($customer->phone_number)
                                        <div class="badge badge-info fs-8 ms-2">
                                            <i class="ki-duotone ki-phone fs-7 me-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            {{ $customer->phone_number }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-muted fs-8">
                                    Joined: {{ $customer->created_at->format('M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--begin::Tab Navigation-->
                <ul class="nav nav-tabs mb-4" id="customerProfileTabs" role="tablist">
                    <!--begin::Account Tab-->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if(!$errors->has('current_password') && !$errors->has('new_password')) active @endif"
                                id="customer-account-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#customer-account-pane"
                                type="button"
                                role="tab">
                            <i class="ki-duotone ki-user fs-4 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Account
                        </button>
                    </li>
                    <!--end::Account Tab-->

                    <!--begin::Security Tab-->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($errors->has('current_password') || $errors->has('new_password')) active @endif"
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
                    <!--end::Security Tab-->
                </ul>
                <!--end::Tab Navigation-->
                <!--end::Navigation Pills-->
            </div>
            <!--begin::Content Area-->
            <div class="col-lg-9">
                <!--begin::Tab Content-->
                <div class="tab-content" id="customerProfileTabContent">
                    <!--begin::Account Tab Pane-->
                    <div class="tab-pane fade @if(!$errors->has('current_password') && !$errors->has('new_password')) show active @endif"
                         id="customer-account-pane"
                         role="tabpanel"
                         aria-labelledby="customer-account-tab">
                        <!--begin::Email Update Card-->
                        <div class="card card-dashed border border-primary">
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
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="email" class="form-label fw-semibold fs-6 mb-3">
                                                    <i class="ki-duotone ki-envelope fs-4 me-3 text-primary">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Email Address
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ki-duotone ki-mail fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <input type="email" class="form-control form-control-solid" name="email" id="email" value="{{ old('email', $customer->email) }}" placeholder="Enter your new email address" required />
                                                </div>
                                                @error('email')
                                                    <div class="invalid-feedback d-block mt-3">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">We'll send a confirmation to your new email address</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-6">
                                        <button type="submit" class="btn btn-primary px-6">
                                            <i class="ki-duotone ki-check fs-3 me-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Update Email
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end::Email Update Card-->
                    </div>
                    <!--end::Account Tab Pane-->

                    <!--begin::Security Tab Pane-->
                    <div class="tab-pane fade @if($errors->has('current_password') || $errors->has('new_password')) show active @endif"
                         id="customer-security-pane"
                         role="tabpanel"
                         aria-labelledby="customer-security-tab">
                        <!--begin::Password Change Card-->
                        <div class="card card-dashed border border-warning">
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
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="current_password" class="form-label fw-semibold fs-6 mb-3">
                                                    <i class="ki-duotone ki-lock-2 fs-4 me-3 text-warning">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i>
                                                    Current Password
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ki-duotone ki-lock fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <input type="password" class="form-control form-control-solid" name="current_password" id="current_password" placeholder="Enter your current password" required />
                                                </div>
                                                @error('current_password')
                                                    <div class="invalid-feedback d-block mt-3">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row">
                                                <label for="new_password" class="form-label fw-semibold fs-6 mb-3">
                                                    <i class="ki-duotone ki-shield-check fs-4 me-3 text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    New Password
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ki-duotone ki-lock fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <input type="password" class="form-control form-control-solid" name="new_password" id="new_password" placeholder="Enter new password" required />
                                                </div>
                                                @error('new_password')
                                                    <div class="invalid-feedback d-block mt-3">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Use 8+ characters, including uppercase, lowercase, and numbers</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row">
                                                <label for="new_password_confirmation" class="form-label fw-semibold fs-6 mb-3">
                                                    <i class="ki-duotone ki-double-check fs-4 me-3 text-info">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    Confirm Password
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ki-duotone ki-lock fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <input type="password" class="form-control form-control-solid" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" required />
                                                </div>
                                                <div class="form-text">Re-enter your new password to confirm</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-6">
                                        <button type="submit" class="btn btn-warning px-6">
                                            <i class="ki-duotone ki-key fs-3 me-3">
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
                        <!--end::Password Change Card-->
                    </div>
                    <!--end::Security Tab Pane-->
                </div>
                <!--end::Tab Content-->
            </div>
        </div>
    </div>
</div>

<!--begin::Additional Profile Information-->
<div class="card card-flush mt-6">
    <div class="card-header bg-light-info border-0 pt-6">
        <div class="card-title d-flex align-items-center">
            <div class="symbol symbol-45px bg-info me-3">
                <span class="symbol-label">
                    <i class="ki-duotone ki-user-tick text-white fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                </span>
            </div>
            <div>
                <h3 class="mb-1 text-dark fw-bold">Profile Information</h3>
                <div class="text-muted fs-7">Additional account details and preferences</div>
            </div>
        </div>
    </div>
    <div class="card-body p-6 p-lg-8">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-primary me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-calendar text-primary fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Account Information</div>
                    </div>
                    <div class="space-y-3">
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Customer ID:</div>
                            <div class="text-dark">{{ $customer->id ?? 'N/A' }}</div>
                        </div>
                        @if($customer->customer_id)
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Customer Ref:</div>
                            <div class="text-dark">{{ $customer->customer_id }}</div>
                        </div>
                        @endif
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Account Status:</div>
                            <div class="badge badge-success fs-8">Active</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-info me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-abstract-23 text-info fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Personal Details</div>
                    </div>
                    <div class="space-y-3">
                        @if($customer->gender)
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Gender:</div>
                            <div class="text-dark">{{ $customer->gender }}</div>
                        </div>
                        @endif
                        @if($customer->date_of_birth)
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Date of Birth:</div>
                            <div class="text-dark">{{ \Carbon\Carbon::parse($customer->date_of_birth)->format('d M Y') }}</div>
                        </div>
                        @endif
                        @if($customer->address)
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Address:</div>
                            <div class="text-dark">{{ $customer->address }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-success me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-credit-card text-success fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Billing Information</div>
                    </div>
                    <div class="space-y-3">
                        @if($customer->meter_number)
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Meter Number:</div>
                            <div class="text-dark">{{ $customer->meter_number }}</div>
                        </div>
                        @endif
                        @if($customer->tariff)
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Tariff:</div>
                            <div class="text-dark">{{ $customer->tariff }}</div>
                        </div>
                        @endif
                        @if($customer->category)
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Category:</div>
                            <div class="text-dark">{{ $customer->category }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light border-0 py-4">
        <div class="text-center">
            <small class="text-muted">
                <i class="ki-duotone ki-information-5 fs-6 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                Need to update your information? Contact support for assistance.
            </small>
        </div>
    </div>
</div>
<!--end::Additional Profile Information-->
<!--end::Account Settings Card-->

<style>
    /* Customer Profile Custom Styles */
    .nav-pills-custom .nav-link {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        border: 1px solid transparent;
    }

    .nav-pills-custom .nav-link:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.08);
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-pills-custom .nav-link.active {
        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.15), rgba(var(--bs-primary-rgb), 0.05));
        border-left: 3px solid rgba(var(--bs-primary-rgb), 0.8);
        color: var(--bs-primary);
    }

    .nav-pills-custom .nav-text {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .nav-pills-custom .nav-desc {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .card-dashed {
        border-style: dashed;
        border-width: 2px;
        transition: all 0.3s ease;
    }

    .card-dashed:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .nav-pills-custom .nav-link {
            padding: 1rem 0.75rem;
            margin-bottom: 0.75rem;
        }

        .nav-pills-custom .nav-text {
            font-size: 0.85rem;
        }

        .nav-pills-custom .nav-desc {
            font-size: 0.7rem;
        }

        .symbol-60px {
            width: 48px !important;
            height: 48px !important;
        }

        .symbol-45px {
            width: 40px !important;
            height: 40px !important;
        }

        .card-dashed {
            margin-bottom: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .col-lg-3 {
            margin-bottom: 1.5rem;
        }

        .nav-pills-custom {
            width: 100%;
        }

        .nav-pills-custom .nav-link {
            justify-content: flex-start;
            text-align: left;
        }
    }
</style>

@endsection