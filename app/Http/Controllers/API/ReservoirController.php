<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DolibarrService;
use Illuminate\Http\Request;

class ReservoirController extends Controller
{
    protected $dolibarrService;

    public function __construct(DolibarrService $dolibarrService)
    {
        $this->dolibarrService = $dolibarrService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservoirs = $this->dolibarrService->getAssets();

        if ($reservoirs === false) {
            return response()->json(['error' => 'Failed to fetch reservoirs from Dolibarr'], 500);
        }

        return response()->json($reservoirs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            // Add any other fields required by Dolibarr for creating a product
        ]);

        $reservoir = $this->dolibarrService->createAsset($data);

        if ($reservoir === false) {
            return response()->json(['error' => 'Failed to create reservoir in Dolibarr'], 500);
        }

        return response()->json($reservoir, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservoir = $this->dolibarrService->getAsset($id);

        if ($reservoir === false) {
            return response()->json(['error' => 'Reservoir not found in Dolibarr'], 404);
        }

        return response()->json($reservoir);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            // Add any other fields that can be updated
        ]);

        $reservoir = $this->dolibarrService->updateAsset($id, $data);

        if ($reservoir === false) {
            return response()->json(['error' => 'Failed to update reservoir in Dolibarr'], 500);
        }

        return response()->json($reservoir);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->dolibarrService->deleteAsset($id);

        if ($result === false) {
            return response()->json(['error' => 'Failed to delete reservoir from Dolibarr'], 500);
        }

        return response()->json(['message' => 'Reservoir deleted successfully from Dolibarr']);
    }
}