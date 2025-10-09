@extends('layouts.staff')

@section('page_title')
    Role Assignment
@endsection

@section('page_description')
    Assign roles to staff members
@endsection

@section('content')
    <!--begin::Container-->
    <div class="container-xxl">
        <!--begin::Staff Management Navigation-->
        @include('staff.partials.navigation')
        <!--end::Staff Management Navigation-->
        
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
        <!--end::Alerts-->

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="fw-bold text-dark">Role Assignment</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('staff.staff.roles') }}" class="btn btn-light-primary">
                        <i class="ki-duotone ki-left fs-2"></i>
                        Back to Staff Roles
                    </a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Staff Info-->
                <div class="d-flex align-items-center mb-10">
                    <div class="symbol symbol-100px symbol-circle me-5">
                        @if($staff->photo_path)
                            <img src="{{ Storage::url($staff->photo_path) }}" alt="{{ $staff->first_name }}">
                        @else
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-profile-user fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-dark fw-bold fs-3">{{ $staff->first_name }} {{ $staff->middle_name }} {{ $staff->surname }}</span>
                        <span class="text-muted fs-6">{{ $staff->email }}</span>
                        <span class="text-muted fs-7">Staff ID: {{ $staff->staff_id }}</span>
                    </div>
                </div>
                <!--end::Staff Info-->

                <!--begin::Form-->
                <form action="{{ route('staff.staff.assign-roles', $staff->id) }}" method="POST" id="roleAssignmentForm">
                    @csrf
                    @method('PUT')
                    <div class="row mb-8">
                        <div class="col-md-8">
                            <div class="mb-5">
                                <label class="fs-5 fw-semibold mb-2">Select Roles</label>
                                <div class="d-flex flex-column">
                                    <select name="roles[]" id="roles" class="form-select form-select-solid" data-control="select2" multiple="multiple" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $staff->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-muted fs-7 mt-2">Select one or more roles to assign to this staff member.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--begin::Separator-->
                    <div class="separator mb-6"></div>
                    <!--end::Separator-->

                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('staff.staff.roles') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="kt_role_assignment_submit">
                            <span class="indicator-label">Assign Roles</span>
                            <span class="indicator-progress">Please wait... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#roles').select2({
                placeholder: "Select roles",
                allowClear: true
            });

            // Handle form submission
            $('#roleAssignmentForm').submit(function() {
                // Show loading indicator
                $('#kt_role_assignment_submit').attr('data-kt-indicator', 'on');
                $('#kt_role_assignment_submit').prop('disabled', true);
            });
        });
    </script>
@endsection