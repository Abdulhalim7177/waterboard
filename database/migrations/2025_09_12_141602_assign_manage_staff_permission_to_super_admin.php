<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Assign manage-staff permission to super-admin role
        $superAdminRole = Role::where('name', 'super-admin')->where('guard_name', 'staff')->first();
        $manageStaffPermission = Spatie\Permission\Models\Permission::where('name', 'manage-staff')->where('guard_name', 'staff')->first();
        
        if ($superAdminRole && $manageStaffPermission) {
            $superAdminRole->givePermissionTo($manageStaffPermission);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove manage-staff permission from super-admin role
        $superAdminRole = Role::where('name', 'super-admin')->where('guard_name', 'staff')->first();
        $manageStaffPermission = Spatie\Permission\Models\Permission::where('name', 'manage-staff')->where('guard_name', 'staff')->first();
        
        if ($superAdminRole && $manageStaffPermission) {
            $superAdminRole->revokePermissionTo($manageStaffPermission);
        }
    }
};