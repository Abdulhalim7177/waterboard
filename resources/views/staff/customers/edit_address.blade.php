@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Address: {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->billing_id ?? 'Pending' }})</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('staff.customers.edit', $customer->id) }}" class="btn btn-secondary">Back to Edit Options</a>
                    </div>
                </div>
                <div class="card-body">
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
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- LGA Selection Form -->
                    <form action="{{ route('staff.customers.filter.wards') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div class="row">
                            <div class="col-md-6 fv-row">
                                <label for="lga_id" class="form-label required">LGA</label>
                                <select class="form-select form-select-solid @error('lga_id') is-invalid @enderror" id="lga_id" name="lga_id" onchange="this.form.submit()" required>
                                    <option value="">Select LGA</option>
                                    @foreach ($lgas as $lga)
                                        <option value="{{ $lga->id }}" {{ old('lga_id', $selectedLgaId ?? $customer->lga_id) == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                    @endforeach
                                </select>
                                @error('lga_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>

                    <!-- Ward Selection Form -->
                    <form action="{{ route('staff.customers.filter.areas') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="lga_id" value="{{ old('lga_id', $selectedLgaId ?? $customer->lga_id) }}">
                        <div class="row">
                            <div class="col-md-6 fv-row">
                                <label for="ward_id" class="form-label required">Ward</label>
                                <select class="form-select form-select-solid @error('ward_id') is-invalid @enderror" id="ward_id" name="ward_id" onchange="this.form.submit()" required>
                                    <option value="">Select Ward</option>
                                    @foreach ($wards as $ward)
                                        <option value="{{ $ward->id }}" {{ old('ward_id', $selectedWardId ?? $customer->ward_id) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                    @endforeach
                                </select>
                                @error('ward_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>

                    <!-- Main Address Update Form -->
                    <form action="{{ route('staff.customers.update.address', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="lga_id" value="{{ old('lga_id', $selectedLgaId ?? $customer->lga_id) }}">
                        <input type="hidden" name="ward_id" value="{{ old('ward_id', $selectedWardId ?? $customer->ward_id) }}">
                        <div class="row mb-6">
                            <div class="col-md-6 fv-row">
                                <label for="area_id" class="form-label required">Area</label>
                                <select class="form-select form-select-solid @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
                                    <option value="">Select Area</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', $customer->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="street_name" class="form-label required">Street Name</label>
                                <input type="text" class="form-control form-control-solid @error('street_name') is-invalid @enderror" id="street_name" name="street_name" value="{{ old('street_name', $customer->street_name) }}" required>
                                @error('street_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-md-6 fv-row">
                                <label for="house_number" class="form-label">House Number</label>
                                <input type="text" class="form-control form-control-solid @error('house_number') is-invalid @enderror" id="house_number" name="house_number" value="{{ old('house_number', $customer->house_number) }}">
                                @error('house_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="landmark" class="form-label">Landmark</label>
                                <input type="text" class="form-control form-control-solid @error('landmark') is-invalid @enderror" id="landmark" name="landmark" value="{{ old('landmark', $customer->landmark) }}">
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('staff.customers.edit', $customer->id) }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit for Approval</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection