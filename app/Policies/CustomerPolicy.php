<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
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
        
        return $staff->hasPermissionTo('view-customers');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Staff $staff, Customer $customer)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        // Check if the staff has permission to view customers
        if (!$staff->hasPermissionTo('view-customer')) {
            return false;
        }
        
        // Check if the customer belongs to a ward that the staff has access to based on their paypoint
        $accessibleWardIds = $staff->getAccessibleWardIds();
        
        // If the staff has no accessible wards (unassigned paypoint), they can't view any customers
        if (empty($accessibleWardIds)) {
            return false;
        }
        
        // Return true if the customer's ward is in the list of accessible wards
        return in_array($customer->ward_id, $accessibleWardIds);
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
        
        return $staff->hasPermissionTo('create-customer');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Staff $staff, Customer $customer)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        // Check if the staff has permission to edit customers
        if (!$staff->hasPermissionTo('edit-customer')) {
            return false;
        }
        
        // Check if the customer belongs to a ward that the staff has access to based on their paypoint
        $accessibleWardIds = $staff->getAccessibleWardIds();
        
        // If the staff has no accessible wards (unassigned paypoint), they can't edit any customers
        if (empty($accessibleWardIds)) {
            return false;
        }
        
        // Return true if the customer's ward is in the list of accessible wards
        return in_array($customer->ward_id, $accessibleWardIds);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Staff $staff, Customer $customer)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        // Check if the staff has permission to delete customers
        if (!$staff->hasPermissionTo('delete-customer')) {
            return false;
        }
        
        // Check if the customer belongs to a ward that the staff has access to based on their paypoint
        $accessibleWardIds = $staff->getAccessibleWardIds();
        
        // If the staff has no accessible wards (unassigned paypoint), they can't delete any customers
        if (empty($accessibleWardIds)) {
            return false;
        }
        
        // Return true if the customer's ward is in the list of accessible wards
        return in_array($customer->ward_id, $accessibleWardIds);
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(Staff $staff, Customer $customer = null)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        return $staff->hasPermissionTo('approve-customer');
    }

    /**
     * Determine whether the user can reject the model.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reject(Staff $staff, Customer $customer = null)
    {
        // For super-admin, allow all
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        return $staff->hasPermissionTo('reject-customer');
    }
}