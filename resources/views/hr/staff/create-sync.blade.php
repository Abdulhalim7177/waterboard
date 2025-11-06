@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create and Sync Staff</h1>

    <form action="{{ route('hr.staff.store-sync') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" name="surname" id="surname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" name="middle_name" id="middle_name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="staff_no">Staff Number</label>
                    <input type="text" name="staff_no" id="staff_no" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="nationality">Nationality</label>
                    <input type="text" name="nationality" id="nationality" class="form-control">
                </div>
                <div class="form-group">
                    <label for="nin">NIN</label>
                    <input type="text" name="nin" id="nin" class="form-control">
                </div>
                <div class="form-group">
                    <label for="mobile_no">Mobile Number</label>
                    <input type="text" name="mobile_no" id="mobile_no" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_of_first_appointment">Date of First Appointment</label>
                    <input type="date" name="date_of_first_appointment" id="date_of_first_appointment" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="expected_retirement_date">Expected Retirement Date</label>
                    <input type="date" name="expected_retirement_date" id="expected_retirement_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" name="status" id="status" class="form-control">
                </div>
                <div class="form-group">
                    <label for="highest_certificate">Highest Certificate</label>
                    <input type="text" name="highest_certificate" id="highest_certificate" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create and Sync</button>
    </form>
</div>
@endsection
