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
                    <h2 class="mb-0">Asset Management System</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('staff.assets.index') }}" class="btn btn-light-primary">Back to Assets</a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Alert-->
                <div class="alert alert-info mb-10" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-information fs-2x text-info me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div>
                            All asset data is managed through the external asset management system. This page displays all available assets from the integrated system.
                        </div>
                    </div>
                </div>
                <!--end::Alert-->
                
                @if(isset($assets) && !empty($assets))
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_import_assets_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Asset Name</th>
                                    <th class="min-w-125px">Reference</th>
                                    <th class="min-w-125px">Description</th>
                                    <th class="min-w-125px">Type</th>
                                    <th class="min-w-125px">Price</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach($assets as $asset)
                                <tr>
                                    <td>
                                        <a href="{{ route('staff.assets.show', $asset['id']) }}" class="text-gray-800 text-hover-primary mb-1">
                                            {{ $asset['label'] ?? $asset['ref'] ?? 'Unknown Asset' }}
                                        </a>
                                    </td>
                                    <td>{{ $asset['ref'] ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($asset['description'] ?? '', 50) }}</td>
                                    <td>
                                        @if(($asset['type'] ?? 0) == 0)
                                            <span class="badge badge-light-primary">Product</span>
                                        @else
                                            <span class="badge badge-light-info">Service</span>
                                        @endif
                                    </td>
                                    <td>{{ $asset['price'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                    
                @else
                    <!--begin::Empty State-->
                    <div class="text-center py-10">
                        <i class="ki-duotone ki-kanban fs-3x text-muted mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        <div class="text-muted fs-6">No assets found in the external system</div>
                    </div>
                    <!--end::Empty State-->
                @endif
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection