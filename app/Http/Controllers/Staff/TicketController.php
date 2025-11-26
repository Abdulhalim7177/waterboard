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
        $query = Ticket::with('customer', 'staff', 'paypoint', 'zone', 'district');

        if ($user->hasRole('super-admin')) {
            // No additional filtering needed for super-admin
        } elseif ($user->hasRole('manager')) {
            if ($user->zone_id) {
                $query->where('zone_id', $user->zone_id);
            } elseif ($user->district_id) {
                $query->where('district_id', $user->district_id);
            } elseif ($user->paypoint_id) {
                $query->where('paypoint_id', $user->paypoint_id);
            }
        } elseif ($user->hasRole('staff')) {
            $query->where('staff_id', $user->id);
        } else {
            abort(403, 'Unauthorized access to tickets');
        }

        $tickets = $query->get();

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
        $this->authorize('view', $ticket);

        // Refresh ticket data from GLPI
        $this->refreshTicketFromGlpi($ticket);

        // Fetch followups from GLPI
        $followups = $this->glpiService->getFollowups($ticket->glpi_ticket_id);

        // Data for assignment dropdowns
        $assignable = [
            'zones' => collect(),
            'districts' => collect(),
            'paypoints' => collect(),
            'staff' => collect(),
        ];

        if (Gate::allows('assign', $ticket)) {
            if ($user->hasRole('super-admin')) {
                $assignable['zones'] = \App\Models\Zone::all();
                $assignable['districts'] = \App\Models\District::all();
                $assignable['paypoints'] = \App\Models\Paypoint::all();
                $assignable['staff'] = \App\Models\Staff::whereDoesntHave('roles', fn($q) => $q->where('name', 'super-admin'))->get();
            } elseif ($user->hasRole('manager')) {
                if ($user->zone_id) {
                    $assignable['districts'] = \App\Models\District::where('zone_id', $user->zone_id)->get();
                    $assignable['paypoints'] = \App\Models\Paypoint::whereIn('district_id', $assignable['districts']->pluck('id'))->get();
                } elseif ($user->district_id) {
                    $assignable['paypoints'] = \App\Models\Paypoint::where('district_id', $user->district_id)->get();
                }

                if ($user->paypoint_id) {
                    $assignable['staff'] = \App\Models\Staff::where('paypoint_id', $user->paypoint_id)
                        ->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['super-admin', 'manager']))
                        ->get();
                }
            }
        }

        return view('staff.tickets.show', compact('ticket', 'followups', 'assignable'));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $request->validate([
            'zone_id' => 'nullable|exists:zones,id',
            'district_id' => 'nullable|exists:districts,id',
            'paypoint_id' => 'nullable|exists:paypoints,id',
            'staff_id' => 'nullable|exists:staff,id',
        ]);

        $data = [];
        if ($request->filled('zone_id')) {
            $data['zone_id'] = $request->zone_id;
            $data['district_id'] = null;
            $data['paypoint_id'] = null;
            $data['staff_id'] = null;
        } elseif ($request->filled('district_id')) {
            $data['district_id'] = $request->district_id;
            $data['paypoint_id'] = null;
            $data['staff_id'] = null;
        } elseif ($request->filled('paypoint_id')) {
            $data['paypoint_id'] = $request->paypoint_id;
            $data['staff_id'] = null;
        } elseif ($request->filled('staff_id')) {
            $data['staff_id'] = $request->staff_id;
        }

        $ticket->update($data);

        if ($request->filled('staff_id')) {
            $staff = Staff::find($request->staff_id);
            if ($staff && $staff->email) {
                $glpiUserId = $this->glpiService->getGlpiUserIdByEmail($staff->email);
                if ($glpiUserId) {
                    $this->glpiService->assignTicket($ticket->glpi_ticket_id, $glpiUserId);
                }
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

    public function create()
    {
        if (!Gate::allows('create', Ticket::class)) {
            abort(403, 'Unauthorized to create a ticket');
        }

        $customers = \App\Models\Customer::all();
        $urgencyMappings = $this->glpiService->getUrgencyMappings();
        $priorityMappings = $this->glpiService->getPriorityMappings();

        return view('staff.tickets.create', compact('customers', 'urgencyMappings', 'priorityMappings'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('create', Ticket::class)) {
            abort(403, 'Unauthorized to create a ticket');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'urgency' => 'required|integer',
            'priority' => 'required|integer',
        ]);

        $customer = \App\Models\Customer::find($request->customer_id);

        // Check if customer exists
        if (!$customer) {
            logger()->error('Customer not found', ['customer_id' => $request->customer_id]);
            return redirect()->back()->with('error', 'Customer not found.');
        }

        // Try to get the GLPI user ID for the customer
        $glpiUserId = $this->glpiService->getGlpiUserIdByEmail($customer->email);
        logger()->info('GLPI user lookup result', ['email' => $customer->email, 'glpi_user_id' => $glpiUserId]);

        $ticketData = [
            'name' => $request->title,
            'content' => $request->description,
            'urgency' => $request->urgency,
            'priority' => $request->priority,
        ];

        // Add requester only if customer exists in GLPI
        if ($glpiUserId) {
            $ticketData['_users_id_requester'] = $glpiUserId;
        } else {
            // If customer doesn't exist in GLPI, use the staff member who's creating the ticket as the requester
            // This ensures ticket creation still works even if the customer isn't in GLPI
            $currentStaff = auth('staff')->user();
            $staffGlpiUserId = $this->glpiService->getGlpiUserIdByEmail($currentStaff->email);
            if ($staffGlpiUserId) {
                $ticketData['_users_id_requester'] = $staffGlpiUserId;
                logger()->info('Using staff as requester since customer not in GLPI', ['staff_email' => $currentStaff->email, 'staff_glpi_id' => $staffGlpiUserId]);
            }
        }

        $glpiTicket = $this->glpiService->createTicket($ticketData);

        logger()->info('Ticket creation result', ['glpi_ticket' => $glpiTicket]);

        if (!$glpiTicket || !isset($glpiTicket['id'])) {
            return redirect()->back()->with('error', 'Failed to create ticket in the support system. Please check logs for details.');
        }

        // Create the local ticket record linked to the customer (not the staff member creating it)
        $ticket = Ticket::create([
            'customer_id' => $customer->id,      // The actual customer the ticket is for
            'staff_id' => auth('staff')->id(),  // The staff member who created it
            'glpi_ticket_id' => $glpiTicket['id'],
            'title' => $request->title,
            'description' => $request->description,
            'status' => $glpiTicket['status'] ?? 1, // Default to 'New'
            'priority' => $request->priority,
            'urgency' => $request->urgency,
            // Add any other relevant fields that might be needed for ticket routing/permissions
        ]);

        return redirect()->route('staff.tickets.show', $ticket->id)->with('success', 'Ticket created successfully.');
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

        if ($glpiTicket) {
            $ticket->glpiTicket = (object)$glpiTicket;
            if (isset($glpiTicket['status'])) {
                $ticket->update(['status' => $glpiTicket['status']]);
            }

            // Fetch assigned user details
            if (!empty($glpiTicket['_users_id_assign'])) {
                $ticket->glpiTicket->assigned_user = $this->glpiService->getUser($glpiTicket['_users_id_assign']);
            }
        }
    }
}