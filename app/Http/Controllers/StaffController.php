<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Imports\StaffImport;
use App\Exports\StaffExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Services\HrmService;

class StaffController extends Controller
{
    protected $hrmService;

    public function __construct(HrmService $hrmService)
    {
        $this->hrmService = $hrmService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Staff::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('surname', 'LIKE', "%{$search}%")
                  ->orWhere('middle_name', 'LIKE', "%{$search}%")
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

        // Order by staff ID
        $query->orderBy('staff_id', 'asc');

        $staffs = $query->paginate(10);
        
        // Get unique departments for filter dropdown
        $departments = Staff::select('department')->distinct()->whereNotNull('department')->pluck('department');

        return view('hr.staff.index', compact('staffs', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        
        return view('hr.staff.create', compact('lgas', 'wards', 'areas'));
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

        return redirect()->route('hr.staff.index')->with('success', 'Staff member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return view('hr.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        
        return view('hr.staff.edit', compact('staff', 'lgas', 'wards', 'areas'));
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

        return redirect()->route('hr.staff.index')->with('success', 'Staff member updated successfully.');
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

        return redirect()->route('hr.staff.index')->with('success', 'Staff member deleted successfully.');
    }

    /**
     * Import staff data from Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new StaffImport, $request->file('file'));
            
            return redirect()->route('hr.staff.index')->with('success', 'Staff data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('hr.staff.index')->with('error', 'Error importing staff data: ' . $e->getMessage());
        }
    }

    /**
     * Export staff data to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new StaffExport, 'staff.xlsx');
    }

    /**
     * Export staff data to PDF.
     */
    public function exportPdf()
    {
        $staffs = Staff::all();
        
        $pdf = Pdf::view('pdf.staff', compact('staffs'))
            ->withBrowsershot(function ($browsershot) {
                $browsershot->setOption('landscape', true);
            })
            ->name('staff-list.pdf');
            
        return $pdf;
    }

    /**
     * Download staff import template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\StaffTemplateExport, 'staff-template.xlsx');
    }

    public function createSync()
    {
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        
        return view('hr.staff.create-sync', compact('lgas', 'wards', 'areas'));
    }

    public function storeSync(Request $request)
    {
        $request->validate([
            'staff_no' => 'required|unique:staff',
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:staff',
            'mobile_no' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'date_of_first_appointment' => 'required|date',
            'nin' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password'] ?? 'password');

        $staff = Staff::create($data);

        $hrmData = $this->hrmService->createEmployee($data);

        if ($hrmData) {
            return redirect()->route('hr.staff.index')->with('success', 'Staff member created and synced successfully.');
        } else {
            return redirect()->route('hr.staff.index')->with('warning', 'Staff member created locally, but failed to sync with HRM system.');
        }
    }
}
