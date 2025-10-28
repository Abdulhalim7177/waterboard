
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New Asset</h1>
        <form action="{{ route('staff.assets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" name="serial_number" class="form-control">
            </div>
            <div class="form-group">
                <label for="purchase_price">Purchase Price</label>
                <input type="number" name="purchase_price" class="form-control">
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date</label>
                <input type="date" name="purchase_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="warehouse_id">Warehouse</label>
                <select name="warehouse_id" class="form-control" required>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse['id'] }}">{{ $warehouse['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category" class="form-control">
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" class="form-control">
                    <option value="product">Product</option>
                    <option value="service">Service</option>
                    <option value="equipment">Equipment</option>
                    <option value="infrastructure">Infrastructure</option>
                    <option value="vehicle">Vehicle</option>
                    <option value="tool">Tool</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" name="model" class="form-control">
            </div>
            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" name="brand" class="form-control">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" class="form-control">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="retired">Retired</option>
                    <option value="damaged">Damaged</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
