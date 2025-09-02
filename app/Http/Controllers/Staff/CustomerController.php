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

    public function index()
    {
        $this->authorize('view-customers', Customer::class);
        $customers = Customer::with(['lga', 'ward', 'area', 'category', 'tariff'])->paginate(10);
        return view('staff.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $this->authorize('view-customer', $customer);
        return view('staff.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->authorize('edit-customer', $customer);
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
            'billing_condition' => 'nullable',
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
}