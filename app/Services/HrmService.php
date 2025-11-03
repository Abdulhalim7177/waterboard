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
        return '1|pTrilD8VjIjp0kKo9eNx8ISemuX1oeaPGPUltQEG28de0003';
    }

    public function getEmployees(array $filters = [])
    {
        Log::info('Fetching employees from HRM API', ['filters' => $filters]);

        $response = Http::withoutVerifying()->withToken($this->token)
            ->get($this->baseUrl . '/api/employees', $filters);

        if ($response->successful()) {
            Log::info('Successfully fetched employees from HRM API');
            return $response->json();
        }

        Log::error('Failed to fetch employees from HRM API', ['response' => $response->body()]);
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
}
