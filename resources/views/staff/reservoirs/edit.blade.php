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
                    <h2 class="mb-0">Edit Reservoir</h2>
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
                <form action="{{ route('staff.reservoirs.update', $reservoir['id']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="name" class="form-label required">Reservoir Name</label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $reservoir['label'] ?? $reservoir['name'] ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control form-control-solid @error('serial_number') is-invalid @enderror" 
                                   id="serial_number" name="serial_number" value="{{ old('serial_number', $reservoir['ref'] ?? $reservoir['serial_number'] ?? '') }}">
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
                                   id="location" name="location" value="{{ old('location', $reservoir['location'] ?? '') }}">
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
                                    <option value="{{ $warehouse['id'] }}" {{ old('warehouse_id', $reservoir['warehouse_id'] ?? '') == $warehouse['id'] ? 'selected' : '' }}>{{ $warehouse['label'] }}</option>
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
                                <option value="active" {{ old('status', $reservoir['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ old('status', $reservoir['status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="retired" {{ old('status', $reservoir['status'] ?? '') == 'retired' ? 'selected' : '' }}>Retired</option>
                                <option value="damaged" {{ old('status', $reservoir['status'] ?? '') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control form-control-solid @error('purchase_date') is-invalid @enderror" 
                                   id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $reservoir['purchase_date'] ? date('Y-m-d', strtotime($reservoir['purchase_date'])) : '') }}">
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
                                   id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $reservoir['price'] ?? '') }}">
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
                                  id="description" name="description" rows="4">{{ old('description', $reservoir['description'] ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('staff.reservoirs.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Reservoir</button>
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
