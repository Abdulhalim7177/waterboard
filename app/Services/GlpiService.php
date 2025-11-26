<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GlpiService
{
    protected $client;
    protected $sessionToken;

    public function __construct()
    {
        $this->client = Http::withHeaders([
            'Content-Type' => 'application/json',
            'App-Token' => config('services.glpi.app_token'),
        ])->baseUrl(config('services.glpi.api_url'));
    }

    public function initSession(): string
    {
        try {
            $response = $this->client->get('initSession', [
                'user_token' => config('services.glpi.api_token'),
            ]);

            if ($response->successful()) {
                $this->sessionToken = $response->json('session_token');
                return $this->sessionToken ?? '';
            } else {
                logger()->error('GLPI session initialization failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            // Log the error
            logger()->error('GLPI session initialization failed: ' . $e->getMessage());
        }

        return '';
    }

    public function createTicket(string|array $title, string $content = '', array $options = [])
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to create ticket: Could not get valid GLPI session');
            return null;
        }

        if (is_array($title)) {
            // Handle array format (from staff ticket controller)
            $ticketData = $title;
        } else {
            // Handle string format (from customer ticket controller)
            $ticketData = [
                'name' => $title,
                'content' => $content,
            ];

            if (isset($options['itilcategories_id'])) {
                $ticketData['itilcategories_id'] = $options['itilcategories_id'];
            }

            if (isset($options['priority'])) {
                $ticketData['priority'] = $options['priority'];
            }

            if (isset($options['urgency'])) {
                $ticketData['urgency'] = $options['urgency'];
            }
        }

        // Log ticket data being sent to GLPI for debugging
        logger()->info('Creating ticket in GLPI', ['ticket_data' => $ticketData]);

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->post('Ticket', [
                'input' => $ticketData,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                logger()->info('Successfully created ticket in GLPI', ['result' => $result]);
                return $result;
            } else {
                logger()->error('Failed to create ticket in GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'ticket_data' => $ticketData,
                ]);
            }
        } catch (\Exception $e) {
            logger()->error('Failed to create ticket in GLPI: ' . $e->getMessage(), [
                'ticket_data' => $ticketData,
                'exception' => $e->getTraceAsString(),
            ]);
        }

        return null;
    }

    public function getTicket(int $ticketId)
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to get ticket: Could not get valid GLPI session');
            return null;
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->get("Ticket/{$ticketId}");

            if ($response->successful()) {
                return $response->json();
            } else {
                logger()->error('Failed to get ticket from GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to get ticket {$ticketId} from GLPI: " . $e->getMessage());
        }

        return null;
    }

    public function assignTicket(int $ticketId, int $glpiUserId): bool
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to assign ticket: Could not get valid GLPI session');
            return false;
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->patch("Ticket/{$ticketId}", [
                'input' => [
                    '_users_id_assign' => $glpiUserId,
                ],
            ]);

            if ($response->successful()) {
                return true;
            } else {
                logger()->error('Failed to assign ticket in GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to assign ticket {$ticketId} in GLPI: " . $e->getMessage());
        }

        return false;
    }

    public function updateTicketStatus(int $ticketId, int $status): bool
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to update ticket status: Could not get valid GLPI session');
            return false;
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->put("Ticket/{$ticketId}", [
                'input' => [
                    'status' => $status,
                ],
            ]);

            if ($response->successful()) {
                return true;
            } else {
                logger()->error('Failed to update ticket status in GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to update ticket status for ticket {$ticketId} in GLPI: " . $e->getMessage());
        }

        return false;
    }

    public function addFollowup(int $ticketId, string $content): bool
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to add followup: Could not get valid GLPI session');
            return false;
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->post('ITILFollowup', [
                'input' => [
                    'itemtype' => 'Ticket',
                    'items_id' => $ticketId,
                    'content' => $content,
                ],
            ]);

            if ($response->successful()) {
                return true;
            } else {
                logger()->error('Failed to add followup in GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to add followup to ticket {$ticketId} in GLPI: " . $e->getMessage());
        }

        return false;
    }

    public function getGlpiUserIdByEmail(string $email): ?int
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to get GLPI user ID by email: Could not get valid GLPI session');
            return null;
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->get('search/User', [
                'criteria[0][field]' => 5, // Email field
                'criteria[0][searchtype]' => 'equals',
                'criteria[0][value]' => $email,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'][0]['id'])) {
                    return $data['data'][0]['id'];
                }
            } else {
                logger()->error('Failed to get user from GLPI by email', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to get user {$email} from GLPI: " . $e->getMessage());
        }

        return null;
    }

    public function getFollowups(int $ticketId): array
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to get followups: Could not get valid GLPI session');
            return [];
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->get("Ticket/{$ticketId}/ITILFollowup");

            if ($response->successful()) {
                return $response->json();
            } else {
                logger()->error('Failed to get followups from GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to get followups for ticket {$ticketId} from GLPI: " . $e->getMessage());
        }

        return [];
    }

    public function getITILCategories()
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to get ITIL categories: Could not get valid GLPI session');
            return [];
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->get('ITILCategory');

            logger()->info('GLPI getITILCategories response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                logger()->error('Failed to get ITIL categories from GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to get ITIL categories from GLPI: " . $e->getMessage());
        }

        return [];
    }

    public function getAllTickets(): array
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to get all tickets: Could not get valid GLPI session');
            return [];
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->get('Ticket');

            if ($response->successful()) {
                return $response->json();
            } else {
                logger()->error('Failed to get all tickets from GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to get all tickets from GLPI: " . $e->getMessage());
        }

        return [];
    }

    public function getUrgencyMappings(): array
    {
        return [
            1 => 'Very Low',
            2 => 'Low',
            3 => 'Medium',
            4 => 'High',
            5 => 'Very High',
        ];
    }

    public function getPriorityMappings(): array
    {
        // These are default GLPI priority levels. Adjust if customized.
        return [
            1 => 'Very Low',
            2 => 'Low',
            3 => 'Medium',
            4 => 'High',
            5 => 'Very High',
            6 => 'Major',
        ];
    }

    public function getStatusMappings(): array
    {
        // These are default GLPI status levels. Adjust if customized.
        return [
            1 => 'New',
            2 => 'Processing (assigned)',
            3 => 'Processing (planned)',
            4 => 'Pending',
            5 => 'Solved',
            6 => 'Closed',
        ];
    }

    public function getUser(int $userId): ?array
    {
        // Check if we have a session token and verify it's still valid
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to get user: Could not get valid GLPI session');
            return null;
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->get("User/{$userId}");

            if ($response->successful()) {
                return $response->json();
            } else {
                logger()->error("Failed to get user {$userId} from GLPI", [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to get user {$userId} from GLPI: " . $e->getMessage());
        }

        return null;
    }

    public function createUser(array $userData): ?array
    {
        if (!$this->ensureValidSession()) {
            logger()->error('Failed to create user: Could not get valid GLPI session');
            return null;
        }

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->post('User', [
                'input' => $userData,
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                logger()->error('Failed to create user in GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'user_data' => $userData,
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to create user in GLPI: " . $e->getMessage());
        }

        return null;
    }

    private function ensureValidSession(): bool
    {
        // If no session token, initialize
        if (empty($this->sessionToken)) {
            $this->initSession();
            return !empty($this->sessionToken);
        }

        // Test if the current session token is still valid by making a simple API call
        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->get('getFullSession');

            if ($response->successful()) {
                $data = $response->json();
                // If we get a valid response, the session is still active
                if (isset($data['session'])) {
                    return true;
                }
            } else {
                $body = $response->body();
                // Check if response indicates session is invalid
                if (strpos($body, 'ERROR_SESSION_TOKEN_INVALID') !== false) {
                    logger()->info('Session token is invalid, reinitializing');
                    $this->initSession();
                    return !empty($this->sessionToken);
                }
            }
        } catch (\Exception $e) {
            logger()->info('Session validation failed, attempting reinitialization: ' . $e->getMessage());
            // If validation fails, try to reinitialize the session
            $this->initSession();
        }

        return !empty($this->sessionToken);
    }
}
