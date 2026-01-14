@extends('layouts.customer-app')

@section('title', 'Connection Fees')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3>Connection Fees</h3>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="mb-10">
                    <h5 class="mb-5">Your Connection History</h5>
                    @if($customerConnections->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">Type</th>
                                        <th class="min-w-125px">Size</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="min-w-125px">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach($customerConnections as $connection)
                                    <tr>
                                        <td>{{ $connection->connectionType->name }}</td>
                                        <td>{{ $connection->connectionSize ? $connection->connectionSize->name : 'N/A' }}</td>
                                        <td>
                                            <div class="badge badge-{{ $connection->status === 'approved' ? 'success' : ($connection->status === 'rejected' ? 'danger' : 'warning') }} fw-bold">
                                                {{ ucfirst($connection->status) }}
                                            </div>
                                        </td>
                                        <td>{{ $connection->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600">No connection history found.</p>
                    @endif
                </div>

                <div>
                    <h5 class="mb-5">Available Connection Fees</h5>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Type</th>
                                    <th class="min-w-125px">Size</th>
                                    <th class="min-w-125px">Fee Amount</th>
                                    <th class="min-w-125px">Action</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($connectionFees as $fee)
                                <tr>
                                    <td>{{ $fee->connectionType->name }}</td>
                                    <td>{{ $fee->connectionSize ? $fee->connectionSize->name : 'N/A' }}</td>
                                    <td>₦{{ number_format($fee->fee_amount, 2) }}</td>
                                    <td>
                                        <form action="{{ route('customer.connections.fees.pay') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="connection_fee_id" value="{{ $fee->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary">Pay Now</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No connection fees available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection