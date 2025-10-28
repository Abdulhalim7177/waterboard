@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Reservoir</h1>
        <form action="{{ route('staff.reservoirs.update', $reservoir['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $reservoir['label'] }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ $reservoir['description'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" name="serial_number" class="form-control" value="{{ $reservoir['ref'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="purchase_price">Purchase Price</label>
                <input type="number" name="purchase_price" class="form-control" value="{{ $reservoir['price'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" name="purchase_date" class="form-control" value="{{ isset($reservoir['date_purchase']) ? date('Y-m-d', $reservoir['date_purchase']) : '' }}">
            </div>
            <div class="form-group">
                <label for="warehouse_id">Warehouse</label>
                <select name="warehouse_id" class="form-control" required>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse['id'] }}" {{ $reservoir['warehouse_id'] == $warehouse['id'] ? 'selected' : '' }}>
                            {{ $warehouse['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tanks">Tanks</label>
                <input type="number" name="tanks" class="form-control" value="{{ $reservoir['array_options']['options_tanks'] ?? '' }}" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" name="capacity" class="form-control" value="{{ $reservoir['array_options']['options_capacity'] ?? '' }}" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" class="form-control" value="{{ $reservoir['array_options']['options_location'] ?? '' }}" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ ($reservoir['array_options']['options_status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ ($reservoir['array_options']['options_status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="retired" {{ ($reservoir['array_options']['options_status'] ?? '') == 'retired' ? 'selected' : '' }}>Retired</option>
                    <option value="damaged" {{ ($reservoir['array_options']['options_status'] ?? '') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection