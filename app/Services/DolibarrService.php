<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DolibarrService
{
    protected $apiUrl;
    protected $username;
    protected $password;
    protected $apiKey;
    protected $sessionToken;

    public function __construct()
    {
        $this->apiUrl = config('services.dolibarr.api_url');
        $this->username = config('services.dolibarr.username');
        $this->password = config('services.dolibarr.password');
        $this->apiKey = config('services.dolibarr.api_key');
    }

    /**
     * Login to Dolibarr API to get session token
     */
    public function login()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/login', [
                'login' => $this->username,
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success']) {
                    $this->sessionToken = $data['token'] ?? null;
                    return $this->sessionToken;
                }
            }

            Log::error('Dolibarr login failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Dolibarr login exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Alternative authentication using API key
     */
    public function authenticateWithApiKey()
    {
        // For API key authentication, we'll use it directly in headers
        return $this->apiKey;
    }

    /**
     * Make an API request to Dolibarr
     */
    protected function makeRequest($method, $endpoint, $data = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        // Try to authenticate with API key first
        $apiKey = $this->authenticateWithApiKey();
        if ($apiKey) {
            $headers['DOLAPIKEY'] = $apiKey;
        } else {
            // Fallback to session token
            if (!$this->sessionToken && !$this->login()) {
                return false;
            }
            $headers['Authorization'] = 'Bearer ' . $this->sessionToken;
        }

        try {
            $url = $this->apiUrl . $endpoint;
            
            $response = Http::withHeaders($headers);

            if ($method === 'GET') {
                $response = $response->get($url, $data);
            } elseif ($method === 'POST') {
                $response = $response->post($url, $data);
            } elseif ($method === 'PUT') {
                $response = $response->put($url, $data);
            } elseif ($method === 'DELETE') {
                $response = $response->delete($url);
            }

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error("Dolibarr API request failed: {$method} {$endpoint} - " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Dolibarr API request exception: {$method} {$endpoint} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all assets (products in Dolibarr)
     */
    public function getAssets($limit = 100, $page = 0)
    {
        $params = [
            'limit' => $limit,
            'page' => $page,
            'sortfield' => 't.rowid',
            'sortorder' => 'ASC'
        ];
        
        return $this->makeRequest('GET', '/products', $params);
    }

    /**
     * Get a specific asset by ID
     */
    public function getAsset($id)
    {
        return $this->makeRequest('GET', "/products/{$id}");
    }

    /**
     * Create a new asset (product in Dolibarr)
     */
    public function createAsset($assetData)
    {
        return $this->makeRequest('POST', '/products', $assetData);
    }

    /**
     * Update an existing asset
     */
    public function updateAsset($id, $assetData)
    {
        return $this->makeRequest('PUT', "/products/{$id}", $assetData);
    }

    /**
     * Delete an asset
     */
    public function deleteAsset($id)
    {
        return $this->makeRequest('DELETE', "/products/{$id}");
    }

    /**
     * Get assets by category
     */
    public function getAssetsByCategory($categoryId, $limit = 100, $page = 0)
    {
        $params = [
            'limit' => $limit,
            'page' => $page,
            'category' => $categoryId
        ];
        
        return $this->makeRequest('GET', '/products', $params);
    }

    /**
     * Get asset stock information
     */
    public function getAssetStock($id)
    {
        return $this->makeRequest('GET', "/products/{$id}/stock");
    }

    /**
     * Update asset stock
     */
    public function updateAssetStock($id, $newStock)
    {
        $stockData = [
            'warehouse_id' => 1, // Default warehouse, can be parameterized
            'qty' => $newStock,
            'reason' => 'Stock update from waterboard system'
        ];
        
        return $this->makeRequest('POST', "/products/{$id}/stock/movement", $stockData);
    }

    /**
     * Get all warehouses
     */
    public function getWarehouses($limit = 100, $page = 0)
    {
        $params = [
            'limit' => $limit,
            'page' => $page,
        ];
        
        return $this->makeRequest('GET', '/warehouses', $params);
    }

    /**
     * Get a specific warehouse by ID
     */
    public function getWarehouse($id)
    {
        return $this->makeRequest('GET', "/warehouses/{$id}");
    }

    /**
     * Create a new warehouse
     */
    public function createWarehouse($warehouseData)
    {
        return $this->makeRequest('POST', '/warehouses', $warehouseData);
    }

    /**
     * Update an existing warehouse
     */
    public function updateWarehouse($id, $warehouseData)
    {
        return $this->makeRequest('PUT', "/warehouses/{$id}", $warehouseData);
    }

    /**
     * Delete a warehouse
     */
    public function deleteWarehouse($id)
    {
        return $this->makeRequest('DELETE', "/warehouses/{$id}");
    }

    public function getCategoryByName($name)
    {
        $params = [
            'sqlfilters' => "(t.label:like:'%{$name}%')"
        ];
        
        $categories = $this->makeRequest('GET', '/categories', $params);
        
        if ($categories && count($categories) > 0) {
            return $categories[0]; // Assuming the first result is the correct one
        }
        
        return null;
    }

    public function linkProductToCategory($productId, $categoryId)
    {
        return $this->makeRequest('POST', "/categories/{$categoryId}/products/{$productId}");
    }
}