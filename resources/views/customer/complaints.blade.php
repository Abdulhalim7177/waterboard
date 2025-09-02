@extends('layouts.customer')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <!--begin::Card-->
        <div class="card card-flush shadow-md h-xl-100">
            <!--begin::Card header-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">My Complaints</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Submit and track your complaints</span>
                </h3>
                <!--end::Title-->
                <!--begin::Actions-->
                <div class="card-toolbar">
                    <div class="d-flex flex-stack flex-wrap gap-4">
                        <!--begin::Type Filter-->
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Type</div>
                            <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                <option></option>
                                <option value="Show All" selected>Show All</option>
                                <option value="billing">Billing Issue</option>
                                <option value="supply">Water Supply Issue</option>
                                <option value="maintenance">Maintenance Issue</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <!--end::Type Filter-->
                        <!--begin::Status Filter-->
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Status</div>
                            <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-5="filter_status">
                                <option></option>
                                <option value="Show All" selected>Show All</option>
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                        <!--end::Status Filter-->
                        <!--begin::Action-->
                        <a href="#" class="btn btn-primary fs-6 px-8 py-4" data-bs-toggle="modal" data-bs-target="#kt_modal_new_complaint">Submit New Complaint</a>
                        <!--end::Action-->
                    </div>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Success Message-->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <!--end::Success Message-->

                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Type</th>
                            <th class="min-w-200px">Description</th>
                            <th class="text-end pe-3 min-w-100px">Status</th>
                            <th class="text-end pe-0 min-w-150px">Resolution Notes</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($complaints as $complaint)
                            <tr>
                                <td>{{ ucfirst($complaint->type) }}</td>
                                <td>{{ Str::limit($complaint->description, 50) }}</td>
                                <td class="text-end">
                                    <span class="badge py-3 px-4 fs-7 
                                        {{ $complaint->status === 'resolved' ? 'badge-light-primary' : 
                                           ($complaint->status === 'open' ? 'badge-light-danger' : 'badge-light-warning') }}">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </td>
                                <td class="text-end">{{ $complaint->resolution_notes ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
            <!--begin::Card footer-->
            <div class="card-footer">
                <div class="mt-4">
                    {{ $complaints->links() }}
                </div>
            </div>
            <!--end::Card footer-->
        </div>
        <!--end::Card-->
    </div>

    <!--begin::Modal-->
    <div class="modal fade" id="kt_modal_new_complaint" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <h2 class="fw-bold">Submit New Complaint</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-10 my-2">
                    <form action="{{ route('customer.complaints.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Complaint Type</label>
                            <select name="type" id="type" class="form-select w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                                <option value="billing">Billing Issue</option>
                                <option value="supply">Water Supply Issue</option>
                                <option value="maintenance">Maintenance Issue</option>
                                <option value="other">Other</option>
                            </select>
                            @error('type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Complaint</button>
                        </div>
                    </form>
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal-->
@endsection