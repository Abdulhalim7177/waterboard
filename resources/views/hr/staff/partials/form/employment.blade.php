<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="date_of_first_appointment" class="form-label">Date of First Appointment</label>
            <input type="date" class="form-control" id="date_of_first_appointment" name="date_of_first_appointment" value="{{ old('date_of_first_appointment', $staff->date_of_first_appointment ? $staff->date_of_first_appointment->format('Y-m-d') : '') }}">
        </div>
        <div class="mb-3">
            <label for="contract_start_date" class="form-label">Contract Start Date</label>
            <input type="date" class="form-control" id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date', $staff->contract_start_date ? $staff->contract_start_date->format('Y-m-d') : '') }}">
        </div>
        <div class="mb-3">
            <label for="contract_end_date" class="form-label">Contract End Date</label>
            <input type="date" class="form-control" id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date', $staff->contract_end_date ? $staff->contract_end_date->format('Y-m-d') : '') }}">
        </div>
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select class="form-select" id="department_id" name="department_id">
                @foreach (\App\Models\Department::all() as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $staff->department_id ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="rank_id" class="form-label">Rank</label>
            <select class="form-select" id="rank_id" name="rank_id">
                @foreach (\App\Models\Rank::all() as $rank)
                    <option value="{{ $rank->id }}" {{ old('rank_id', $staff->rank_id ?? '') == $rank->id ? 'selected' : '' }}>{{ $rank->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="cadre_id" class="form-label">Cadre</label>
            <select class="form-select" id="cadre_id" name="cadre_id">
                @foreach (\App\Models\Cadre::all() as $cadre)
                    <option value="{{ $cadre->id }}" {{ old('cadre_id', $staff->cadre_id ?? '') == $cadre->id ? 'selected' : '' }}>{{ $cadre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="grade_level_id" class="form-label">Grade Level</label>
            <select class="form-select" id="grade_level_id" name="grade_level_id">
                @foreach (\App\Models\GradeLevel::all() as $gradeLevel)
                    <option value="{{ $gradeLevel->id }}" {{ old('grade_level_id', $staff->grade_level_id ?? '') == $gradeLevel->id ? 'selected' : '' }}>{{ $gradeLevel->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="step_id" class="form-label">Step</label>
            <select class="form-select" id="step_id" name="step_id">
                @foreach (\App\Models\Step::all() as $step)
                    <option value="{{ $step->id }}" {{ old('step_id', $staff->step_id ?? '') == $step->id ? 'selected' : '' }}>{{ $step->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="appointment_type_id" class="form-label">Appointment Type</label>
            <select class="form-select" id="appointment_type_id" name="appointment_type_id">
                @foreach (\App\Models\AppointmentType::all() as $appointmentType)
                    <option value="{{ $appointmentType->id }}" {{ old('appointment_type_id', $staff->appointment_type_id ?? '') == $appointmentType->id ? 'selected' : '' }}>{{ $appointmentType->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="expected_next_promotion" class="form-label">Expected Next Promotion</label>
            <input type="date" class="form-control" id="expected_next_promotion" name="expected_next_promotion" value="{{ old('expected_next_promotion', $staff->expected_next_promotion ? $staff->expected_next_promotion->format('Y-m-d') : '') }}">
        </div>
        <div class="mb-3">
            <label for="expected_retirement_date" class="form-label">Expected Retirement Date</label>
            <input type="date" class="form-control" id="expected_retirement_date" name="expected_retirement_date" value="{{ old('expected_retirement_date', $staff->expected_retirement_date ? $staff->expected_retirement_date->format('Y-m-d') : '') }}">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="pending" {{ old('status', $staff->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ old('status', $staff->status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ old('status', $staff->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="employment_status" class="form-label">Employment Status</label>
            <input type="text" class="form-control" id="employment_status" name="employment_status" value="{{ old('employment_status', $staff->employment_status ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="highest_qualifications" class="form-label">Highest Qualifications</label>
            <input type="text" class="form-control" id="highest_qualifications" name="highest_qualifications" value="{{ old('highest_qualifications', $staff->highest_qualifications ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="years_of_service" class="form-label">Years of Service</label>
            <input type="number" class="form-control" id="years_of_service" name="years_of_service" value="{{ old('years_of_service', $staff->years_of_service ?? '') }}">
        </div>
    </div>
</div>
