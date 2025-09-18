<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the manage-staff permission if it doesn't exist
        if (!Permission::where('name', 'manage-staff')->where('guard_name', 'staff')->exists()) {
            Permission::create([
                'name' => 'manage-staff',
                'guard_name' => 'staff',
                'status' => 'approved'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the manage-staff permission if it exists
        $permission = Permission::where('name', 'manage-staff')->where('guard_name', 'staff')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};