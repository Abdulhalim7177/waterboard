<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HrmService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.hrm.base_url');
        $this->token = $this->getToken();
    }

    protected function getToken()
    {
        return config('services.hrm.token');
    }

    public function getEmployees(array $filters = [])
    {
        Log::info('Fetching employees from HRM API', [
            'filters' => $filters,
            'base_url' => $this->baseUrl,
            'token' => $this->token ? 'Token Present' : 'Token Missing'
        ]);

        $response = Http::withoutVerifying()->withToken($this->token)
            ->get($this->baseUrl . '/api/employees', $filters);

        if ($response->successful()) {
            Log::info('Successfully fetched employees from HRM API');
            return $response->json();
        }

        Log::error('Failed to fetch employees from HRM API', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'response' => $response->body()
        ]);
        return null;
    }

    public function getEmployee($id)
    {
        $response = Http::withoutVerifying()->withToken($this->token)
            ->get($this->baseUrl . '/api/employees/' . $id);

        if ($response->successful()) {
            return $response->json();
        }

        // Handle errors appropriately
        return null;
    }

    public function createEmployee(array $data)
    {
        Log::info('Attempting to create employee in HRM API', [
            'base_url' => $this->baseUrl,
            'token' => $this->token ? 'Token Present' : 'Token Missing',
            'request_data' => $data,
            'api_endpoint' => $this->baseUrl . '/api/employees'
        ]);

        $response = Http::withoutVerifying()->withToken($this->token)
            ->post($this->baseUrl . '/api/employees', $data);

        Log::info('HRM API create employee response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'response_body' => $response->body()
        ]);

        if ($response->successful()) {
            Log::info('Successfully created employee in HRM API', [
                'response' => $response->json()
            ]);
            return $response->json();
        }

        Log::error('Failed to create employee in HRM API', [
            'status' => $response->status(),
            'request_data' => $data,
            'response_body' => $response->body(),
            'headers' => $response->headers()
        ]);
        
        return null;
    }

    public function updateEmployee($id, array $data)
    {
        $response = Http::withoutVerifying()->withToken($this->token)
            ->put($this->baseUrl . '/api/employees/' . $id, $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Failed to update employee in HRM API', ['response' => $response->body()]);
        return null;
    }
    
    public function deleteEmployee($id)
    {
        $response = Http::withoutVerifying()->withToken($this->token)
            ->delete($this->baseUrl . '/api/employees/' . $id);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Failed to delete employee in HRM API', ['response' => $response->body()]);
        return null;
    }
}
