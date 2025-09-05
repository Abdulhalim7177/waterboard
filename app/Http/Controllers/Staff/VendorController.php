<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        $vendors = Vendor::orderBy('created_at', 'desc')->paginate(10);
        return view('staff.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        return view('staff.vendors.create');
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'approved' => true, // Auto-approve vendors created by staff
        ]);

        return redirect()->route('staff.vendors.index')->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified vendor.
     */
    public function show(Vendor $vendor)
    {
        return view('staff.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(Vendor $vendor)
    {
        return view('staff.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors,email,' . $vendor->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vendor->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->password) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            
            $vendor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('staff.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('staff.vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    /**
     * Approve a vendor.
     */
    public function approve(Vendor $vendor)
    {
        $vendor->update(['approved' => true]);
        return redirect()->route('staff.vendors.index')->with('success', 'Vendor approved successfully.');
    }

    /**
     * Reject a vendor.
     */
    public function reject(Vendor $vendor)
    {
        $vendor->update(['approved' => false]);
        return redirect()->route('staff.vendors.index')->with('success', 'Vendor rejected successfully.');
    }
}
