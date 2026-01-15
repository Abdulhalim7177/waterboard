<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Tariff;
use App\Models\Bill;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $tariffs = Tariff::where('type', 'service')->get();
        return view('staff.connections.create', compact('customers', 'tariffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tariff_id' => 'required|exists:tariffs,id',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $tariff = Tariff::findOrFail($request->tariff_id);

        Bill::create([
            'customer_id' => $customer->id,
            'tariff_id' => $tariff->id,
            'billing_id' => $customer->billing_id,
            'amount' => $tariff->amount,
            'due_date' => Carbon::now()->addDays(30),
            'year_month' => Carbon::now()->format('Ym'),
            'billing_date' => Carbon::now(),
            'status' => 'pending',
            'balance' => $tariff->amount,
            'approval_status' => 'pending',
        ]);

        return redirect()->route('staff.connections.bills')->with('success', 'Bill created successfully.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function bills(Request $request)
    {
        $query = Bill::whereHas('tariff', function ($query) {
            $query->where('type', 'service');
        })->with(['customer.lga', 'customer.ward', 'customer.area', 'tariff']);

        // Filtering
        if ($request->filled('tariff_id')) {
            $query->where('tariff_id', $request->tariff_id);
        }

        if ($request->filled('lga_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('lga_id', $request->lga_id);
            });
        }
        
        if ($request->filled('ward_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('ward_id', $request->ward_id);
            });
        }

        if ($request->filled('area_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('billing_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('billing_date', '<=', $request->end_date);
        }

        $bills = $query->latest()->get();

        // Stats
        $stats = [
            'total' => $bills->count(),
            'paid' => $bills->where('status', 'paid')->count(),
            'unpaid' => $bills->where('status', '!=', 'paid')->count(),
            'pending_approval' => $bills->where('approval_status', 'pending')->count(),
        ];
        
        $lgas = Lga::all();
        $wards = Ward::all();
        $areas = Area::all();
        $tariffs = Tariff::where('type', 'service')->get();

        return view('staff.connections.bills', compact('bills', 'stats', 'lgas', 'wards', 'areas', 'tariffs'));
    }
}
