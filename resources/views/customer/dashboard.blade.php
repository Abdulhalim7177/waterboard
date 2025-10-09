@extends('layouts.customer')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Messages-->
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
            <!--end::Messages-->
            <!--begin::Dashboard main-->
            <div class="card">
                <!--begin::Body-->
                <div class="card-body p-lg-20">
           
                    <!--begin::Wrapper-->
                    <div class="m-0">
                        <!--begin::Label-->

                        <!--end::Label-->
                        <!--begin::Summary Card-->
                        <div class="card card-flush mb-8">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Customer Dashboard</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::View Details-->
                                    <button class="btn btn-sm btn-light btn-active-light-primary me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_customer_details">View Customer Details</button>
                                    <!--end::View Details-->
                                    <!--begin::More options-->
                                    <a href="#" class="btn btn-sm btn-light btn-icon" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-duotone ki-dots-square fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-200px py-4" data-kt-menu="true">
                                     
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('customer.payments') }}" class="menu-link px-3">View Billing Payment History</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                    <!--end::More options-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0 fs-6">
                                <!--begin::Section-->
                                <div class="mb-7">
                                    <!--begin::Details-->
                                    <div class="d-flex align-items-center">
                                  
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column">
                                            <!--begin::Name-->
                                            <a class="fs-4 fw-bold text-gray-900 text-hover-primary me-2">{{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->surname }}</a>
                                            <!--end::Name-->
                                            <!--begin::Email-->
                                            <a class="fw-semibold text-gray-600 text-hover-primary">{{ Auth::guard('customer')->user()->email }}</a>
                                            <!--end::Email-->
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Details-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Seperator-->
                                <div class="separator separator-dashed mb-7"></div>
                                <!--end::Seperator-->
                                <!--begin::Section-->
                                <div class="mb-7">
                                    <!--begin::Title-->
                                    <h5 class="mb-4">Billing Details</h5>
                                    <!--end::Title-->
                                    <!--begin::Details-->
                                    <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Billing ID:</td>
                                            <td class="text-gray-800">
                                                @if (Auth::guard('customer')->user()->billing_id)
                                                    {{ Auth::guard('customer')->user()->billing_id }}
                                                @else
                                                    <span class="badge badge-light-warning">Pending Approval</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Account Balance:</td>
                                            <td class="text-success">₦{{ number_format(Auth::guard('customer')->user()->account_balance, 2) }}</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Total Outstanding:</td>
                                            <td class="text-danger">₦{{ number_format(Auth::guard('customer')->user()->total_bill, 2) }}</td>
                                        </tr>
                                        <!--end::Row-->
                                    </table>
                                    <!--end::Details-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Seperator-->
                                <div class="separator separator-dashed mb-7"></div>
                                <!--end::Seperator-->
                                <!--begin::Section-->
                                <div class="mb-7">
                                    <!--begin::Title-->
                                    <h5 class="mb-4">Customer Information</h5>
                                    <!--end::Title-->
                                    <!--begin::Details-->
                                    <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Category:</td>
                                            <td class="text-gray-800">{{ Auth::guard('customer')->user()->category->name ?? 'N/A' }}</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">LGA:</td>
                                            <td class="text-gray-800">{{ Auth::guard('customer')->user()->lga->name ?? 'N/A' }}</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Ward:</td>
                                            <td class="text-gray-800">{{ Auth::guard('customer')->user()->ward->name ?? 'N/A' }}</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Area:</td>
                                            <td class="text-gray-800">{{ Auth::guard('customer')->user()->area->name ?? 'N/A' }}</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Tariff:</td>
                                            <td class="text-gray-800">{{ Auth::guard('customer')->user()->tariff->name ?? 'N/A' }}</td>
                                        </tr>
                                        <!--end::Row-->
                                    </table>
                                    <!--end::Details-->
                                </div>
                                <!--end::Section-->
                             
                             
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Summary Card-->
                        <!--begin::Actions-->
                        <div>
                            <a href="{{ route('customer.bills') }}" class="btn btn-primary">View My Water Bills</a>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Dashboard main-->
            <!--begin::Customer Details Modal-->
            <div class="modal fade" id="kt_modal_customer_details" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="fw-bold">Customer Details: {{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->surname }}</h2>
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="modal-body scroll-y mx-5 mx-xl-10 my-2">
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Name:</td>
                                    <td class="text-gray-800">{{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->surname }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Email:</td>
                                    <td class="text-gray-800">{{ Auth::guard('customer')->user()->email }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Billing ID:</td>
                                    <td class="text-gray-800">
                                        @if (Auth::guard('customer')->user()->billing_id)
                                            {{ Auth::guard('customer')->user()->billing_id }}
                                        @else
                                            <span class="badge badge-light-warning">Pending Approval</span>
                                        @endif
                                    </td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Account Balance:</td>
                                    <td class="text-success">₦{{ number_format(Auth::guard('customer')->user()->account_balance, 2) }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Total Outstanding:</td>
                                    <td class="text-danger">₦{{ number_format(Auth::guard('customer')->user()->total_bill, 2) }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Category:</td>
                                    <td class="text-gray-800">{{ Auth::guard('customer')->user()->category->name ?? 'N/A' }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">LGA:</td>
                                    <td class="text-gray-800">{{ Auth::guard('customer')->user()->lga->name ?? 'N/A' }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Ward:</td>
                                    <td class="text-gray-800">{{ Auth::guard('customer')->user()->ward->name ?? 'N/A' }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Area:</td>
                                    <td class="text-gray-800">{{ Auth::guard('customer')->user()->area->name ?? 'N/A' }}</td>
                                </tr>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <tr>
                                    <td class="text-gray-400">Tariff:</td>
                                    <td class="text-gray-800">{{ Auth::guard('customer')->user()->tariff->name ?? 'N/A' }}</td>
                                </tr>
                                <!--end::Row-->
                            </table>
                            <div class="text-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Customer Details Modal-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
@endsection