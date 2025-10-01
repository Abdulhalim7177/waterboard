<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GLPIService
{
    protected $apiUrl;
    protected $username;
    protected $password;
    protected $apiToken;
    protected $sessionToken;

    public function __construct()
    {
        $this->apiUrl = config('services.glpi.api_url');
        $this->username = config('services.glpi.username');
        $this->password = config('services.glpi.password');
        $this->apiToken = config('services.glpi.api_token');
    }

    /**
     * Initialize GLPI session
     */
    public function initSession()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/initSession', [
                'user_token' => $this->apiToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->sessionToken = $data['session_token'];
                return $this->sessionToken;
            } else {
                Log::error('GLPI initSession failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GLPI initSession exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Alternative login method using user credentials
     */
    public function login()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/initSession', [
                'login_name' => $this->username,
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->sessionToken = $data['session_token'];
                return $this->sessionToken;
            } else {
                Log::error('GLPI login failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GLPI login exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new ticket/complaint in GLPI
     */
    public function createTicket($ticketData)
    {
        if (!$this->sessionToken && !$this->initSession()) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Session-Token' => $this->sessionToken,
            ])->post($this->apiUrl . '/Ticket', $ticketData);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('GLPI createTicket failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GLPI createTicket exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a specific ticket by ID
     */
    public function getTicket($ticketId)
    {
        if (!$this->sessionToken && !$this->initSession()) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Session-Token' => $this->sessionToken,
            ])->get($this->apiUrl . '/Ticket/' . $ticketId);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('GLPI getTicket failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GLPI getTicket exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get list of tickets with optional filters
     */
    public function getTickets($params = [])
    {
        if (!$this->sessionToken && !$this->initSession()) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Session-Token' => $this->sessionToken,
            ])->get($this->apiUrl . '/search/Ticket', $params);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('GLPI getTickets failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GLPI getTickets exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a ticket
     */
    public function updateTicket($ticketId, $ticketData)
    {
        if (!$this->sessionToken && !$this->initSession()) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Session-Token' => $this->sessionToken,
            ])->put($this->apiUrl . '/Ticket/' . $ticketId, $ticketData);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('GLPI updateTicket failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GLPI updateTicket exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Logout from GLPI session
     */
    public function logout()
    {
        if (!$this->sessionToken) {
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Session-Token' => $this->sessionToken,
            ])->post($this->apiUrl . '/killSession');

            $this->sessionToken = null;
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('GLPI logout exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the current session token
     */
    public function getSessionToken()
    {
        return $this->sessionToken;
    }
}