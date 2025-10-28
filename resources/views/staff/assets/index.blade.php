
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Asset Management</h1>
        <a href="{{ route('staff.assets.create') }}" class="btn btn-primary">Add Asset</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assets as $asset)
                    <tr>
                        <td>{{ $asset['id'] }}</td>
                        <td>{{ $asset['label'] }}</td>
                        <td>{{ $asset['array_options']['options_category'] ?? 'N/A' }}</td>
                        <td>{{ $asset['status'] ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('staff.assets.show', $asset['id']) }}" class="btn btn-info">View</a>
                            <a href="{{ route('staff.assets.edit', $asset['id']) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('staff.assets.destroy', $asset['id']) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $assets->links() }}
    </div>
@endsection
