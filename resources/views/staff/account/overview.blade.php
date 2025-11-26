@extends('layouts.staff')

@section('page_title', 'Account Overview')

@section('content')
<!--begin::Container-->
<div class="container-xxl">
    <!--begin::Alerts-->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-success">Success</h4>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">Error</h4>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif
    <!--end::Alerts-->

    <!--begin::Profile Info Card-->
    <div class="card card-flush mb-6 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title d-flex align-items-center">
                <div class="symbol symbol-50px symbol-light-primary me-4">
                    <span class="symbol-label">
                        <i class="ki-duotone ki-user text-primary fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </div>
                <div>
                    <h3 class="mb-1 text-dark fw-bold">Profile Details</h3>
                    <div class="text-muted fs-7">Your personal and contact information</div>
                </div>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body p-6 p-lg-8">
            <!--begin::Personal Information Section-->
            <div class="row g-4 mb-6">
                <div class="col-12">
                    <div class="bg-light-primary rounded p-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px symbol-light-primary me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-user text-primary fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div>
                                <h4 class="mb-1 text-dark fw-bold">{{ $staff->first_name }} {{ $staff->middle_name ?? '' }} {{ $staff->surname }}</h4>
                                <div class="text-muted fs-7">Staff ID: {{ $staff->staff_id ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Contact Information Grid-->
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-info me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-envelope text-info fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">Email Address</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->email }}</div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-success me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-phone text-success fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">Phone Number</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->phone_number ?? 'Not provided' }}</div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-warning me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-mobile text-warning fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">Mobile Number</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->mobile_no ?? 'Not provided' }}</div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-primary me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-abstract-23 text-primary fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">Gender</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->gender ?? 'Not provided' }}</div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-info me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-calendar text-info fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">Date of Birth</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->date_of_birth ? \Carbon\Carbon::parse($staff->date_of_birth)->format('d M Y') : 'Not provided' }}</div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-success me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-flag text-success fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">Nationality</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->nationality ?? 'Not provided' }}</div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-warning me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-identification text-warning fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">NIN</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->nin ?? 'Not provided' }}</div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="bg-white rounded p-4 h-100 border border-gray-200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40px symbol-light-primary me-3">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-home text-primary fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="fs-6 fw-semibold text-gray-700">Address</div>
                        </div>
                        <div class="fs-5 text-gray-800">{{ $staff->address ?? 'Not provided' }}</div>
                    </div>
                </div>
            </div>
            <!--end::Contact Information Grid-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Profile Info-->

    <!--begin::Alerts-->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-success">Success</h4>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">Error</h4>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif
    <!--end::Alerts-->

    <!--begin::Password Security Card-->
    <div class="card card-flush mb-6 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title d-flex align-items-center">
                <div class="symbol symbol-50px symbol-light-warning me-4">
                    <span class="symbol-label">
                        <i class="ki-duotone ki-lock-2 text-warning fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                </div>
                <div>
                    <h3 class="mb-1 text-dark fw-bold">Security Settings</h3>
                    <div class="text-muted fs-7">Manage your account security</div>
                </div>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body p-6 p-lg-8">
            <!--begin::Current Password Section-->
            <div class="bg-light-warning rounded p-4 mb-4">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-45px symbol-light-warning me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-lock-2 text-warning fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <div class="fs-6 fw-bold mb-1">Current Password</div>
                        <div class="fw-semibold text-gray-600">•••••••••••</div>
                        <div class="text-muted fs-8">Last updated: {{ $staff->updated_at ? $staff->updated_at->format('d M Y') : 'Never' }}</div>
                    </div>
                </div>
            </div>
            <!--end::Current Password Section-->

            <!--begin::Password Change Section-->
            <div class="bg-light-primary rounded p-4">
                <form id="kt_account_change_password_form" class="form" method="POST" action="{{ route('staff.account.change-password') }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12 col-lg-6">
                            <div class="fv-row mb-4">
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
                        <div class="col-12 col-lg-6">
                            <div class="fv-row mb-4">
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
                                <div class="form-text">Use 8+ characters with mixed case and numbers</div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="fv-row mb-4">
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
                        <button id="kt_password_submit" type="submit" class="btn btn-primary px-6">
                            <i class="ki-duotone ki-key fs-3 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Update Password
                        </button>
                        <button id="kt_password_cancel" type="button" class="btn btn-light px-6">Cancel</button>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Password Change Section-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Password Security Card-->

    <!--begin::Work Info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Work Information</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Staff ID</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->staff_id ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Staff Number</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->staff_no ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Department</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->department ? $staff->department->name : ($staff->department_id ? 'Department not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Rank</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->rank ? $staff->rank->name : ($staff->rank_id ? 'Rank not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Cadre</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->cadre ? $staff->cadre->name : ($staff->cadre_id ? 'Cadre not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Grade Level</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->gradeLevel ? $staff->gradeLevel->name : ($staff->grade_level_id ? 'Grade Level not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Step</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->step ? $staff->step->name : ($staff->step_id ? 'Step not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Appointment Type</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->appointmentType ? $staff->appointmentType->name : ($staff->appointment_type_id ? 'Appointment Type not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Role(s)</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">
                        @if($staff->roles->count() > 0)
                            {{ $staff->roles->pluck('name')->join(', ') }}
                        @else
                            No roles assigned
                        @endif
                    </span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Employment Status</label>
                <div class="col-lg-8">
                    @php
                        $badgeClass = '';
                        switch ($staff->employment_status) {
                            case 'active':
                                $badgeClass = 'badge-success';
                                break;
                            case 'inactive':
                            case 'terminated':
                                $badgeClass = 'badge-danger';
                                break;
                            case 'suspended':
                                $badgeClass = 'badge-warning';
                                break;
                            case 'on_leave':
                                $badgeClass = 'badge-info';
                                break;
                            default:
                                $badgeClass = 'badge-secondary';
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }} fs-7 fw-bold ms-2">{{ ucfirst(str_replace('_', ' ', $staff->employment_status)) }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Account Status</label>
                <div class="col-lg-8">
                    @php
                        $badgeClass = '';
                        switch ($staff->status) {
                            case 'active':
                            case 'approved':
                                $badgeClass = 'badge-success';
                                break;
                            case 'inactive':
                            case 'rejected':
                            case 'terminated':
                                $badgeClass = 'badge-danger';
                                break;
                            case 'pending':
                            case 'suspended':
                                $badgeClass = 'badge-warning';
                                break;
                            case 'on_leave':
                                $badgeClass = 'badge-info';
                                break;
                            default:
                                $badgeClass = 'badge-secondary';
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }} fs-7 fw-bold ms-2">{{ ucfirst(str_replace('_', ' ', $staff->status)) }}</span>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Work Info-->

    <!--begin::Additional Info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Additional Information</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Date of First Appointment</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->date_of_first_appointment ? \Carbon\Carbon::parse($staff->date_of_first_appointment)->format('d M Y') : 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Contract Start Date</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->contract_start_date ? \Carbon\Carbon::parse($staff->contract_start_date)->format('d M Y') : 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Contract End Date</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->contract_end_date ? \Carbon\Carbon::parse($staff->contract_end_date)->format('d M Y') : 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Expected Next Promotion</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->expected_next_promotion ? \Carbon\Carbon::parse($staff->expected_next_promotion)->format('d M Y') : 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Expected Retirement Date</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->expected_retirement_date ? \Carbon\Carbon::parse($staff->expected_retirement_date)->format('d M Y') : 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Years of Service</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->years_of_service ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Highest Qualifications</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->highest_qualifications ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Additional Info-->

    <!--begin::Location Info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Location Information</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">LGA</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->lga ? $staff->lga->name : ($staff->lga_id ? 'LGA not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Ward</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->ward ? $staff->ward->name : ($staff->ward_id ? 'Ward not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Area</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->area ? $staff->area->name : ($staff->area_id ? 'Area not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Zone</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->zone ? $staff->zone->name : ($staff->zone_id ? 'Zone not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">District</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->district ? $staff->district->name : ($staff->district_id ? 'District not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Paypoint</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->paypoint ? $staff->paypoint->name : ($staff->paypoint_id ? 'Paypoint not found' : 'Not provided') }}</span>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Location Info-->

    <!--begin::System Info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">System Information</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Created At</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->created_at->format('d M Y h:i A') }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Last Login</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->last_login_at ? $staff->last_login_at->format('d M Y h:i A') : 'Never' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Last Updated</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->updated_at->format('d M Y h:i A') }}</span>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::System Info-->

    <!--begin::Bank Info (if available)-->
    @if($bankInfo)
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Bank Information</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Bank Name</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $bankInfo->bank_name }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Account Name</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $bankInfo->account_name }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Account Number</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $bankInfo->account_no }}</span>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
    @endif
    <!--end::Bank Info-->
</div>
    </div>
</div>
<!--end::Container-->

<style>
    /* Staff Account Overview Custom Styles */
    .card-flush {
        transition: all 0.3s ease;
    }

    .card-flush:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .bg-white {
        transition: all 0.3s ease;
    }

    .bg-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .h-100 {
        min-height: 100px;
        display: flex;
        align-items: center;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }

        .card-header {
            padding: 1rem !important;
        }

        .symbol-50px {
            width: 40px !important;
            height: 40px !important;
        }

        .symbol-45px {
            width: 36px !important;
            height: 36px !important;
        }

        .h-100 {
            min-height: 80px;
        }

        .col-lg-6 {
            margin-bottom: 1rem;
        }

        .g-4 > div {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .col-lg-6 {
            margin-bottom: 1.5rem;
        }

        .card-body p-lg-8 {
            padding: 1rem !important;
        }

        .row.g-4 {
            gap: 1rem;
        }
    }

    /* Icon hover effects */
    .symbol {
        transition: all 0.3s ease;
    }

    .symbol:hover {
        transform: scale(1.1);
    }

    /* Form improvements */
    .form-control-solid:focus {
        border-color: rgba(var(--bs-primary-rgb), 0.5);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15);
    }

    .input-group:focus-within .input-group-text {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        border-color: rgba(var(--bs-primary-rgb), 0.5);
    }

    /* Button improvements */
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

@endsection