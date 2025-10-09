<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\DolibarrService;

class Asset extends Model
{
    // This model will act as a service layer for Dolibarr assets only
    // It will not store data locally but interact directly with Dolibarr

    /**
     * Create an asset in Dolibarr
     */
    public static function createInDolibarr($data, DolibarrService $dolibarrService)
    {
        // Convert our asset data to Dolibarr format
        $assetData = [
            'ref' => $data['serial_number'] ?? 'ASSET-' . time(), // Use timestamp for unique ref
            'label' => $data['name'],
            'description' => $data['description'] ?? '',
            'type' => ($data['type'] ?? 'product') === 'service' ? 1 : 0,
            'price' => $data['purchase_price'] ?? 0,
            'price_ttc' => $data['purchase_price'] ?? 0,
            'tva_tx' => $data['tax_rate'] ?? 0,
            'weight' => $data['weight'] ?? 0,
            'weight_units' => $data['weight_units'] ?? 0,
            'length' => $data['length'] ?? 0,
            'width' => $data['width'] ?? 0,
            'height' => $data['height'] ?? 0,
            'length_units' => $data['length_units'] ?? 0,
            'surface' => $data['surface'] ?? 0,
            'surface_units' => $data['surface_units'] ?? 0,
            'volume' => $data['volume'] ?? 0,
            'volume_units' => $data['volume_units'] ?? 0,
            'stock_alert' => $data['stock_alert'] ?? 0,
        ];

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
        // Convert our asset data to Dolibarr format
        $assetData = [];
        
        if (isset($data['name'])) {
            $assetData['label'] = $data['name'];
        }
        if (isset($data['description'])) {
            $assetData['description'] = $data['description'];
        }
        if (isset($data['type'])) {
            $assetData['type'] = ($data['type'] === 'service') ? 1 : 0;
        }
        if (isset($data['purchase_price'])) {
            $assetData['price'] = $data['purchase_price'];
            $assetData['price_ttc'] = $data['purchase_price'];
        }
        if (isset($data['tax_rate'])) {
            $assetData['tva_tx'] = $data['tax_rate'];
        }
        if (isset($data['weight'])) {
            $assetData['weight'] = $data['weight'];
        }
        if (isset($data['weight_units'])) {
            $assetData['weight_units'] = $data['weight_units'];
        }
        if (isset($data['length'])) {
            $assetData['length'] = $data['length'];
        }
        if (isset($data['width'])) {
            $assetData['width'] = $data['width'];
        }
        if (isset($data['height'])) {
            $assetData['height'] = $data['height'];
        }
        if (isset($data['length_units'])) {
            $assetData['length_units'] = $data['length_units'];
        }
        if (isset($data['surface'])) {
            $assetData['surface'] = $data['surface'];
        }
        if (isset($data['surface_units'])) {
            $assetData['surface_units'] = $data['surface_units'];
        }
        if (isset($data['volume'])) {
            $assetData['volume'] = $data['volume'];
        }
        if (isset($data['volume_units'])) {
            $assetData['volume_units'] = $data['volume_units'];
        }
        if (isset($data['stock_alert'])) {
            $assetData['stock_alert'] = $data['stock_alert'];
        }

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