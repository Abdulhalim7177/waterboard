@extends('layouts.staff')

@section('content')
    <!--begin::Container-->
    <div class="container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="mb-0">Add New Reservoir</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('staff.reservoirs.index') }}" class="btn btn-light-primary">Back to Reservoirs</a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form id="kt_reservoir_create_form" action="{{ route('staff.reservoirs.store') }}" method="POST">
                    @csrf
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="name" class="form-label required">Reservoir Name</label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control form-control-solid @error('serial_number') is-invalid @enderror" 
                                   id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                            @error('serial_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control form-control-solid @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="warehouse_id" class="form-label">Warehouse</label>
                            <select class="form-select form-select-solid @error('warehouse_id') is-invalid @enderror" 
                                    id="warehouse_id" name="warehouse_id" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse['id'] }}" {{ old('warehouse_id') == $warehouse['id'] ? 'selected' : '' }}>{{ $warehouse['label'] }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-solid @error('status') is-invalid @enderror" 
                                    id="status" name="status">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                                <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control form-control-solid @error('purchase_date') is-invalid @enderror" 
                                   id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                            @error('purchase_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="purchase_price" class="form-label">Purchase Price (₦)</label>
                            <input type="number" step="0.01" class="form-control form-control-solid @error('purchase_price') is-invalid @enderror" 
                                   id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}">
                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Input group-->
                    <div class="mb-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control form-control-solid @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('staff.reservoirs.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Reservoir</button>
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection

@section('scripts')
    <script>
        // Direct form submission without using FormValidation to avoid conflicts with global scripts
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('kt_reservoir_create_form');
            const submitButton = form.querySelector('button[type="submit"]');
            
            // Remove any potential event listeners from global scripts
            if (form) {
                // Handle form submission directly
                form.onsubmit = function(e) {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    return true; // Allow form to submit normally
                };
            }
        });
    </script>
@endsection
