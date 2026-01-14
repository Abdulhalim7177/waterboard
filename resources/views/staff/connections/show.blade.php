@extends('layouts.staff')

@section('title', 'View Connection')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3>Connection Details</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('staff.connections.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>Back to Connections
                        </a>
                        <a href="{{ route('staff.connection-fees.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-switch fs-2"></i>Manage Fees
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">ID</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->id }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Customer</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->customer->first_name }} {{ $connection->customer->surname }}</span>
                                <div class="text-muted fs-7">{{ $connection->customer->billing_id }}</div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Email</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->customer->email }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Phone</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->customer->phone_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Connection Type</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->connectionType->name }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Connection Size</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->connectionSize ? $connection->connectionSize->name : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Status</label>
                            <div class="col-lg-8 fv-row">
                                <span class="badge badge-{{ $connection->status === 'approved' ? 'success' : ($connection->status === 'rejected' ? 'danger' : 'warning') }} fw-bold">
                                    {{ ucfirst($connection->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Installation Date</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->installation_date ? $connection->installation_date->format('d M Y') : 'Not installed yet' }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Created</label>
                            <div class="col-lg-8 fv-row">
                                <span class="fw-bold fs-6 text-gray-800">{{ $connection->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator my-10"></div>

                <div class="mb-10">
                    <h5 class="mb-5">Notes</h5>
                    <p class="text-gray-800 fs-6">{{ $connection->notes ?: 'No notes provided.' }}</p>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('staff.connections.index') }}" class="btn btn-light me-3">Back to Connections</a>
                    <a href="{{ route('staff.connections.edit', $connection) }}" class="btn btn-primary me-3">Edit</a>

                    @if($connection->status === 'pending')
                        <form action="{{ route('staff.connections.approve', $connection) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success me-3">Approve</button>
                        </form>
                        <form action="{{ route('staff.connections.reject', $connection) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection