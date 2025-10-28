@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Asset</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.assets.update', $asset['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control form-control-solid" value="{{ $asset['label'] }}" required>
                        </div>
                        <div class="form-group mb-5">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-solid">{{ $asset['description'] }}</textarea>
                        </div>
                        <div class="form-group mb-5">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control form-control-solid" value="{{ $asset['ref'] }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" name="purchase_price" class="form-control form-control-solid" value="{{ $asset['price'] }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control form-control-solid" value="{{ isset($asset['date_purchase']) ? date('Y-m-d', $asset['date_purchase']) : '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="warehouse_id" class="form-label">Warehouse</label>
                            <select name="warehouse_id" class="form-select form-select-solid" required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse['id'] }}" {{ $asset['warehouse_id'] == $warehouse['id'] ? 'selected' : '' }}>
                                        {{ $warehouse['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-5">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" name="category" class="form-control form-control-solid" value="{{ $asset['array_options']['options_category'] ?? '' }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" class="form-select form-select-solid">
                                <option value="product" {{ ($asset['array_options']['options_type'] ?? '') == 'product' ? 'selected' : '' }}>Product</option>
                                <option value="service" {{ ($asset['array_options']['options_type'] ?? '') == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="equipment" {{ ($asset['array_options']['options_type'] ?? '') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="infrastructure" {{ ($asset['array_options']['options_type'] ?? '') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                <option value="vehicle" {{ ($asset['array_options']['options_type'] ?? '') == 'vehicle' ? 'selected' : '' }}>Vehicle</option>
                                <option value="tool" {{ ($asset['array_options']['options_type'] ?? '') == 'tool' ? 'selected' : '' }}>Tool</option>
                                <option value="other" {{ ($asset['array_options']['options_type'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="form-group mb-5">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" name="model" class="form-control form-control-solid" value="{{ $asset['array_options']['options_model'] ?? '' }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" name="brand" class="form-control form-control-solid" value="{{ $asset['array_options']['options_brand'] ?? '' }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" class="form-control form-control-solid" value="{{ $asset['array_options']['options_location'] ?? '' }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="active" {{ ($asset['array_options']['options_status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ ($asset['array_options']['options_status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="retired" {{ ($asset['array_options']['options_status'] ?? '') == 'retired' ? 'selected' : '' }}>Retired</option>
                                <option value="damaged" {{ ($asset['array_options']['options_status'] ?? '') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('staff.assets.index') }}" class="btn btn-light me-3">Back to List</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection