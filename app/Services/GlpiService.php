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

    public function createTicket(string $title, string $content, array $options = [])
    {
        if (!$this->sessionToken) {
            $this->initSession();
        }

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

        try {
            $response = $this->client->withHeaders([
                'Session-Token' => $this->sessionToken,
            ])->post('Ticket', [
                'input' => $ticketData,
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                logger()->error('Failed to create ticket in GLPI', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error('Failed to create ticket in GLPI: ' . $e->getMessage());
        }

        return null;
    }

    public function getTicket(int $ticketId)
    {
        if (!$this->sessionToken) {
            $this->initSession();
        }

        if (empty($this->sessionToken)) {
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
        if (!$this->sessionToken) {
            $this->initSession();
        }

        if (empty($this->sessionToken)) {
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
        if (!$this->sessionToken) {
            $this->initSession();
        }

        if (empty($this->sessionToken)) {
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
        if (!$this->sessionToken) {
            $this->initSession();
        }

        if (empty($this->sessionToken)) {
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
        if (!$this->sessionToken) {
            $this->initSession();
        }

        if (empty($this->sessionToken)) {
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
}
