
@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Reservoir Details</h3>
            <div class="card-toolbar">
                <a href="{{ route('staff.reservoirs.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">ID</div>
                        <div class="text-gray-600">{{ $reservoir['id'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Name</div>
                        <div class="text-gray-600">{{ $reservoir['label'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Description</div>
                        <div class="text-gray-600">{{ $reservoir['description'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Serial Number</div>
                        <div class="text-gray-600">{{ $reservoir['ref'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Purchase Price</div>
                        <div class="text-gray-600">{{ $reservoir['price'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Purchase Date</div>
                        <div class="text-gray-600">{{ isset($reservoir['date_purchase']) ? date('Y-m-d', $reservoir['date_purchase']) : 'N/A' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Warehouse</div>
                        <div class="text-gray-600">{{ $reservoir['warehouse_id'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Tanks</div>
                        <div class="text-gray-600">{{ $reservoir['array_options']['options_tanks'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Capacity</div>
                        <div class="text-gray-600">{{ $reservoir['array_options']['options_capacity'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Location</div>
                        <div class="text-gray-600">{{ $reservoir['array_options']['options_location'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Status</div>
                        <div class="text-gray-600">
                            <div class="badge badge-light-{{ ($reservoir['array_options']['options_status'] ?? '') == 'active' ? 'success' : 'danger' }}">{{ $reservoir['array_options']['options_status'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
