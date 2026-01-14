@extends('layouts.staff')

@section('title', 'Connection Management')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3>Connection Management</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">
                        <a href="{{ route('staff.connection-fees.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-switch fs-2"></i>Manage Fees
                        </a>
                        <a href="{{ route('staff.connections.create') }}" class="btn btn-primary">
                            <i class="ki-duotone ki-plus fs-2"></i>Add New Connection
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
                                <th class="min-w-125px">Customer</th>
                                <th class="min-w-125px">Connection Type</th>
                                <th class="min-w-125px">Connection Size</th>
                                <th class="min-w-125px">Status</th>
                                <th class="min-w-125px">Installation Date</th>
                                <th class="min-w-125px">Created At</th>
                                <th class="text-end min-w-70px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse($connections as $connection)
                            <tr>
                                <td>{{ $connection->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ $connection->customer->first_name }} {{ $connection->customer->surname }}</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $connection->customer->billing_id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $connection->connectionType->name }}</td>
                                <td>{{ $connection->connectionSize ? $connection->connectionSize->name : 'N/A' }}</td>
                                <td>
                                    <div class="badge badge-{{ $connection->status === 'approved' ? 'success' : ($connection->status === 'rejected' ? 'danger' : 'warning') }} fw-bold">
                                        {{ ucfirst($connection->status) }}
                                    </div>
                                </td>
                                <td>{{ $connection->installation_date ? $connection->installation_date->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $connection->created_at->format('d M Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('staff.connections.show', $connection) }}" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                    <a href="{{ route('staff.connections.edit', $connection) }}" class="btn btn-sm btn-light btn-active-light-primary">Edit</a>

                                    @if($connection->status === 'pending')
                                        <form action="{{ route('staff.connections.approve', $connection) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('staff.connections.reject', $connection) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No connections found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-stack flex-wrap justify-content-between align-items-center">
                    <div class="me-5 fw-semibold">
                        <div class="d-flex align-items-center py-1">
                            <div class="text-gray-600 fs-6 me-2">Displaying</div>
                            <div class="fw-bold text-gray-800 me-2">{{ $connections->firstItem() ?? 0 }}</div>
                            <div class="text-gray-600 fs-6 me-2">to</div>
                            <div class="fw-bold text-gray-800 me-2">{{ $connections->lastItem() ?? 0 }}</div>
                            <div class="text-gray-600 fs-6">of</div>
                            <div class="fw-bold text-gray-800">{{ $connections->total() }}</div>
                        </div>
                    </div>
                    <div class="d-flex">
                        {{ $connections->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection