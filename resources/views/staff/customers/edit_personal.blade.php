@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Customer - Personal Information</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('staff.customers.index') }}" class="btn btn-secondary">Back to Customers</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alerts -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staff.customers.update.personal', $customer) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="first_name" class="required form-label">First Name</label>
                                <input type="text" class="form-control form-control-solid" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" required>
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <label for="surname" class="required form-label">Surname</label>
                                <input type="text" class="form-control form-control-solid" name="surname" id="surname" value="{{ old('surname', $customer->surname) }}" required>
                                @error('surname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control form-control-solid" name="middle_name" id="middle_name" value="{{ old('middle_name', $customer->middle_name) }}">
                                @error('middle_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" class="required form-label">Email</label>
                                <input type="email" class="form-control form-control-solid" name="email" id="email" value="{{ old('email', $customer->email) }}" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="phone_number" class="required form-label">Phone Number</label>
                                <input type="text" class="form-control form-control-solid" name="phone_number" id="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" required>
                                @error('phone_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="alternate_phone_number" class="form-label">Alternate Phone Number</label>
                            <input type="text" class="form-control form-control-solid" name="alternate_phone_number" id="alternate_phone_number" value="{{ old('alternate_phone_number', $customer->alternate_phone_number) }}">
                            @error('alternate_phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Personal Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection