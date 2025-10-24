<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Staff;
use App\Services\GlpiService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $glpiService;

    public function __construct(GlpiService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    public function index()
    {
        $tickets = Ticket::with('customer')->get();

        return view('staff.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $staff = \App\Models\Staff::all();
        $paypoints = \App\Models\Paypoint::all();

        return view('staff.tickets.show', compact('ticket', 'staff', 'paypoints'));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $staff = Staff::find($request->staff_id);
        if (!$staff) {
            return redirect()->back()->with('error', 'Selected staff not found.');
        }

        $glpiUserId = $this->glpiService->getGlpiUserIdByEmail($staff->email);

        if (!$glpiUserId) {
            return redirect()->back()->with('error', 'Could not find the selected staff member in the support system.');
        }

        $success = $this->glpiService->assignTicket($ticket->glpi_ticket_id, $glpiUserId);

        if ($success) {
            $ticket->update([
                'staff_id' => $request->staff_id,
                'paypoint_id' => $request->paypoint_id,
            ]);

            return redirect()->route('staff.tickets.show', $ticket->id)->with('success', 'Ticket assigned successfully.');
        }

        return redirect()->back()->with('error', 'Failed to assign ticket in the support system.');
    }

    public function addFollowup(Request $request, Ticket $ticket)
    {
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
        $tickets = Ticket::where('staff_id', auth()->id())->with('customer')->get();

        return view('staff.tickets.my-tickets', compact('tickets'));
    }
}
