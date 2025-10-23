<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../../../" />
    <title>Water Board System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                <div class="me-n2">
                                    <a href="#" class="btn btn-icon btn-sm btn-active-color-primary mt-n2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-overflow="true">
                                        <i class="ki-duotone ki-gear fs-1 text-muted">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <div class="symbol symbol-50px me-5">
                                                    <img alt="Logo" src="{{ asset('assets/media/avatars/blank.png') }}" />
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::guard('staff')->user()->name }}
                                                        <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Staff</span>
                                                    </div>
                                                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::guard('staff')->user()->email }}</a>
                                                </div>
                                                </div>
                                        </div>
                                        <div class="menu-item px-5 my-1">
                                            <a href="../../demo8/dist/account/settings.html" class="menu-link px-5">Account Settings</a>
                                        </div>
                                        <div class="menu-item px-5">
                                            <form action="{{ route('staff.logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger menu-link px-5">Logout</button>
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
                                    <span class="menu-heading fw-bold text-uppercase fs-7">System Management</span>
                                </div>
                            </div>
                            @can('view-locations', 'staff')
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-geolocation fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Location Management</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    @can('view-locations', 'staff')
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.lgas.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">LGA Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.wards.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Ward Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.areas.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Area Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.zones.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Zone Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.districts.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">District Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.paypoints.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Paypoint Management</span>
                                        </a>
                                    </div>
                                    @endcan
                                </div>
                                </div>
                            @endcan
                            @can('manage-users', 'staff')
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-price-tag fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Tariff and Category Management</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.categories.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Category Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.tariffs.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Tariff Management</span>
                                        </a>
                                    </div>
                                </div>
                                </div>
                            @endcan
                            @can('manage-users', 'staff')
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-user-edit fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Staff Management</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.staff.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Staff Overview</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.roles.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Roles Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.permissions.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Permission Management</span>
                                        </a>
                                    </div>
                                </div>
                                </div>
                            @endcan
                            @can('manage-staff', 'staff')
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-user-tick fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">HR Management</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.hr.staff.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Staff Directory</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.hr.staff.create') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Add New Staff</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('view-customers', 'staff')
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-profile-user fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Customer Management</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.customers.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Customer Overview</span>
                                        </a>
                                    </div>
                                    @can('create-customer', 'staff')
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.customers.create.personal') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Customer Creation</span>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('create-customer', 'staff')
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.customers.pending') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Pending Changes</span>
                                        </a>
                                    </div>
                                    @endcan
                                </div>
                                </div>
                            @endcan
                            @can('view-payment', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.payments.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-dollar fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Payment History</span>
                                </a>
                                </div>
                            @endcan
                            @can('view-bill', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.bills.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-file-down fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Customer Billing</span>
                                </a>
                                </div>
                            @endcan
                            @can('view-bill', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.gis') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-map fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Customer GIS Overview</span>
                                </a>
                                </div>
                            @endcan
                            @can('view-analytics', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.analytics.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-graph fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Analytics</span>
                                </a>
                                </div>
                            @endcan
                            @can('approve-actions', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.approvals.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-check-circle fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Approvals</span>
                                </a>
                                </div>
                            @endcan
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-user fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Account</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link" href="../../demo8/dist/account/overview.html">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Overview</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="../../demo8/dist/account/settings.html">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Settings</span>
                                        </a>
                                    </div>
                                </div>
                                </div>
                            @can('view-audit-trail', 'staff')
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.audits.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-security-user fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Audit Trail</span>
                                </a>
                            </div>
                            @endcan

                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-kanban fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Asset Management</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.assets.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">All Assets</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.assets.create') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Add New Asset</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('staff.warehouses.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Warehouse Management</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('staff.vendors.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-shop fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Vendor Management</span>
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
                      <a href="{{route('customer.dashboard')}}" class="d-flex align-items-center">
							<img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="h-40px h-lg-40px me-2" />
							<span class="fs-2 fw-bold text-primary d-none d-lg-inline">KTSWB</span>
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
                                <h1 class="d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                    @yield('page_title', 'Dashboard')
                                </h1>
                                <div class="breadcrumb-wrapper">
                                    {!! app(App\Services\BreadcrumbService::class)->render() !!}
                                </div>
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
                            @yield('content')
                            @yield('scripts')
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
                font-size: 1.5rem !important;
            }
            
            .page-title h1 {
                font-size: 1.5rem !important;
            }
            
            .breadcrumb {
                font-size: 0.8rem !important;
            }
            
            .aside-user-info {
                margin-left: 0.75rem !important;
            }
            
            .toolbar .container-xxl {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        @media (max-width: 575.98px) {
            .header-brand img {
                height: 30px !important;
            }
            
            .aside-user .symbol {
                width: 40px !important;
                height: 40px !important;
            }
            
            .menu-title {
                font-size: 0.9rem;
            }
            
            .footer .container-fluid {
                flex-direction: column !important;
                text-align: center;
            }
            
            .footer .menu {
                margin-top: 1rem;
            }
        }
        
        /* Menu hover effects */
        .menu-link:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            border-radius: 0.475rem;
            transition: all 0.2s ease;
        }
        
        /* Improved scrollbar for aside menu */
        .hover-scroll-overlay-y::-webkit-scrollbar {
            width: 6px;
        }
        
        .hover-scroll-overlay-y::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .hover-scroll-overlay-y::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
        }
        
        .hover-scroll-overlay-y::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.3);
        }
    </style>
</body>
</html>