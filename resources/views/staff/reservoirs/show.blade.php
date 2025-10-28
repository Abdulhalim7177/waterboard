@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reservoir Details</h1>
        <table class="table">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ $reservoir['id'] }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $reservoir['label'] }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $reservoir['description'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Serial Number</th>
                    <td>{{ $reservoir['ref'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Purchase Price</th>
                    <td>{{ $reservoir['price'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Purchase Date</th>
                    <td>{{ isset($reservoir['date_purchase']) ? date('Y-m-d', $reservoir['date_purchase']) : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Warehouse</th>
                    <td>{{ $reservoir['warehouse_id'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Tanks</th>
                    <td>{{ $reservoir['array_options']['options_tanks'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Capacity</th>
                    <td>{{ $reservoir['array_options']['options_capacity'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td>{{ $reservoir['array_options']['options_location'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $reservoir['array_options']['options_status'] ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
        <a href="{{ route('staff.reservoirs.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection