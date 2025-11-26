<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the staff can view any tickets.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Staff $staff)
    {
        // Super Admin (Admin Staff) can view all tickets
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        // Manager can view tickets within their assigned areas
        if ($staff->hasRole('manager')) {
            // Managers can view tickets, but the controller should filter based on their assigned areas
            return true;
        }
        
        // Regular staff can view tickets assigned to them or their paypoint
        if ($staff->hasRole('staff')) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the staff can view a specific ticket.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Staff $staff, Ticket $ticket)
    {
        // Super Admin (Admin Staff) can view any ticket
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        // Manager can view tickets within their assigned hierarchy
        if ($staff->hasRole('manager')) {
            if ($staff->zone_id && $ticket->zone_id == $staff->zone_id) {
                return true;
            }
            if ($staff->district_id && $ticket->district_id == $staff->district_id) {
                return true;
            }
            if ($staff->paypoint_id && $ticket->paypoint_id == $staff->paypoint_id) {
                return true;
            }
        }
        
        // Staff can only view tickets assigned to them
        return $ticket->staff_id == $staff->id;
    }

    /**
     * Determine whether the staff can create tickets.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Staff $staff)
    {
        // For staff creating tickets on behalf of customers
        return $staff->hasRole(['super-admin', 'manager', 'staff']);
    }

    /**
     * Determine whether the staff can update a ticket.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Staff $staff, Ticket $ticket)
    {
        // Super Admin (Admin Staff) has full access to update any ticket
        if ($staff->hasRole('super-admin')) {
            return true;
        }

        // Manager can update tickets within their assigned hierarchy
        if ($staff->hasRole('manager')) {
            if ($staff->zone_id && $ticket->zone_id == $staff->zone_id) {
                return true;
            }
            if ($staff->district_id && $ticket->district_id == $staff->district_id) {
                return true;
            }
            if ($staff->paypoint_id && $ticket->paypoint_id == $staff->paypoint_id) {
                return true;
            }
        }

        // Staff can update tickets they can view (assigned to them or in their hierarchy)
        if ($staff->hasRole('staff')) {
            // Staff can update tickets assigned to them
            if ($ticket->staff_id == $staff->id) {
                return true;
            }

            // Staff can update tickets in their assigned area
            if ($staff->zone_id && $ticket->zone_id == $staff->zone_id) {
                return true;
            }
            if ($staff->district_id && $ticket->district_id == $staff->district_id) {
                return true;
            }
            if ($staff->paypoint_id && $ticket->paypoint_id == $staff->paypoint_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the staff can assign/reassign a ticket.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assign(Staff $staff, Ticket $ticket = null)
    {
        // Super Admin (Admin Staff) can assign/reassign at any level
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        // Manager can assign/reassign within their assigned hierarchy
        if ($staff->hasRole('manager')) {
            return true;
        }
        
        // Regular staff cannot assign tickets
        return false;
    }

    /**
     * Determine whether the staff can delete a ticket.
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Staff $staff, Ticket $ticket)
    {
        // Only Super Admin (Admin Staff) can delete tickets
        return $staff->hasRole('super-admin');
    }

    /**
     * Determine whether the staff can take ownership of a ticket (obtain ticket).
     *
     * @param  \App\Models\Staff  $staff
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function takeOwnership(Staff $staff, Ticket $ticket)
    {
        // Super Admin can always take ownership
        if ($staff->hasRole('super-admin')) {
            return true;
        }
        
        // Staff cannot take ownership of tickets
        if ($staff->hasRole('staff')) {
            return false;
        }

        // Manager can take ownership of tickets in their assigned paypoint
        if ($staff->hasRole('manager')) {
            return $ticket->paypoint_id && $ticket->paypoint_id == $staff->paypoint_id;
        }
        
        return false;
    }
}