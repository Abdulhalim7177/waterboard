<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\DolibarrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    protected $dolibarrService;

    public function __construct(DolibarrService $dolibarrService)
    {
        $this->dolibarrService = $dolibarrService;
    }

    /**
     * Display a listing of the assets from Dolibarr.
     */
    public function index()
    {
        // Get assets directly from Dolibarr using the Asset model service layer
        $dolibarrResponse = Asset::getAllFromDolibarr($this->dolibarrService, 100, 0);
        
        $assets = collect();
        if ($dolibarrResponse && is_array($dolibarrResponse)) {
            $assets = collect($dolibarrResponse);
        }

        // Create a paginator manually since we're using Dolibarr data
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $currentItems = array_slice($assets->toArray(), $offset, $perPage);
        $totalItems = count($assets);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $totalItems,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('staff.assets.index', ['assets' => $paginator]);
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        return view('staff.assets.create');
    }

    /**
     * Store a newly created asset in Dolibarr.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|in:product,service,equipment,infrastructure,vehicle,tool,other',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
        ]);

        // Create asset directly in Dolibarr
        $assetData = $request->only([
            'name', 'description', 'category', 'type', 'serial_number', 
            'model', 'brand', 'location', 'purchase_date', 'purchase_price', 'status'
        ]);
        
        $dolibarrResponse = Asset::createInDolibarr($assetData, $this->dolibarrService);

        if ($dolibarrResponse && isset($dolibarrResponse['id'])) {
            return redirect()->route('staff.assets.index')
                ->with('success', 'Asset created successfully in asset management system. ID: ' . $dolibarrResponse['id']);
        } else {
            return redirect()->route('staff.assets.index')
                ->with('error', 'Failed to create asset in asset management system. Please try again.');
        }
    }

    /**
     * Display the specified asset from Dolibarr.
     */
    public function show($id)
    {
        // Get asset directly from Dolibarr
        $asset = Asset::getFromDolibarr($id, $this->dolibarrService);

        if (!$asset) {
            abort(404, 'Asset not found');
        }

        return view('staff.assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit($id)
    {
        // Get asset directly from Dolibarr
        $asset = Asset::getFromDolibarr($id, $this->dolibarrService);

        if (!$asset) {
            abort(404, 'Asset not found');
        }

        return view('staff.assets.edit', compact('asset'));
    }

    /**
     * Update the specified asset in Dolibarr.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|in:product,service,equipment,infrastructure,vehicle,tool,other',
            'serial_number' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,maintenance,retired,damaged',
        ]);

        // Update asset directly in Dolibarr
        $assetData = $request->only([
            'name', 'description', 'category', 'type', 'serial_number', 
            'model', 'brand', 'location', 'purchase_date', 'purchase_price', 'status'
        ]);
        
        $dolibarrResponse = Asset::updateInDolibarr($id, $assetData, $this->dolibarrService);

        if ($dolibarrResponse) {
            return redirect()->route('staff.assets.index')
                ->with('success', 'Asset updated successfully in asset management system.');
        } else {
            return redirect()->route('staff.assets.index')
                ->with('error', 'Failed to update asset in asset management system. Please try again.');
        }
    }

    /**
     * Remove the specified asset from Dolibarr.
     */
    public function destroy($id)
    {
        // Delete asset directly from Dolibarr
        $dolibarrResponse = Asset::deleteFromDolibarr($id, $this->dolibarrService);

        if ($dolibarrResponse) {
            return redirect()->route('staff.assets.index')
                ->with('success', 'Asset deleted successfully from asset management system.');
        } else {
            return redirect()->route('staff.assets.index')
                ->with('error', 'Failed to delete asset from asset management system. Please try again.');
        }
    }

    /**
     * Show assets from Dolibarr (no local sync needed)
     */
    public function importFromDolibarr()
    {
        $assets = Asset::getAllFromDolibarr($this->dolibarrService);

        if ($assets) {
            return view('staff.assets.import', compact('assets'));
        } else {
            return redirect()->route('staff.assets.index')
                ->with('error', 'Failed to fetch assets from asset management system.');
        }
    }
}