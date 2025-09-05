@extends('layouts.vendor')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Payment History</h2>
            <p>View all payments processed by your vendor account.</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Payments</h4>
                </div>
                <div class="card-body">
                    @if (isset($payments) && $payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Billing ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date->format('Y-m-d H:i') }}</td>
                                            <td>{{ $payment->customer->first_name }} {{ $payment->customer->surname }}</td>
                                            <td>{{ $payment->customer->billing_id }}</td>
                                            <td>{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->method) }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $payment->payment_status }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No payments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection