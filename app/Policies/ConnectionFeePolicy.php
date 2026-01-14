<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\ConnectionFee;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConnectionFeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Staff $staff)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        return $staff->hasPermissionTo('view-connection-fees');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\ConnectionFee  $connectionFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Staff $staff, ConnectionFee $connectionFee)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        return $staff->hasPermissionTo('view-connection-fee');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Staff $staff)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        return $staff->hasPermissionTo('create-connection-fee');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\ConnectionFee  $connectionFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Staff $staff, ConnectionFee $connectionFee)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        return $staff->hasPermissionTo('edit-connection-fee');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\ConnectionFee  $connectionFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Staff $staff, ConnectionFee $connectionFee)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        return $staff->hasPermissionTo('delete-connection-fee');
    }
}