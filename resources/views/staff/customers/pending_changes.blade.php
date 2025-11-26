@extends('layouts.staff')

@section('content')
<div class="container-xxl">
    <!--begin::Card-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2 class="fw-bold text-dark">Pending Customer Updates</h2>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.customers.index') }}" class="btn btn-light-primary">Back to Customers</a>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Alerts-->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!--end::Alerts-->

            @if ($pendingUpdates->isEmpty())
                <div class="alert alert-info" role="alert">
                    No pending updates found.
                </div>
            @else
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_pending_updates_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_pending_updates_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-150px">Customer</th>
                                <th class="min-w-120px">Billing ID</th>
                                <th class="min-w-100px">Field</th>
                                <th class="min-w-150px">Old Value</th>
                                <th class="min-w-150px">New Value</th>
                                <th class="min-w-120px">Updated By</th>
                                <th class="min-w-150px">Submitted At</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach ($pendingUpdates as $update)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="{{ $update->id }}" />
                                        </div>
                                    </td>
                                    <td>{{ $update->customer->first_name }} {{ $update->customer->surname }}</td>
                                    <td>{{ $update->customer->billing_id ?? 'Pending' }}</td>
                                    <td>{{ $update->field }}</td>
                                    <td>{{ $update->old_value ?? 'N/A' }}</td>
                                    <td>{{ $update->new_value }}</td>
                                    <td>{{ $update->staff ? $update->staff->name : 'Unknown' }}</td>
                                    <td>{{ $update->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#approveModal{{ $update->id }}">Approve</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $update->id }}">Reject</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $pendingUpdates->links('pagination::bootstrap-5') }}
                </div>
                <!--end::Table-->
            @endif
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!--begin::Modals for Reject Confirmation-->
    @foreach ($pendingUpdates as $update)
    <div class="modal fade" id="rejectModal{{ $update->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $update->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel{{ $update->id }}">Confirm Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this update?</p>
                    <p><strong>Customer:</strong> {{ $update->customer->first_name }} {{ $update->customer->surname }}</p>
                    <p><strong>Field:</strong> {{ $update->field }}</p>
                    <p><strong>Old Value:</strong> {{ $update->old_value ?? 'N/A' }}</p>
                    <p><strong>New Value:</strong> {{ $update->new_value }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="reject-update-{{ $update->id }}" action="{{ route('staff.customers.pending.reject', $update->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('POST') <!-- Changed from PUT to POST -->
                        <button type="submit" class="btn btn-danger">Reject Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!--end::Modals for Reject Confirmation-->

    <!--begin::Modals for Approve Confirmation-->
    @foreach ($pendingUpdates as $update)
    <div class="modal fade" id="approveModal{{ $update->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $update->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel{{ $update->id }}">Confirm Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this update?</p>
                    <p><strong>Customer:</strong> {{ $update->customer->first_name }} {{ $update->customer->surname }}</p>
                    <p><strong>Field:</strong> {{ $update->field }}</p>
                    <p><strong>Old Value:</strong> {{ $update->old_value ?? 'N/A' }}</p>
                    <p><strong>New Value:</strong> {{ $update->new_value }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="approve-update-{{ $update->id }}" action="{{ route('staff.customers.pending.approve', $update->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-success">Approve Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!--end::Modals for Approve Confirmation-->
</div>
@endsection
