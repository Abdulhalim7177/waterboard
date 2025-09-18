<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Imports\StaffImport;
use App\Exports\StaffExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'permission:manage-staff'])->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        $this->middleware(['auth:staff', 'permission:approve-staff'])->only(['approve', 'reject']);
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

        // Filter by employment_status
        if ($request->has('status') && $request->status != '') {
            $query->where('employment_status', $request->status);
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
        
        // Get stats for the dashboard
        $stats = [
            'total' => Staff::count(),
            'active' => Staff::where('employment_status', 'active')->count(),
            'on_leave' => Staff::where('employment_status', 'on_leave')->count(),
            'pending' => Staff::where('status', 'pending')->count(),
            'suspended' => Staff::where('employment_status', 'suspended')->count(),
            'terminated' => Staff::where('employment_status', 'terminated')->count(),
        ];

        return view('hr.staff.index', compact('staffs', 'departments', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        
        // Generate a temporary UUID for this session
        $tempUuid = Str::uuid();
        session(['staff_create_uuid' => $tempUuid]);
        
        return view('hr.staff.create', compact('lgas', 'wards', 'areas', 'tempUuid'));
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
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
            'employment_status' => 'required|in:active,inactive,on_leave,suspended,terminated',
        ]);

        $data = $request->except('photo');
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('staff_photos', 'public');
            $data['photo_path'] = $photoPath;
        }

        // Set employment status and system status
        $data['employment_status'] = $request->employment_status;
        $data['status'] = 'approved'; // System status for access control
        
        // Set default password if not provided
        $data['password'] = Hash::make($request->password ?? 'password'); // Default password, should be changed by user

        $staff = Staff::create($data);

        return redirect()->route('staff.hr.staff.index')->with('success', 'Staff member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($staff)
    {
        $staff = Staff::findOrFail($staff);
        return view('hr.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($staff)
    {
        $staff = Staff::findOrFail($staff);
        // Load all data upfront for client-side filtering
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        
        return view('hr.staff.edit', compact('staff', 'lgas', 'wards', 'areas'));
    }

    /**
     * Get a specific section of the edit form.
     */
    public function getEditSection(Request $request, $staff)
    {
        $staff = Staff::findOrFail($staff);
        
        $request->validate([
            'part' => 'required|in:personal,employment,location',
        ]);

        $part = $request->input('part');
        $data = compact('staff');

        if ($part === 'location') {
            // Load all data upfront for client-side filtering
            $lgas = Lga::all();
            $wards = Ward::all();
            $areas = Area::all();
            $data = array_merge($data, compact('lgas', 'wards', 'areas'));
        }

        return response()->json([
            'html' => view("hr.staff.partials.edit_{$part}", $data)->render(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $staff)
    {
        $staff = Staff::findOrFail($staff);
        
        // Determine validation rules based on the section
        $request->validate([
            'part' => 'required|in:personal,employment,location',
        ]);
        
        $part = $request->input('part');
        $data = [];
        
        if ($part === 'personal') {
            $request->validate([
                'staff_id' => 'required|unique:staff,staff_id,' . $staff->id,
                'first_name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|unique:staff,email,' . $staff->id,
                'mobile_no' => 'required|string|max:20',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'middle_name' => 'nullable|string|max:255',
                'nationality' => 'nullable|string|max:255',
                'state_of_origin' => 'nullable|string|max:255',
                'nin' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);
            
            $data = $request->only([
                'staff_id', 'first_name', 'surname', 'middle_name', 'email', 'mobile_no', 
                'date_of_birth', 'gender', 'nationality', 'state_of_origin', 'nin', 'address'
            ]);
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($staff->photo_path) {
                    Storage::disk('public')->delete($staff->photo_path);
                }
                
                $photoPath = $request->file('photo')->store('staff_photos', 'public');
                $data['photo_path'] = $photoPath;
            }
        } elseif ($part === 'employment') {
            $request->validate([
                'date_of_first_appointment' => 'required|date',
                'employment_status' => 'required|in:active,inactive,on_leave,suspended,terminated',
                'rank' => 'nullable|string|max:255',
                'staff_no' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'years_of_service' => 'nullable|integer|min:0',
                'expected_next_promotion' => 'nullable|date',
                'expected_retirement_date' => 'nullable|date',
                'appointment_type' => 'nullable|string|max:255',
                'highest_qualifications' => 'nullable|string|max:255',
                'grade_level_limit' => 'nullable|string|max:255',
            ]);
            
            $data = $request->only([
                'date_of_first_appointment', 'employment_status', 'rank', 'staff_no', 'department',
                'years_of_service', 'expected_next_promotion', 'expected_retirement_date', 
                'appointment_type', 'highest_qualifications', 'grade_level_limit'
            ]);
        } elseif ($part === 'location') {
            $request->validate([
                'lga_id' => 'nullable|exists:lgas,id',
                'ward_id' => 'nullable|exists:wards,id',
                'area_id' => 'nullable|exists:areas,id',
            ]);
            
            $data = $request->only(['lga_id', 'ward_id', 'area_id']);
        }
        
        $staff->update($data);

        return response()->json(['message' => 'Staff member updated successfully.', 'status' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($staff)
    {
        $staff = Staff::findOrFail($staff);
        
        // Delete photo if exists
        if ($staff->photo_path) {
            Storage::disk('public')->delete($staff->photo_path);
        }
        
        $staff->delete();

        return redirect()->route('staff.hr.staff.index')->with('success', 'Staff member deleted successfully.');
    }

    /**
     * Approve a staff member.
     */
    public function approve($staff)
    {
        $staff = Staff::findOrFail($staff);
        
        if ($staff->status === 'pending_delete') {
            $staff->logAuditEvent('deleted');
            $staff->delete();
        } else {
            $staff->update(['status' => 'approved']);
            $staff->logAuditEvent('approved');
        }

        return redirect()->route('staff.hr.staff.index')->with('success', 'Staff request approved.');
    }

    /**
     * Reject a staff member.
     */
    public function reject($staff)
    {
        $staff = Staff::findOrFail($staff);
        
        $staff->update(['status' => 'rejected']);
        return redirect()->route('staff.hr.staff.index')->with('error', 'Staff request rejected.');
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
            
            return redirect()->route('staff.hr.staff.index')->with('success', 'Staff data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('staff.hr.staff.index')->with('error', 'Error importing staff data: ' . $e->getMessage());
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
}