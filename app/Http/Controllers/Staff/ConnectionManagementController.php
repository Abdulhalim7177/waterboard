<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ConnectionType;
use App\Models\ConnectionSize;
use App\Models\ConnectionFee;
use App\Models\CustomerConnection;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConnectionManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'restrict.login']);
    }

    public function index(Request $request)
    {
        $connections = CustomerConnection::with(['customer', 'connectionType', 'connectionSize'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('staff.connections.index', compact('connections'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'approved')->get(['id', 'first_name', 'surname', 'email', 'billing_id']);
        $connectionTypes = ConnectionType::where('is_active', true)->get();
        $connectionSizes = ConnectionSize::where('is_active', true)->get();

        return view('staff.connections.create', compact('customers', 'connectionTypes', 'connectionSizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'connection_type_id' => 'required|exists:connection_types,id',
            'connection_size_id' => 'nullable|exists:connection_sizes,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $connection = CustomerConnection::create([
                'customer_id' => $request->customer_id,
                'connection_type_id' => $request->connection_type_id,
                'connection_size_id' => $request->connection_size_id,
                'status' => 'pending', // Default to pending for approval
                'notes' => $request->notes,
                'installed_by' => auth()->id(),
            ]);

            return redirect()->route('staff.connections.index')->with('success', 'Connection record created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create customer connection', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create connection: ' . $e->getMessage());
        }
    }

    public function show(CustomerConnection $connection)
    {
        $connection->load(['customer', 'connectionType', 'connectionSize', 'installedBy']);

        return view('staff.connections.show', compact('connection'));
    }

    public function edit(CustomerConnection $connection)
    {
        $customers = Customer::where('status', 'approved')->get(['id', 'first_name', 'surname', 'email', 'billing_id']);
        $connectionTypes = ConnectionType::where('is_active', true)->get();
        $connectionSizes = ConnectionSize::where('is_active', true)->get();

        return view('staff.connections.edit', compact('connection', 'customers', 'connectionTypes', 'connectionSizes'));
    }

    public function update(Request $request, CustomerConnection $connection)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'connection_type_id' => 'required|exists:connection_types,id',
            'connection_size_id' => 'nullable|exists:connection_sizes,id',
            'status' => 'required|in:pending,approved,rejected,active,inactive',
            'notes' => 'nullable|string|max:500',
            'installation_date' => 'nullable|date',
        ]);

        try {
            $connection->update([
                'customer_id' => $request->customer_id,
                'connection_type_id' => $request->connection_type_id,
                'connection_size_id' => $request->connection_size_id,
                'status' => $request->status,
                'notes' => $request->notes,
                'installation_date' => $request->installation_date,
            ]);

            return redirect()->route('staff.connections.index')->with('success', 'Connection record updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update customer connection', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update connection: ' . $e->getMessage());
        }
    }

    public function destroy(CustomerConnection $connection)
    {
        try {
            $connection->delete();
            return redirect()->route('staff.connections.index')->with('success', 'Connection record deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete customer connection', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete connection: ' . $e->getMessage());
        }
    }

    public function approve(CustomerConnection $connection)
    {
        try {
            $connection->update(['status' => 'approved']);
            return redirect()->back()->with('success', 'Connection approved successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to approve customer connection', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to approve connection: ' . $e->getMessage());
        }
    }

    public function reject(CustomerConnection $connection)
    {
        try {
            $connection->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Connection rejected successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to reject customer connection', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to reject connection: ' . $e->getMessage());
        }
    }

    public function feesIndex()
    {
        $connectionTypes = ConnectionType::all();
        $connectionSizes = ConnectionSize::all();
        $fees = ConnectionFee::with(['connectionType', 'connectionSize'])->get();

        return view('staff.connections.fees.index', compact('connectionTypes', 'connectionSizes', 'fees'));
    }

    public function feesCreate()
    {
        $connectionTypes = ConnectionType::all();
        $connectionSizes = ConnectionSize::all();

        return view('staff.connections.fees.create', compact('connectionTypes', 'connectionSizes'));
    }

    public function feesStore(Request $request)
    {
        $request->validate([
            'connection_type_id' => 'required|exists:connection_types,id',
            'connection_size_id' => 'nullable|exists:connection_sizes,id',
            'fee_amount' => 'required|numeric|min:0',
        ]);

        try {
            // For legalisation and reconnection fees, size is not required
            $connectionType = ConnectionType::find($request->connection_type_id);
            if (in_array($connectionType->slug, ['legalisation', 'reconnection_fee'])) {
                $request->merge(['connection_size_id' => null]);
            }

            ConnectionFee::create([
                'connection_type_id' => $request->connection_type_id,
                'connection_size_id' => $request->connection_size_id,
                'fee_amount' => $request->fee_amount,
            ]);

            return redirect()->route('staff.connection-fees.index')->with('success', 'Connection fee created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create connection fee', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create connection fee: ' . $e->getMessage());
        }
    }

    public function feesEdit(ConnectionFee $fee)
    {
        $connectionTypes = ConnectionType::all();
        $connectionSizes = ConnectionSize::all();

        return view('staff.connections.fees.edit', compact('fee', 'connectionTypes', 'connectionSizes'));
    }

    public function feesUpdate(Request $request, ConnectionFee $fee)
    {
        $request->validate([
            'connection_type_id' => 'required|exists:connection_types,id',
            'connection_size_id' => 'nullable|exists:connection_sizes,id',
            'fee_amount' => 'required|numeric|min:0',
        ]);

        try {
            // For legalisation and reconnection fees, size is not required
            $connectionType = ConnectionType::find($request->connection_type_id);
            if (in_array($connectionType->slug, ['legalisation', 'reconnection_fee'])) {
                $request->merge(['connection_size_id' => null]);
            }

            $fee->update([
                'connection_type_id' => $request->connection_type_id,
                'connection_size_id' => $request->connection_size_id,
                'fee_amount' => $request->fee_amount,
            ]);

            return redirect()->route('staff.connection-fees.index')->with('success', 'Connection fee updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update connection fee', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update connection fee: ' . $e->getMessage());
        }
    }

    public function feesDestroy(ConnectionFee $fee)
    {
        try {
            $fee->delete();
            return redirect()->route('staff.connection-fees.index')->with('success', 'Connection fee deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete connection fee', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete connection fee: ' . $e->getMessage());
        }
    }
}