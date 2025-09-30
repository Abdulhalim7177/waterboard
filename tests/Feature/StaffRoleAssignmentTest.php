<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Staff;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class StaffRoleAssignmentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_assign_roles_to_staff()
    {
        // Create a staff member
        $staff = Staff::factory()->create();

        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'staff']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'staff']);

        // Assign roles to staff
        $staff->assignRole($adminRole, $managerRole);

        // Assert that staff has the roles
        $this->assertTrue($staff->hasRole('admin'));
        $this->assertTrue($staff->hasRole('manager'));
        $this->assertCount(2, $staff->roles);
    }

    /** @test */
    public function it_can_remove_roles_from_staff()
    {
        // Create a staff member
        $staff = Staff::factory()->create();

        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'staff']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'staff']);

        // Assign roles to staff
        $staff->assignRole($adminRole, $managerRole);

        // Remove one role
        $staff->removeRole($adminRole);

        // Assert that staff has only one role left
        $this->assertFalse($staff->hasRole('admin'));
        $this->assertTrue($staff->hasRole('manager'));
        $this->assertCount(1, $staff->roles);
    }

    /** @test */
    public function it_can_sync_roles_for_staff()
    {
        // Create a staff member
        $staff = Staff::factory()->create();

        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'staff']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'staff']);
        $supervisorRole = Role::create(['name' => 'supervisor', 'guard_name' => 'staff']);

        // Assign initial roles
        $staff->assignRole($adminRole, $managerRole);

        // Sync with new roles
        $staff->syncRoles([$managerRole, $supervisorRole]);

        // Assert that staff has the new roles only
        $this->assertFalse($staff->hasRole('admin'));
        $this->assertTrue($staff->hasRole('manager'));
        $this->assertTrue($staff->hasRole('supervisor'));
        $this->assertCount(2, $staff->roles);
    }
}