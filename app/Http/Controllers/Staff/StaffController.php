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
        $this->middleware(['auth:staff', 'role:super-admin|manager'])->only(['assignRoles', 'removeRoles', 'assignLocations']);
    }

    public function dashboard()
    {
        // Get staff statistics
        $totalStaff = Staff::count();
        $activeStaff = Staff::where('status', 'approved')->count();
        $pendingChanges = Staff::where('status', 'pending')->count();
        
        // Get customer statistics
        $totalCustomers = \App\Models\Customer::count();
        $activeCustomers = \App\Models\Customer::where('status', 'approved')->count();
        $pendingCustomerChanges = \App\Models\Customer::where('status', 'pending')->count();
        
        // Get billing statistics
        $totalBills = \App\Models\Bill::count();
        $unpaidBills = \App\Models\Bill::where('status', 'unpaid')->count();
        $paidBills = \App\Models\Bill::where('status', 'paid')->count();
        
        // Get payment statistics
        $totalPayments = \App\Models\Payment::count();
        $successfulPayments = \App\Models\Payment::where('status', 'successful')->count();
        $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();
        
        // Get complaint statistics
        $totalComplaints = \App\Models\Complaint::count();
        $openComplaints = \App\Models\Complaint::where('status', 'open')->count();
        $resolvedComplaints = \App\Models\Complaint::where('status', 'resolved')->count();
        
        // Get recent role assignments (last 5)
        $recentRoleAssignments = \App\Models\Audit::where('event', 'roles_assigned')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get recent HR updates (last 5)
        $recentHrUpdates = \App\Models\Audit::whereIn('event', ['created', 'updated', 'deleted'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get recent customer activities (last 5)
        $recentCustomerActivities = \App\Models\Audit::where('auditable_type', 'App\Models\Customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get recent billing activities (last 5)
        $recentBillingActivities = \App\Models\Audit::where('auditable_type', 'App\Models\Bill')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact(
            'totalStaff', 
            'activeStaff', 
            'pendingChanges', 
            'totalCustomers',
            'activeCustomers',
            'pendingCustomerChanges',
            'totalBills',
            'unpaidBills',
            'paidBills',
            'totalPayments',
            'successfulPayments',
            'pendingPayments',
            'totalComplaints',
            'openComplaints',
            'resolvedComplaints',
            'recentRoleAssignments', 
            'recentHrUpdates',
            'recentCustomerActivities',
            'recentBillingActivities'
        ));
    }

    public function staff(Request $request)
    {
        // Get stats for the staff roles view
        $stats = [
            'total' => Staff::count(),
            'active' => Staff::where('status', 'approved')->count(),
            'on_leave' => Staff::where('status', 'on_leave')->count(),
            'pending' => Staff::where('status', 'pending')->count(),
        ];

        // Get data for the unified view
        $totalStaff = Staff::count();
        $activeRoles = Role::where('guard_name', 'staff')->count();
        $totalHrStaff = Staff::count(); // Same as totalStaff for now
        $totalDepartments = Staff::whereNotNull('department')->distinct('department')->count('department');
        $totalStaffCombined = $totalStaff;
        $activeStaffCombined = Staff::where('status', 'approved')->count();
        $pendingStaffCombined = Staff::where('status', 'pending')->count();
        $totalRolesCombined = $activeRoles;

        return view('staff.staff.index', compact(
            'stats',
            'totalStaff',
            'activeRoles',
            'totalHrStaff',
            'totalDepartments',
            'totalStaffCombined',
            'activeStaffCombined',
            'pendingStaffCombined',
            'totalRolesCombined'
        ));
    }
    
    public function staffRoles(Request $request)
    {
        $staff = Staff::when($request->search_staff, function ($query, $search) {
            return $query->where('first_name', 'like', "%{$search}%")
                         ->orWhere('surname', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })->with('roles')->paginate(10);

        $roles = Role::where('guard_name', 'staff')->get();
        
        // Get stats
        $stats = [
            'total' => Staff::count(),
            'active' => Staff::where('status', 'approved')->count(),
            'on_leave' => Staff::where('status', 'on_leave')->count(),
            'pending' => Staff::where('status', 'pending')->count(),
        ];

        return view('staff.staff.roles', compact('staff', 'roles', 'stats'));
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