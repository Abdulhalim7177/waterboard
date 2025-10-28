
@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Asset Details</h3>
            <div class="card-toolbar">
                <a href="{{ route('staff.assets.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">ID</div>
                        <div class="text-gray-600">{{ $asset['id'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Name</div>
                        <div class="text-gray-600">{{ $asset['label'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Description</div>
                        <div class="text-gray-600">{{ $asset['description'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Serial Number</div>
                        <div class="text-gray-600">{{ $asset['ref'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Purchase Price</div>
                        <div class="text-gray-600">{{ $asset['price'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Purchase Date</div>
                        <div class="text-gray-600">{{ isset($asset['date_purchase']) ? date('Y-m-d', $asset['date_purchase']) : 'N/A' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Warehouse</div>
                        <div class="text-gray-600">{{ $asset['warehouse_id'] }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Category</div>
                        <div class="text-gray-600">{{ $asset['array_options']['options_category'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Type</div>
                        <div class="text-gray-600">{{ $asset['array_options']['options_type'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Model</div>
                        <div class="text-gray-600">{{ $asset['array_options']['options_model'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Brand</div>
                        <div class="text-gray-600">{{ $asset['array_options']['options_brand'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Location</div>
                        <div class="text-gray-600">{{ $asset['array_options']['options_location'] ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex flex-stack mb-5">
                        <div class="fw-bold">Status</div>
                        <div class="text-gray-600">
                            <div class="badge badge-light-{{ ($asset['array_options']['options_status'] ?? '') == 'active' ? 'success' : 'danger' }}">{{ $asset['array_options']['options_status'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
