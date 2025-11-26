@extends('layouts.vendor')

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

<!--begin::Vendor Account Settings Card-->
<div class="card card-flush">
    <div class="card-body p-6 p-lg-8">
        <div class="row g-4 g-lg-8">
            <!--begin::Sidebar Navigation-->
            <div class="col-lg-3">
                <div class="bg-light-success rounded p-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-60px symbol-light-success me-4">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-shop fs-2 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-2 text-dark fw-bold">{{ $vendor->name }}</h4>
                            <div class="d-flex align-items-center mb-2">
                                <i class="ki-duotone ki-envelope fs-5 text-muted me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div class="text-muted fs-7">{{ $vendor->email }}</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="badge badge-success fs-8 me-2">
                                        <i class="ki-duotone ki-check-circle fs-7 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Active Vendor
                                    </div>
                                    @if($vendor->phone_number)
                                        <div class="badge badge-info fs-8 ms-2">
                                            <i class="ki-duotone ki-phone fs-7 me-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            {{ $vendor->phone_number }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-muted fs-8">
                                    Joined: {{ $vendor->created_at->format('M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4 pt-3 border-top border-light">
                        <div class="fs-4 fw-bold text-success mb-2">₦{{ number_format($vendor->account_balance, 2) }}</div>
                        <div class="text-muted fs-8">Available Balance</div>
                        <div class="mt-2">
                            <small class="text-muted">Last login: {{ $vendor->last_login_at ? $vendor->last_login_at->format('d M Y h:i A') : 'Never' }}</small>
                        </div>
                    </div>
                </div>

                <!--begin::Tab Navigation-->
                <ul class="nav nav-tabs mb-4" id="vendorProfileTabs" role="tablist">
                    <!--begin::Account Tab-->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if(!$errors->has('current_password') && !$errors->has('new_password')) active @endif"
                                id="account-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#account-pane"
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
                                id="security-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#security-pane"
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
                <div class="tab-content mt-4" id="vendorProfileTabContent">
                    <!--begin::Account Tab Pane-->
                    <div class="tab-pane fade @if(!$errors->has('current_password') && !$errors->has('new_password')) show active @endif"
                         id="account-pane"
                         role="tabpanel"
                         aria-labelledby="account-tab">
                        <!--begin::Email Update Card-->
                        <div class="card card-dashed border border-success">
                            <div class="card-header bg-light-success border-0 py-4">
                                <div class="card-title d-flex align-items-center">
                                    <div class="symbol symbol-45px bg-success me-3">
                                        <span class="symbol-label">
                                            <i class="ki-duotone ki-envelope text-white fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="mb-1 fw-bold text-dark">Email Address</h3>
                                        <div class="text-muted fs-7">Update your vendor account email</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-6">
                                <form action="{{ route('vendor.profile.update') }}" method="POST" class="form">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="email" class="form-label fw-semibold fs-6 mb-2">
                                                    <i class="ki-duotone ki-envelope fs-4 me-2 text-success">
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
                                                    <input type="email" class="form-control form-control-solid" name="email" id="email" value="{{ old('email', $vendor->email) }}" placeholder="Enter your new email address" required />
                                                </div>
                                                @error('email')
                                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">This email will be used for all vendor communications</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-6">
                                        <button type="submit" class="btn btn-success px-6">
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
                        <!--end::Email Update Card-->
                    </div>
                    <!--end::Account Tab Pane-->

                    <!--begin::Security Tab Pane-->
                    <div class="tab-pane fade @if($errors->has('current_password') || $errors->has('new_password')) show active @endif"
                         id="security-pane"
                         role="tabpanel"
                         aria-labelledby="security-tab">
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
                                        <div class="text-muted fs-7">Update your vendor account password</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-6">
                                <form action="{{ route('vendor.password.change') }}" method="POST" class="form">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="current_password" class="form-label fw-semibold fs-6 mb-2">
                                                    <i class="ki-duotone ki-lock-2 fs-4 me-2 text-warning">
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
                                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row">
                                                <label for="new_password" class="form-label fw-semibold fs-6 mb-2">
                                                    <i class="ki-duotone ki-shield-check fs-4 me-2 text-success">
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
                                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Use strong password with 8+ characters</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row">
                                                <label for="new_password_confirmation" class="form-label fw-semibold fs-6 mb-2">
                                                    <i class="ki-duotone ki-double-check fs-4 me-2 text-info">
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
                        <!--end::Password Change Card-->
                    </div>
                    <!--end::Security Tab Pane-->
                </div>
                <!--end::Tab Content-->
            </div>
        </div>
    </div>
</div>

<!--begin::Additional Vendor Statistics-->
<div class="card card-flush mt-6">
    <div class="card-header bg-light-info border-0 pt-6">
        <div class="card-title d-flex align-items-center">
            <div class="symbol symbol-45px bg-info me-3">
                <span class="symbol-label">
                    <i class="ki-duotone ki-chart-line text-white fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                </span>
            </div>
            <div>
                <h3 class="mb-1 text-dark fw-bold">Performance Statistics</h3>
                <div class="text-muted fs-7">Your business metrics and performance data</div>
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
                        <div class="fs-6 fw-semibold text-gray-700">Account Age</div>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold text-primary">{{ $vendor->created_at ? \Carbon\Carbon::parse($vendor->created_at)->diffInMonths(\Carbon\Carbon::now()) : 0 }}</div>
                        <div class="text-muted fs-8">months active</div>
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
                        <div class="fs-6 fw-semibold text-gray-700">Account Balance</div>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold text-success">₦{{ number_format($vendor->account_balance ?? 0, 2) }}</div>
                        <div class="text-muted fs-8">Current balance</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-warning me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-abstract-26 text-warning fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Total Transactions</div>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold text-warning">{{ $vendor->transaction_count ?? 0 }}</div>
                        <div class="text-muted fs-8">All time</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-info me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-activity text-info fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Success Rate</div>
                    </div>
                    <div class="text-center">
                        <div class="fs-3 fw-bold text-info">{{ $vendor->success_rate ?? 98 }}%</div>
                        <div class="text-muted fs-8">Last 30 days</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Additional Vendor Statistics-->

<!--begin::Recent Activity Timeline-->
<div class="card card-flush mt-6">
    <div class="card-header bg-light-primary border-0 pt-6">
        <div class="card-title d-flex align-items-center">
            <div class="symbol symbol-45px bg-primary me-3">
                <span class="symbol-label">
                    <i class="ki-duotone ki-time text-white fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
            </div>
            <div>
                <h3 class="mb-1 text-dark fw-bold">Recent Activity</h3>
                <div class="text-muted fs-7">Your recent account activities and actions</div>
            </div>
        </div>
    </div>
    <div class="card-body p-6 p-lg-8">
        <div class="timeline timeline-6">
            <!--begin::Timeline Item 1-->
            <div class="timeline-item align-items-start">
                <div class="timeline-badge">
                    <div class="symbol symbol-35px symbol-light-success">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-check text-white fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                </div>
                <div class="timeline-content">
                    <div class="fw-bold text-dark mb-2">Account Created</div>
                    <div class="text-muted fs-8 mb-1">{{ $vendor->created_at ? \Carbon\Carbon::parse($vendor->created_at)->format('d M Y, h:i A') : 'N/A' }}</div>
                    <div class="badge badge-light-success fs-8">Initial Setup</div>
                </div>
            </div>
            <!--end::Timeline Item 1-->

            <!--begin::Timeline Item 2-->
            <div class="timeline-item align-items-start">
                <div class="timeline-badge">
                    <div class="symbol symbol-35px symbol-light-info">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-dollar text-white fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                </div>
                <div class="timeline-content">
                    <div class="fw-bold text-dark mb-2">Last Login</div>
                    <div class="text-muted fs-8 mb-1">{{ $vendor->last_login_at ? \Carbon\Carbon::parse($vendor->last_login_at)->format('d M Y, h:i A') : 'Never' }}</div>
                    <div class="badge badge-light-info fs-8">Authentication</div>
                </div>
            </div>
            <!--end::Timeline Item 2-->

            <!--begin::Timeline Item 3-->
            <div class="timeline-item align-items-start">
                <div class="timeline-badge">
                    <div class="symbol symbol-35px symbol-light-warning">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-credit-card text-white fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                </div>
                <div class="timeline-content">
                    <div class="fw-bold text-dark mb-2">Profile Updated</div>
                    <div class="text-muted fs-8 mb-1">{{ $vendor->updated_at ? \Carbon\Carbon::parse($vendor->updated_at)->format('d M Y, h:i A') : 'N/A' }}</div>
                    <div class="badge badge-light-warning fs-8">Account Modification</div>
                </div>
            </div>
            <!--end::Timeline Item 3-->
        </div>
    </div>
</div>
<!--end::Recent Activity Timeline-->

<!--begin::Comprehensive Business Information-->
<div class="card card-flush mt-6">
    <div class="card-header bg-light-success border-0 pt-6">
        <div class="card-title d-flex align-items-center">
            <div class="symbol symbol-45px bg-success me-3">
                <span class="symbol-label">
                    <i class="ki-duotone ki-shop text-white fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                </span>
            </div>
            <div>
                <h3 class="mb-1 text-dark fw-bold">Business Information</h3>
                <div class="text-muted fs-7">Complete vendor account and business details</div>
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
                                <i class="ki-duotone ki-identification text-primary fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Business Details</div>
                    </div>
                    <div class="space-y-3">
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Vendor ID:</div>
                            <div class="text-dark">{{ $vendor->id ?? 'N/A' }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Business Type:</div>
                            <div class="text-dark">{{ $vendor->business_type ?? 'Not specified' }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Registration Date:</div>
                            <div class="text-dark">{{ $vendor->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Business Registration:</div>
                            <div class="text-dark">{{ $vendor->business_registration ?? 'Not provided' }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Tax ID:</div>
                            <div class="text-dark">{{ $vendor->tax_id ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-success me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-shield-check text-success fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Account Status</div>
                    </div>
                    <div class="space-y-3">
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Status:</div>
                            <div class="badge badge-success fs-8">Active</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Verification:</div>
                            <div class="badge badge-info fs-8">Verified</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Account Type:</div>
                            <div class="text-dark">Premium Vendor</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Approval Status:</div>
                            <div class="badge badge-primary fs-8">Approved</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">License Status:</div>
                            <div class="badge badge-success fs-8">Valid</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-info me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-dollar text-info fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Financial Summary</div>
                    </div>
                    <div class="space-y-3">
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Total Revenue:</div>
                            <div class="text-dark">₦{{ number_format($vendor->total_revenue ?? 0, 2) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Transactions:</div>
                            <div class="text-dark">{{ $vendor->transaction_count ?? 0 }} payments</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Rating:</div>
                            <div class="text-dark">{{ $vendor->rating ?? 'Not rated' }}/5.0</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Commission Rate:</div>
                            <div class="text-dark">{{ $vendor->commission_rate ?? '2.5' }}%</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Monthly Target:</div>
                            <div class="text-dark">₦{{ number_format($vendor->monthly_target ?? 100000, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="bg-white rounded p-4 h-100 border border-gray-200">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-light-warning me-3">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-map text-warning fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="fs-6 fw-semibold text-gray-700">Contact Information</div>
                    </div>
                    <div class="space-y-3">
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Phone:</div>
                            <div class="text-dark">{{ $vendor->phone_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Mobile:</div>
                            <div class="text-dark">{{ $vendor->mobile_no ?? 'Not provided' }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">Address:</div>
                            <div class="text-dark">{{ $vendor->address ?? 'Not provided' }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">City:</div>
                            <div class="text-dark">{{ $vendor->city ?? 'Not provided' }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="fw-semibold text-gray-600 me-3">State:</div>
                            <div class="text-dark">{{ $vendor->state ?? 'Not provided' }}</div>
                        </div>
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
                Need assistance with your vendor account? Contact our support team at support@waterboard.com
            </small>
        </div>
    </div>
</div>
<!--end::Comprehensive Business Information-->

<style>
    /* Vendor Profile Custom Styles - Bootstrap Tab Implementation */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        margin-bottom: -2px;
        transition: all 0.3s ease;
        background: transparent;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: rgba(25, 135, 84, 0.3);
        color: #198754;
        background: rgba(25, 135, 84, 0.05);
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: #198754;
        color: #198754;
        background: rgba(25, 135, 84, 0.1);
        font-weight: 600;
    }

    .nav-tabs .nav-link:focus {
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
        border-bottom-color: #198754;
    }

    .nav-tabs .nav-link i {
        font-size: 1.125rem;
    }

    .tab-content {
        padding-top: 0;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-dashed {
        border-style: dashed;
        border-width: 2px;
        transition: all 0.3s ease;
        border-radius: 0.75rem;
    }

    .card-dashed:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Standard color scheme */
    .bg-light-success {
        background-color: #d1e7dd !important;
        border-color: #198754;
    }

    .bg-light-warning {
        background-color: #fff3cd !important;
        border-color: #ffc107;
    }

    .bg-light-primary {
        background-color: #cfe2ff !important;
        border-color: #0d6efd;
    }

    .bg-light-info {
        background-color: #cff4fc !important;
        border-color: #0dcaf0;
    }

    .text-success {
        color: #198754 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .text-info {
        color: #0dcaf0 !important;
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

    /* Space utility for additional profile information */
    .space-y-3 > * + * {
        margin-top: 0.75rem;
    }

    .h-100 {
        min-height: 100px;
        display: flex;
        align-items: center;
    }

    /* Additional styles for new elements */
    .border-gray-200 {
        border-color: #e9ecef !important;
    }

    .text-gray-600 {
        color: #6c757d !important;
    }

    .text-gray-700 {
        color: #495057 !important;
    }

    /* Mobile responsiveness for new elements */
    @media (max-width: 768px) {
        .col-lg-6 {
            margin-bottom: 1rem;
        }
    }

    /* Timeline styles */
    .timeline-6 .timeline-item {
        display: flex;
        margin-bottom: 1.5rem;
        align-items: flex-start;
    }

    .timeline-6 .timeline-badge {
        margin-right: 1rem;
    }

    .timeline-6 .timeline-content {
        flex-grow- 1;
    }

    .timeline-6 .symbol-35px {
        width: 35px;
        height: 35px;
    }

    /* Statistics grid improvements */
    .bg-white.border-gray-200:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .h-100 {
        min-height: 100px;
        display: flex;
        align-items: center;
    }

    /* Additional responsive improvements */
    @media (max-width: 576px) {
        .timeline-6 .timeline-item {
            flex-direction: column;
            text-align: center;
        }

        .timeline-6 .timeline-badge {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .timeline-6 .timeline-content {
            margin-top: 1rem;
        }

        .card-body .row.g-4 > div {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection