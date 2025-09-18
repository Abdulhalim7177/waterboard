<!--begin::Toolbar-->
<div class="d-flex justify-content-end" data-kt-staff-table-toolbar="base">
    <!--begin::Filter-->
    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
        <i class="ki-duotone ki-filter fs-2">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        Filter
    </button>
    <!--end::Filter-->
    
    <!--begin::Add staff-->
    @can('create-staff', App\Models\Staff::class)
        <a href="{{ route('staff.staff.create') }}" class="btn btn-primary">
            <i class="ki-duotone ki-plus fs-2"></i>
            Add Staff
        </a>
    @endcan
    <!--end::Add staff-->
</div>
<!--end::Toolbar-->