<div class="card-body">
    <form id="edit-personal-form" action="{{ url('mngr-secure-9374/hr/staff/' . $staff->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="personal">
        <div class="row mb-7">
            <div class="col-lg-3">
                <div class="d-flex flex-column align-items-center text-center mb-7">
                    <div class="mb-7">
                        @if($staff && $staff->photo_path)
                            <img src="{{ asset('storage/' . $staff->photo_path) }}" alt="{{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'N/A' }}" class="w-125px h-125px rounded-circle" />
                        @else
                            <div class="symbol symbol-125px symbol-circle">
                                <div class="symbol-label fs-1 bg-light-primary text-primary">{{ $staff && $staff->first_name ? substr($staff->first_name, 0, 1) : '?' }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <div class="fw-bolder fs-3">{{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'N/A' }}</div>
                        <div class="text-muted fw-bold mt-1">{{ $staff->rank ?? 'N/A' }}</div>
                        <div class="text-muted fw-bold mt-1">{{ $staff->department ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row mb-5">
                    <div class="col-md-4 text-center mb-5">
                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('assets/media/avatars/blank.png') }}')">
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $staff->photo_path ? asset('storage/' . $staff->photo_path) : asset('assets/media/avatars/blank.png') }}')"></div>
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
                        <label class="fs-6 fw-semibold form-label mb-2 required">Staff ID</label>
                        <input type="text" name="staff_id" class="form-control form-control-solid @error('staff_id') is-invalid @enderror" value="{{ old('staff_id', $staff->staff_id) }}" required />
                        @error('staff_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">First Name</label>
                        <input type="text" name="first_name" class="form-control form-control-solid @error('first_name') is-invalid @enderror" value="{{ old('first_name', $staff->first_name) }}" required />
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control form-control-solid @error('middle_name') is-invalid @enderror" value="{{ old('middle_name', $staff->middle_name) }}" />
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">Surname</label>
                        <input type="text" name="surname" class="form-control form-control-solid @error('surname') is-invalid @enderror" value="{{ old('surname', $staff->surname) }}" required />
                        @error('surname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">Email</label>
                        <input type="email" name="email" class="form-control form-control-solid @error('email') is-invalid @enderror" value="{{ old('email', $staff->email) }}" required />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">Mobile No</label>
                        <input type="text" name="mobile_no" class="form-control form-control-solid @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no', $staff->mobile_no) }}" required />
                        @error('mobile_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control form-control-solid @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $staff->date_of_birth ? $staff->date_of_birth->format('Y-m-d') : '') }}" required />
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2 required">Gender</label>
                        <select name="gender" class="form-control form-control-solid @error('gender') is-invalid @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $staff->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $staff->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $staff->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Nationality</label>
                        <input type="text" name="nationality" class="form-control form-control-solid @error('nationality') is-invalid @enderror" value="{{ old('nationality', $staff->nationality) }}" />
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">State of Origin</label>
                        <input type="text" name="state_of_origin" class="form-control form-control-solid @error('state_of_origin') is-invalid @enderror" value="{{ old('state_of_origin', $staff->state_of_origin) }}" />
                        @error('state_of_origin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">NIN</label>
                        <input type="text" name="nin" class="form-control form-control-solid @error('nin') is-invalid @enderror" value="{{ old('nin', $staff->nin) }}" />
                        @error('nin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Address</label>
                        <textarea name="address" class="form-control form-control-solid @error('address') is-invalid @enderror" rows="3">{{ old('address', $staff->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('staff.hr.staff.show', $staff->id) }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Personal Information</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission via AJAX
    const form = document.getElementById('edit-personal-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'An error occurred');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Show success message
                    alert(data.message);
                    // Optionally reload the section
                    // loadSection('personal');
                } else {
                    alert('Error: ' + (data.message || 'An error occurred'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
        });
    }
    
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
});
</script>