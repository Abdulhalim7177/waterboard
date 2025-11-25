<div class="card">
    <div class="card-header">
        <h3 class="card-title">Personal Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('staff.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="part" value="personal">
            
            <div class="row mb-6">
                <div class="col-md-6 fv-row">
                    <label for="first_name" class="required form-label">First Name</label>
                    <input type="text" class="form-control form-control-solid" name="first_name" id="first_name" value="{{ old('first_name', $customer?->first_name) }}" required>
                    @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 fv-row">
                    <label for="surname" class="required form-label">Surname</label>
                    <input type="text" class="form-control form-control-solid" name="surname" id="surname" value="{{ old('surname', $customer?->surname) }}" required>
                    @error('surname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-6">
                <div class="col-md-6 fv-row">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control form-control-solid" name="middle_name" id="middle_name" value="{{ old('middle_name', $customer?->middle_name) }}">
                    @error('middle_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 fv-row">
                    <label for="email" class="required form-label">Email</label>
                    <input type="email" class="form-control form-control-solid" name="email" id="email" value="{{ old('email', $customer?->email) }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6 fv-row">
                    <label for="phone_number" class="required form-label">Phone Number</label>
                    <input type="text" inputmode="numeric" class="form-control form-control-solid" name="phone_number" id="phone_number" value="{{ old('phone_number', $customer?->phone_number) }}" required>
                    @error('phone_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 fv-row">
                    <label for="alternate_phone_number" class="form-label">Alternate Phone Number</label>
                    <input type="text" inputmode="numeric" class="form-control form-control-solid" name="alternate_phone_number" id="alternate_phone_number" value="{{ old('alternate_phone_number', $customer?->alternate_phone_number) }}">
                    @error('alternate_phone_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit Changes</button>
            </div>
        </form>
    </div>
</div>