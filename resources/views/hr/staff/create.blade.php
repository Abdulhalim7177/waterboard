@extends('layouts.staff')

@section('content')
<div class="container-xxl">
    <!--begin::Card-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2 class="fw-bold text-dark">Create Staff</h2>
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
            <form action="{{ route('staff.hr.staff.store') }}" method="POST">
                @csrf
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
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
                        @include('hr.staff.partials.form.personal')
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                        @include('hr.staff.partials.form.employment')
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                        @include('hr.staff.partials.form.location')
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                        @include('hr.staff.partials.form.financial')
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                        @include('hr.staff.partials.form.next_of_kin')
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection