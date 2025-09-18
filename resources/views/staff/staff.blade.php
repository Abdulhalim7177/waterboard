@extends('layouts.staff')

@section('content')
    <script>
        window.location.href = "{{ route('staff.staff.index') }}";
    </script>
@endsection