<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\GlpiService;
use Illuminate\Http\Request;

class RefreshTicketStatusController extends Controller
{
    protected $glpiService;

    public function __construct(GlpiService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    public function refresh(Ticket $ticket)
    {
        $glpiTicket = $this->glpiService->getTicket($ticket->glpi_ticket_id);

        if ($glpiTicket && isset($glpiTicket['status'])) {
            $ticket->update(['status' => $glpiTicket['status']]);
            return redirect()->route('customer.tickets.show', $ticket)->with('success', 'Ticket status has been refreshed.');
        }

        return redirect()->route('customer.tickets.show', $ticket)->with('error', 'Could not refresh ticket status from GLPI.');
    }
}
