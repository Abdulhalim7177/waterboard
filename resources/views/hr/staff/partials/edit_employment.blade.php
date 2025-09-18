<div class="card-body">
    <form id="edit-employment-form" action="{{ url('mngr-secure-9374/hr/staff/' . $staff->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="employment">
        <div class="row mb-7">
            <div class="col-lg-3">
                <div class="d-flex flex-column align-items-center text-center mb-7">
                    <div class="mb-7">
                        <div class="symbol symbol-125px symbol-circle">
                            <div class="symbol-label fs-1 bg-light-primary text-primary">E</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bolder fs-3">Employment Info</div>
                        <div class="text-muted fw-bold mt-1">Work-related details</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row mb-5">
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">Date of First Appointment</label>
                        <input type="date" name="date_of_first_appointment" class="form-control form-control-solid @error('date_of_first_appointment') is-invalid @enderror" value="{{ old('date_of_first_appointment', $staff->date_of_first_appointment ? $staff->date_of_first_appointment->format('Y-m-d') : '') }}" required />
                        @error('date_of_first_appointment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Rank</label>
                        <input type="text" name="rank" class="form-control form-control-solid @error('rank') is-invalid @enderror" value="{{ old('rank', $staff->rank) }}" />
                        @error('rank')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Staff No</label>
                        <input type="text" name="staff_no" class="form-control form-control-solid @error('staff_no') is-invalid @enderror" value="{{ old('staff_no', $staff->staff_no) }}" />
                        @error('staff_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Department</label>
                        <input type="text" name="department" class="form-control form-control-solid @error('department') is-invalid @enderror" value="{{ old('department', $staff->department) }}" />
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">Status</label>
                        <select name="employment_status" class="form-control form-control-solid @error('employment_status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('employment_status', $staff->employment_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('employment_status', $staff->employment_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ old('employment_status', $staff->employment_status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="suspended" {{ old('employment_status', $staff->employment_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="terminated" {{ old('employment_status', $staff->employment_status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('employment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Years of Service</label>
                        <input type="number" name="years_of_service" class="form-control form-control-solid @error('years_of_service') is-invalid @enderror" value="{{ old('years_of_service', $staff->years_of_service) }}" />
                        @error('years_of_service')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Expected Next Promotion</label>
                        <input type="date" name="expected_next_promotion" class="form-control form-control-solid @error('expected_next_promotion') is-invalid @enderror" value="{{ old('expected_next_promotion', $staff->expected_next_promotion ? $staff->expected_next_promotion->format('Y-m-d') : '') }}" />
                        @error('expected_next_promotion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Expected Retirement Date</label>
                        <input type="date" name="expected_retirement_date" class="form-control form-control-solid @error('expected_retirement_date') is-invalid @enderror" value="{{ old('expected_retirement_date', $staff->expected_retirement_date ? $staff->expected_retirement_date->format('Y-m-d') : '') }}" />
                        @error('expected_retirement_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Appointment Type</label>
                        <input type="text" name="appointment_type" class="form-control form-control-solid @error('appointment_type') is-invalid @enderror" value="{{ old('appointment_type', $staff->appointment_type) }}" />
                        @error('appointment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Highest Qualifications</label>
                        <input type="text" name="highest_qualifications" class="form-control form-control-solid @error('highest_qualifications') is-invalid @enderror" value="{{ old('highest_qualifications', $staff->highest_qualifications) }}" />
                        @error('highest_qualifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Grade Level Limit</label>
                        <input type="text" name="grade_level_limit" class="form-control form-control-solid @error('grade_level_limit') is-invalid @enderror" value="{{ old('grade_level_limit', $staff->grade_level_limit) }}" />
                        @error('grade_level_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('staff.hr.staff.show', $staff->id) }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Employment Information</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission via AJAX
    const form = document.getElementById('edit-employment-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'An error occurred');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Show success message
                    alert(data.message);
                    // Optionally reload the section
                    // loadSection('employment');
                } else {
                    alert('Error: ' + (data.message || 'An error occurred'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>