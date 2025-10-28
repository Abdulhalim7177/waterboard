<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DolibarrService;
use App\Models\Asset;
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
        $allAssets = Asset::getAllFromDolibarr($this->dolibarrService);

        // Filter for reservoirs
        $reservoirs = array_filter($allAssets, function ($asset) {
            return isset($asset['array_options']['options_tanks']) || isset($asset['array_options']['options_capacity']);
        });

        return response()->json(array_values($reservoirs));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'warehouse_id' => 'required|integer',
            'purchase_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
            'tanks' => 'required|integer|min:1',
            'capacity' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
        ]);

        $reservoir = Asset::createInDolibarr($validatedData, $this->dolibarrService);

        if (!$reservoir) {
            return response()->json(['error' => 'Failed to create reservoir in Dolibarr'], 500);
        }

        return response()->json($reservoir, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservoir = Asset::getFromDolibarr($id, $this->dolibarrService);

        if (!$reservoir || !(isset($reservoir['array_options']['options_tanks']) || isset($reservoir['array_options']['options_capacity']))) {
            return response()->json(['error' => 'Reservoir not found in Dolibarr'], 404);
        }

        return response()->json($reservoir);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'warehouse_id' => 'sometimes|required|integer',
            'purchase_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
            'tanks' => 'sometimes|required|integer|min:1',
            'capacity' => 'sometimes|required|numeric|min:0',
            'location' => 'sometimes|required|string|max:255',
        ]);

        $reservoir = Asset::updateInDolibarr($id, $validatedData, $this->dolibarrService);

        if (!$reservoir) {
            return response()->json(['error' => 'Failed to update reservoir in Dolibarr'], 500);
        }

        return response()->json($reservoir);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = Asset::deleteFromDolibarr($id, $this->dolibarrService);

        if ($result === false) {
            return response()->json(['error' => 'Failed to delete reservoir from Dolibarr'], 500);
        }

        return response()->json(['message' => 'Reservoir deleted successfully.']);
    }
}