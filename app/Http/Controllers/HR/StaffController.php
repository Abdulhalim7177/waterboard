<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffBank;
use App\Models\NextOfKin;
use App\Exports\StaffExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\HrmService;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Services\BreadcrumbService;

class StaffController extends Controller
{
    protected $hrmService;

    public function __construct(HrmService $hrmService)
    {
        $this->hrmService = $hrmService;
        $this->middleware(['auth:staff', 'permission:manage-staff'])->only(['index', 'show', 'destroy']);
        $this->middleware(['auth:staff', 'permission:approve-staff'])->only(['approve', 'reject']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('HR Management')->add('Staff Directory');

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
     * Display the specified resource.
     */
    public function show($staff)
    {
        $staff = Staff::findOrFail($staff);
        return view('hr.staff.show', compact('staff'));
    }

    public function create()
    {
        $staff = new Staff();
        return view('hr.staff.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:staff',
        ]);

        $data = $request->all();

        $response = $this->hrmService->createEmployee($data);

        if ($response) {
            // Optionally, you can sync the local database after creating the employee in the HRM system
            Artisan::call('app:sync-staff-data');
            return redirect()->route('staff.hr.staff.index')->with('success', 'Employee creation request submitted for approval.');
        } else {
            return redirect()->back()->with('error', 'Failed to create employee in HRM system.');
        }
    }

    public function edit(Staff $staff)
    {
        return view('hr.staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'staff_id' => 'required|unique:staff,staff_id,' . $staff->id,
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
        ]);

        $data = $request->all();

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $staff->update($data);

        $staff->bank()->updateOrCreate([], [
            'bank_name' => $request->bank_name,
            'bank_code' => $request->bank_code,
            'account_name' => $request->account_name,
            'account_no' => $request->account_no,
        ]);

        $staff->nextOfKin()->updateOrCreate([], [
            'name' => $request->next_of_kin_name,
            'relationship' => $request->next_of_kin_relationship,
            'mobile_no' => $request->next_of_kin_mobile_no,
            'address' => $request->next_of_kin_address,
            'occupation' => $request->next_of_kin_occupation,
            'place_of_work' => $request->next_of_kin_place_of_work,
        ]);

        return redirect()->route('staff.hr.staff.index')->with('success', 'Staff member updated successfully.');
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

    public function sync()
    {
        try {
            $startTime = now();
            Artisan::call('app:sync-staff-data');
            $affectedStaff = Staff::where('updated_at', '>=', $startTime)->get();

            return view('hr.staff.sync', compact('affectedStaff'));
        } catch (\Exception $e) {
            return redirect()->route('staff.hr.staff.index')->with('error', 'Error synchronizing staff data: ' . $e->getMessage());
        }
    }
}
