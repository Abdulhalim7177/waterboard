@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <h2 class="text-center text-primary">Fund Your Wallet</h2>
        <p class="text-center text-muted">Add funds to your account balance</p>
    </div>

    <!-- Messages -->
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

    <!-- Fund Wallet Form -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Enter Funding Amount</h5>
            <form method="POST" action="{{ route('customer.wallet.fund') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Amount (₦)</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" required>
                    </div>
                    <div class="col-md-6 align-self-end">
                        <button type="submit" class="btn btn-primary">Fund Wallet with NABRoll</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection