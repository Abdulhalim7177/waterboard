<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../" />
    <title>@yield('title', 'Vendor Portal')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    
    @yield('styles')
</head>
<!--end::Head-->

<!--begin::Body-->

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
            <div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
                <div class="aside-toolbar flex-column-auto" id="kt_aside_toolbar">
                    <div class="aside-user d-flex align-items-sm-center justify-content-between py-5 w-100">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px d-none d-lg-block">
                                <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="" />
                            </div>
                            <div class="aside-user-info flex-row-fluid flex-wrap ms-5">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5 d-block d-lg-none">
                                            <img alt="Logo" src="{{ asset('assets/media/avatars/blank.png') }}" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold d-flex align-items-center fs-6 fs-lg-5">{{ Auth::guard('vendor')->user()->name }}
                                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Vendor</span>
                                            </div>
                                            <a href="#" class="fw-semibold text-muted text-hover-primary fs-8 fs-lg-7">{{ Auth::guard('vendor')->user()->email }}</a>
                                            <div class="d-flex align-items-center text-success fs-10 fs-lg-9 d-none d-lg-block">
                                                <span class="bullet bullet-dot bg-success me-1"></span>online
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-success fs-10 fs-lg-9 d-block d-lg-none mt-1">
                                        <span class="bullet bullet-dot bg-success me-1"></span>online
                                    </div>
                                    <div class="d-flex flex-column mt-2">
                                        <div class="menu-item px-0">
                                            <form action="{{ route('vendor.logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm menu-link px-3 py-1 fs-9">Logout</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="aside-menu flex-column-fluid">
                    <div class="hover-scroll-overlay-y px-2 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px">
                        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                            <div class="menu-item pt-5">
                                <div class="menu-content">
                                    <span class="menu-heading fw-bold text-uppercase fs-7">Vendor Portal</span>
                                </div>
                            </div>
                            
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-home fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </div>
                            
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('vendor.payments.index') ? 'active' : '' }}" href="{{ route('vendor.payments.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-dollar fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Transaction History</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ request()->routeIs('vendor.payments.funding') ? 'active' : '' }}" href="{{ route('vendor.payments.funding') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-wallet fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Funding History</span>
                                </a>
                            </div>
                            
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('vendor.profile') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-profile-user fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Profile</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="aside-footer flex-column-auto py-5" id="kt_aside_footer">
                    <a href="#" class="btn btn-flex btn-custom btn-primary w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="Katsina State Water Board">
                        <span class="btn-label">KTSWB </span>
                        <i class="ki-duotone ki-water fs-2 ms-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                </div>
            </div>
            
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <div id="kt_header" class="header align-items-stretch" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '0px', lg: '0px'}">
                    <div class="header-brand">
                        <a href="{{ route('vendor.dashboard') }}" class="d-flex align-items-center">
                            <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="h-40px h-lg-40px me-2" />
                            <span class="fs-2 fw-bold text-primary d-none d-lg-inline">KTSWB - Vendor Portal</span>
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
                    
                    <div class="toolbar d-flex align-items-stretch">
                        <div class="container-xxl py-6 py-lg-0 d-flex flex-column flex-lg-row align-items-lg-stretch justify-content-lg-between">
                            <div class="page-title d-flex justify-content-center flex-column me-5">
                                <h1 class="d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">@yield('page-title', 'Dashboard')</h1>
                                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                    <li class="breadcrumb-item text-muted">
                                        <a href="{{ route('vendor.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                                    </li>
                                    <li class="breadcrumb-item text-dark">@yield('breadcrumb', 'Dashboard')</li>
                                </ul>
                            </div>
                            
                            <div class="d-flex align-items-stretch overflow-auto pt-3 pt-lg-0">
                                <div class="d-flex align-items-center">
                                    <span class="fs-7 text-gray-700 fw-bold pe-3 d-none d-xxl-block">System Theme:</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <a href="#" class="btn btn-sm btn-icon btn-icon-muted btn-active-icon-primary" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                        <i class="ki-duotone ki-night-day theme-light-show fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                            <span class="path6"></span>
                                            <span class="path7"></span>
                                            <span class="path8"></span>
                                            <span class="path9"></span>
                                            <span class="path10"></span>
                                        </i>
                                        <i class="ki-duotone ki-moon theme-dark-show fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                                        <div class="menu-item px-3 my-0">
                                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                                <span class="menu-icon" data-kt-element="icon">
                                                    <i class="ki-duotone ki-night-day fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                        <span class="path6"></span>
                                                        <span class="path7"></span>
                                                        <span class="path8"></span>
                                                        <span class="path9"></span>
                                                        <span class="path10"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Light</span>
                                            </a>
                                        </div>
                                        <div class="menu-item px-3 my-0">
                                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                                <span class="menu-icon" data-kt-element="icon">
                                                    <i class="ki-duotone ki-moon fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Dark</span>
                                            </a>
                                        </div>
                                        <div class="menu-item px-3 my-0">
                                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                                <span class="menu-icon" data-kt-element="icon">
                                                    <i class="ki-duotone ki-screen fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">System</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div id="kt_content_container" class="container-xxl">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            @yield('content')
                        </div>
                    </div>
                </div>
                
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted fw-semibold me-1">2023&copy;</span>
                            <a href="https://steadfast.com.ng/" target="_blank" class="text-gray-800 text-hover-primary">@steadfast</a>
                        </div>
                        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                            <li class="menu-item">
                                <a href="https://steadfast.com.ng/about/about-us" target="_blank" class="menu-link px-2">About</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://steadfast.com.ng/contact" target="_blank" class="menu-link px-2">Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        var hostUrl = "assets/";
    </script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/ecommerce/customers/listing/listing.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/ecommerce/customers/listing/add.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/ecommerce/customers/listing/export.js') }}"></script>
    <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
    
    <style>
        /* Enhanced responsive styles */
        @media (max-width: 991.98px) {
            .aside-minimize {
                display: none !important;
            }

            .header-brand .fs-2 {
                font-size: 1.25rem !important;
            }

            .page-title h1 {
                font-size: 1.25rem !important;
                margin-bottom: 0.25rem !important;
            }

            .breadcrumb {
                font-size: 0.75rem !important;
                margin-bottom: 0 !important;
            }

            .aside-user-info {
                margin-left: 0.5rem !important;
            }

            .toolbar .container-xxl {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .header {
                padding: 0.5rem 0 !important;
            }

            .content {
                padding-top: 1rem !important;
            }
        }

        @media (max-width: 768px) {
            .page-title {
                text-align: center !important;
                margin-bottom: 1rem !important;
                me-5: 0 !important;
            }

            .toolbar {
                justify-content: center !important;
            }

            .toolbar .container-xxl {
                flex-direction: column !important;
                align-items: center !important;
            }
        }

        @media (max-width: 575.98px) {
            .header-brand img {
                height: 28px !important;
            }

            .aside-user .symbol {
                width: 36px !important;
                height: 36px !important;
            }

            .menu-title {
                font-size: 0.85rem;
            }

            .menu-icon {
                font-size: 1.25rem !important;
            }

            .footer .container-fluid {
                flex-direction: column !important;
                text-align: center;
                gap: 1rem;
            }

            .footer .menu {
                margin-top: 0.5rem;
                justify-content: center;
            }

            .aside-user {
                padding: 0.5rem 0.75rem !important;
            }

            .aside-user-info .fw-bold {
                font-size: 0.9rem !important;
            }

            .aside-user-info .text-muted {
                font-size: 0.8rem !important;
            }
        }

        /* Modern menu hover effects */
        .menu-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
            margin: 0.25rem 0.5rem;
        }

        .menu-link:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.08);
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .menu-link.active {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.15), rgba(var(--bs-primary-rgb), 0.05));
            border-left: 3px solid rgba(var(--bs-primary-rgb), 0.8);
        }

        /* Improved scrollbar for aside menu */
        .hover-scroll-overlay-y::-webkit-scrollbar {
            width: 4px;
        }

        .hover-scroll-overlay-y::-webkit-scrollbar-track {
            background: transparent;
        }

        .hover-scroll-overlay-y::-webkit-scrollbar-thumb {
            background: rgba(var(--bs-primary-rgb), 0.3);
            border-radius: 8px;
        }

        .hover-scroll-overlay-y::-webkit-scrollbar-thumb:hover {
            background: rgba(var(--bs-primary-rgb), 0.5);
        }

        /* Mobile profile section improvements */
        @media (max-width: 991.98px) {
            .aside-user .symbol-50px,
            .aside-user .text-success {
                display: none !important;
            }

            .aside-user {
                padding: 0.5rem 0.75rem !important;
                align-items: center !important;
                border-bottom: 1px solid rgba(0,0,0,0.08);
            }

            .aside-user-info {
                margin-left: 0 !important;
            }

            .aside-user-info .d-flex {
                align-items: flex-start !important;
            }

            .aside-user-info .menu-link {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.8rem !important;
            }
        }

        /* Card hover effects */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Button improvements */
        .btn {
            transition: all 0.2s ease;
            border-radius: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Table responsive improvements */
        .table-responsive {
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Form improvements */
        .form-control, .form-select {
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: rgba(var(--bs-primary-rgb), 0.5);
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15);
        }

        /* Alert improvements */
        .alert {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Mobile-specific improvements */
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 0.75rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                font-size: 0.875rem;
            }

            .table th, .table td {
                padding: 0.75rem 0.5rem;
                vertical-align: middle;
            }

            .badge {
                font-size: 0.75rem;
                padding: 0.35rem 0.65rem;
            }

            .btn-sm {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .form-control, .form-select {
                font-size: 0.875rem;
                padding: 0.75rem;
            }

            .input-group-text {
                padding: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.8rem;
            }

            .table th, .table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.75rem;
            }

            .fs-3x {
                font-size: 2rem !important;
            }

            .symbol-45px, .symbol-50px {
                width: 36px !important;
                height: 36px !important;
            }

            .card-header {
                padding: 1rem 0.75rem !important;
            }

            .card-body {
                padding: 1rem 0.75rem !important;
            }

            .card-footer {
                padding: 0.75rem !important;
            }

            .g-4 > * {
                margin-bottom: 1rem !important;
            }
        }

        /* Custom gradient backgrounds for cards */
        .bg-gradient-start {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.05), rgba(var(--bs-primary-rgb), 0.02));
        }

        .bg-gradient-end {
            background: linear-gradient(135deg, rgba(var(--bs-success-rgb), 0.05), rgba(var(--bs-success-rgb), 0.02));
        }

        .bg-gradient-middle {
            background: linear-gradient(135deg, rgba(var(--bs-info-rgb), 0.05), rgba(var(--bs-info-rgb), 0.02));
        }

        /* Space utility for customer info */
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }

        /* Improved filter collapse animation */
        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }

        .collapse.show + .rotate-180 {
            transform: rotate(0deg);
        }

        /* Better mobile table overflow */
        @media (max-width: 768px) {
            .table-responsive::-webkit-scrollbar {
                height: 6px;
            }

            .table-responsive::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .table-responsive::-webkit-scrollbar-thumb {
                background: rgba(var(--bs-primary-rgb), 0.5);
                border-radius: 10px;
            }

            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: rgba(var(--bs-primary-rgb), 0.7);
            }
        }
    </style>
    
    @yield('scripts')
</body>
<!--end::Body-->

</html>