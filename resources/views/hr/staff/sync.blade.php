@extends('layouts.staff')

@section('content')
    <!--begin::Container-->
    <div class="container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="fw-bold text-dark">Synchronization Report</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light-primary me-3">Back to Staff</a>
                    <a href="{{ route('staff.hr.staff.sync', ['full_refresh' => 1]) }}" class="btn btn-danger" id="fullSyncBtn">
                        <i class="ki-duotone ki-arrows-circle fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Complete Refresh
                    </a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Alerts-->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="ki-duotone ki-check-circle fs-2x me-3"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="ki-duotone ki-cross-circle fs-2x me-3"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="alert alert-info">
                    <div class="d-flex flex-stack flex-wrap">
                        <div class="me-5">
                            <h4 class="text-dark fw-bold mb-2">Synchronization Summary</h4>
                            <div class="fs-6 text-gray-700">
                                Sync {{ isset($fullRefresh) && $fullRefresh ? 'with complete refresh' : 'with incremental update' }}
                            </div>
                        </div>
                        
                        <div class="d-flex my-2">
                            <div class="border border-gray-300 border-dashed rounded min-w-100px py-3 px-4 me-6">
                                <div class="fs-5 text-gray-700 fw-bold">{{ count($affectedStaff) }}</div>
                                <div class="fs-7 text-gray-400">Total Records</div>
                            </div>
                            
                            <div class="border border-gray-300 border-dashed rounded min-w-100px py-3 px-4">
                                <div class="fs-5 text-gray-700 fw-bold">{{ $affectedStaff->where('wasRecentlyCreated', true)->count() }}</div>
                                <div class="fs-7 text-gray-400">New Records</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Display command output if available -->
                @if(isset($output) && !empty($output))
                    <div class="mb-5">
                        <h4 class="mb-3">Sync Process Output:</h4>
                        <pre class="bg-light p-3 rounded text-muted">{{ $output }}</pre>
                    </div>
                @endif
                <!--end::Alerts-->
                
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_staff_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Staff ID</th>
                                <th class="min-w-125px">Name</th>
                                <th class="min-w-125px">Email</th>
                                <th class="min-w-125px">Department</th>
                                <th class="min-w-125px">Rank</th>
                                <th class="min-w-125px">Employment Status</th>
                                <th class="min-w-125px">Sync Status</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse ($affectedStaff as $staff)
                                <tr>
                                    <td>{{ $staff->staff_id }}</td>
                                    <td>
                                        <a href="{{ route('staff.hr.staff.show', $staff) }}" class="text-gray-800 text-hover-primary mb-1">
                                            {{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'N/A' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="text-gray-600 text-hover-primary mb-1">{{ $staff->email }}</a>
                                    </td>
                                    <td>{{ $staff->department ?? 'N/A' }}</td>
                                    <td>{{ $staff->rank ?? 'N/A' }}</td>
                                    <td>
                                        <div class="badge badge-light-{{ $staff->employment_status == 'active' ? 'success' : ($staff->employment_status == 'on_leave' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $staff->employment_status)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $staff->wasRecentlyCreated ? 'light-primary' : 'light-warning' }}">
                                            {{ $staff->wasRecentlyCreated ? 'New' : 'Updated' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center py-10">
                                            <i class="ki-duotone ki-calendar fs-2tx text-gray-400 mb-3"></i>
                                            <h4 class="text-gray-700 mb-1">No staff were affected</h4>
                                            <p class="text-gray-500 fs-6">No changes were made during synchronization</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
                
                <!--begin::Additional Actions-->
                <div class="d-flex justify-content-between mt-10">
                    <div>
                        <button type="button" class="btn btn-light-primary" id="refreshSyncBtn" onclick="location.reload()">
                            <i class="ki-duotone ki-refresh fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Refresh Report
                        </button>
                    </div>
                    
                    <div>
                        <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light-primary me-3">Back to Staff</a>
                        <a href="{{ route('staff.hr.staff.sync', ['full_refresh' => 1]) }}" class="btn btn-danger">
                            <i class="ki-duotone ki-arrows-circle fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Complete Refresh
                        </a>
                    </div>
                </div>
                <!--end::Additional Actions-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
    
    <!--begin::Modal for confirmation of full refresh-->
    <div class="modal fade" id="fullSyncModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Confirm Complete Refresh</h3>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10">
                    <div class="text-center mb-5">
                        <i class="ki-duotone ki-information-5 fs-5x text-warning mb-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <h4 class="text-dark mb-3">Warning: Complete Data Refresh</h4>
                        <p class="text-gray-600">
                            This action will completely replace all staff data with information from the HRM system.
                            Local changes that are not synced with the HRM system will be lost.
                        </p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <a href="{{ route('staff.hr.staff.sync', ['full_refresh' => 1]) }}" class="btn btn-danger" id="confirmFullSyncBtn">
                            Confirm Refresh
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add confirmation for full sync
            const fullSyncBtn = document.getElementById('fullSyncBtn');
            if (fullSyncBtn) {
                fullSyncBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Show confirmation modal
                    const modal = new bootstrap.Modal(document.getElementById('fullSyncModal'));
                    modal.show();
                });
            }
            
            // Confirm full sync button
            const confirmFullSyncBtn = document.getElementById('confirmFullSyncBtn');
            if (confirmFullSyncBtn) {
                confirmFullSyncBtn.addEventListener('click', function() {
                    window.location.href = this.href;
                });
            }
        });
    </script>
@endsection
