
@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Reservoir</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.reservoirs.update', $reservoir['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control form-control-solid" value="{{ $reservoir['label'] }}" required>
                        </div>
                        <div class="form-group mb-5">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-solid">{{ $reservoir['description'] }}</textarea>
                        </div>
                        <div class="form-group mb-5">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control form-control-solid" value="{{ $reservoir['ref'] }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" name="purchase_price" class="form-control form-control-solid" value="{{ $reservoir['price'] }}">
                        </div>
                        <div class="form-group mb-5">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control form-control-solid" value="{{ isset($reservoir['date_purchase']) ? date('Y-m-d', $reservoir['date_purchase']) : '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="warehouse_id" class="form-label">Warehouse</label>
                            <select name="warehouse_id" class="form-select form-select-solid" required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse['id'] }}" {{ $reservoir['warehouse_id'] == $warehouse['id'] ? 'selected' : '' }}>
                                        {{ $warehouse['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-5">
                            <label for="tanks" class="form-label">Tanks</label>
                            <input type="number" name="tanks" class="form-control form-control-solid" value="{{ $reservoir['array_options']['options_tanks'] ?? '' }}" required>
                        </div>
                        <div class="form-group mb-5">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" name="capacity" class="form-control form-control-solid" value="{{ $reservoir['array_options']['options_capacity'] ?? '' }}" required>
                        </div>
                        <div class="form-group mb-5">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" class="form-control form-control-solid" value="{{ $reservoir['array_options']['options_location'] ?? '' }}" required>
                        </div>
                        <div class="form-group mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="active" {{ ($reservoir['array_options']['options_status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ ($reservoir['array_options']['options_status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="retired" {{ ($reservoir['array_options']['options_status'] ?? '') == 'retired' ? 'selected' : '' }}>Retired</option>
                                <option value="damaged" {{ ($reservoir['array_options']['options_status'] ?? '') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('staff.reservoirs.index') }}" class="btn btn-light me-3">Back to List</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
