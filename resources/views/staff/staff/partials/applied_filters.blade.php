<!--begin::Applied Filters-->
@if (request()->hasAny(['search_staff', 'status_filter', 'department_filter']))
    <div class="alert alert-info mb-5">
        <strong>Applied Filters:</strong>
        @if (request('search_staff')) Search: {{ request('search_staff') }} @endif
        @if (request('status_filter')) | Status: {{ ucfirst(request('status_filter')) }} @endif
        @if (request('department_filter')) | Department: {{ request('department_filter') }} @endif
    </div>
@endif
<!--end::Applied Filters-->