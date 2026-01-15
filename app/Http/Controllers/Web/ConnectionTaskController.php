<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\ConnectionTask;
use App\Models\Staff;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;

class ConnectionTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ConnectionTask::with(['bill.customer.lga', 'bill.customer.ward', 'bill.customer.area', 'staff']);

        // Filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }
        
        if ($request->filled('lga_id')) {
            $query->whereHas('bill.customer', function ($q) use ($request) {
                $q->where('lga_id', $request->lga_id);
            });
        }
        
        if ($request->filled('ward_id')) {
            $query->whereHas('bill.customer', function ($q) use ($request) {
                $q->where('ward_id', $request->ward_id);
            });
        }

        if ($request->filled('area_id')) {
            $query->whereHas('bill.customer', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $tasks = $query->latest()->get();

        // Stats
        $stats = [
            'total' => $tasks->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'assigned' => $tasks->where('status', 'assigned')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'cancelled' => $tasks->where('status', 'cancelled')->count(),
        ];

        $staff = Staff::all();
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();

        return view('staff.connection-tasks.index', compact('tasks', 'stats', 'staff', 'lgas', 'wards', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = ConnectionTask::with(['bill.customer', 'staff'])->findOrFail($id);
        $staff = Staff::all();
        $customerPipePath = $task->bill->customer->pipe_path;
        return view('staff.connection-tasks.edit', compact('task', 'staff', 'customerPipePath'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'staff_id' => 'nullable|exists:staff,id',
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $task = ConnectionTask::findOrFail($id);
        $task->update($request->except('pipe_path')); // Exclude pipe_path from mass assignment

        if ($task->status === 'completed') {
            $customer = $task->bill->customer;
            // Copy customer's pipe_path to task's pipe_path when task is completed
            $task->pipe_path = $customer->pipe_path;
            $task->save();
        }

        return redirect()->route('staff.connection-tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
