@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Asset</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.assets.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control form-control-solid" required>
                        </div>
                        <div class="form-group mb-5">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-solid"></textarea>
                        </div>
                        <div class="form-group mb-5">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control form-control-solid">
                        </div>
                        <div class="form-group mb-5">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" name="purchase_price" class="form-control form-control-solid">
                        </div>
                        <div class="form-group mb-5">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control form-control-solid">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="warehouse_id" class="form-label">Warehouse</label>
                            <select name="warehouse_id" class="form-select form-select-solid" required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse['id'] }}">{{ $warehouse['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-5">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" name="category" class="form-control form-control-solid">
                        </div>
                        <div class="form-group mb-5">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" class="form-select form-select-solid">
                                <option value="product">Product</option>
                                <option value="service">Service</option>
                                <option value="equipment">Equipment</option>
                                <option value="infrastructure">Infrastructure</option>
                                <option value="vehicle">Vehicle</option>
                                <option value="tool">Tool</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group mb-5">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" name="model" class="form-control form-control-solid">
                        </div>
                        <div class="form-group mb-5">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" name="brand" class="form-control form-control-solid">
                        </div>
                        <div class="form-group mb-5">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" class="form-control form-control-solid">
                        </div>
                        <div class="form-group mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="active">Active</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="retired">Retired</option>
                                <option value="damaged">Damaged</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection