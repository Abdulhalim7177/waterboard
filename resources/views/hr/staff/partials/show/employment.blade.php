<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Employment Status</span>
        <span class="badge badge-light-{{ $staff->employment_status == 'active' ? 'success' : ($staff->employment_status == 'on_leave' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $staff->employment_status)) }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Department</span>
        <span class="text-dark fs-6">{{ $staff->department->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Rank</span>
        <span class="text-dark fs-6">{{ $staff->rank->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Cadre</span>
        <span class="text-dark fs-6">{{ $staff->cadre->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Grade Level</span>
        <span class="text-dark fs-6">{{ $staff->gradeLevel->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Step</span>
        <span class="text-dark fs-6">{{ $staff->step->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Date of First Appointment</span>
        <span class="text-dark fs-6">{{ $staff->date_of_first_appointment ? $staff->date_of_first_appointment->format('F j, Y') : 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Contract Start Date</span>
        <span class="text-dark fs-6">{{ $staff->contract_start_date ? $staff->contract_start_date->format('F j, Y') : 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Contract End Date</span>
        <span class="text-dark fs-6">{{ $staff->contract_end_date ? $staff->contract_end_date->format('F j, Y') : 'N/A' }}</span>
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
        <span class="text-muted fs-7 fw-bold w-150px">Appointment Type</span>
        <span class="text-dark fs-6">{{ $staff->appointmentType->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Years of Service</span>
        <span class="text-dark fs-6">{{ $staff->years_of_service ?? 'N/A' }}</span>
    </div>
</div>
