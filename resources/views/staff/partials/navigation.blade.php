<!--begin::Staff Management Navigation-->
<div class="d-flex align-items-center mb-8">
    <!--begin::Nav wrapper-->
    <div class="d-flex align-items-center flex-wrap w-100">
        <!--begin::Heading-->
        <div class="me-5">
            <h3 class="fw-bold text-dark mb-0">Staff Management</h3>
            <span class="text-muted fs-7">Manage roles, permissions, and staff records</span>
        </div>
        <!--end::Heading-->
        
        <!--begin::Spacer-->
        <div class="flex-grow-1"></div>
        <!--end::Spacer-->
        
        <!--begin::Nav-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
            <!--begin::Nav item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 {{ request()->routeIs('staff.staff.*') && !request()->routeIs('staff.staff.index') ? 'active' : '' }}" href="{{ route('staff.staff.index') }}">
                    <i class="ki-duotone ki-shield fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Role Management
                </a>
            </li>
            <!--end::Nav item-->
            <!--begin::Nav item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 {{ request()->routeIs('staff.hr.staff.*') ? 'active' : '' }}" href="{{ route('staff.hr.staff.index') }}">
                    <i class="ki-duotone ki-profile-user fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    HR Management
                </a>
            </li>
        </ul>
        <!--end::Nav-->
    </div>
    <!--end::Nav wrapper-->
</div>
<!--end::Staff Management Navigation-->