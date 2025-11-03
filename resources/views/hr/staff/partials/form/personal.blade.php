<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="staff_id" class="form-label">Staff ID</label>
            <input type="text" class="form-control" id="staff_id" name="staff_id" value="{{ old('staff_id', $staff->staff_id ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $staff->first_name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname', $staff->surname ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $staff->middle_name ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $staff->email ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" id="gender" name="gender">
                <option value="male" {{ old('gender', $staff->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $staff->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $staff->date_of_birth ? $staff->date_of_birth->format('Y-m-d') : '') }}">
        </div>
        <div class="mb-3">
            <label for="nationality" class="form-label">Nationality</label>
            <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality', $staff->nationality ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="nin" class="form-label">NIN</label>
            <input type="text" class="form-control" id="nin" name="nin" value="{{ old('nin', $staff->nin ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="mobile_no" class="form-label">Mobile No</label>
            <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $staff->mobile_no ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $staff->phone_number ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address">{{ old('address', $staff->address ?? '') }}</textarea>
        </div>
    </div>
</div>
