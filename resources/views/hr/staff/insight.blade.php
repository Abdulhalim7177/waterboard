@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Staff Sync Insight</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">New Staff</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($newStaff as $staff)
                                <tr>
                                    <td>{{ $staff['staff_id'] }}</td>
                                    <td>{{ $staff['first_name'] }} {{ $staff['surname'] }}</td>
                                    <td>{{ $staff['email'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No new staff found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Existing Staff</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($existingStaff as $staff)
                                <tr>
                                    <td>{{ $staff['staff_id'] }}</td>
                                    <td>{{ $staff['first_name'] }} {{ $staff['surname'] }}</td>
                                    <td>{{ $staff['email'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No existing staff found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
