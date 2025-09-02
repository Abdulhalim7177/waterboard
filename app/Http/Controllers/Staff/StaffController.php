<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Audit;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'permission:manage-users'])->only(['staff', 'roles', 'permissions']);
        $this->middleware(['auth:staff', 'permission:view-audit-trail'])->only('auditTrail');
        $this->middleware(['auth:staff', 'permission:create-staff'])->only('store');
        $this->middleware(['auth:staff', 'permission:edit-staff'])->only('update');
        $this->middleware(['auth:staff', 'permission:delete-staff'])->only('destroy');
        $this->middleware(['auth:staff', 'permission:approve-staff'])->only('approve');
        $this->middleware(['auth:staff', 'permission:reject-staff'])->only('reject');
        $this->middleware(['auth:staff', 'permission:create-role'])->only('storeRole');
        $this->middleware(['auth:staff', 'permission:edit-role'])->only('updateRole');
        $this->middleware(['auth:staff', 'permission:delete-role'])->only('destroyRole');
        $this->middleware(['auth:staff', 'permission:create-permission'])->only('storePermission');
        $this->middleware(['auth:staff', 'permission:edit-permission'])->only('updatePermission');
        $this->middleware(['auth:staff', 'permission:delete-permission'])->only('destroyPermission');
        $this->middleware(['auth:staff', 'role:super-admin|manager'])->only(['assignRoles', 'removeRoles', 'assignLocations']);
    }

    public function dashboard()
    {
        return view('staff.dashboard');
    }

    public function staff(Request $request)
    {
        $staff = Staff::when($request->search_staff, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        })->with('roles')->paginate(10);

        $roles = Role::where('guard_name', 'staff')->get();
        $lgas = Lga::where('status', 'approved')->get();
        $wards = Ward::where('status', 'approved')->get();
        $areas = Area::where('status', 'approved')->get();

        return view('staff.staff', compact('staff', 'roles', 'lgas', 'wards', 'areas'));
    }

    public function roles(Request $request)
    {
        $roles = Role::when($request->search_role, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->where('guard_name', 'staff')->paginate(10);

        $permissions = Permission::where('guard_name', 'staff')->get();

        return view('staff.roles', compact('roles', 'permissions'));
    }

    public function permissions(Request $request)
    {
        $permissions = Permission::when($request->search_permission, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->where('guard_name', 'staff')->paginate(10);

        return view('staff.permissions', compact('permissions'));
    }

    public function auditTrail(Request $request)
    {
        $audits = Audit::when($request->search_model, function ($query, $search) {
            return $query->where('auditable_type', 'like', "%{$search}%");
        })->when($request->event, function ($query, $event) {
            return $query->where('event', $event);
        })->when($request->search_user, function ($query, $search) {
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        })->when($request->date_from, function ($query, $date) {
            return $query->whereDate('created_at', '>=', $date);
        })->when($request->date_to, function ($query, $date) {
            return $query->whereDate('created_at', '<=', $date);
        })->with(['user'])->orderBy('created_at', 'desc')->paginate(10);

        return view('staff.audits.index', compact('audits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'password' => 'required|string|min:8|confirmed',
            'district' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'subzone' => 'required|string|max:255',
            'road' => 'required|string|max:255',
            'succ' => 'required|string|max:255',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
            'roles' => 'array|exists:roles,name'
        ]);

        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'district' => $request->district,
            'zone' => $request->zone,
            'subzone' => $request->subzone,
            'road' => $request->road,
            'succ' => $request->succ,
            'lga_id' => $request->lga_id,
            'ward_id' => $request->ward_id,
            'area_id' => $request->area_id,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        if ($request->roles) {
            $staff->syncRoles($request->roles);
        }

        $staff->logAuditEvent('created');

        return redirect()->route('staff.staff.index')->with('success', 'Staff creation request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('staff')->ignore($staff->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'district' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'subzone' => 'required|string|max:255',
            'road' => 'required|string|max:255',
            'succ' => 'required|string|max:255',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
            'roles' => 'array|exists:roles,name'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'district' => $request->district,
            'zone' => $request->zone,
            'subzone' => $request->subzone,
            'road' => $request->road,
            'succ' => $request->succ,
            'lga_id' => $request->lga_id,
            'ward_id' => $request->ward_id,
            'area_id' => $request->area_id,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        if ($request->roles) {
            $staff->syncRoles($request->roles);
        } else {
            $staff->syncRoles([]);
        }

        $staff->logAuditEvent('updated');

        return redirect()->route('staff.staff.index')->with('success', 'Staff update request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function destroy(Staff $staff)
    {
        $staff->update(['status' => 'pending_delete']);
        $staff->logAuditEvent('delete_requested');

        return redirect()->route('staff.staff.index')->with('success', 'Staff deletion request submitted for approval.');
    }

    public function approve(Staff $staff)
    {
        $this->authorize('approve-staff');

        if ($staff->status === 'pending_delete') {
            $staff->logAuditEvent('deleted');
            $staff->delete();
        } else {
            $staff->update(['status' => 'approved']);
            $staff->logAuditEvent('approved');
        }

        return redirect()->route('staff.staff.index')->with('success', 'Staff request approved.');
    }

    public function reject(Staff $staff)
    {
        $this->authorize('reject-staff');

        $staff->update(['status' => 'rejected']);
        return redirect()->route('staff.staff.index')->with('error', 'Staff request rejected.');
    }

    public function assignRoles(Request $request, Staff $staff)
    {
        $request->validate([
            'roles' => 'required|array|exists:roles,name'
        ]);

        $staff->syncRoles($request->roles);
        $staff->update(['status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending']);
        $staff->logAuditEvent('roles_assigned', ['roles' => implode(', ', $request->roles)]);

        return redirect()->route('staff.staff.index')->with('success', 'Role assignment request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function removeRoles(Request $request, Staff $staff)
    {
        $request->validate([
            'roles' => 'required|array|exists:roles,name'
        ]);

        $staff->removeRole(...$request->roles);
        $staff->update(['status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending']);
        $staff->logAuditEvent('roles_removed', ['roles' => implode(', ', $request->roles)]);

        return redirect()->route('staff.staff.index')->with('success', 'Role removal request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function assignLocations(Request $request, Staff $staff)
    {
        $request->validate([
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        // Ensure at least one location is provided
        if (!$request->lga_id && !$request->ward_id && !$request->area_id) {
            return redirect()->route('staff.staff.index')
                ->with('error', 'At least one location (LGA, Ward, or Area) must be assigned.');
        }

        $data = [
            'lga_id' => $request->lga_id,
            'ward_id' => $request->ward_id,
            'area_id' => $request->area_id,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ];

        $staff->update($data);
        $staff->logAuditEvent('locations_assigned', [
            'lga_id' => $request->lga_id,
            'ward_id' => $request->ward_id,
            'area_id' => $request->area_id
        ]);

        return redirect()->route('staff.staff.index')
            ->with('success', 'Location assignment request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array|exists:permissions,name'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'staff',
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('staff.roles.index')->with('success', 'Role creation request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'permissions' => 'array|exists:permissions,name'
        ]);

        $role->update([
            'name' => $request->name,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('staff.roles.index')->with('success', 'Role update request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function destroyRole(Role $role)
    {
        $role->update(['status' => 'pending_delete']);
        $role->logAuditEvent('delete_requested');

        return redirect()->route('staff.roles.index')->with('success', 'Role deletion request submitted for approval.');
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'staff',
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        return redirect()->route('staff.permissions.index')->with('success', 'Permission creation request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)]
        ]);

        $permission->update([
            'name' => $request->name,
            'status' => Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved' : 'pending'
        ]);

        return redirect()->route('staff.permissions.index')->with('success', 'Permission update request ' . (Auth::guard('staff')->user()->hasRole('super-admin') ? 'approved.' : 'submitted for approval.'));
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->update(['status' => 'pending_delete']);
        $permission->logAuditEvent('delete_requested');

        return redirect()->route('staff.permissions.index')->with('success', 'Permission deletion request submitted for approval.');
    }
}