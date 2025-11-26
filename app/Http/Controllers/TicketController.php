<?php

namespace App\Http\Controllers;

use App\Services\GlpiService;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    protected $glpiService;

    public function __construct(GlpiService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    public function index()
    {
        $tickets = Ticket::where('customer_id', auth()->id())->get();

        return view('customer.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('customer.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|integer',
            'urgency' => 'required|integer',
        ]);

        $options = [
            'priority' => $request->priority,
            'urgency' => $request->urgency,
        ];

        // Log the ticket creation attempt
        logger()->info('Creating customer ticket in GLPI', [
            'title' => $request->title,
            'description' => $request->description,
            'options' => $options
        ]);

        $glpiTicket = $this->glpiService->createTicket($request->title, $request->description, $options);

        logger()->info('Customer ticket creation result', ['glpi_ticket' => $glpiTicket]);

        if (isset($glpiTicket['id'])) {
            $customer = auth()->user()->load('ward.district');
            $paypointId = null;

            if ($customer && $customer->ward && $customer->ward->district) {
                $districtId = $customer->ward->district->id;
                $paypoint = \App\Models\Paypoint::where('district_id', $districtId)->first();
                if ($paypoint) {
                    $paypointId = $paypoint->id;
                }
            }

            Ticket::create([
                'glpi_ticket_id' => $glpiTicket['id'],
                'customer_id' => auth()->id(),
                'staff_id' => null, // Customer-created tickets don't have a staff creator initially
                'title' => $request->title,
                'description' => $request->description,
                'status' => $glpiTicket['status'] ?? 1, // Use GLPI status instead of hardcoded 'open'
                'priority' => $request->priority,
                'urgency' => $request->urgency,
                'paypoint_id' => $paypointId,
                'zone_id' => $customer?->ward?->district?->zone_id, // Link to zone through customer's location
                'district_id' => $customer?->ward?->district_id, // Link to district through customer's location
            ]);

            return redirect()->route('customer.tickets.index')->with('success', 'Ticket created successfully.');
        }

        return back()->with('error', 'Failed to create ticket in GLPI. Please check logs for details.');
    }

    public function show(Ticket $ticket)
    {
        // Ensure the customer can only view their own tickets
        if ($ticket->customer_id != auth()->id()) {
            abort(403, 'Unauthorized to view this ticket');
        }
        
        $followups = $this->glpiService->getFollowups($ticket->glpi_ticket_id);
        
        return view('customer.tickets.show', compact('ticket', 'followups'));
    }
}
