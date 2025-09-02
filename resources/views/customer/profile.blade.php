@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Your Profile</h2>
    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="{{ $customer->first_name }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Surname</label>
            <input type="text" name="surname" value="{{ $customer->surname }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $customer->email }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" value="{{ $customer->phone_number }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alternate Phone Number</label>
            <input type="text" name="alternate_phone_number" value="{{ $customer->alternate_phone_number }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection