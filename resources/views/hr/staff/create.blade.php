@extends('layouts.staff')

@section('content')
    <div id="kt_content_container" class="container-xxl">
        <!-- Alerts -->
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

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Add New Staff</h3>
                </div>
            </div>
            <div class="card-body p-9">
                <!--begin::Form-->
                <form action="{{ route('staff.hr.staff.store') }}" method="POST" enctype="multipart/form-data" id="staff_form">
                    @csrf
                    
                    <!--begin::Tabs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personal_info">Personal Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#employment_info">Employment Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#location_info">Location Info</a>
                        </li>
                    </ul>
                    <!--end::Tabs-->
                    
                    <!--begin::Tab content-->
                    <div class="tab-content" id="staffTabContent">
                        <!--begin::Tab pane - Personal Info-->
                        <div class="tab-pane fade show active" id="personal_info" role="tabpanel">
                            <div class="row mb-7">
                                <div class="col-lg-3">
                                    <div class="d-flex flex-column align-items-center text-center mb-7">
                                        <div class="mb-7">
                                            <div class="symbol symbol-125px symbol-circle">
                                                <div class="symbol-label fs-1 bg-light-primary text-primary">A</div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="fw-bolder fs-3">New Staff</div>
                                            <div class="text-muted fw-bold mt-1">Personal Information</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="row mb-5">
                                        <div class="col-md-4 text-center mb-5">
                                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('assets/media/avatars/blank.png') }}')">
                                                <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('assets/media/avatars/blank.png') }}')"></div>
                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change photo">
                                                    <i class="ki-duotone ki-pencil fs-7">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="file" name="photo" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="photo_remove" />
                                                </label>
                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel photo">
                                                    <i class="ki-duotone ki-cross fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove photo">
                                                    <i class="ki-duotone ki-trash fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </span>
                                            </div>
                                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-5">
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Staff ID</label>
                                            <input type="text" name="staff_id" class="form-control form-control-solid" value="{{ old('staff_id') }}" required />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">First Name</label>
                                            <input type="text" name="first_name" class="form-control form-control-solid" value="{{ old('first_name') }}" required />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Middle Name</label>
                                            <input type="text" name="middle_name" class="form-control form-control-solid" value="{{ old('middle_name') }}" />
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-5">
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Surname</label>
                                            <input type="text" name="surname" class="form-control form-control-solid" value="{{ old('surname') }}" required />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Email</label>
                                            <input type="email" name="email" class="form-control form-control-solid" value="{{ old('email') }}" required />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Mobile No</label>
                                            <input type="text" name="mobile_no" class="form-control form-control-solid" value="{{ old('mobile_no') }}" required />
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-5">
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Date of Birth</label>
                                            <input type="date" name="date_of_birth" class="form-control form-control-solid" value="{{ old('date_of_birth') }}" required />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Gender</label>
                                            <select name="gender" class="form-control form-control-solid" required>
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Nationality</label>
                                            <input type="text" name="nationality" class="form-control form-control-solid" value="{{ old('nationality') }}" />
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-5">
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">State of Origin</label>
                                            <input type="text" name="state_of_origin" class="form-control form-control-solid" value="{{ old('state_of_origin') }}" />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">NIN</label>
                                            <input type="text" name="nin" class="form-control form-control-solid" value="{{ old('nin') }}" />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Address</label>
                                            <textarea name="address" class="form-control form-control-solid" rows="3">{{ old('address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light">Cancel</a>
                                <button type="button" class="btn btn-primary" onclick="switchTab('employment_info')">Next</button>
                            </div>
                        </div>
                        <!--end::Tab pane - Personal Info-->
                        
                        <!--begin::Tab pane - Employment Info-->
                        <div class="tab-pane fade" id="employment_info" role="tabpanel">
                            <div class="row mb-7">
                                <div class="col-lg-3">
                                    <div class="d-flex flex-column align-items-center text-center mb-7">
                                        <div class="mb-7">
                                            <div class="symbol symbol-125px symbol-circle">
                                                <div class="symbol-label fs-1 bg-light-primary text-primary">E</div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="fw-bolder fs-3">Employment Info</div>
                                            <div class="text-muted fw-bold mt-1">Work-related details</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="row mb-5">
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Date of First Appointment</label>
                                            <input type="date" name="date_of_first_appointment" class="form-control form-control-solid" value="{{ old('date_of_first_appointment') }}" required />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Rank</label>
                                            <input type="text" name="rank" class="form-control form-control-solid" value="{{ old('rank') }}" />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Staff No</label>
                                            <input type="text" name="staff_no" class="form-control form-control-solid" value="{{ old('staff_no') }}" />
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-5">
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Department</label>
                                            <input type="text" name="department" class="form-control form-control-solid" value="{{ old('department') }}" />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Status</label>
                                            <select name="employment_status" class="form-control form-control-solid" required>
                                                <option value="">Select Status</option>
                                                <option value="active" {{ old('employment_status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('employment_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="on_leave" {{ old('employment_status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                                <option value="suspended" {{ old('employment_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                <option value="terminated" {{ old('employment_status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Years of Service</label>
                                            <input type="number" name="years_of_service" class="form-control form-control-solid" value="{{ old('years_of_service') }}" />
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-5">
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Expected Next Promotion</label>
                                            <input type="date" name="expected_next_promotion" class="form-control form-control-solid" value="{{ old('expected_next_promotion') }}" />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Expected Retirement Date</label>
                                            <input type="date" name="expected_retirement_date" class="form-control form-control-solid" value="{{ old('expected_retirement_date') }}" />
                                        </div>
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Appointment Type</label>
                                            <input type="text" name="appointment_type" class="form-control form-control-solid" value="{{ old('appointment_type') }}" />
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-5">
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Highest Qualifications</label>
                                            <input type="text" name="highest_qualifications" class="form-control form-control-solid" value="{{ old('highest_qualifications') }}" />
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Grade Level Limit</label>
                                            <input type="text" name="grade_level_limit" class="form-control form-control-solid" value="{{ old('grade_level_limit') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-light" onclick="switchTab('personal_info')">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="switchTab('location_info')">Next</button>
                            </div>
                        </div>
                        <!--end::Tab pane - Employment Info-->
                        
                        <!--begin::Tab pane - Location Info-->
                        <div class="tab-pane fade" id="location_info" role="tabpanel">
                            <div class="row mb-7">
                                <div class="col-lg-3">
                                    <div class="d-flex flex-column align-items-center text-center mb-7">
                                        <div class="mb-7">
                                            <div class="symbol symbol-125px symbol-circle">
                                                <div class="symbol-label fs-1 bg-light-primary text-primary">L</div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="fw-bolder fs-3">Location Info</div>
                                            <div class="text-muted fw-bold mt-1">Geographic details</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="row mb-5">
                                        <div class="col-md-3 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Zone</label>
                                            <select name="zone_id" class="form-control form-control-solid" data-control="select2">
                                                <option value="">Select Zone</option>
                                                @foreach ($zones as $zone)
                                                    <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">District</label>
                                            <select name="district_id" class="form-control form-control-solid" data-control="select2">
                                                <option value="">Select District</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">LGA</label>
                                            <select name="lga_id" class="form-control form-control-solid" data-control="select2">
                                                <option value="">Select LGA</option>
                                                @foreach ($lgas as $lga)
                                                    <option value="{{ $lga->id }}" {{ old('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Ward</label>
                                            <select name="ward_id" class="form-control form-control-solid" data-control="select2">
                                                <option value="">Select Ward</option>
                                                @foreach ($wards as $ward)
                                                    <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12 fv-row">
                                            <label class="fs-6 fw-semibold form-label mb-2">Area</label>
                                            <select name="area_id" class="form-control form-control-solid" data-control="select2">
                                                <option value="">Select Area</option>
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-light" onclick="switchTab('employment_info')">Previous</button>
                                <div>
                                    <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Add Staff</button>
                                </div>
                            </div>
                        </div>
                        <!--end::Tab pane - Location Info-->
                    </div>
                    <!--end::Tab content-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Initialize image input
        $(document).ready(function() {
            // Initialize image input
            $('[data-kt-image-input="true"]').each(function() {
                var imageInput = $(this);
                var preview = imageInput.find('.image-input-wrapper');
                var input = imageInput.find('input[type="file"]');
                var removeBtn = imageInput.find('[data-kt-image-input-action="remove"]');
                
                input.on('change', function() {
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            preview.css('background-image', 'url(' + e.target.result + ')');
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
                
                removeBtn.on('click', function() {
                    preview.css('background-image', 'url({{ asset('assets/media/avatars/blank.png') }})');
                    input.val('');
                });
            });
            
            // Initialize Select2
            $('select[data-control="select2"]').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
        
        // Function to switch tabs
        function switchTab(tabId) {
            // Hide all tab panes
            $('.tab-pane').removeClass('show active');
            
            // Show the target tab pane
            $('#' + tabId).addClass('show active');
            
            // Update nav links
            $('.nav-link').removeClass('active');
            $('.nav-link[href="#' + tabId + '"]').addClass('active');
        }
    </script>
@endsection