@extends('layouts.staff')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Reservoir</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $reservoir['label'] ?? '' }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control form-control-solid" rows="3" readonly>{{ $reservoir['description'] ?? '' }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $reservoir['ref'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Price</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $reservoir['price'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $reservoir['location'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $reservoir['status'] ?? '' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="text" class="form-control form-control-solid" value="{{ ($reservoir['purchase_date'] ?? null) ? date('Y-m-d', strtotime($reservoir['purchase_date'])) : '' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warehouse</label>
                        <input type="text" class="form-control form-control-solid" value="{{ $reservoir['warehouse_label'] ?? '' }}" readonly>
                    </div>
                </div>

                <a href="{{ route('staff.reservoirs.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection
