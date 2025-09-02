@extends('layouts.staff')

@section('content')
<div class="container-xxl">
    <!--begin::Card-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2 class="fw-bold text-dark">Customer Details</h2>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.customers.index') }}" class="btn btn-light-primary">Back to Customers</a>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <div class="d-flex flex-column gap-4">
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Name</span>
                    <span class="text-dark fs-6">{{ $customer->first_name }} {{ $customer->surname }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Email</span>
                    <span class="text-dark fs-6">{{ $customer->email }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Phone</span>
                    <span class="text-dark fs-6">{{ $customer->phone_number }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Billing ID</span>
                    <span class="text-dark fs-6">{{ $customer->billing_id ?? 'Pending' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Status</span>
                    <span class="badge badge-light-{{ $customer->status == 'approved' ? 'success' : ($customer->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($customer->status) }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">LGA</span>
                    <span class="text-dark fs-6">{{ $customer->lga->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Ward</span>
                    <span class="text-dark fs-6">{{ $customer->ward->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Area</span>
                    <span class="text-dark fs-6">{{ $customer->area->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Category</span>
                    <span class="text-dark fs-6">{{ $customer->category->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Tariff</span>
                    <span class="text-dark fs-6">{{ $customer->tariff->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-7 fw-bold w-150px">Account Balance</span>
                    <span class="text-dark fs-6">{{ $customer->account_balance }}</span>
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection