<tr>
    <td>
        <div class="form-check form-check-sm form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" value="{{ $member->id }}" />
        </div>
    </td>
    <td>
        <a href="#" class="text-gray-800 text-hover-primary mb-1" data-bs-toggle="modal" data-bs-target="#kt_staff_view_modal{{ $member->id }}">
            {{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}
        </a>
    </td>
    <td>
        <a href="#" class="text-gray-600 text-hover-primary mb-1">{{ $member->email }}</a>
    </td>
    <td>{{ $member->getRoleNames()->join(', ') }}</td>
    <td>
        <div class="badge badge-light-{{ $member->status == 'approved' ? 'success' : ($member->status == 'pending' || $member->status == 'pending_delete' ? 'warning' : 'danger') }}">
            {{ ucfirst(str_replace('_', ' ', $member->status)) }}
        </div>
    </td>
    <td class="text-end">
        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
            Actions
            <i class="ki-duotone ki-down fs-5 ms-1"></i>
        </a>
        <!--begin::Menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_view_modal{{ $member->id }}">View</a>
            </div>
            <!--end::Menu item-->
            @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_assign_roles_modal{{ $member->id }}">Assign Roles</a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_remove_roles_modal{{ $member->id }}">Remove Roles</a>
                </div>
                <!--end::Menu item-->
            @endif
        </div>
        <!--end::Menu-->
    </td>
</tr>

@include('staff.staff.partials.view_modal', ['member' => $member])
@include('staff.staff.partials.assign_roles_modal', ['member' => $member, 'roles' => $roles])
@include('staff.staff.partials.remove_roles_modal', ['member' => $member, 'roles' => $roles])