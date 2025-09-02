@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Vendor Dashboard</h2>
    <p>Welcome, {{ Auth::guard('vendor')->user()->name }}</p>
    <!-- Form for billing ID payment -->
</div>
@endsection