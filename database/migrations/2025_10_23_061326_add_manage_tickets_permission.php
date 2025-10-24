<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::findByName('manage-tickets', 'staff')->delete();
    }
};