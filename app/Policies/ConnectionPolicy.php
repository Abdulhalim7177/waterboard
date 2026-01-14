<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\CustomerConnection;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConnectionPolicy
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

        return $staff->hasPermissionTo('view-connections');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\CustomerConnection  $connection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Staff $staff, CustomerConnection $connection)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        // Check if the staff has permission to view connections
        if (!$staff->hasPermissionTo('view-connection')) {
            return false;
        }

        // Check if the connection belongs to a customer in a ward that the staff has access to based on their paypoint
        $accessibleWardIds = $staff->getAccessibleWardIds();

        // If the staff has no accessible wards (unassigned paypoint), they can't view any connections
        if (empty($accessibleWardIds)) {
            return false;
        }

        // Return true if the customer's ward is in the list of accessible wards
        return in_array($connection->customer->ward_id, $accessibleWardIds);
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

        return $staff->hasPermissionTo('create-connection');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\CustomerConnection  $connection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Staff $staff, CustomerConnection $connection)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        // Check if the staff has permission to edit connections
        if (!$staff->hasPermissionTo('edit-connection')) {
            return false;
        }

        // Check if the connection belongs to a customer in a ward that the staff has access to based on their paypoint
        $accessibleWardIds = $staff->getAccessibleWardIds();

        // If the staff has no accessible wards (unassigned paypoint), they can't edit any connections
        if (empty($accessibleWardIds)) {
            return false;
        }

        // Return true if the customer's ward is in the list of accessible wards
        return in_array($connection->customer->ward_id, $accessibleWardIds);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\CustomerConnection  $connection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Staff $staff, CustomerConnection $connection)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        // Check if the staff has permission to delete connections
        if (!$staff->hasPermissionTo('delete-connection')) {
            return false;
        }

        // Check if the connection belongs to a customer in a ward that the staff has access to based on their paypoint
        $accessibleWardIds = $staff->getAccessibleWardIds();

        // If the staff has no accessible wards (unassigned paypoint), they can't delete any connections
        if (empty($accessibleWardIds)) {
            return false;
        }

        // Return true if the customer's ward is in the list of accessible wards
        return in_array($connection->customer->ward_id, $accessibleWardIds);
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\CustomerConnection  $connection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(Staff $staff, CustomerConnection $connection = null)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        return $staff->hasPermissionTo('approve-connection');
    }

    /**
     * Determine whether the user can reject the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\CustomerConnection  $connection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reject(Staff $staff, CustomerConnection $connection = null)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        return $staff->hasPermissionTo('reject-connection');
    }
}