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
        // Map our asset fields to Dolibarr product fields
        $productData = [
            'ref' => $assetData['ref'] ?? 'AUTO',
            'label' => $assetData['label'] ?? $assetData['name'] ?? 'Asset',
            'description' => $assetData['description'] ?? '',
            'type' => $assetData['type'] ?? 0, // 0 for product, 1 for service
            'price' => $assetData['price'] ?? 0,
            'price_ttc' => $assetData['price_ttc'] ?? 0,
            'tva_tx' => $assetData['tax_rate'] ?? 0,
            'weight' => $assetData['weight'] ?? 0,
            'weight_units' => $assetData['weight_units'] ?? 0,
            'length' => $assetData['length'] ?? 0,
            'width' => $assetData['width'] ?? 0,
            'height' => $assetData['height'] ?? 0,
            'length_units' => $assetData['length_units'] ?? 0,
            'surface' => $assetData['surface'] ?? 0,
            'surface_units' => $assetData['surface_units'] ?? 0,
            'volume' => $assetData['volume'] ?? 0,
            'volume_units' => $assetData['volume_units'] ?? 0,
            'stock_alert' => $assetData['stock_alert'] ?? 0,
            'customdata' => $assetData['custom_data'] ?? [], // Custom fields if needed
        ];

        return $this->makeRequest('POST', '/products', $productData);
    }

    /**
     * Update an existing asset
     */
    public function updateAsset($id, $assetData)
    {
        $productData = [
            'label' => $assetData['label'] ?? $assetData['name'] ?? null,
            'description' => $assetData['description'] ?? null,
            'type' => $assetData['type'] ?? null,
            'price' => $assetData['price'] ?? null,
            'price_ttc' => $assetData['price_ttc'] ?? null,
            'tva_tx' => $assetData['tax_rate'] ?? null,
            'weight' => $assetData['weight'] ?? null,
            'weight_units' => $assetData['weight_units'] ?? null,
            'length' => $assetData['length'] ?? null,
            'width' => $assetData['width'] ?? null,
            'height' => $assetData['height'] ?? null,
            'length_units' => $assetData['length_units'] ?? null,
            'surface' => $assetData['surface'] ?? null,
            'surface_units' => $assetData['surface_units'] ?? null,
            'volume' => $assetData['volume'] ?? null,
            'volume_units' => $assetData['volume_units'] ?? null,
            'stock_alert' => $assetData['stock_alert'] ?? null,
            'customdata' => $assetData['custom_data'] ?? null,
        ];

        // Remove null values
        $productData = array_filter($productData, function($value) {
            return $value !== null;
        });

        return $this->makeRequest('PUT', "/products/{$id}", $productData);
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
}