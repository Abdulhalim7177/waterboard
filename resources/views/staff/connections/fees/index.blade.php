@extends('layouts.staff')

@section('title', 'Connection Fees Management')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3>Connection Fees Management</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">
                        <a href="{{ route('staff.connections.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-switch fs-2"></i>Manage Connections
                        </a>
                        <a href="{{ route('staff.connection-fees.create') }}" class="btn btn-primary">
                            <i class="ki-duotone ki-plus fs-2"></i>Add New Fee
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">ID</th>
                                <th class="min-w-125px">Connection Type</th>
                                <th class="min-w-125px">Connection Size</th>
                                <th class="min-w-125px">Fee Amount</th>
                                <th class="min-w-125px">Status</th>
                                <th class="min-w-125px">Created At</th>
                                <th class="text-end min-w-70px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse($fees as $fee)
                            <tr>
                                <td>{{ $fee->id }}</td>
                                <td>{{ $fee->connectionType->name }}</td>
                                <td>{{ $fee->connectionSize ? $fee->connectionSize->name : 'N/A' }}</td>
                                <td>₦{{ number_format($fee->fee_amount, 2) }}</td>
                                <td>
                                    <div class="badge badge-{{ $fee->is_active ? 'success' : 'danger' }} fw-bold">
                                        {{ $fee->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </td>
                                <td>{{ $fee->created_at->format('d M Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('staff.connection-fees.edit', $fee) }}" class="btn btn-sm btn-light btn-active-light-primary">Edit</a>
                                    <form action="{{ route('staff.connection-fees.destroy', $fee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light btn-active-light-danger" onclick="return confirm('Are you sure you want to delete this fee?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No connection fees found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-stack flex-wrap justify-content-between align-items-center">
                    <div class="me-5 fw-semibold">
                        <div class="d-flex align-items-center py-1">
                            <div class="text-gray-600 fs-6 me-2">Displaying</div>
                            <div class="fw-bold text-gray-800 me-2">{{ $fees->firstItem() ?? 0 }}</div>
                            <div class="text-gray-600 fs-6 me-2">to</div>
                            <div class="fw-bold text-gray-800 me-2">{{ $fees->lastItem() ?? 0 }}</div>
                            <div class="text-gray-600 fs-6">of</div>
                            <div class="fw-bold text-gray-800">{{ $fees->total() }}</div>
                        </div>
                    </div>
                    <div class="d-flex">
                        {{ $fees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection