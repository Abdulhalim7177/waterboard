@extends('layouts.staff')

@section('content')
<style>
    /* Center the whole card on the page */
    .page-center {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    /* Limit card width and make it full width on small screens */
    .card-centered {
        width: 100%;
        max-width: 1100px;
    }

    /* Center alerts */
    .alert-container {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    /* Center form contents and submit button */
    .tab-content form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    .tab-content form .btn {
        align-self: center;
        margin-top: 0.5rem;
    }

    /* Make input groups stretch to the card width while keeping centered layout */
    .tab-content form .row,
    .tab-content form .form-group {
        width: 100%;
    }
</style>

<div class="container page-center">
    <div class="card card-flush card-centered">
        <div class="alert-container"></div>
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2 class="fw-bold text-dark">Edit Staff</h2>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light-primary">Back to Staff</a>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6 justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Personal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Employment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Location</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">Financial</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Next of Kin</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    <form action="{{ route('staff.hr.staff.update.personal', $staff) }}" method="POST" class="form-center">
                        @csrf
                        @method('PUT')
                        @include('hr.staff.partials.form.personal', ['staff' => $staff])
                        <button type="submit" class="btn btn-primary">Update Personal</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                    <form action="{{ route('staff.hr.staff.update.employment', $staff) }}" method="POST" class="form-center">
                        @csrf
                        @method('PUT')
                        @include('hr.staff.partials.form.employment', ['staff' => $staff])
                        <button type="submit" class="btn btn-primary">Update Employment</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                    <form action="{{ route('staff.hr.staff.update.location', $staff) }}" method="POST" class="form-center">
                        @csrf
                        @method('PUT')
                        @include('hr.staff.partials.form.location', ['staff' => $staff])
                        <button type="submit" class="btn btn-primary">Update Location</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                    <form action="{{ route('staff.hr.staff.update.financial', $staff) }}" method="POST" class="form-center">
                        @csrf
                        @method('PUT')
                        @include('hr.staff.partials.form.financial', ['staff' => $staff])
                        <button type="submit" class="btn btn-primary">Update Financial</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                    <form action="{{ route('staff.hr.staff.update.next-of-kin', $staff) }}" method="POST" class="form-center">
                        @csrf
                        @method('PUT')
                        @include('hr.staff.partials.form.next_of_kin', ['staff' => $staff])
                        <button type="submit" class="btn btn-primary">Update Next of Kin</button>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to show success alert
        function showSuccessAlert(message) {
            const alertContainer = document.querySelector('.alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
        }

        const lgaSelect = document.getElementById('lga_id');
        const wardSelect = document.getElementById('ward_id');
        const areaSelect = document.getElementById('area_id');

        const allWards = wardSelect ? Array.from(wardSelect.options) : [];
        const allAreas = areaSelect ? Array.from(areaSelect.options) : [];

        if (lgaSelect && wardSelect && areaSelect) {
            lgaSelect.addEventListener('change', function () {
                const lgaId = this.value;
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                areaSelect.innerHTML = '<option value="">Select Area</option>';

                allWards.forEach(option => {
                    if (option.dataset.lgaId === lgaId) {
                        wardSelect.appendChild(option.cloneNode(true));
                    }
                });
            });

            wardSelect.addEventListener('change', function () {
                const wardId = this.value;
                areaSelect.innerHTML = '<option value="">Select Area</option>';

                allAreas.forEach(option => {
                    if (option.dataset.wardId === wardId) {
                        areaSelect.appendChild(option.cloneNode(true));
                    }
                });
            });
        }

        // Handle form submission
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const url = this.action;
                const formData = new FormData(this);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    showSuccessAlert(data.message);
                })
                .catch(() => {
                    showSuccessAlert('An error occurred. Please try again.');
                });
            });
        });
    });
</script>
@endsection