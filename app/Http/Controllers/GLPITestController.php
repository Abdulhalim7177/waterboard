<?php

namespace App\Http\Controllers;

use App\Services\GLPIService;
use Illuminate\Http\Request;

class GLPITestController extends Controller
{
    protected $glpiService;

    public function __construct(GLPIService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    public function testConnection()
    {
        // Try to initialize GLPI session
        $sessionToken = $this->glpiService->initSession();
        
        if ($sessionToken) {
            return response()->json([
                'success' => true,
                'message' => 'GLPI connection successful',
                'session_token' => $sessionToken
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'GLPI connection failed'
            ], 500);
        }
    }
    
    public function testTicketCreation()
    {
        $ticketData = [
            'input' => [
                'name' => 'Test Ticket from Water Board System',
                'content' => 'This is a test ticket created from the Water Board management system.',
                'urgency' => 3, // Medium urgency
                'entities_id' => 0,
                'requesttypes_id' => 1,
            ]
        ];

        $result = $this->glpiService->createTicket($ticketData);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully',
                'ticket' => $result
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket'
            ], 500);
        }
    }
}