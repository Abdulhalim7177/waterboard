<?php

namespace App\Http\Controllers\Staff;

use App\Models\Audit;
use App\Models\Complaint;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'permission:view-complaints']);
    }

    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $query = Complaint::whereHas('customer', function ($query) use ($staff) {
            $query->where('lga_id', $staff->lga_id)
                  ->where('ward_id', $staff->ward_id)
                  ->where('area_id', $staff->area_id);
        })->with(['customer']);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sort: pending first, then by created_at descending
        $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'resolved')")
              ->orderBy('created_at', 'desc');

        $complaints = $query->paginate(10);

        // Get available complaint types for filter dropdown
        $complaintTypes = Complaint::distinct()->pluck('type');

        return view('staff.complaints', compact('complaints', 'complaintTypes'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $this->authorize('update-complaints', $complaint);

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
            'resolution_notes' => 'required_if:status,resolved|max:1000',
        ]);

        $staff = Auth::guard('staff')->user();

        if ($staff->hasRole('super-admin')) {
            $complaint->update([
                'status' => $request->status,
                'resolution_notes' => $request->resolution_notes,
            ]);

            $this->logAuditEvent($complaint, 'status_updated', [
                'status' => $request->status,
                'resolution_notes' => $request->resolution_notes,
            ]);

            return redirect()->route('staff.complaints.index')
                             ->with('success', 'Complaint status updated successfully.');
        }

        $this->logAuditEvent($complaint, 'status_update_requested', [
            'requested_status' => $request->status,
            'resolution_notes' => $request->resolution_notes,
        ]);

        return redirect()->route('staff.complaints.index')
                         ->with('success', 'Complaint status update submitted for super-admin approval.');
    }

    public function destroy(Request $request, Complaint $complaint)
    {
        $this->authorize('delete-complaints', $complaint);

        $staff = Auth::guard('staff')->user();

        if ($staff->hasRole('super-admin')) {
            $complaint->delete();

            $this->logAuditEvent($complaint, 'deleted', []);

            return redirect()->route('staff.complaints.index')
                             ->with('success', 'Complaint deleted successfully.');
        }

        $this->logAuditEvent($complaint, 'delete_requested', []);

        return redirect()->route('staff.complaints.index')
                         ->with('success', 'Complaint deletion submitted for super-admin approval.');
    }

    protected function logAuditEvent($model, $event, $metadata)
    {
        Audit::create([
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'event' => $event,
            'old_values' => json_encode($model->getOriginal()),
            'new_values' => json_encode(array_merge($model->getAttributes(), $metadata)),
            'user_type' => 'App\Models\Staff',
            'user_id' => Auth::guard('staff')->id(),
            'related_type' => null,
            'related_id' => null,
            'created_at' => now(),
        ]);
    }
}