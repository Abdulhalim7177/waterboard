@extends('layouts.staff')

@section('content')
<style>
    /* Center the whole card on the page */
    .page-center {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    /* Limit card width and make it full width on small screens */
    .card-centered {
        width: 100%;
        max-width: 1100px;
    }

    /* Center alerts */
    .alert-container {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    /* Center form contents and submit button */
    .tab-content form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    .tab-content form .btn {
        align-self: center;
        margin-top: 0.5rem;
    }

    /* Make input groups stretch to the card width while keeping centered layout */
    .tab-content form .row,
    .tab-content form .form-group {
        width: 100%;
    }
    
    .form-section {
        display: none;
    }
    
    .form-section.active {
        display: block;
    }
    
    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        width: 100%;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }
    
    .tab-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .step {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
        font-weight: bold;
        color: #64748b;
        position: relative;
    }
    
    .step.active {
        background-color: #3b82f6;
        color: white;
    }
    
    .step.completed {
        background-color: #10b981;
        color: white;
    }
    
    .step::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: 30px;
        height: 2px;
        background-color: #e2e8f0;
        transform: translateY(-50%);
    }
    
    .step.completed::after {
        background-color: #10b981;
    }
    
    .step:last-child::after {
        display: none;
    }
    
    .is-invalid {
        border-color: #e3342f !important;
    }
    
    .text-danger {
        color: #e3342f !important;
    }
    
    .form-control.is-invalid:focus {
        border-color: #e3342f;
        box-shadow: 0 0 0 0.2rem rgba(227, 52, 47, 0.25);
    }
</style>

<div class="container page-center">
    <div class="card card-flush card-centered">
        <div class="alert-container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2 class="fw-bold text-dark">Create Staff</h2>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light-primary">Back to Staff</a>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <form action="{{ route('staff.hr.staff.store') }}" method="POST" id="create-staff-form">
                @csrf
                
                <!-- Tab Indicator -->
                <div class="tab-indicator">
                    <div class="step active" data-step="1">1</div>
                    <div class="step" data-step="2">2</div>
                    <div class="step" data-step="3">3</div>
                    <div class="step" data-step="4">4</div>
                    <div class="step" data-step="5">5</div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <!-- Personal Information Tab -->
                        <div class="form-section active" id="step1">
                            <div class="section-title">Personal Information</div>
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <label for="staff_id" class="form-label w-150px">Staff ID</label>
                                    <input type="text" class="form-control" id="staff_id" name="staff_id" value="{{ old('staff_id') }}" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="first_name" class="form-label w-150px">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="surname" class="form-label w-150px">Surname</label>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="middle_name" class="form-label w-150px">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="email" class="form-label w-150px">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="password" class="form-label w-150px">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="gender" class="form-label w-150px">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="date_of_birth" class="form-label w-150px">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="nationality" class="form-label w-150px">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality') }}" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="nin" class="form-label w-150px">NIN</label>
                                    <input type="text" class="form-control" id="nin" name="nin" value="{{ old('nin') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="mobile_no" class="form-label w-150px">Mobile No</label>
                                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" required maxlength="15" placeholder="+2348012345678">
                                    <div class="form-text text-muted small">Max 15 characters</div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="phone_number" class="form-label w-150px">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="address" class="form-label w-150px">Address</label>
                                    <textarea class="form-control" id="address" name="address" required>{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Employment Information Tab -->
                        <div class="form-section" id="step2">
                            <div class="section-title">Employment Information</div>
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <label for="date_of_first_appointment" class="form-label w-150px">Date of First Appointment</label>
                                    <input type="date" class="form-control" id="date_of_first_appointment" name="date_of_first_appointment" value="{{ old('date_of_first_appointment') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="contract_start_date" class="form-label w-150px">Contract Start Date</label>
                                    <input type="date" class="form-control" id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="contract_end_date" class="form-label w-150px">Contract End Date</label>
                                    <input type="date" class="form-control" id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="department_id" class="form-label w-150px">Department</label>
                                    <select class="form-select" id="department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        @foreach (\App\Models\Department::all() as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="rank_id" class="form-label w-150px">Rank</label>
                                    <select class="form-select" id="rank_id" name="rank_id">
                                        <option value="">Select Rank</option>
                                        @foreach (\App\Models\Rank::all() as $rank)
                                            <option value="{{ $rank->id }}" {{ old('rank_id') == $rank->id ? 'selected' : '' }}>{{ $rank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="cadre_id" class="form-label w-150px">Cadre</label>
                                    <select class="form-select" id="cadre_id" name="cadre_id">
                                        <option value="">Select Cadre</option>
                                        @foreach (\App\Models\Cadre::all() as $cadre)
                                            <option value="{{ $cadre->id }}" {{ old('cadre_id') == $cadre->id ? 'selected' : '' }}>{{ $cadre->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="grade_level_id" class="form-label w-150px">Grade Level</label>
                                    <select class="form-select" id="grade_level_id" name="grade_level_id">
                                        <option value="">Select Grade Level</option>
                                        @foreach (\App\Models\GradeLevel::all() as $gradeLevel)
                                            <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id') == $gradeLevel->id ? 'selected' : '' }}>{{ $gradeLevel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="step_id" class="form-label w-150px">Step</label>
                                    <select class="form-select" id="step_id" name="step_id">
                                        <option value="">Select Step</option>
                                        @foreach (\App\Models\Step::all() as $step)
                                            <option value="{{ $step->id }}" {{ old('step_id') == $step->id ? 'selected' : '' }}>{{ $step->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="appointment_type_id" class="form-label w-150px">Appointment Type</label>
                                    <select class="form-select" id="appointment_type_id" name="appointment_type_id">
                                        <option value="">Select Appointment Type</option>
                                        @foreach (\App\Models\AppointmentType::all() as $appointmentType)
                                            <option value="{{ $appointmentType->id }}" {{ old('appointment_type_id') == $appointmentType->id ? 'selected' : '' }}>{{ $appointmentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="expected_next_promotion" class="form-label w-150px">Expected Next Promotion</label>
                                    <input type="date" class="form-control" id="expected_next_promotion" name="expected_next_promotion" value="{{ old('expected_next_promotion') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="expected_retirement_date" class="form-label w-150px">Expected Retirement Date</label>
                                    <input type="date" class="form-control" id="expected_retirement_date" name="expected_retirement_date" value="{{ old('expected_retirement_date') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="status" class="form-label w-150px">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Select Status</option>
                                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="On Leave" {{ old('status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                        <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="Terminated" {{ old('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="employment_status" class="form-label w-150px">Employment Status</label>
                                    <input type="text" class="form-control" id="employment_status" name="employment_status" value="{{ old('employment_status', 'Active') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="highest_qualifications" class="form-label w-150px">Highest Qualifications</label>
                                    <input type="text" class="form-control" id="highest_qualifications" name="highest_qualifications" value="{{ old('highest_qualifications') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="years_of_service" class="form-label w-150px">Years of Service</label>
                                    <input type="number" class="form-control" id="years_of_service" name="years_of_service" value="{{ old('years_of_service') }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Information Tab -->
                        <div class="form-section" id="step3">
                            <div class="section-title">Location Information</div>
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <label for="lga_id" class="form-label w-150px">LGA</label>
                                    <select class="form-select" id="lga_id" name="lga_id">
                                        <option value="">Select LGA</option>
                                        @foreach (\App\Models\Lga::all() as $lga)
                                            <option value="{{ $lga->id }}" {{ old('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="ward_id" class="form-label w-150px">Ward</label>
                                    <select class="form-select" id="ward_id" name="ward_id">
                                        <option value="">Select Ward</option>
                                        @foreach (\App\Models\Ward::all() as $ward)
                                            <option value="{{ $ward->id }}" data-lga-id="{{ $ward->lga_id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="area_id" class="form-label w-150px">Area</label>
                                    <select class="form-select" id="area_id" name="area_id">
                                        <option value="">Select Area</option>
                                        @foreach (\App\Models\Area::all() as $area)
                                            <option value="{{ $area->id }}" data-ward-id="{{ $area->ward_id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="zone_id" class="form-label w-150px">Zone</label>
                                    <select class="form-select" id="zone_id" name="zone_id">
                                        <option value="">Select Zone</option>
                                        @foreach (\App\Models\Zone::all() as $zone)
                                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="district_id" class="form-label w-150px">District</label>
                                    <select class="form-select" id="district_id" name="district_id">
                                        <option value="">Select District</option>
                                        @foreach (\App\Models\District::all() as $district)
                                            <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="paypoint_id" class="form-label w-150px">Paypoint</label>
                                    <select class="form-select" id="paypoint_id" name="paypoint_id">
                                        <option value="">Select Paypoint</option>
                                        @foreach (\App\Models\Paypoint::all() as $paypoint)
                                            <option value="{{ $paypoint->id }}" {{ old('paypoint_id') == $paypoint->id ? 'selected' : '' }}>{{ $paypoint->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Financial Information Tab -->
                        <div class="form-section" id="step4">
                            <div class="section-title">Financial Information</div>
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <label for="bank_name" class="form-label w-150px">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="bank_code" class="form-label w-150px">Bank Code</label>
                                    <input type="text" class="form-control" id="bank_code" name="bank_code" value="{{ old('bank_code') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="account_name" class="form-label w-150px">Account Name</label>
                                    <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="account_no" class="form-label w-150px">Account Number</label>
                                    <input type="text" class="form-control" id="account_no" name="account_no" value="{{ old('account_no') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="pension_administrator" class="form-label w-150px">Pension Administrator</label>
                                    <input type="text" class="form-control" id="pension_administrator" name="pension_administrator" value="{{ old('pension_administrator') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="rsa_pin" class="form-label w-150px">RSA Pin</label>
                                    <input type="text" class="form-control" id="rsa_pin" name="rsa_pin" value="{{ old('rsa_pin') }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Next of Kin Tab -->
                        <div class="form-section" id="step5">
                            <div class="section-title">Next of Kin</div>
                            <div class="d-flex flex-column gap-4">
                                <div class="d-flex align-items-center">
                                    <label for="next_of_kin_name" class="form-label w-150px">Name</label>
                                    <input type="text" class="form-control" id="next_of_kin_name" name="next_of_kin_name" value="{{ old('next_of_kin_name') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="next_of_kin_relationship" class="form-label w-150px">Relationship</label>
                                    <input type="text" class="form-control" id="next_of_kin_relationship" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="next_of_kin_mobile_no" class="form-label w-150px">Mobile No</label>
                                    <input type="text" class="form-control" id="next_of_kin_mobile_no" name="next_of_kin_mobile_no" value="{{ old('next_of_kin_mobile_no') }}" maxlength="15" placeholder="+2348012345678">
                                    <div class="form-text text-muted small">Max 15 characters</div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="next_of_kin_address" class="form-label w-150px">Address</label>
                                    <textarea class="form-control" id="next_of_kin_address" name="next_of_kin_address">{{ old('next_of_kin_address') }}</textarea>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="next_of_kin_occupation" class="form-label w-150px">Occupation</label>
                                    <input type="text" class="form-control" id="next_of_kin_occupation" name="next_of_kin_occupation" value="{{ old('next_of_kin_occupation') }}">
                                </div>
                                <div class="d-flex align-items-center">
                                    <label for="next_of_kin_place_of_work" class="form-label w-150px">Place of Work</label>
                                    <input type="text" class="form-control" id="next_of_kin_place_of_work" name="next_of_kin_place_of_work" value="{{ old('next_of_kin_place_of_work') }}">
                                </div>
                            </div>
                            
                            <!-- Submit button for last step -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">Create Staff</button>
                            </div>
                        </div>
                        
                        <!-- Navigation Buttons -->
                        <div class="navigation-buttons">
                            <button type="button" class="btn btn-secondary" id="prevBtn" disabled>Previous</button>
                            <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = 5;
        const formSections = document.querySelectorAll('.form-section');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const stepIndicators = document.querySelectorAll('.step');
        
        // Function to show current step
        function showStep(step) {
            formSections.forEach((section, index) => {
                if (index + 1 === step) {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            });
            
            // Update step indicators
            stepIndicators.forEach((indicator, index) => {
                if (index + 1 < step) {
                    indicator.classList.remove('active');
                    indicator.classList.add('completed');
                } else if (index + 1 === step) {
                    indicator.classList.add('active');
                    indicator.classList.remove('completed');
                } else {
                    indicator.classList.remove('active');
                    indicator.classList.remove('completed');
                }
            });
            
            // Enable/disable navigation buttons
            prevBtn.disabled = step === 1;
            nextBtn.disabled = step === totalSteps;
            nextBtn.textContent = step === totalSteps ? 'Create Staff' : 'Next';
            
            // Update button text for the last step
            if (step === totalSteps) {
                document.querySelector('#step5 .btn-primary').textContent = 'Create Staff';
            }
        }
        
        // Navigate to next step
        nextBtn.addEventListener('click', function () {
            if (currentStep < totalSteps) {
                // Validate current step before proceeding to the next
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
        
        // Navigate to previous step
        prevBtn.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
        
        // Validate current step fields
        function validateStep(step) {
            const currentSection = document.getElementById('step' + step);
            const requiredFields = currentSection.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Additional specific validations based on step
            if (step === 1) {
                // Validate email format
                const emailField = document.getElementById('email');
                if (emailField.value && !isValidEmail(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = emailField;
                    showValidationError('Please enter a valid email address');
                } else {
                    emailField.classList.remove('is-invalid');
                }
                
                // Validate mobile number format
                const mobileField = document.getElementById('mobile_no');
                if (mobileField.value && !isValidMobileNumber(mobileField.value)) {
                    isValid = false;
                    mobileField.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = mobileField;
                    showValidationError('Please enter a valid mobile number');
                } else {
                    mobileField.classList.remove('is-invalid');
                }
                
                // Validate password length
                const passwordField = document.getElementById('password');
                if (passwordField.value && passwordField.value.length < 6) {
                    isValid = false;
                    passwordField.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = passwordField;
                    showValidationError('Password must be at least 6 characters long');
                } else {
                    passwordField.classList.remove('is-invalid');
                }
            }
            
            // Additional validations for step 2 (Employment Information)
            if (step === 2) {
                // Validate date of first appointment if provided
                const dateOfAppointment = document.getElementById('date_of_first_appointment');
                if (dateOfAppointment.value && !isValidDate(dateOfAppointment.value)) {
                    isValid = false;
                    dateOfAppointment.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = dateOfAppointment;
                } else {
                    dateOfAppointment.classList.remove('is-invalid');
                }
                
                // Validate contract start date if provided
                const contractStart = document.getElementById('contract_start_date');
                if (contractStart.value && !isValidDate(contractStart.value)) {
                    isValid = false;
                    contractStart.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = contractStart;
                } else {
                    contractStart.classList.remove('is-invalid');
                }
                
                // Validate contract end date if provided
                const contractEnd = document.getElementById('contract_end_date');
                if (contractEnd.value && !isValidDate(contractEnd.value)) {
                    isValid = false;
                    contractEnd.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = contractEnd;
                } else {
                    contractEnd.classList.remove('is-invalid');
                }
                
                // Validate expected retirement date if provided
                const retirementDate = document.getElementById('expected_retirement_date');
                if (retirementDate.value && !isValidDate(retirementDate.value)) {
                    isValid = false;
                    retirementDate.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = retirementDate;
                } else {
                    retirementDate.classList.remove('is-invalid');
                }
                
                // Validate expected next promotion date if provided
                const promotionDate = document.getElementById('expected_next_promotion');
                if (promotionDate.value && !isValidDate(promotionDate.value)) {
                    isValid = false;
                    promotionDate.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = promotionDate;
                } else {
                    promotionDate.classList.remove('is-invalid');
                }
            }
            
            // Additional validations for step 4 (Financial Information)
            if (step === 4) {
                // Validate bank account number format if provided
                const accountNo = document.getElementById('account_no');
                if (accountNo.value && !isValidAccountNumber(accountNo.value)) {
                    isValid = false;
                    accountNo.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = accountNo;
                } else {
                    accountNo.classList.remove('is-invalid');
                }
            }
            
            // Additional validations for step 5 (Next of Kin)
            if (step === 5) {
                // Validate next of kin mobile number if provided
                const nokMobile = document.getElementById('next_of_kin_mobile_no');
                if (nokMobile.value && !isValidMobileNumber(nokMobile.value)) {
                    isValid = false;
                    nokMobile.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = nokMobile;
                } else {
                    nokMobile.classList.remove('is-invalid');
                }
            }
            
            // If validation failed, focus on first invalid field
            if (!isValid && firstInvalidField) {
                firstInvalidField.focus();
            }
            
            return isValid;
        }
        
        // Email validation helper
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Mobile number validation helper for API constraint (max 15 chars)
        function isValidMobileNumber(mobileNumber) {
            // Allow numbers with optional country code, spaces, hyphens, brackets
            // Common formats: 08012345678, +2348012345678, 2348012345678
            // Also ensure length doesn't exceed 15 characters when cleaned
            const cleanNumber = mobileNumber.replace(/[^0-9+]/g, '');
            if (cleanNumber.length > 15) {
                return false;
            }
            const mobileRegex = /^(\+?\d{1,3})?\d{7,15}$/;
            return mobileRegex.test(cleanNumber);
        }
        
        // Date validation helper
        function isValidDate(dateString) {
            const date = new Date(dateString);
            return date instanceof Date && !isNaN(date) && dateString === date.toISOString().split('T')[0];
        }
        
        // Account number validation helper
        function isValidAccountNumber(accountNumber) {
            // Should be 10 digits
            const accountRegex = /^\d{10}$/;
            return accountRegex.test(accountNumber);
        }
        
        // Status validation helper
        function isValidStatus(status) {
            const validStatuses = ['Active', 'Inactive', 'On Leave', 'Suspended', 'Terminated', 'pending', 'approved', 'rejected'];
            return validStatuses.includes(status);
        }
        
        // Function to show validation error
        function showValidationError(message) {
            const alertContainer = document.querySelector('.alert-container');
            // Remove existing error alerts
            const existingAlerts = alertContainer.querySelectorAll('.alert-danger');
            existingAlerts.forEach(alert => alert.remove());
            
            alertContainer.innerHTML += `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
        }
        
        // Clear validation errors when user starts typing
        formSections.forEach((section, index) => {
            const fields = section.querySelectorAll('input, select, textarea');
            fields.forEach(field => {
                // Clear error when user types
                field.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    
                    // Remove error message if field is being edited
                    const errorMessages = document.querySelectorAll('.text-danger');
                    errorMessages.forEach(msg => {
                        if (msg.id === this.id + '-error' || msg.getAttribute('data-field') === this.id) {
                            msg.remove();
                        }
                    });
                });
                
                // Validate field on blur
                field.addEventListener('blur', function() {
                    validateField(this);
                });
            });
        });
        
        // Function to validate individual field
        function validateField(field) {
            let isValid = true;
            let errorMsg = '';
            
            // Check required field
            if (field.hasAttribute('required') && !field.value.trim()) {
                isValid = false;
                errorMsg = 'This field is required';
            }
            // Validate specific field types
            else if (field.type === 'email' && field.value && !isValidEmail(field.value)) {
                isValid = false;
                errorMsg = 'Please enter a valid email address';
            }
            else if (field.type === 'date' && field.value && !isValidDate(field.value)) {
                isValid = false;
                errorMsg = 'Please enter a valid date';
            }
            else if ((field.id === 'mobile_no' || field.id === 'next_of_kin_mobile_no') && field.value) {
                const cleanNumber = field.value.replace(/[^0-9+]/g, '');
                if (cleanNumber.length > 15) {
                    isValid = false;
                    errorMsg = 'Mobile number must not exceed 15 characters';
                } else if (!isValidMobileNumber(field.value)) {
                    isValid = false;
                    errorMsg = 'Please enter a valid mobile number';
                }
            }
            else if (field.id === 'password' && field.value && field.value.length < 6) {
                isValid = false;
                errorMsg = 'Password must be at least 6 characters long';
            }
            else if (field.id === 'account_no' && field.value && !isValidAccountNumber(field.value)) {
                isValid = false;
                errorMsg = 'Account number must be 10 digits';
            }
            else if (field.id === 'status' && field.value && !isValidStatus(field.value)) {
                isValid = false;
                errorMsg = 'Please select a valid status (Active, Inactive, On Leave, Suspended, Terminated)';
            }
            
            // Add/remove error styling and message
            field.classList.toggle('is-invalid', !isValid);
            
            if (!isValid) {
                // Remove existing error message for this field
                const existingError = document.getElementById(field.id + '-error');
                if (existingError) {
                    existingError.remove();
                }
                
                // Add new error message
                const errorElement = document.createElement('div');
                errorElement.id = field.id + '-error';
                errorElement.className = 'text-danger small mt-1';
                errorElement.setAttribute('data-field', field.id);
                errorElement.textContent = errorMsg;
                
                // Insert after the field's parent container
                const parentContainer = field.closest('.d-flex.align-items-center');
                if (parentContainer) {
                    parentContainer.appendChild(errorElement);
                } else {
                    field.parentNode.insertBefore(errorElement, field.nextSibling);
                }
            } else {
                // Remove existing error message for this field
                const existingError = document.getElementById(field.id + '-error');
                if (existingError) {
                    existingError.remove();
                }
            }
            
            return isValid;
        }
        
        // Initialize the form
        showStep(currentStep);
        
        // Function to show success alert
        function showSuccessAlert(message) {
            const alertContainer = document.querySelector('.alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            
            // Clear the alert after 5 seconds
            setTimeout(function() {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        const lgaSelect = document.getElementById('lga_id');
        const wardSelect = document.getElementById('ward_id');
        const areaSelect = document.getElementById('area_id');

        if (lgaSelect && wardSelect && areaSelect) {
            const allWards = Array.from(wardSelect.options);
            const allAreas = Array.from(areaSelect.options);

            lgaSelect.addEventListener('change', function () {
                const lgaId = this.value;
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                areaSelect.innerHTML = '<option value="">Select Area</option>';

                allWards.forEach(option => {
                    if (option.dataset.lgaId === lgaId) {
                        wardSelect.appendChild(option.cloneNode(true));
                    }
                });
            });

            wardSelect.addEventListener('change', function () {
                const wardId = this.value;
                areaSelect.innerHTML = '<option value="">Select Area</option>';

                allAreas.forEach(option => {
                    if (option.dataset.wardId === wardId) {
                        areaSelect.appendChild(option.cloneNode(true));
                    }
                });
            });
        }
        
        // Handle form submission
        document.getElementById('create-staff-form').addEventListener('submit', function(e) {
            // Final validation before submission
            if (!validateStep(currentStep)) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection