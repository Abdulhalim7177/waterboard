<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\DolibarrService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    protected $dolibarrService;

    public function __construct(DolibarrService $dolibarrService)
    {
        $this->dolibarrService = $dolibarrService;
    }

    public function index(Request $request)
    {
        $allAssets = Asset::getAllFromDolibarr($this->dolibarrService);

        // Filter out reservoirs
        $assets = array_filter($allAssets, function ($asset) {
            return !isset($asset['array_options']['options_tanks']) && !isset($asset['array_options']['options_capacity']);
        });

        $perPage = 15;
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $slicedAssets = array_slice($assets, $offset, $perPage);

        $assets = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedAssets,
            count($assets),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('staff.assets.index', compact('assets'));
    }

    public function create()
    {
        $warehouses = $this->dolibarrService->getWarehouses();
        if ($warehouses === false) {
            return redirect()->route('staff.assets.index')->with('error', 'Failed to fetch warehouses from Dolibarr. Please check the logs.');
        }
        return view('staff.assets.create', compact('warehouses'));
    }

    public function store(Request $request)
    {
        Log::info('Asset creation request received', $request->all());

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'warehouse_id' => 'required|integer',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|in:product,service,equipment,infrastructure,vehicle,tool,other',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
        ]);

        try {
            Log::info('Attempting to create asset in Dolibarr with data:', $data);
            $response = Asset::createInDolibarr($data, $this->dolibarrService);
            Log::info('Dolibarr response:', [$response]);

            if ($response) {
                return redirect()->route('staff.assets.index')->with('success', 'Asset created successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to create asset in Dolibarr. Please check the logs.');
            }
        } catch (\Exception $e) {
            Log::error('Exception during asset creation:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please check the logs.');
        }
    }

    public function edit($id)
    {
        $asset = Asset::getFromDolibarr($id, $this->dolibarrService);
        $warehouses = $this->dolibarrService->getWarehouses();
        if ($warehouses === false) {
            return redirect()->route('staff.assets.index')->with('error', 'Failed to fetch warehouses from Dolibarr. Please check the logs.');
        }
        if ($asset === false) {
            return redirect()->route('staff.assets.index')->with('error', 'Failed to fetch asset from Dolibarr. Please check the logs.');
        }
        return view('staff.assets.edit', compact('asset', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'warehouse_id' => 'required|integer',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|in:product,service,equipment,infrastructure,vehicle,tool,other',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
        ]);

        $response = Asset::updateInDolibarr($id, $data, $this->dolibarrService);

        if ($response) {
            return redirect()->route('staff.assets.index')->with('success', 'Asset updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update asset in Dolibarr. Please check the logs.');
        }
    }

    public function show($id)
    {
        $asset = Asset::getFromDolibarr($id, $this->dolibarrService);
        if ($asset === false) {
            return redirect()->route('staff.assets.index')->with('error', 'Failed to fetch asset from Dolibarr. Please check the logs.');
        }
        return view('staff.assets.show', compact('asset'));
    }

    public function destroy($id)
    {
        Asset::deleteFromDolibarr($id, $this->dolibarrService);

        return redirect()->route('staff.assets.index')->with('success', 'Asset deleted successfully.');
    }
}
