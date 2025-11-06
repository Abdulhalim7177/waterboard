<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffBank;
use App\Models\NextOfKin;
use App\Exports\StaffExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\HrmService;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Services\BreadcrumbService;

class StaffController extends Controller
{
    protected $hrmService;

    public function __construct(HrmService $hrmService)
    {
        $this->hrmService = $hrmService;
        $this->middleware(['auth:staff', 'permission:manage-staff'])->only(['index', 'show', 'destroy', 'sync']);
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

        if ($request->has('insight')) {
            try {
                // Fetch all employees from the HRM service
                $hrmStaff = $this->hrmService->getEmployees();

                if (!$hrmStaff || !isset($hrmStaff['data'])) {
                    return back()->with('error', 'Could not fetch staff data from HRM system.');
                }

                $hrmStaffIds = collect($hrmStaff['data'])->pluck('employee_id')->all();

                // Get all local staff IDs
                $localStaffIds = Staff::pluck('staff_id')->all();

                // Find staff who are in HRM but not in the local database
                $newStaffIds = array_diff($hrmStaffIds, $localStaffIds);
                $newStaff = collect($hrmStaff['data'])->whereIn('employee_id', $newStaffIds)->all();

                // Find staff who are in both HRM and the local database
                $existingStaffIds = array_intersect($hrmStaffIds, $localStaffIds);
                $existingStaff = collect($hrmStaff['data'])->whereIn('employee_id', $existingStaffIds)->all();

                $message = "Insight: " . count($newStaff) . " new staff and " . count($existingStaff) . " existing staff found in HRM.";

                return redirect()->route('staff.hr.staff.index')->with('info', $message)
                    ->with('newStaff', $newStaff)
                    ->with('existingStaff', $existingStaff);

            } catch (\Exception $e) {
                \Log::error('Error in staff insight: ' . $e->getMessage());
                return back()->with('error', 'Error generating staff insight: ' . $e->getMessage());
            }
        }

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
     * Remove the specified resource from storage (Local only).
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
     * Remove staff member from HRM system and trigger sync.
     */
    public function remove($staff)
    {
        $staff = Staff::findOrFail($staff);
        
        // Remove the staff from the HRM system first
        $response = $this->hrmService->deleteEmployee($staff->staff_id);

        if ($response) {
            return redirect()->route('staff.hr.staff.index')->with('success', 'Staff member removed from HRM system successfully.');
        } else {
            return redirect()->route('staff.hr.staff.index')->with('error', 'Failed to remove staff member from HRM system.');
        }
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

    public function sync(Request $request)
    {
        try {
            $fullRefresh = $request->get('full_refresh', false);
            
            $startTime = now();
            $commandResult = Artisan::call('app:sync-staff-data', [
                '--refresh' => $fullRefresh
            ]);
            
            $output = Artisan::output();
            
            if ($commandResult === 0) {
                $this->info('Staff data synchronized successfully.');
                $affectedStaff = Staff::where('updated_at', '>=', $startTime)->get();
                
                // If AJAX request, return JSON response with affected data
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Staff data synchronized successfully',
                        'total_affected' => $affectedStaff->count(),
                        'new_records' => $affectedStaff->where('wasRecentlyCreated', true)->count(),
                        'updated_records' => $affectedStaff->where('wasRecentlyCreated', false)->count(),
                        'full_refresh' => $fullRefresh
                    ]);
                }
                
                // For regular requests, return to the index with a success message
                return redirect()->route('staff.hr.staff.index')->with('success', 
                    'Staff data synchronized successfully. ' . 
                    $affectedStaff->count() . ' records affected (' . 
                    $affectedStaff->where('wasRecentlyCreated', true)->count() . ' new, ' . 
                    $affectedStaff->where('wasRecentlyCreated', false)->count() . ' updated).'
                );
            } else {
                \Log::error('Sync command failed: ' . $output);
                
                // If AJAX request, return JSON error response
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error synchronizing staff data. Check logs for details.'
                    ], 500);
                }
                
                return redirect()->route('staff.hr.staff.index')->with('error', 'Error synchronizing staff data. Check logs for details.');
            }
        } catch (\Exception $e) {
            \Log::error('Error in staff sync: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // If AJAX request, return JSON error response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error synchronizing staff data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('staff.hr.staff.index')->with('error', 'Error synchronizing staff data: ' . $e->getMessage());
        }
    }

    /**
     * Fetch staff data from HRM API without saving to database
     */
    public function fetchStaffData(Request $request)
    {
        try {
            // Use the external API token in the request to the HRM service
            $filters = $request->only(['department', 'status', 'search']);
            
            // If you have a specific token to use, you can pass it to the service
            // For now, I'll assume the HrmService handles authentication internally
            $hrmData = $this->hrmService->getEmployees($filters);

            if ($hrmData) {
                $data = $hrmData['data'] ?? [];
                
                // If it's an AJAX request, return JSON as before
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $data,
                        'message' => 'Staff data fetched successfully'
                    ]);
                }
                
                // For non-AJAX requests, return a view with the data formatted in Bootstrap alerts
                return view('hr.staff.fetched-data', compact('data'));
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to fetch staff data from HRM system'
                    ], 500);
                } else {
                    return view('hr.staff.fetched-data', [
                        'data' => [],
                        'error' => 'Failed to fetch staff data from HRM system'
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching staff data: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching staff data: ' . $e->getMessage()
                ], 500);
            } else {
                return view('hr.staff.fetched-data', [
                    'data' => [],
                    'error' => 'Error fetching staff data: ' . $e->getMessage()
                ]);
            }
        }
    }
}
