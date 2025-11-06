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
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $wards = \App\Models\Ward::all();
        $areas = \App\Models\Area::all();
        return view('hr.staff.create', compact('wards', 'areas'));
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
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|string|max:50|unique:staff,staff_id',
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff,email',
            'mobile_no' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'date_of_first_appointment' => 'nullable|date',
            'password' => 'required|string|min:6',
            'gender' => 'required|string|in:male,female',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string'
        ]);

        $data = $request->except(['password', 'bank_name', 'bank_code', 'account_name', 'account_no', 'pension_administrator', 'rsa_pin', 'next_of_kin_name', 'next_of_kin_relationship', 'next_of_kin_mobile_no', 'next_of_kin_address', 'next_of_kin_occupation', 'next_of_kin_place_of_work']);

        $data['password'] = Hash::make($request->password);

        $staff = Staff::create($data);

        // Handle financial information
        if ($request->bank_name || $request->bank_code || $request->account_name || $request->account_no) {
            $staff->bank()->create([
                'bank_name' => $request->bank_name,
                'bank_code' => $request->bank_code,
                'account_name' => $request->account_name,
                'account_no' => $request->account_no,
            ]);
        }

        // Handle pension information
        if ($request->pension_administrator || $request->rsa_pin) {
            $staff->pension()->create([
                'pension_administrator' => $request->pension_administrator,
                'rsa_pin' => $request->rsa_pin,
            ]);
        }

        // Handle next of kin information
        if ($request->next_of_kin_name || $request->next_of_kin_relationship || $request->next_of_kin_mobile_no) {
            $staff->nextOfKin()->create([
                'name' => $request->next_of_kin_name,
                'relationship' => $request->next_of_kin_relationship,
                'mobile_no' => $request->next_of_kin_mobile_no,
                'address' => $request->next_of_kin_address,
                'occupation' => $request->next_of_kin_occupation,
                'place_of_work' => $request->next_of_kin_place_of_work,
            ]);
        }

        // Assign default role
        $staff->assignRole('staff');

        // Sync with external HRM API
        try {
            $hrmData = $this->prepareHrmData($staff, $request);
            \Log::info("Attempting to sync staff with HRM API", [
                'staff_id' => $staff->id,
                'staff_email' => $staff->email,
                'hrm_data' => $hrmData,
                'hrm_base_url' => config('services.hrm.base_url'),
                'hrm_token_configured' => !empty(config('services.hrm.token'))
            ]);
            
            $hrmResponse = $this->hrmService->createEmployee($hrmData);

            if ($hrmResponse && isset($hrmResponse['success']) && $hrmResponse['success']) {
                \Log::info("Staff member synced with HRM successfully", [
                    'staff_id' => $staff->id,
                    'hrm_response' => $hrmResponse
                ]);
                
                // Update staff with any data returned from HRM if needed
                if (isset($hrmResponse['data']['reg_no']) || isset($hrmResponse['reg_no'])) {
                    $newRegNo = $hrmResponse['data']['reg_no'] ?? $hrmResponse['reg_no'];
                    $staff->update(['staff_id' => $newRegNo]);
                }
                
                $request->session()->flash('success', 'Staff member created successfully and synced with HRM system.');
                return redirect()->route('staff.hr.staff.index');
            } else {
                \Log::warning("Staff member created locally but failed to sync with HRM", [
                    'staff_id' => $staff->id,
                    'hrm_response' => $hrmResponse,
                    'hrm_base_url' => config('services.hrm.base_url')
                ]);
                $request->session()->flash('warning', 'Staff member created successfully locally, but sync with HRM system failed. Check logs for details.');
                return redirect()->route('staff.hr.staff.index');
            }
        } catch (\Exception $e) {
            \Log::error("Error syncing staff with HRM API", [
                'error' => $e->getMessage(),
                'staff_id' => $staff->id,
                'staff_email' => $staff->email,
                'hrm_base_url' => config('services.hrm.base_url'),
                'trace' => $e->getTraceAsString()
            ]);
            
            $request->session()->flash('warning', 'Staff member created successfully locally, but sync with HRM system failed due to an API error. Check logs for details.');
            return redirect()->route('staff.hr.staff.index');
        }
    }

    /**
     * Prepare data for HRM API
     */
    private function prepareHrmData($staff, $request)
    {
        // Format mobile number to comply with API requirements (max 15 characters)
        $mobileNo = $this->formatMobileNumber($staff->mobile_no);
        $kinMobileNo = $this->formatMobileNumber($staff->nextOfKin->mobile_no ?? '');
        
        return [
            'first_name' => $staff->first_name,
            'surname' => $staff->surname,
            'middle_name' => $staff->middle_name ?? '',
            'staff_no' => $staff->staff_id,
            'gender' => ucfirst($staff->gender),
            'date_of_birth' => $staff->date_of_birth ? $staff->date_of_birth->format('Y-m-d') : null,
            'state_id' => 1, // Default state - you might want to get this from a field
            'lga_id' => $staff->lga_id ?? null,
            'ward_id' => $staff->ward_id ?? null,
            'nationality' => $staff->nationality,
            'nin' => $staff->nin ?? '',
            'reg_no' => $staff->staff_id,
            'mobile_no' => $mobileNo,
            'email' => $staff->email,
            'pay_point' => $staff->paypoint->name ?? '',
            'address' => $staff->address,
            'date_of_first_appointment' => $staff->date_of_first_appointment ? $staff->date_of_first_appointment->format('Y-m-d') : null,
            'appointment_type_id' => $staff->appointment_type_id ?? null,
            'cadre_id' => $staff->cadre_id ?? null,
            'salary_scale_id' => $staff->grade_level_id ?? null, // Assuming grade level maps to salary scale
            'grade_level_id' => $staff->grade_level_id ?? null,
            'step_id' => $staff->step_id ?? null,
            'step_level' => $staff->step_id ? 'Step ' . $staff->step_id : null,
            'expected_retirement_date' => $staff->expected_retirement_date ? $staff->expected_retirement_date->format('Y-m-d') : null,
            'rank_id' => $staff->rank_id ?? null,
            'status' => $this->validateStatus($staff->employment_status ?? 'Active'),
            'highest_certificate' => $staff->highest_qualifications ?? '',
            'kin_name' => $staff->nextOfKin->name ?? '',
            'kin_relationship' => $staff->nextOfKin->relationship ?? '',
            'kin_mobile_no' => $kinMobileNo,
            'kin_address' => $staff->nextOfKin->address ?? '',
            'kin_occupation' => $staff->nextOfKin->occupation ?? '',
            'kin_place_of_work' => $staff->nextOfKin->place_of_work ?? '',
            'bank_name' => $staff->bank->bank_name ?? '',
            'bank_code' => $staff->bank->bank_code ?? '',
            'account_name' => $staff->bank->account_name ?? '',
            'account_no' => $staff->bank->account_no ?? '',
            'department_id' => $staff->department_id ?? null,
            'password' => $request->password
        ];
    }
    
    /**
     * Format mobile number to comply with API requirements
     */
    private function formatMobileNumber($mobileNumber)
    {
        if (empty($mobileNumber)) {
            return '';
        }
        
        // Remove any non-digit characters except leading +
        $cleanNumber = preg_replace('/[^\d+]/', '', $mobileNumber);
        
        // If it starts with + and is too long, truncate to keep only last 10 digits after country code
        if (strlen($cleanNumber) > 15) {
            // If it starts with +, keep + and last 10 digits (e.g., +2347012345678)
            if (substr($cleanNumber, 0, 1) === '+') {
                $numberWithoutPlus = substr($cleanNumber, 1);
                $last10Digits = substr($numberWithoutPlus, -10);
                $cleanNumber = '+' . $last10Digits;
            } else {
                // Otherwise just take the last 10-15 digits
                $cleanNumber = substr($cleanNumber, -10);
            }
        }
        
        return $cleanNumber;
    }
    
    /**
     * Validate status value for HRM API
     */
    private function validateStatus($status)
    {
        // Map status values to valid HRM API values
        $validStatuses = ['Active', 'Inactive', 'On Leave', 'Suspended', 'Terminated'];
        $status = trim($status);
        
        // If the status is one of the valid values, return as is
        if (in_array(ucfirst($status), $validStatuses)) {
            return ucfirst($status);
        }
        
        // If status contains certain keywords, map to appropriate value
        $statusLower = strtolower($status);
        if (strpos($statusLower, 'active') !== false) {
            return 'Active';
        } elseif (strpos($statusLower, 'inact') !== false) {
            return 'Inactive';
        } elseif (strpos($statusLower, 'leave') !== false) {
            return 'On Leave';
        } elseif (strpos($statusLower, 'suspend') !== false) {
            return 'Suspended';
        } elseif (strpos($statusLower, 'term') !== false) {
            return 'Terminated';
        } elseif (strpos($statusLower, 'pend') !== false) {
            return 'Inactive'; // Default to Inactive for pending
        }
        
        // Default to Active if status is not recognized
        return 'Active';
    }

    public function edit(Staff $staff)
    {
        $wards = \App\Models\Ward::all();
        $areas = \App\Models\Area::all();
        return view('hr.staff.edit', compact('staff', 'wards', 'areas'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'staff_id' => 'required|unique:staff,staff_id,' . $staff->id,
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
        ]);

        $data = $request->only(['staff_id', 'first_name', 'surname', 'middle_name', 'gender', 'date_of_birth', 'nationality', 'nin', 'mobile_no', 'email', 'address']);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $staff->update($data);

        return response()->json(['message' => 'Staff member updated successfully.']);
    }

    public function updatePersonal(Request $request, Staff $staff)
    {
        $request->validate([
            'staff_id' => 'required|unique:staff,staff_id,' . $staff->id,
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
        ]);

        $data = $request->only(['staff_id', 'first_name', 'surname', 'middle_name', 'gender', 'date_of_birth', 'nationality', 'nin', 'mobile_no', 'email', 'address']);

        $staff->update($data);

        return response()->json(['message' => 'Personal information updated successfully.']);
    }

    public function updateEmployment(Request $request, Staff $staff)
    {
        $data = $request->only(['date_of_first_appointment', 'contract_start_date', 'contract_end_date', 'rank_id', 'staff_no', 'department_id', 'cadre_id', 'grade_level_id', 'step_id', 'expected_next_promotion', 'expected_retirement_date', 'status', 'employment_status', 'highest_qualifications', 'appointment_type_id', 'years_of_service']);

        $staff->update($data);

        return response()->json(['message' => 'Employment information updated successfully.']);
    }

    public function updateLocation(Request $request, Staff $staff)
    {
        $data = $request->only(['lga_id', 'ward_id', 'area_id', 'zone_id', 'district_id', 'paypoint_id']);

        $staff->update($data);

        return response()->json(['message' => 'Location information updated successfully.']);
    }

    public function updateFinancial(Request $request, Staff $staff)
    {
        $staff->bank()->updateOrCreate(
            ['staff_id' => $staff->id],
            [
                'bank_name' => $request->bank_name,
                'bank_code' => $request->bank_code,
                'account_name' => $request->account_name,
                'account_no' => $request->account_no,
            ]
        );

        return response()->json(['message' => 'Financial information updated successfully.']);
    }

    public function updateNextOfKin(Request $request, Staff $staff)
    {
        $staff->nextOfKin()->updateOrCreate(
            ['staff_id' => $staff->id],
            [
                'name' => $request->next_of_kin_name,
                'relationship' => $request->next_of_kin_relationship,
                'mobile_no' => $request->next_of_kin_mobile_no,
                'address' => $request->next_of_kin_address,
                'occupation' => $request->next_of_kin_occupation,
                'place_of_work' => $request->next_of_kin_place_of_work,
            ]
        );

        return response()->json(['message' => 'Next of kin information updated successfully.']);
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
            // Sync HRM data before syncing staff
            $staffData = $this->syncHrmData();

            if (!$staffData) {
                return back()->with('error', 'Could not fetch staff data from HRM system.');
            }

            $syncedCount = 0;

            foreach ($staffData as $employee) {
                $statusMap = [
                    'Active' => 'approved',
                    'Inactive' => 'rejected',
                    'On Leave' => 'pending',
                    'Suspended' => 'pending',
                    'Terminated' => 'rejected',
                ];

                $status = $statusMap[$employee['status']] ?? 'pending';

                $staff = Staff::updateOrCreate(
                    ['staff_id' => $employee['employee_id']],
                    [
                        'first_name' => $employee['first_name'],
                        'surname' => $employee['surname'],
                        'middle_name' => $employee['middle_name'],
                        'gender' => $employee['gender'],
                        'date_of_birth' => $employee['date_of_birth'],
                        'lga_id' => $employee['lga_id'],
                        'ward_id' => $employee['ward_id'],
                        'nationality' => $employee['nationality'],
                        'nin' => $employee['nin'],
                        'mobile_no' => $employee['mobile_no'],
                        'email' => $employee['email'],
                        'address' => $employee['address'],
                        'password' => Hash::make('password'), // Set a default password
                        'date_of_first_appointment' => $employee['date_of_first_appointment'],
                        'contract_start_date' => $employee['contract_start_date'],
                        'contract_end_date' => $employee['contract_end_date'],
                        'rank_id' => $employee['rank_id'],
                        'staff_no' => $employee['staff_no'],
                        'department_id' => $employee['department_id'],
                        'cadre_id' => $employee['cadre_id'],
                        'grade_level_id' => $employee['grade_level_id'],
                        'step_id' => $employee['step_id'],
                        'expected_next_promotion' => $employee['expected_next_promotion'],
                        'expected_retirement_date' => $employee['expected_retirement_date'],
                        'status' => $status,
                        'employment_status' => $employee['status'],
                        'highest_qualifications' => $employee['highest_certificate'],
                        'appointment_type_id' => $employee['appointment_type_id'],
                        'photo_path' => $employee['photo_path'],
                        'years_of_service' => $employee['years_of_service'],
                    ]
                );

                if ($staff->wasRecentlyCreated) {
                    $staff->assignRole('staff');
                }

                // Sync bank details
                if (isset($employee['bank'])) {
                    $staff->bank()->updateOrCreate(
                        ['staff_id' => $staff->id],
                        [
                            'bank_name' => $employee['bank']['bank_name'],
                            'bank_code' => $employee['bank']['bank_code'],
                            'account_name' => $employee['bank']['account_name'],
                            'account_no' => $employee['bank']['account_no'],
                        ]
                    );
                }

                // Sync next of kin details
                if (isset($employee['next_of_kin'])) {
                    $staff->nextOfKin()->updateOrCreate(
                        ['staff_id' => $staff->id],
                        [
                            'name' => $employee['next_of_kin']['name'],
                            'relationship' => $employee['next_of_kin']['relationship'],
                            'mobile_no' => $employee['next_of_kin']['mobile_no'],
                            'address' => $employee['next_of_kin']['address'],
                            'occupation' => $employee['next_of_kin']['occupation'],
                            'place_of_work' => $employee['next_of_kin']['place_of_work'],
                        ]
                    );
                }

                $syncedCount++;
            }

            return redirect()->route('staff.hr.staff.index')->with('success', "Successfully synced {$syncedCount} staff records.");

        } catch (\Exception $e) {
            \Log::error('Error in staff sync: ' . $e->getMessage());
            return back()->with('error', 'Error syncing staff data: ' . $e->getMessage());
        }
    }

    private function syncHrmData()
    {
        // Fetch all data from HRM
        $hrmData = $this->hrmService->getEmployees();

        if (!$hrmData || !isset($hrmData['data'])) {
            return null;
        }

        $employees = $hrmData['data'];

        foreach ($employees as &$employee) {
            // Sync LGAs
            if (isset($employee['lga'])) {
                $lga = \App\Models\Lga::updateOrCreate(['code' => $employee['lga']['name']], ['name' => $employee['lga']['name']]);
                $employee['lga_id'] = $lga->id;
            }

            // Sync Departments
            if (isset($employee['department'])) {
                \App\Models\Department::updateOrCreate(['id' => $employee['department']['department_id']], ['name' => $employee['department']['department_name']]);
            }

            // Sync Cadres
            if (isset($employee['cadre'])) {
                \App\Models\Cadre::updateOrCreate(['id' => $employee['cadre']['cadre_id']], ['name' => $employee['cadre']['name']]);
            }

            // Sync Grade Levels
            if (isset($employee['grade_level'])) {
                $gradeLevel = \App\Models\GradeLevel::updateOrCreate(['id' => $employee['grade_level']['id']], ['name' => $employee['grade_level']['name']]);
                $employee['grade_level_id'] = $gradeLevel->id;
            }

            // Sync Ranks
            if (isset($employee['rank'])) {
                \App\Models\Rank::updateOrCreate(['id' => $employee['rank']['id']], ['name' => $employee['rank']['name']]);
            }
        }

        foreach ($employees as &$employee) {
            // Sync Wards
            if (isset($employee['ward'])) {
                $ward = \App\Models\Ward::updateOrCreate(['code' => $employee['ward']['ward_name']], ['name' => $employee['ward']['ward_name'], 'lga_id' => $employee['lga_id']]);
                $employee['ward_id'] = $ward->id;
            }

            // Sync Steps
            if (isset($employee['step'])) {
                $step = \App\Models\Step::updateOrCreate(['id' => $employee['step']['id']], ['name' => $employee['step']['name'], 'grade_level_id' => $employee['grade_level_id']]);
                $employee['step_id'] = $step->id;
            }
        }

        return $employees;
    }

    public function getWards(Request $request, Lga $lga)
    {
        return $lga->wards;
    }

    public function getAreas(Request $request, Ward $ward)
    {
        return $ward->areas;
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

    public function createSync()
    {
        $lgas = \App\Models\Lga::all();
        $wards = \App\Models\Ward::all();
        $areas = \App\Models\Area::all();
        
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
            return redirect()->route('staff.hr.staff.index')->with('success', 'Staff member created and synced successfully.');
        } else {
            return redirect()->route('staff.hr.staff.index')->with('warning', 'Staff member created locally, but failed to sync with HRM system.');
        }
    }
}
