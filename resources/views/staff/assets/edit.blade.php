
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Asset</h1>
        <form action="{{ route('staff.assets.update', $asset['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $asset['label'] }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ $asset['description'] }}</textarea>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" name="serial_number" class="form-control" value="{{ $asset['ref'] }}">
            </div>
            <div class="form-group">
                <label for="purchase_price">Purchase Price</label>
                <input type="number" name="purchase_price" class="form-control" value="{{ $asset['price'] }}">
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" name="purchase_date" class="form-control" value="{{ isset($asset['date_purchase']) ? date('Y-m-d', $asset['date_purchase']) : '' }}">
            </div>
            <div class="form-group">
                <label for="warehouse_id">Warehouse</label>
                <select name="warehouse_id" class="form-control" required>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse['id'] }}" {{ $asset['warehouse_id'] == $warehouse['id'] ? 'selected' : '' }}>
                            {{ $warehouse['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category" class="form-control" value="{{ $asset['array_options']['options_category'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" class="form-control">
                    <option value="product" {{ ($asset['array_options']['options_type'] ?? '') == 'product' ? 'selected' : '' }}>Product</option>
                    <option value="service" {{ ($asset['array_options']['options_type'] ?? '') == 'service' ? 'selected' : '' }}>Service</option>
                    <option value="equipment" {{ ($asset['array_options']['options_type'] ?? '') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                    <option value="infrastructure" {{ ($asset['array_options']['options_type'] ?? '') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                    <option value="vehicle" {{ ($asset['array_options']['options_type'] ?? '') == 'vehicle' ? 'selected' : '' }}>Vehicle</option>
                    <option value="tool" {{ ($asset['array_options']['options_type'] ?? '') == 'tool' ? 'selected' : '' }}>Tool</option>
                    <option value="other" {{ ($asset['array_options']['options_type'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" name="model" class="form-control" value="{{ $asset['array_options']['options_model'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $asset['array_options']['options_brand'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" class="form-control" value="{{ $asset['array_options']['options_location'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ ($asset['array_options']['options_status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ ($asset['array_options']['options_status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="retired" {{ ($asset['array_options']['options_status'] ?? '') == 'retired' ? 'selected' : '' }}>Retired</option>
                    <option value="damaged" {{ ($asset['array_options']['options_status'] ?? '') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
