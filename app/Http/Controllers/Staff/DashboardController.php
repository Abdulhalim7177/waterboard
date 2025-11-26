<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\GlpiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $glpiService;

    public function __construct(GlpiService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    public function index()
    {
        $allTickets = $this->glpiService->getAllTickets();
        $statusMappings = $this->glpiService->getStatusMappings();

        $ticketsByStatus = array_fill_keys(array_keys($statusMappings), 0);
        $totalTickets = 0;

        if(!empty($allTickets)) {
            $totalTickets = count($allTickets);
            foreach ($allTickets as $ticket) {
                if (isset($ticket['status']) && isset($ticketsByStatus[$ticket['status']])) {
                    $ticketsByStatus[$ticket['status']]++;
                }
            }
        }

        // TODO: Fetch and process SLA data from GLPI.
        // This will likely involve a new method in GlpiService to get SLA information for tickets.
        // The data will then be passed to the view to be displayed in the SLA Status card.

        return view('staff.dashboard.index', compact('ticketsByStatus', 'statusMappings', 'totalTickets'));
    }
}
