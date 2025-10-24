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

        $glpiTicket = $this->glpiService->createTicket($request->title, $request->description, $options);

        if (isset($glpiTicket['id'])) {
            Ticket::create([
                'glpi_ticket_id' => $glpiTicket['id'],
                'customer_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'open', // You might want to get the status from GLPI
                'priority' => $request->priority,
                'urgency' => $request->urgency,
            ]);

            return redirect()->route('customer.tickets.index')->with('success', 'Ticket created successfully.');
        }

        return back()->with('error', 'Failed to create ticket in GLPI.');
    }

    public function show(Ticket $ticket)
    {
        return view('customer.tickets.show', compact('ticket'));
    }
}
