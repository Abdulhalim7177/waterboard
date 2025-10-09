@extends('layouts.staff')

@section('content')
    <!--begin::Container-->
    <div class="container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="mb-0">Asset Details</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('staff.assets.index') }}" class="btn btn-light-primary me-3">Back to Assets</a>
                    <a href="{{ route('staff.assets.edit', $asset['id']) }}" class="btn btn-primary">Edit Asset</a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Asset details header-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    <!--begin: Asset avatar-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <i class="ki-duotone ki-abstract-41 fs-2x text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <!--end::Asset avatar-->
                    <!--begin::Asset info-->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::Asset name-->
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                        {{ $asset['label'] ?? $asset['ref'] ?? 'Unknown Asset' }}
                                    </a>
                                </div>
                                
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                        <i class="ki-duotone ki-geolocation fs-4 text-gray-500 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        {{ $asset['location'] ?? 'N/A' }}
                                    </span>
                                    <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                        <i class="ki-duotone ki-abstract-42 fs-4 text-gray-500 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        {{ $asset['ref'] ?? 'N/A' }}
                                    </span>
                                    <span class="d-flex align-items-center text-gray-400 mb-2">
                                        @if(($asset['type'] ?? 0) == 0)
                                            <span class="badge badge-light-primary">Product</span>
                                        @else
                                            <span class="badge badge-light-info">Service</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <!--end::Asset name-->
                            <!--begin::Asset status-->
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge badge-light-{{ ($asset['status'] ?? 1) == 1 ? 'success' : 'secondary' }} fs-6 px-4 py-3">
                                    {{ ($asset['status'] ?? 1) == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <!--end::Asset status-->
                        </div>
                    </div>
                    <!--end::Asset info-->
                </div>
                <!--end::Asset details header-->
                
                <!--begin::Asset details tab content-->
                <div class="tab-content" id="myTabContent">
                    <!--begin::Asset overview-->
                    <div class="tab-pane fade show active" id="kt_asset_overview_tab" role="tabpanel">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Col-->
                            <div class="col-lg-6">
                                <table class="table table-striped">
                                    <tr>
                                        <td><strong>Asset ID:</strong></td>
                                        <td>{{ $asset['id'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Reference:</strong></td>
                                        <td>{{ $asset['ref'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Price:</strong></td>
                                        <td>{{ $asset['price'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax Rate:</strong></td>
                                        <td>{{ $asset['tva_tx'] ?? 'N/A' }}%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category:</strong></td>
                                        <td>{{ $asset['category'] ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-lg-6">
                                <table class="table table-striped">
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            {{ ($asset['status'] ?? 1) == 1 ? 'Active' : 'Inactive' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ isset($asset['datec']) ? \Carbon\Carbon::parse($asset['datec'])->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Modified:</strong></td>
                                        <td>{{ isset($asset['tms']) ? \Carbon\Carbon::parse($asset['tms'])->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Weight:</strong></td>
                                        <td>{{ $asset['weight'] ?? 'N/A' }} {{ $asset['weight_units'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dimensions:</strong></td>
                                        <td>
                                            {{ $asset['length'] ?? 'N/A' }} × {{ $asset['width'] ?? 'N/A' }} × {{ $asset['height'] ?? 'N/A' }} {{ $asset['length_units'] ?? '' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        
                        @if($asset['description'] ?? '')
                        <div class="card mb-7">
                            <div class="card-body">
                                <h4 class="card-title">Description</h4>
                                <p class="card-text">{{ $asset['description'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <!--begin::Asset Management Details Card-->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Asset Management System Details</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Asset ID in Dolibarr:</strong> {{ $asset['id'] ?? 'N/A' }}</p>
                                <button class="btn btn-light-primary" type="button" data-bs-toggle="collapse" data-bs-target="#dolibarrDetails" aria-expanded="false">
                                    View Full Asset Details
                                </button>
                                <div class="collapse mt-3" id="dolibarrDetails">
                                    <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">{{ json_encode($asset, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                        <!--end::Asset Management Details Card-->
                    </div>
                    <!--end::Asset overview-->
                </div>
                <!--end::Asset details tab content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection