<?php

namespace App\Models;

use App\Services\DolibarrService;

class Asset
{
    // This model will act as a service layer for Dolibarr assets only
    // It will not store data locally but interact directly with Dolibarr

    /**
     * Create an asset in Dolibarr
     */
    public static function createInDolibarr($data, DolibarrService $dolibarrService)
    {
        $statusMapping = [
            'active' => 1,
            'maintenance' => 1, // Assuming maintenance is also an active state in Dolibarr
            'retired' => 0,
            'damaged' => 0,
        ];

        $assetData = [
            'ref' => $data['serial_number'] ?? 'ASSET-' . time(),
            'label' => $data['name'],
            'description' => $data['description'] ?? '',
            'type' => isset($data['type']) ? (($data['type'] === 'service') ? 1 : 0) : 0,
            'price' => $data['purchase_price'] ?? 0,
            'price_ttc' => $data['purchase_price'] ?? 0,
            'date_purchase' => isset($data['purchase_date']) ? strtotime($data['purchase_date']) : null,
            'statut' => isset($data['status']) ? $statusMapping[$data['status']] : 1,
            'warehouse_id' => $data['warehouse_id'],
            'array_options' => [
                'options_model' => $data['model'] ?? null,
                'options_brand' => $data['brand'] ?? null,
                'options_location' => $data['location'] ?? null,
            ],
        ];

        // Remove null values
        $assetData = array_filter($assetData, function($value) {
            return $value !== null;
        });

        return $dolibarrService->createAsset($assetData);
    }

    /**
     * Get an asset from Dolibarr
     */
    public static function getFromDolibarr($id, DolibarrService $dolibarrService)
    {
        return $dolibarrService->getAsset($id);
    }

    /**
     * Get all assets from Dolibarr
     */
    public static function getAllFromDolibarr(DolibarrService $dolibarrService, $limit = 100, $page = 0)
    {
        return $dolibarrService->getAssets($limit, $page);
    }

    /**
     * Update an asset in Dolibarr
     */
    public static function updateInDolibarr($id, $data, DolibarrService $dolibarrService)
    {
        $statusMapping = [
            'active' => 1,
            'maintenance' => 1,
            'retired' => 0,
            'damaged' => 0,
        ];

        $assetData = [
            'label' => $data['name'],
            'description' => $data['description'] ?? null,
            'type' => isset($data['type']) ? (($data['type'] === 'service') ? 1 : 0) : null,
            'price' => $data['purchase_price'] ?? null,
            'price_ttc' => $data['purchase_price'] ?? null,
            'date_purchase' => isset($data['purchase_date']) ? strtotime($data['purchase_date']) : null,
            'statut' => isset($data['status']) ? $statusMapping[$data['status']] : null,
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'array_options' => [
                'options_model' => $data['model'] ?? null,
                'options_brand' => $data['brand'] ?? null,
                'options_location' => $data['location'] ?? null,
            ],
        ];

        // Remove null values
        $assetData = array_filter($assetData, function($value) {
            return $value !== null;
        });

        return $dolibarrService->updateAsset($id, $assetData);
    }

    /**
     * Delete an asset from Dolibarr
     */
    public static function deleteFromDolibarr($id, DolibarrService $dolibarrService)
    {
        return $dolibarrService->deleteAsset($id);
    }

    /**
     * Get asset stock from Dolibarr
     */
    public static function getStockFromDolibarr($id, DolibarrService $dolibarrService)
    {
        return $dolibarrService->getAssetStock($id);
    }

    /**
     * Update asset stock in Dolibarr
     */
    public static function updateStockInDolibarr($id, $newStock, DolibarrService $dolibarrService)
    {
        return $dolibarrService->updateAssetStock($id, $newStock);
    }
}