@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reservoir Management</h1>
        <a href="{{ route('staff.reservoirs.create') }}" class="btn btn-primary">Add Reservoir</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Tanks</th>
                    <th>Capacity</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservoirs as $reservoir)
                    <tr>
                        <td>{{ $reservoir['id'] }}</td>
                        <td>{{ $reservoir['label'] }}</td>
                        <td>{{ $reservoir['array_options']['options_tanks'] ?? 'N/A' }}</td>
                        <td>{{ $reservoir['array_options']['options_capacity'] ?? 'N/A' }}</td>
                        <td>{{ $reservoir['array_options']['options_location'] ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('staff.reservoirs.show', $reservoir['id']) }}" class="btn btn-info">View</a>
                            <a href="{{ route('staff.reservoirs.edit', $reservoir['id']) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('staff.reservoirs.destroy', $reservoir['id']) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $reservoirs->links() }}
    </div>
@endsection