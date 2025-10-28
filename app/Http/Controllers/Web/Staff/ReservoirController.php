<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\DolibarrService;
use Illuminate\Http\Request;

class ReservoirController extends Controller
{
    protected $dolibarrService;

    public function __construct(DolibarrService $dolibarrService)
    {
        $this->dolibarrService = $dolibarrService;
    }

    public function index(Request $request)
    {
        // Assuming 'reservoir' is a category in Dolibarr
        // You might need to adjust this based on your Dolibarr setup
        $category = $this->dolibarrService->getCategoryByName('reservoir');

        if (!$category) {
            return view('staff.reservoirs.index', ['reservoirs' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15)])
                ->with('error', 'The reservoir category does not exist in Dolibarr. Please create it first.');
        }

        $allReservoirs = $this->dolibarrService->getAssetsByCategory($category['id']);

        if ($allReservoirs === false) {
            return view('staff.reservoirs.index', ['reservoirs' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15)])
                ->with('error', 'Failed to fetch reservoirs from Dolibarr. Please check the logs and ensure the reservoir category exists.');
        }

        $perPage = 15;
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $slicedReservoirs = array_slice($allReservoirs, $offset, $perPage);

        $reservoirs = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedReservoirs,
            count($allReservoirs),
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'warehouse_id' => 'required|integer',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
        ]);

        $data['type'] = 'reservoir'; // Automatically set the type to 'reservoir'

        $response = Asset::createInDolibarr($data, $this->dolibarrService);

        if ($response) {
            return redirect()->route('staff.reservoirs.index')->with('success', 'Reservoir created successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to create reservoir in Dolibarr. Please check the logs.');
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
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
        ]);
        
        $data['type'] = 'reservoir';

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
        return view('staff.reservoirs.show', compact('reservoir'));
    }

    public function destroy($id)
    {
        Asset::deleteFromDolibarr($id, $this->dolibarrService);

        return redirect()->route('staff.reservoirs.index')->with('success', 'Reservoir deleted successfully.');
    }
}