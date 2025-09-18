@extends('layouts.staff')

@section('content')
<div class="container-xxl">
    <!--begin::Card-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2 class="fw-bold text-dark">Staff Details</h2>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.hr.staff.edit', $staff->id) }}" class="btn btn-primary me-3">Edit Staff</a>
                <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light-primary">Back to Staff</a>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Tiles-->
            <div class="row g-5">
                <!--begin::Personal Info Tile-->
                <div class="col-xxl-4">
                    <div class="card card-xxl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Personal Information</span>
                                <span class="text-muted fw-semibold fs-7">Basic details and contact info</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Staff ID</span>
                                    <span class="text-dark fs-6">{{ $staff->staff_id }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Full Name</span>
                                    <span class="text-dark fs-6">{{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Email</span>
                                    <span class="text-dark fs-6">{{ $staff->email }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Mobile</span>
                                    <span class="text-dark fs-6">{{ $staff->mobile_no }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Date of Birth</span>
                                    <span class="text-dark fs-6">{{ $staff->date_of_birth ? $staff->date_of_birth->format('F j, Y') : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Gender</span>
                                    <span class="text-dark fs-6">{{ ucfirst($staff->gender ?? 'N/A') }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Nationality</span>
                                    <span class="text-dark fs-6">{{ $staff->nationality ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">State of Origin</span>
                                    <span class="text-dark fs-6">{{ $staff->state_of_origin ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">NIN</span>
                                    <span class="text-dark fs-6">{{ $staff->nin ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Address</span>
                                    <span class="text-dark fs-6">{{ $staff->address ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Personal Info Tile-->
                
                <!--begin::Employment Info Tile-->
                <div class="col-xxl-4">
                    <div class="card card-xxl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Employment Information</span>
                                <span class="text-muted fw-semibold fs-7">Work-related details</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Employment Status</span>
                                    <span class="badge badge-light-{{ $staff->employment_status == 'active' ? 'success' : ($staff->employment_status == 'on_leave' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $staff->employment_status)) }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Department</span>
                                    <span class="text-dark fs-6">{{ $staff->department ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Rank</span>
                                    <span class="text-dark fs-6">{{ $staff->rank ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Date of First Appointment</span>
                                    <span class="text-dark fs-6">{{ $staff->date_of_first_appointment ? $staff->date_of_first_appointment->format('F j, Y') : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Expected Next Promotion</span>
                                    <span class="text-dark fs-6">{{ $staff->expected_next_promotion ? $staff->expected_next_promotion->format('F j, Y') : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Expected Retirement Date</span>
                                    <span class="text-dark fs-6">{{ $staff->expected_retirement_date ? $staff->expected_retirement_date->format('F j, Y') : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Staff No</span>
                                    <span class="text-dark fs-6">{{ $staff->staff_no ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Highest Qualifications</span>
                                    <span class="text-dark fs-6">{{ $staff->highest_qualifications ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Grade Level Limit</span>
                                    <span class="text-dark fs-6">{{ $staff->grade_level_limit ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Appointment Type</span>
                                    <span class="text-dark fs-6">{{ $staff->appointment_type ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Years of Service</span>
                                    <span class="text-dark fs-6">{{ $staff->years_of_service ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Employment Info Tile-->
                
                <!--begin::Location Info Tile-->
                <div class="col-xxl-4">
                    <div class="card card-xxl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Location Information</span>
                                <span class="text-muted fw-semibold fs-7">Geographic details</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">LGA</span>
                                    <span class="text-dark fs-6">{{ $staff->lga ? $staff->lga->name : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Ward</span>
                                    <span class="text-dark fs-6">{{ $staff->ward ? $staff->ward->name : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Area</span>
                                    <span class="text-dark fs-6">{{ $staff->area ? $staff->area->name : 'N/A' }}</span>
                                </div>
                                @if($staff->photo_path)
                                <div class="d-flex align-items-center">
                                    <span class="text-muted fs-7 fw-bold w-150px">Photo</span>
                                    <img src="{{ asset('storage/' . $staff->photo_path) }}" alt="{{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'N/A' }}" class="w-100px h-100px rounded" />
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Location Info Tile-->
            </div>
            <!--end::Tiles-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection