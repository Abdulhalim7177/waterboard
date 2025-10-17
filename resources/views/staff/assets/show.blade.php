@extends('layouts.staff')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Asset</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $asset['label'] ?? '' }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control form-control-solid" rows="3" readonly>{{ $asset['description'] ?? '' }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['ref'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Price</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['price'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['category'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['type'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['model'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['brand'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['location'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['status'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="text" class="form-control form-control-solid" value="{{ ($asset['purchase_date'] ?? null) ? date('Y-m-d', strtotime($asset['purchase_date'])) : '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warehouse</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $asset['warehouse_label'] ?? '' }}" readonly>
                    </div>
                </div>

                <a href="{{ route('staff.assets.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection
