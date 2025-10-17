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
                    <h2 class="mb-0">Edit Asset</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('staff.assets.index') }}" class="btn btn-light-primary">Back to Assets</a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form action="{{ route('staff.assets.update', $asset['id']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="name" class="form-label required">Asset Name</label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $asset['label'] ?? $asset['name'] ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control form-control-solid @error('category') is-invalid @enderror" 
                                   id="category" name="category" value="{{ old('category', $asset['category'] ?? '') }}">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select form-select-solid @error('type') is-invalid @enderror" 
                                    id="type" name="type">
                                <option value="">Select Asset Type</option>
                                <option value="product" {{ old('type', $asset['type'] ?? '') == 'product' ? 'selected' : '' }}>Product</option>
                                <option value="service" {{ old('type', $asset['type'] ?? '') == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="equipment" {{ old('type', $asset['type'] ?? '') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="infrastructure" {{ old('type', $asset['type'] ?? '') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                <option value="vehicle" {{ old('type', $asset['type'] ?? '') == 'vehicle' ? 'selected' : '' }}>Vehicle</option>
                                <option value="tool" {{ old('type', $asset['type'] ?? '') == 'tool' ? 'selected' : '' }}>Tool</option>
                                <option value="other" {{ old('type', $asset['type'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control form-control-solid @error('serial_number') is-invalid @enderror" 
                                   id="serial_number" name="serial_number" value="{{ old('serial_number', $asset['ref'] ?? $asset['serial_number'] ?? '') }}">
                            @error('serial_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control form-control-solid @error('model') is-invalid @enderror" 
                                   id="model" name="model" value="{{ old('model', $asset['model'] ?? '') }}">
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control form-control-solid @error('brand') is-invalid @enderror" 
                                   id="brand" name="brand" value="{{ old('brand', $asset['brand'] ?? '') }}">
                            @error('brand')
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
                                   id="location" name="location" value="{{ old('location', $asset['location'] ?? '') }}">
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
                                    <option value="{{ $warehouse['id'] }}" {{ old('warehouse_id', $asset['warehouse_id'] ?? '') == $warehouse['id'] ? 'selected' : '' }}>{{ $warehouse['label'] }}</option>
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
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control form-control-solid @error('purchase_date') is-invalid @enderror" 
                                   id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $asset['purchase_date'] ? date('Y-m-d', strtotime($asset['purchase_date'])) : '') }}">
                            @error('purchase_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="purchase_price" class="form-label">Purchase Price (₦)</label>
                            <input type="number" step="0.01" class="form-control form-control-solid @error('purchase_price') is-invalid @enderror" 
                                   id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $asset['price'] ?? '') }}">
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
                                  id="description" name="description" rows="4">{{ old('description', $asset['description'] ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('staff.assets.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Asset</button>
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