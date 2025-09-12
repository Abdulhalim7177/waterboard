<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Staff::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('surname', 'LIKE', "%{$search}%")
                  ->orWhere('staff_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('mobile_no', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }

        $staffs = $query->paginate(10);
        
        // Get unique departments for filter dropdown
        $departments = Staff::select('department')->distinct()->whereNotNull('department')->pluck('department');

        return view('staff.index', compact('staffs', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        
        return view('staff.create', compact('lgas', 'wards', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|unique:staff',
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:staff',
            'mobile_no' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'date_of_first_appointment' => 'required|date',
            'nin' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('photo');
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('staff_photos', 'public');
            $data['photo_path'] = $photoPath;
        }

        // Set default password
        $data['password'] = Hash::make('password'); // Default password, should be changed by user

        $staff = Staff::create($data);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        
        return view('staff.edit', compact('staff', 'lgas', 'wards', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'staff_id' => 'required|unique:staff,staff_id,' . $staff->id,
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'mobile_no' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'date_of_first_appointment' => 'required|date',
            'nin' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('photo');
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($staff->photo_path) {
                Storage::disk('public')->delete($staff->photo_path);
            }
            
            $photoPath = $request->file('photo')->store('staff_photos', 'public');
            $data['photo_path'] = $photoPath;
        }

        $staff->update($data);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        // Delete photo if exists
        if ($staff->photo_path) {
            Storage::disk('public')->delete($staff->photo_path);
        }
        
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
