@extends('layouts.vendor')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Vendor Dashboard</h2>
            <p>Welcome, {{ Auth::guard('vendor')->user()->name }}</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Make Payment for Customer</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('vendor.payment.process') }}">
                        @csrf
                        <div class="form-group">
                            <label for="billing_id">Customer Billing ID</label>
                            <input type="text" name="billing_id" id="billing_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="pos">POS</option>
                                <option value="transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Process Payment</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Payments</h4>
                </div>
                <div class="card-body">
                    <p>Recent payment history will appear here.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection