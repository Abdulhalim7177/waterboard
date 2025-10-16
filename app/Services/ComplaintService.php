<?php

namespace App\Services;

use App\Services\GLPIService;

class ComplaintService
{
    // This model will act as a service layer for GLPI tickets only
    // It will not store data locally but interact directly with GLPI

    // Static method to create a complaint in GLPI
    public static function createInGLPI($data, GLPIService $glpiService)
    {
        $ticketData = [
            'input' => [
                'name' => $data['subject'],
                'content' => $data['description'],
                'urgency' => self::mapPriorityToUrgency($data['priority'] ?? 'medium'),
                'entities_id' => 0, // Default entity
                'requesttypes_id' => 1, // Default request type
                'users_id_requester' => $data['customer_id'] ?? 0, // Link to customer in GLPI if applicable
            ]
        ];

        return $glpiService->createTicket($ticketData);
    }

    // Static method to get a complaint from GLPI
    public static function findInGLPI($ticketId, GLPIService $glpiService)
    {
        return $glpiService->getTicket($ticketId);
    }

    // Static method to get all complaints from GLPI for a customer
    public static function getAllForCustomer($customerId, GLPIService $glpiService, $params = [])
    {
        // Add customer-based filters to the params
        $defaultParams = [
            'criteria' => [
                [
                    'field' => 4, // Requester field in GLPI
                    'searchtype' => 'equals',
                    'value' => $customerId
                ]
            ],
            'sort' => 19, // Sort by date_mod by default
            'order' => 'DESC'
        ];
        
        return $glpiService->getTickets(array_merge_recursive($defaultParams, $params));
    }

    // Static method to get all complaints from GLPI (for staff)
    public static function getAllFromGLPI(GLPIService $glpiService, $params = [])
    {
        // Get all tickets without customer restrictions
        return $glpiService->getTickets($params);
    }

    // Static method to update complaint in GLPI
    public static function updateInGLPI($ticketId, $data, GLPIService $glpiService)
    {
        $ticketData = [
            'input' => [
                'id' => $ticketId,
            ]
        ];

        if (isset($data['subject'])) {
            $ticketData['input']['name'] = $data['subject'];
        }
        if (isset($data['description'])) {
            $ticketData['input']['content'] = $data['description'];
        }
        if (isset($data['status'])) {
            $ticketData['input']['status'] = self::mapStatusToGlpi($data['status']);
        }
        if (isset($data['priority'])) {
            $ticketData['input']['urgency'] = self::mapPriorityToUrgency($data['priority']);
        }

        return $glpiService->updateTicket($ticketId, $ticketData);
    }

    // Static method to assign complaint in GLPI
    public static function assignInGLPI($ticketId, $staffId, GLPIService $glpiService)
    {
        $ticketData = [
            'input' => [
                'id' => $ticketId,
                'users_id_assign' => $staffId,
                'status' => 2, // In progress
            ]
        ];

        return $glpiService->updateTicket($ticketId, $ticketData);
    }

    private static function mapPriorityToUrgency($priority)
    {
        $urgencyMap = [
            'low' => 1,
            'medium' => 3,
            'high' => 4,
            'urgent' => 5,
        ];

        return $urgencyMap[$priority] ?? 3; // Default to medium (3)
    }

    private static function mapStatusToGlpi($status)
    {
        $statusMap = [
            'open' => 1,        // New in GLPI
            'in_progress' => 2, // Assigned/In Progress in GLPI
            'resolved' => 5,    // Resolved in GLPI
            'closed' => 6,      // Closed in GLPI
        ];

        return $statusMap[$status] ?? 1; // Default to "New" status
    }
}
