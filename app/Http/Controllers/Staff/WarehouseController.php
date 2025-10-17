<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\DolibarrService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    protected $dolibarrService;

    public function __construct(DolibarrService $dolibarrService)
    {
        $this->dolibarrService = $dolibarrService;
    }

    public function index(Request $request)
    {
        $allWarehouses = $this->dolibarrService->getWarehouses();
        $perPage = 15;
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $slicedWarehouses = array_slice($allWarehouses, $offset, $perPage);

        $warehouses = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedWarehouses,
            count($allWarehouses),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('staff.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('staff.warehouses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|boolean',
        ]);

        $this->dolibarrService->createWarehouse($data);

        return redirect()->route('staff.warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function edit($id)
    {
        $warehouse = $this->dolibarrService->getWarehouse($id);
        return view('staff.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'required|boolean',
        ]);

        $this->dolibarrService->updateWarehouse($id, $data);

        return redirect()->route('staff.warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy($id)
    {
        $this->dolibarrService->deleteWarehouse($id);

        return redirect()->route('staff.warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
