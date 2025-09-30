<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../../../" />
    <title>Water Board Management System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_body" class="aside-enabled">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Aside-->
            <div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
                <div class="aside-toolbar flex-column-auto" id="kt_aside_toolbar">
                    <div class="aside-user d-flex align-items-sm-center justify-content-center py-5">
                        <div class="symbol symbol-50px">
                            <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="" />
                        </div>
                        <div class="aside-user-info flex-row-fluid flex-wrap ms-5">
                            <div class="d-flex">
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="text-white text-hover-primary fs-6 fw-bold">{{ Auth::guard('staff')->user()->name }}</a>
                                    <div class="d-flex align-items-center text-success fs-9">
                                        <span class="bullet bullet-dot bg-success me-1"></span>online
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center flex-wrap fw-semibold fs-8 mb-1">
                                <span class="text-muted text-capitalize">{{ Auth::guard('staff')->user()->roles->pluck('name')->join(', ') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="aside-menu flex-column-fluid">
                    <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-offset="0">
                        <div class="menu menu-column menu-title-gray-900 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.dashboard') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-home fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </div>
                            
                            @can('view-analytics', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.analytics.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-chart-line fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Analytics</span>
                                </a>
                            </div>
                            @endcan
                            
                            @can('view-customers', 'staff')
                            <div class="menu-item pt-5">
                                <div class="menu-content">
                                    <span class="menu-heading fw-bold text-uppercase fs-7">Customer Management</span>
                                </div>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.customers.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-people fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Customers</span>
                                </a>
                            </div>
                            @endcan
                            
                            @can('view-bill', 'staff')
                            <div class="menu-item pt-5">
                                <div class="menu-content">
                                    <span class="menu-heading fw-bold text-uppercase fs-7">Billing & Payments</span>
                                </div>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.bills.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-credit-cart fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Bills</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.payments.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-wallet fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Payments</span>
                                </a>
                            </div>
                            @endcan
                            
                            @can('view-locations', 'staff')
                            <div class="menu-item pt-5">
                                <div class="menu-content">
                                    <span class="menu-heading fw-bold text-uppercase fs-7">System Management</span>
                                </div>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.lgas.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-geolocation fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Locations</span>
                                </a>
                            </div>
                            @endcan
                            
                            @can('view-categories', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.categories.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-category fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Categories</span>
                                </a>
                            </div>
                            @endcan
                            
                            @can('view-tariffs', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.tariffs.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-price-tag fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Tariffs</span>
                                </a>
                            </div>
                            @endcan
                            
                            @can('manage-users', 'staff')
                            <div class="menu-item pt-5">
                                <div class="menu-content">
                                    <span class="menu-heading fw-bold text-uppercase fs-7">User Management</span>
                                </div>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.staff.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-user-tick fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Staff</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.roles.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-security-user fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Roles</span>
                                </a>
                            </div>
                            @endcan
                            
                            @can('view-audit-trail', 'staff')
                            <div class="menu-item pt-5">
                                <div class="menu-content">
                                    <span class="menu-heading fw-bold text-uppercase fs-7">System Tools</span>
                                </div>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.audits.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-security-check fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Audit Trail</span>
                                </a>
                            </div>
                            @endcan
                            
                            <div class="menu-item pt-5">
                                <div class="menu-content">
                                    <span class="menu-heading fw-bold text-uppercase fs-7">Account</span>
                                </div>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-exit-left fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Sign Out</span>
                                </a>
                                <form id="logout-form" action="{{ route('staff.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Aside-->
            
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" style="" class="header align-items-stretch">
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-5">
                            <a href="{{ route('staff.dashboard') }}" class="d-flex align-items-center">
                                <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="h-40px h-lg-40px me-2" />
                                <span class="fs-2 fw-bold text-primary d-none d-lg-inline">Water Board</span>
                            </a>
                            <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-minimize d-none d-lg-flex" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
                                <i class="ki-duotone ki-entrance-right fs-1 me-n1 minimize-default">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <i class="ki-duotone ki-entrance-left fs-1 minimize-active">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <div class="d-flex align-items-center d-lg-none me-n2" title="Show aside menu">
                                <div class="btn btn-icon btn-active-color-primary w-30px h-30px" id="kt_aside_mobile_toggle">
                                    <i class="ki-duotone ki-abstract-14 fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-stretch flex-shrink-0">
                            <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                                <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="user" />
                                </div>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <div class="menu-content d-flex align-items-center px-3">
                                            <div class="symbol symbol-50px me-5">
                                                <img alt="Logo" src="{{ asset('assets/media/avatars/blank.png') }}" />
                                            </div>
                                            <div class="d-flex flex-column">
                                                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::guard('staff')->user()->name }}</div>
                                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::guard('staff')->user()->email }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator my-2"></div>
                                    <div class="menu-item px-5">
                                        <a href="{{ route('staff.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();" class="menu-link px-5">Sign Out</a>
                                        <form id="logout-form-header" action="{{ route('staff.logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="container-xxl" id="kt_content_container">
                        @hasSection('page_title')
                        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5">
                            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                @yield('page_title')
                            </h1>
                            @hasSection('page_description')
                            <div class="d-flex align-items-center text-muted fs-7 fw-semibold mt-2">
                                @yield('page_description')
                            </div>
                            @endif
                            <!--begin::Breadcrumb-->
                            @hasSection('breadcrumbs')
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                @yield('breadcrumbs')
                            </ul>
                            @else
                            {!! app(App\Services\BreadcrumbService::class)->render() !!}
                            @endif
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page Title-->
                        @endif
                        @yield('content')
                    </div>
                </div>
                <!--end::Content-->
                
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted fw-semibold me-1">2023&copy;</span>
                            <a href="https://steadfast.com.ng/" target="_blank" class="text-gray-800 text-hover-primary">KTSWB</a>
                        </div>
                    </div>
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
    </div>
    
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    @yield('scripts')
</body>
</html>