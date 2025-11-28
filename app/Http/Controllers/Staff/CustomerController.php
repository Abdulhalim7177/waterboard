<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Category;
use App\Models\Tariff;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function index(Request $request)
    {
        $this->authorize('view-customers', Customer::class);

        $staff = auth()->guard('staff')->user();
        $accessibleWardIds = $staff->getAccessibleWardIds();

        $query = Customer::with(['lga', 'ward', 'area', 'category', 'tariff']);

        // If staff has restricted access based on paypoint, filter by accessible wards
        if (!empty($accessibleWardIds)) {
            $query->whereIn('ward_id', $accessibleWardIds);
        }

        // Apply filters from the request
        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
        }
        if ($request->filled('lga_filter')) {
            $query->where('lga_id', $request->input('lga_filter'));
        }
        if ($request->filled('ward_filter')) {
            $query->where('ward_id', $request->input('ward_filter'));
        }
        if ($request->filled('area_filter')) {
            $query->where('area_id', $request->input('area_filter'));
        }
        if ($request->filled('category_filter')) {
            $query->where('category_id', $request->input('category_filter'));
        }
        if ($request->filled('tariff_filter')) {
            $query->where('tariff_id', $request->input('tariff_filter'));
        }

        if ($request->has('search_customer') && $request->input('search_customer') != '') {
            $searchTerm = $request->input('search_customer');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('surname', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('billing_id', 'like', "%{$searchTerm}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $customers = $query->paginate($perPage)->appends($request->query());
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();

        $stats = [
            'total' => Customer::count(),
            'pending' => Customer::where('status', 'pending')->count(),
            'approved' => Customer::where('status', 'approved')->count(),
            'rejected' => Customer::where('status', 'rejected')->count(),
        ];

        return view('staff.customers.index', compact('customers', 'lgas', 'wards', 'areas', 'stats'));
    }

    public function show(Customer $customer)
    {
        $this->authorize('view-customer', $customer);
        
        // Additional check to ensure the staff can access this customer
        $staff = auth()->guard('staff')->user();
        $accessibleWardIds = $staff->getAccessibleWardIds();
        
        if (!empty($accessibleWardIds) && !in_array($customer->ward_id, $accessibleWardIds)) {
            abort(403, 'You are not authorized to view this customer.');
        }
        
        return view('staff.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->authorize('edit-customer', $customer);
        // Load all data upfront for client-side filtering
        $lgas = Lga::where('status', 'approved')->get();
        $wards = Ward::where('status', 'approved')->get();
        $areas = Area::where('status', 'approved')->get();
        $categories = Category::where('status', 'approved')->get();
        $tariffs = Tariff::where('status', 'approved')->get();
        return view('staff.customers.edit', compact('customer', 'lgas', 'wards', 'areas', 'categories', 'tariffs'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('edit-customer', $customer);
        $validated = $request->validate([
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone_number' => 'required|unique:customers,phone_number,' . $customer->id,
            'alternate_phone_number' => 'nullable',
            'street_name' => 'nullable',
            'house_number' => 'nullable',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
            'category_id' => 'nullable|exists:categories,id',
            'tariff_id' => 'nullable|exists:tariffs,id',
            'delivery_code' => 'nullable',
            'billing_condition' => 'nullable|in:Metered,Non-Metered',
            'water_supply_status' => 'nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_coordinates' => 'nullable|json',
            'meter_reading' => 'nullable|numeric|min:0',
        ]);

        $customer->update($validated + ['status' => 'pending']);
        return redirect()->route('staff.customers.index')->with('success', 'Customer updated, pending approval');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete-customer', $customer);
        $customer->update(['status' => 'pending']);
        return redirect()->route('staff.customers.index')->with('success', 'Customer deletion requested');
    }

    public function approve(Customer $customer)
    {
        $this->authorize('approve-customer', Customer::class);
        $customer->update(['status' => 'approved']);
        return redirect()->route('staff.customers.index')->with('success', 'Customer approved');
    }

    public function reject(Customer $customer)
    {
        $this->authorize('reject-customer', Customer::class);
        $customer->update(['status' => 'rejected']);
        return redirect()->route('staff.customers.index')->with('success', 'Customer rejected');
    }

    public function editSection(Request $request, Customer $customer)
    {
        $this->authorize('edit-customer', $customer);

        $part = $request->input('part');

        if (!in_array($part, ['personal', 'address', 'billing', 'location'])) {
            return response()->json(['error' => 'Invalid section specified'], 400);
        }

        // Pass necessary data to the partials
        $lgas = Lga::where('status', 'approved')->get();
        $wards = Ward::where('status', 'approved')->get();
        $areas = Area::where('status', 'approved')->get();
        $categories = Category::where('status', 'approved')->get();
        $tariffs = Tariff::where('status', 'approved')->get();

        try {
            $html = view("staff.customers.partials.edit_{$part}", compact('customer', 'lgas', 'wards', 'areas', 'categories', 'tariffs'))->render();
            return response()->json(['html' => $html, 'lgas' => $lgas, 'wards' => $wards, 'areas' => $areas, 'categories' => $categories, 'tariffs' => $tariffs]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not load section: ' . $e->getMessage()], 500);
        }
    }
}