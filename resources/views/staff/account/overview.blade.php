@extends('layouts.staff')

@section('page_title', 'Account Overview')

@section('content')
<!--begin::Container-->
<div class="container-xxl">
    <!--begin::Profile Info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Profile Details</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->first_name }} {{ $staff->middle_name ?? '' }} {{ $staff->surname }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Email</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $staff->email }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Phone</label>
                <div class="col-lg-8 d-flex align-items-center">
                    <span class="fw-bold fs-6 text-gray-800 me-2">{{ $staff->phone_number ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Mobile Number</label>
                <div class="col-lg-8 d-flex align-items-center">
                    <span class="fw-bold fs-6 text-gray-800 me-2">{{ $staff->mobile_no ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Gender</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->gender ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Date of Birth</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->date_of_birth ? \Carbon\Carbon::parse($staff->date_of_birth)->format('d M Y') : 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Nationality</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->nationality ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">NIN</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->nin ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Address</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $staff->address ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Profile Info-->

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
                    <span class="badge badge-{{ $staff->employment_status === 'active' ? 'success' : ($staff->employment_status === 'on_leave' ? 'info' : 'warning') }} fs-7 fw-bold ms-2">{{ $staff->employment_status ?? 'Not provided' }}</span>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Account Status</label>
                <div class="col-lg-8">
                    <span class="badge badge-{{ $staff->status === 'approved' ? 'success' : ($staff->status === 'pending' ? 'warning' : 'danger') }} fs-7 fw-bold ms-2">{{ ucfirst($staff->status) }}</span>
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
<!--end::Container-->
@endsection