<!--begin::Analytics Management Navigation-->
<div class="d-flex align-items-center mb-8">
    <!--begin::Nav wrapper-->
    <div class="d-flex align-items-center flex-wrap w-100">
        <!--begin::Heading-->
        <div class="me-5">
            <h3 class="fw-bold text-dark mb-0">Analytics Management</h3>
            <span class="text-muted fs-7">System overview and key metrics</span>
        </div>
        <!--end::Heading-->
        
        <!--begin::Spacer-->
        <div class="flex-grow-1"></div>
        <!--end::Spacer-->
        
        <!--begin::Nav-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
            <!--begin::Nav item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 {{ request()->routeIs('staff.analytics.index') ? 'active' : '' }}" href="{{ route('staff.analytics.index') }}">
                    <i class="ki-duotone ki-chart-line fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Dashboard
                </a>
            </li>
            <!--end::Nav item-->
            <!--begin::Nav item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 {{ request()->routeIs('staff.analytics.report') ? 'active' : '' }}" href="{{ route('staff.analytics.report') }}">
                    <i class="ki-duotone ki-document fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Reports
                </a>
            </li>
            <!--end::Nav item-->
            <!--begin::Nav item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 {{ request()->routeIs('staff.analytics.details') ? 'active' : '' }}" href="{{ route('staff.analytics.details') }}">
                    <i class="ki-duotone ki-information fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Details
                </a>
            </li>
            <!--end::Nav item-->
        </ul>
        <!--end::Nav-->
    </div>
    <!--end::Nav wrapper-->
</div>
<!--end::Analytics Management Navigation-->