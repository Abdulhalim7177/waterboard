<div class="card-body">
    <form id="edit-personal-form" action="{{ route('staff.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="personal">
        <div class="row mb-6">
            <div class="col-md-6 fv-row">
                <label for="first_name" class="form-label required">First Name</label>
                <input type="text" class="form-control form-control-solid @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 fv-row">
                <label for="surname" class="form-label required">Last Name</label>
                <input type="text" class="form-control form-control-solid @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $customer->surname) }}" required>
                @error('surname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6 fv-row">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" class="form-control form-control-solid @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $customer->middle_name) }}">
                @error('middle_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 fv-row">
                <label for="email" class="form-label required">Email Address</label>
                <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6 fv-row">
                <label for="phone_number" class="form-label required">Primary Phone Number</label>
                <input type="text" class="form-control form-control-solid @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" required>
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 fv-row">
                <label for="alternate_phone_number" class="form-label">Alternate Phone Number</label>
                <input type="text" class="form-control form-control-solid @error('alternate_phone_number') is-invalid @enderror" id="alternate_phone_number" name="alternate_phone_number" value="{{ old('alternate_phone_number', $customer->alternate_phone_number) }}">
                @error('alternate_phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-end">
                <a href="{{ route('staff.customers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit for Approval</button>
            </div>
        </div>
    </form>
</div>   