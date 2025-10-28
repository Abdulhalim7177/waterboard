<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\DolibarrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservoirController extends Controller
{
    protected $dolibarrService;

    public function __construct(DolibarrService $dolibarrService)
    {
        $this->dolibarrService = $dolibarrService;
    }

    public function index(Request $request)
    {
        $allAssets = Asset::getAllFromDolibarr($this->dolibarrService);

        // Filter for reservoirs
        $reservoirs = array_filter($allAssets, function ($asset) {
            return isset($asset['array_options']['options_tanks']) || isset($asset['array_options']['options_capacity']);
        });

        $perPage = 15;
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $slicedReservoirs = array_slice($reservoirs, $offset, $perPage);

        $reservoirs = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedReservoirs,
            count($reservoirs),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('staff.reservoirs.index', compact('reservoirs'));
    }

    public function create()
    {
        $warehouses = $this->dolibarrService->getWarehouses();
        if ($warehouses === false) {
            return redirect()->route('staff.reservoirs.index')->with('error', 'Failed to fetch warehouses from Dolibarr. Please check the logs.');
        }
        return view('staff.reservoirs.create', compact('warehouses'));
    }

    public function store(Request $request)
    {
        Log::info('Reservoir creation request received', $request->all());

        $data = $request->validate([
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

        try {
            Log::info('Attempting to create reservoir in Dolibarr with data:', $data);
            $response = Asset::createInDolibarr($data, $this->dolibarrService);
            Log::info('Dolibarr response:', [$response]);

            if ($response) {
                return redirect()->route('staff.reservoirs.index')->with('success', 'Reservoir created successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to create reservoir in Dolibarr. Please check the logs.');
            }
        } catch (\Exception $e) {
            Log::error('Exception during reservoir creation:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please check the logs.');
        }
    }

    public function edit($id)
    {
        $reservoir = Asset::getFromDolibarr($id, $this->dolibarrService);
        $warehouses = $this->dolibarrService->getWarehouses();
        if ($warehouses === false) {
            return redirect()->route('staff.reservoirs.index')->with('error', 'Failed to fetch warehouses from Dolibarr. Please check the logs.');
        }
        if ($reservoir === false) {
            return redirect()->route('staff.reservoirs.index')->with('error', 'Failed to fetch reservoir from Dolibarr. Please check the logs.');
        }
        return view('staff.reservoirs.edit', compact('reservoir', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
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

        $response = Asset::updateInDolibarr($id, $data, $this->dolibarrService);

        if ($response) {
            return redirect()->route('staff.reservoirs.index')->with('success', 'Reservoir updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update reservoir in Dolibarr. Please check the logs.');
        }
    }

    public function show($id)
    {
        $reservoir = Asset::getFromDolibarr($id, $this->dolibarrService);
        if ($reservoir === false) {
            return redirect()->route('staff.reservoirs.index')->with('error', 'Failed to fetch reservoir from Dolibarr. Please check the logs.');
        }
        return view('staff.reservoirs.show', compact('reservoir'));
    }

    public function destroy($id)
    {
        Asset::deleteFromDolibarr($id, $this->dolibarrService);

        return redirect()->route('staff.reservoirs.index')->with('success', 'Reservoir deleted successfully.');
    }
}