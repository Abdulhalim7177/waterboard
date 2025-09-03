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
        
        return $staff->hasPermissionTo('view-customer');
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
        
        return $staff->hasPermissionTo('edit-customer');
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
        
        return $staff->hasPermissionTo('delete-customer');
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