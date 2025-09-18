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
                    <h3 class="fw-bolder m-0">Edit Staff</h3>
                </div>
            </div>
            <div class="card-body p-9">
                <!--begin::Form-->
                <form action="{{ url('mngr-secure-9374/hr/staff/' . $staff->id) }}" method="POST" enctype="multipart/form-data" id="staff_form">
                    @csrf
                    @method('PUT')
                </form>
                
                <!-- Section Selection -->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label for="part" class="form-label required">Select Section to Edit</label>
                        <select class="form-select form-select-solid" id="part" name="part" required>
                            <option value="">Select Section</option>
                            <option value="personal">Personal Information</option>
                            <option value="employment">Employment Information</option>
                            <option value="location">Location Information</option>
                        </select>
                    </div>
                </div>
                
                <!-- Form Container -->
                <div id="section-form" class="mt-6"></div>
                <!--end::Form-->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const partSelect = document.getElementById('part');
            const sectionForm = document.getElementById('section-form');
            const staffId = "{{ $staff->id }}";
            const csrfToken = "{{ csrf_token() }}";

            // Function to load section form
            function loadSection(part) {
                fetch("{{ url('mngr-secure-9374/hr/staff/edit') }}/" + staffId + "/section", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ part: part }),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        // Show error in a simple alert for now
                        alert('Error: ' + data.error);
                        sectionForm.innerHTML = '';
                    } else {
                        sectionForm.innerHTML = data.html;
                        // Reinitialize any JavaScript components
                        if (typeof initializeComponents === 'function') {
                            initializeComponents();
                        }
                    }
                })
                .catch(error => {
                    alert('Failed to load section: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Load section when dropdown changes
            partSelect.addEventListener('change', function() {
                const part = this.value;
                if (part) {
                    loadSection(part);
                } else {
                    sectionForm.innerHTML = '';
                }
            });
        });
    </script>
@endsection