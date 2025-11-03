<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Staff;
use App\Services\GlpiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    protected $glpiService;

    public function __construct(GlpiService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    public function index()
    {
        $user = auth('staff')->user();
        
        // Super Admin can see all tickets
        if ($user->hasRole('super-admin')) {
            $tickets = Ticket::with('customer', 'staff', 'paypoint')->get();
        } 
        // Manager can see tickets within their assigned paypoint
        elseif ($user->hasRole('manager')) {
            $tickets = Ticket::where('paypoint_id', $user->paypoint_id)
                ->with('customer', 'staff', 'paypoint')
                ->get();
        } 
        // Staff can only see tickets assigned to them
        elseif ($user->hasRole('staff')) {
            $tickets = Ticket::where('staff_id', $user->id)
                ->with('customer', 'staff', 'paypoint')
                ->get();
        }
        else {
            abort(403, 'Unauthorized access to tickets');
        }

        // Refresh each ticket from GLPI. Note: This can be slow if there are many tickets.
        foreach ($tickets as $ticket) {
            $this->refreshTicketFromGlpi($ticket);
        }

        return view('staff.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $user = auth()->user();
        
        // Authorize access to this specific ticket
        if (!Gate::allows('view', $ticket)) {
            abort(403, 'Unauthorized access to this ticket');
        }

        // Refresh ticket data from GLPI
        $this->refreshTicketFromGlpi($ticket);

        // Only load staff and paypoints for users who can assign tickets
        $staff = collect();
        $paypoints = collect();
        
        if (Gate::allows('assign', $ticket)) {
            if ($user->hasRole('super-admin')) {
                $staff = \App\Models\Staff::all();
                $paypoints = \App\Models\Paypoint::all();
            } elseif ($user->hasRole('manager')) {
                $paypointQuery = \App\Models\Paypoint::query();
                if ($user->paypoint && $user->paypoint->zone_id) {
                    $paypointQuery->where('zone_id', $user->paypoint->zone_id);
                } elseif ($user->paypoint && $user->paypoint->district_id) {
                    $paypointQuery->where('district_id', $user->paypoint->district_id);
                }
                $paypointIds = $paypointQuery->pluck('id');

                $staff = \App\Models\Staff::whereIn('paypoint_id', $paypointIds)
                    ->whereDoesntHave('roles', function ($query) {
                        $query->where('name', 'super-admin');
                    })
                    ->get();
                
                // Get paypoints in the manager's area
                $paypoints = \App\Models\Paypoint::where(function($query) use ($user) {
                    if ($user->paypoint && $user->paypoint->zone_id) {
                        $query->where('zone_id', $user->paypoint->zone_id);
                    } elseif ($user->paypoint && $user->paypoint->district_id) {
                        $query->where('district_id', $user->paypoint->district_id);
                    }
                })->get();
            }
        }

        return view('staff.tickets.show', compact('ticket', 'staff', 'paypoints'));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $user = auth()->user();
        
        // Authorize assigning this ticket
        if (!Gate::allows('assign', $ticket)) {
            abort(403, 'Unauthorized to assign this ticket');
        }

        $staff = Staff::find($request->staff_id);
        if (!$staff) {
            return redirect()->back()->with('error', 'Selected staff not found.');
        }

        // Manager can only assign to regular staff
        if ($user->hasRole('manager') && $staff->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'Managers can only assign tickets to regular staff.');
        }



        // Update the local ticket assignment first
        $ticket->update([
            'staff_id' => $request->staff_id,
            'paypoint_id' => $request->paypoint_id ?? $staff->paypoint_id,
        ]);

        // Now update the GLPI system if possible
        $glpiUserId = $this->glpiService->getGlpiUserIdByEmail($staff->email);

        if ($glpiUserId) {
            $success = $this->glpiService->assignTicket($ticket->glpi_ticket_id, $glpiUserId);
            
            if (!$success) {
                // If GLPI assignment fails, at least the local assignment was successful
                return redirect()->route('staff.tickets.show', $ticket->id)
                    ->with('warning', 'Ticket assigned locally successfully, but GLPI assignment failed.');
            }
        }

        return redirect()->route('staff.tickets.show', $ticket->id)->with('success', 'Ticket assigned successfully.');
    }

    public function addFollowup(Request $request, Ticket $ticket)
    {
        $user = auth()->user();
        
        // Authorize adding followup to this ticket
        if (!Gate::allows('view', $ticket)) {
            abort(403, 'Unauthorized to add followup to this ticket');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $success = $this->glpiService->addFollowup($ticket->glpi_ticket_id, $request->content);

        if ($success) {
            return redirect()->route('staff.tickets.show', $ticket->id)->with('success', 'Follow-up added successfully.');
        }

        return redirect()->back()->with('error', 'Failed to add follow-up in the support system.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = auth()->user();
        
        // Authorize updating status for this ticket
        if (!Gate::allows('update', $ticket)) {
            abort(403, 'Unauthorized to update status for this ticket');
        }

        $request->validate([
            'status' => 'required|integer',
        ]);

        $success = $this->glpiService->updateTicketStatus($ticket->glpi_ticket_id, $request->status);

        if ($success) {
            $ticket->update(['status' => $request->status]);
            return redirect()->route('staff.tickets.show', $ticket->id)->with('success', 'Ticket status updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update ticket status in the support system.');
    }

    public function myTickets()
    {
        $user = auth()->user();
        
        // For my tickets, only show tickets assigned to the current user
        $tickets = Ticket::where('staff_id', $user->id)
                        ->with('customer', 'staff', 'paypoint')
                        ->get();

        return view('staff.tickets.my-tickets', compact('tickets'));
    }
    
    public function obtainTicket(Ticket $ticket)
    {
        $user = auth()->user();
        
        // Authorize taking ownership of this ticket
        if (!Gate::allows('takeOwnership', $ticket)) {
            abort(403, 'Unauthorized to obtain this ticket');
        }

        // Assign the ticket to the current staff member
        $ticket->update([
            'staff_id' => $user->id,
        ]);

        return redirect()->route('staff.tickets.show', $ticket->id)->with('success', 'Ticket obtained successfully.');
    }

    private function refreshTicketFromGlpi(Ticket $ticket)
    {
        $glpiTicket = $this->glpiService->getTicket($ticket->glpi_ticket_id);

        if ($glpiTicket && isset($glpiTicket['status'])) {
            $ticket->update(['status' => $glpiTicket['status']]);
        }
    }
}
