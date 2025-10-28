@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Asset Details</h1>
        <table class="table">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ $asset['id'] }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $asset['label'] }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $asset['description'] }}</td>
                </tr>
                <tr>
                    <th>Serial Number</th>
                    <td>{{ $asset['ref'] }}</td>
                </tr>
                <tr>
                    <th>Purchase Price</th>
                    <td>{{ $asset['price'] }}</td>
                </tr>
                <tr>
                    <th>Purchase Date</th>
                    <td>{{ isset($asset['date_purchase']) ? date('Y-m-d', $asset['date_purchase']) : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Warehouse</th>
                    <td>{{ $asset['warehouse_id'] }}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ $asset['array_options']['options_category'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $asset['array_options']['options_type'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Model</th>
                    <td>{{ $asset['array_options']['options_model'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Brand</th>
                    <td>{{ $asset['array_options']['options_brand'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td>{{ $asset['array_options']['options_location'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $asset['array_options']['options_status'] ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
        <a href="{{ route('staff.assets.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection