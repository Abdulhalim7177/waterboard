<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StaffVendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        $vendors = Vendor::orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $vendors
        ]);
    }

    /**
     * Show a specific vendor.
     */
    public function show(Vendor $vendor)
    {
        return response()->json([
            'success' => true,
            'data' => $vendor
        ]);
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
            'street_name' => 'required|string|max:255',
            'vendor_code' => 'required|string|max:255|unique:vendors',
            'lga_id' => 'required|exists:lgas,id',
            'ward_id' => 'required|exists:wards,id',
            'area_id' => 'required|exists:areas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['approved'] = true; // Auto-approve vendors created by staff

        $vendor = Vendor::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vendor created successfully',
            'data' => $vendor
        ], 201);
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors,email,' . $vendor->id,
            'street_name' => 'required|string|max:255',
            'vendor_code' => 'required|string|max:255|unique:vendors,vendor_code,' . $vendor->id,
            'lga_id' => 'required|exists:lgas,id',
            'ward_id' => 'required|exists:wards,id',
            'area_id' => 'required|exists:areas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor->update($request->only(['name', 'email', 'street_name', 'vendor_code', 'lga_id', 'ward_id', 'area_id']));

        // Update password if provided
        if ($request->password) {
            $passwordValidator = Validator::make($request->all(), [
                'password' => 'string|min:8|confirmed',
            ]);

            if ($passwordValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $passwordValidator->errors()
                ], 422);
            }

            $vendor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vendor updated successfully',
            'data' => $vendor
        ]);
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Vendor deleted successfully'
        ]);
    }

    /**
     * Approve a vendor.
     */
    public function approve(Vendor $vendor)
    {
        $vendor->update(['approved' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Vendor approved successfully',
            'data' => $vendor
        ]);
    }

    /**
     * Reject a vendor.
     */
    public function reject(Vendor $vendor)
    {
        $vendor->update(['approved' => false]);
        
        return response()->json([
            'success' => true,
            'message' => 'Vendor rejected successfully',
            'data' => $vendor
        ]);
    }

    /**
     * Get vendors with filter options
     */
    public function filteredIndex(Request $request)
    {
        $query = Vendor::query();

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('approved', $request->status === 'approved');
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('vendor_code', 'like', '%' . $request->search . '%');
            });
        }

        $vendors = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $vendors
        ]);
    }

    /**
     * Get vendor statistics
     */
    public function statistics()
    {
        $stats = [
            'total_vendors' => Vendor::count(),
            'approved_vendors' => Vendor::where('approved', true)->count(),
            'pending_vendors' => Vendor::where('approved', false)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}