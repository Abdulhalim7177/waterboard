<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\ComplaintService;
use App\Services\GLPIService;
use App\Models\Staff;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffComplaintController extends Controller
{
    protected $glpiService;

    public function __construct(GLPIService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    /**
     * Display a listing of complaints for staff
     */
    public function index()
    {
        // Get all complaints from GLPI
        $glpiResponse = ComplaintService::getAllFromGLPI($this->glpiService, [
            'sort' => 19, // Sort by date_mod
            'order' => 'DESC'
        ]);

        $complaints = collect();
        if ($glpiResponse && isset($glpiResponse['data'])) {
            $complaints = collect($glpiResponse['data']);
        }

        // Create a paginator manually since we're using GLPI data
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $currentItems = array_slice($complaints->toArray(), $offset, $perPage);
        $totalItems = count($complaints);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $totalItems,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('staff.complaints.index', ['complaints' => $paginator]);
    }

    /**
     * Display the specified complaint
     */
    public function show($id)
    {
        $complaint = ComplaintService::findInGLPI($id, $this->glpiService);

        if (!$complaint) {
            abort(404, 'Complaint not found');
        }

        // Get customer information from GLPI or local DB if needed
        $customer = null;
        if (isset($complaint['users_id_requester'])) {
            // Try to find the corresponding customer in your local database
            $customer = Customer::find($complaint['users_id_requester']);
        }

        return view('staff.complaints.show', compact('complaint', 'customer'));
    }

    /**
     * Assign complaint to a staff member
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:staff,id',
        ]);

        $staff = Staff::find($request->assigned_to);
        if (!$staff) {
            return redirect()->back()->with('error', 'Selected staff member not found.');
        }

        // Assign the complaint in GLPI
        $result = ComplaintService::assignInGLPI($id, $staff->id, $this->glpiService);

        if ($result) {
            return redirect()->route('staff.complaints.show', $id)
                ->with('success', 'Complaint assigned successfully.');
        } else {
            return redirect()->route('staff.complaints.show', $id)
                ->with('error', 'Failed to assign complaint. Please try again.');
        }
    }

    /**
     * Update complaint status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $request->status
        ];

        if ($request->filled('resolution_notes')) {
            $updateData['resolution_notes'] = $request->resolution_notes;
        }

        // Update complaint in GLPI
        $result = ComplaintService::updateInGLPI($id, $updateData, $this->glpiService);

        if ($result) {
            return redirect()->route('staff.complaints.show', $id)
                ->with('success', 'Complaint status updated successfully.');
        } else {
            return redirect()->route('staff.complaints.show', $id)
                ->with('error', 'Failed to update complaint status. Please try again.');
        }
    }
}
