<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('view-customers', \App\Models\Customer::class);

        $customers = \App\Models\Customer::with(['lga', 'ward', 'area', 'category', 'tariff'])
            ->when($request->search_customer, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('billing_id', 'like', "%{$search}%");
                });
            })
            ->when($request->lga_filter, function ($query, $lga_id) {
                return $query->where('lga_id', $lga_id);
            })
            ->when($request->ward_filter, function ($query, $ward_id) {
                return $query->where('ward_id', $ward_id);
            })
            ->when($request->area_filter, function ($query, $area_id) {
                return $query->where('area_id', $area_id);
            })
            ->when($request->category_filter, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($request->tariff_filter, function ($query, $tariff_id) {
                return $query->where('tariff_id', $tariff_id);
            })
            ->when($request->status_filter, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($customers);
    }

    public function export(Request $request)
    {
        $this->authorize('view-customers', \App\Models\Customer::class);

        $filters = [
            'status' => $request->input('status'),
            'lga' => $request->input('lga_filter'),
            'ward' => $request->input('ward_filter'),
            'area' => $request->input('area_filter'),
            'category' => $request->input('category_filter'),
            'tariff' => $request->input('tariff_filter'),
            'search' => $request->input('search_customer'),
        ];

        $format = $request->input('format', 'csv');
        if (!in_array($format, ['csv', 'xlsx'])) {
            return response()->json(['error' => 'Invalid export format. Only CSV and Excel are supported.'], 400);
        }

        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $filename = 'customers_' . now()->format('Ymd_His') . '.' . $extension;

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Staff\CustomersExport($filters), $filename);
    }

    public function import(Request $request)
    {
        $this->authorize('create-customer', \App\Models\Customer::class);

        $request->validate([
            'file' => 'required|mimes:csv,xlsx|max:2048', // Max 2MB
        ]);

        $import = new \App\Imports\Staff\CustomersImport();
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        $errors = $import->getErrors();
        if (!empty($errors)) {
            return response()->json(['message' => 'Import completed with errors', 'errors' => $errors], 422);
        }

        return response()->json(['message' => 'Customers imported successfully and are pending approval.']);
    }

    public function downloadSample()
    {
        $this->authorize('create-customer', \App\Models\Customer::class);

        $filePath = public_path('samples/customer_import_sample.csv');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Sample file not found.'], 404);
        }

        return response()->download($filePath);
    }

    public function pending()
    {
        $this->authorize('approve-customer', \App\Models\Customer::class);

        $pendingUpdates = \App\Models\PendingCustomerUpdate::with(['customer', 'staff'])
            ->where('status', 'pending')
            ->paginate(10);

        return response()->json($pendingUpdates);
    }

    public function store(Request $request)
    {
        $this->authorize('create-customer', \App\Models\Customer::class);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone_number' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'area_id' => 'required|exists:areas,id',
            'lga_id' => 'required|exists:lgas,id',
            'ward_id' => 'required|exists:wards,id',
            'category_id' => 'required|exists:categories,id',
            'tariff_id' => 'required|exists:tariffs,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = \App\Models\Customer::create([
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'area_id' => $request->area_id,
            'lga_id' => $request->lga_id,
            'ward_id' => $request->ward_id,
            'category_id' => $request->category_id,
            'tariff_id' => $request->tariff_id,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        return response()->json($customer, 201);
    }

    public function show($id)
    {
        $customer = \App\Models\Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->authorize('view-customer', $customer);

        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $customer = \App\Models\Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->authorize('edit-customer', $customer);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'surname' => 'string|max:255',
            'email' => 'string|email|max:255|unique:customers,email,' . $id,
            'phone_number' => 'string|max:255',
            'password' => 'string|min:8',
            'area_id' => 'exists:areas,id',
            'lga_id' => 'exists:lgas,id',
            'ward_id' => 'exists:wards,id',
            'category_id' => 'exists:categories,id',
            'tariff_id' => 'exists:tariffs,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $updatesCreated = false;
        foreach ($request->all() as $field => $newValue) {
            if (in_array($field, ['password_confirmation', 'status'])) {
                continue; 
            }
            $oldValue = $customer->$field ?? null;
            if ($field === 'password' && !$newValue) {
                continue; 
            }
            if ($newValue != $oldValue) {
                \App\Models\PendingCustomerUpdate::create([
                    'customer_id' => $customer->id,
                    'field' => $field,
                    'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                    'new_value' => is_array($newValue) ? json_encode($newValue) : ($field === 'password' ? \Illuminate\Support\Facades\Hash::make($newValue) : $newValue),
                    'updated_by' => auth()->id(),
                ]);
                $updatesCreated = true;
            }
        }

        if (!$updatesCreated) {
            return response()->json(['message' => 'No changes detected to submit for approval.'], 200);
        }

        return response()->json(['message' => 'Update submitted for approval.'], 200);
    }

    public function destroy($id)
    {
        $customer = \App\Models\Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->authorize('delete-customer', $customer);

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }

    public function approve($id)
    {
        $customer = \App\Models\Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->authorize('approve-customer', $customer);

        $customer->status = 'approved';
        $customer->save();

        if (!$customer->billing_id) {
            $customer->billing_id = \App\Models\Customer::generateBillingId($customer);
            $customer->save();
        }

        \App\Models\PendingCustomerUpdate::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        return response()->json(['message' => 'Customer approved successfully']);
    }

    public function reject($id)
    {
        $customer = \App\Models\Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->authorize('reject-customer', $customer);

        $customer->status = 'rejected';
        $customer->save();

        \App\Models\PendingCustomerUpdate::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return response()->json(['message' => 'Customer rejected successfully']);
    }

    public function approvePending($updateId)
    {
        $update = \App\Models\PendingCustomerUpdate::find($updateId);

        if (!$update) {
            return response()->json(['message' => 'Pending update not found'], 404);
        }

        $this->authorize('approve-customer', $update->customer);

        $customer = $update->customer;
        $field = $update->field;
        $newValue = $update->new_value;

        if (in_array($field, ['polygon_coordinates', 'pipe_path']) && $newValue) {
            $newValue = json_decode($newValue, true);
        }

        $customer->update([$field => $newValue]);
        $update->update(['status' => 'approved']);

        return response()->json(['message' => 'Update approved successfully.']);
    }

    public function rejectPending($updateId)
    {
        $update = \App\Models\PendingCustomerUpdate::find($updateId);

        if (!$update) {
            return response()->json(['message' => 'Pending update not found'], 404);
        }

        $this->authorize('reject-customer', $update->customer);

        $update->update(['status' => 'rejected']);

        return response()->json(['message' => 'Update rejected successfully.']);
    }
}