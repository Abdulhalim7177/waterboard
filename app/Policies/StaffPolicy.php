<?php

namespace App\Policies;

use App\Models\Staff;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
{
    use HandlesAuthorization;

    public function update(Staff $user, Staff $staff)
    {
        return $user->hasPermissionTo('edit-staff');
    }

    public function delete(Staff $user, Staff $staff)
    {
        return $user->hasPermissionTo('delete-staff');
    }
}
