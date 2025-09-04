<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PendingCustomerUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * List customers with filtering and pagination
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // $this->authorize('view-customers', Customer::class);

        try {
            $customers = Customer::with(['lga', 'ward', 'area', 'category', 'tariff'])
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

            return response()->json([
                'success' => true,
                'data' => $customers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // $this->authorize('create-customer', Customer::class);

            $validator = Validator::make($request->all(), [
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
                'street_name' => 'required|string|max:255',
                'house_number' => 'required|string|max:255',
                'landmark' => 'required|string|max:255',
                'delivery_code' => 'nullable|string|max:255',
                'billing_condition' => 'required|in:Metered,Non-Metered',
                'water_supply_status' => 'required|in:Functional,Non-Functional',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'altitude' => 'nullable|numeric',
                'pipe_path' => 'nullable|string',
                'polygon_coordinates' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customerData = $request->all();
            $customerData['password'] = Hash::make($customerData['password']);
            $customerData['status'] = 'pending';
            $customerData['created_by'] = Auth::guard('staff')->id();

            $customer = Customer::create($customerData);

            // The creation event is automatically logged by the Auditable trait

            return response()->json([
                'success' => true,
                'message' : 'Customer created successfully and is pending approval.',
                'data' => $customer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified customer
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $customer = Customer::with(['lga', 'ward', 'area', 'category', 'tariff'])->find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // $this->authorize('view-customer', $customer);

            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // $this->authorize('edit-customer', $customer);

            $validator = Validator::make($request->all(), [
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
                'street_name' => 'string|max:255',
                'house_number' => 'string|max:255',
                'landmark' => 'string|max:255',
                'delivery_code' => 'nullable|string|max:255',
                'billing_condition' => 'in:Metered,Non-Metered',
                'water_supply_status' => 'in:Functional,Non-Functional',
                'latitude' => 'numeric|between:-90,90',
                'longitude' => 'numeric|between:-180,180',
                'altitude' => 'nullable|numeric',
                'pipe_path' => 'nullable|string',
                'polygon_coordinates' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updatesCreated = false;
            $changes = [];
            foreach ($request->all() as $field => $newValue) {
                if (in_array($field, ['password_confirmation', 'status'])) {
                    continue;
                }
                $oldValue = $customer->$field ?? null;
                if ($field === 'password' && !$newValue) {
                    continue;
                }
                if ($newValue != $oldValue) {
                    PendingCustomerUpdate::create([
                        'customer_id' => $customer->id,
                        'field' => $field,
                        'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                        'new_value' => is_array($newValue) ? json_encode($newValue) : ($field === 'password' ? Hash::make($newValue) : $newValue),
                        'updated_by' => Auth::guard('staff')->id(),
                    ]);
                    $changes[$field] = ['old' => $oldValue, 'new' => $newValue];
                    $updatesCreated = true;
                }
            }

            if (!$updatesCreated) {
                return response()->json([
                    'success' => true,
                    'message' => 'No changes detected to submit for approval.'
                ], 200);
            }

            // Log the submission of pending updates
            $customer->logAuditEvent('pending_updates_submitted', $changes);

            return response()->json([
                'success' => true,
                'message' => 'Update submitted for approval.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified customer
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // $this->authorize('delete-customer', $customer);

            // Log the deletion event before actually deleting
            $customer->logAuditEvent('deleted', [
                'customer_data' => $customer->toArray()
            ]);

            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve the specified customer
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // $this->authorize('approve-customer', $customer);

            $oldStatus = $customer->status;
            $customer->status = 'approved';
            $customer->save();

            if (!$customer->billing_id) {
                $customer->billing_id = Customer::generateBillingId($customer);
                $customer->save();
            }

            PendingCustomerUpdate::where('customer_id', $customer->id)
                ->where('status', 'pending')
                ->update(['status' => 'approved']);

            // Log the approval event
            $customer->logAuditEvent('approved', [
                'old' => ['status' => $oldStatus],
                'new' => ['status' => 'approved']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject the specified customer
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // $this->authorize('reject-customer', $customer);

            $oldStatus = $customer->status;
            $customer->status = 'rejected';
            $customer->save();

            PendingCustomerUpdate::where('customer_id', $customer->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            // Log the rejection event
            $customer->logAuditEvent('rejected', [
                'old' => ['status' => $oldStatus],
                'new' => ['status' => 'rejected']
            ]);

            return response()->json([
                'success' => true,
                'message' : 'Customer rejected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List pending customers
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendingCustomers(Request $request)
    {
        try {
            // Get pending customer creations
            $pendingCustomers = Customer::with(['lga', 'ward', 'area', 'category', 'tariff', 'staff'])
                ->where('status', 'pending')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $pendingCustomers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving pending customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List pending customer updates
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendingUpdates(Request $request)
    {
        try {
            // Get pending customer updates
            $pendingUpdates = PendingCustomerUpdate::with(['customer', 'staff'])
                ->where('status', 'pending')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $pendingUpdates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving pending updates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a pending customer update
     *
     * @param  int  $updateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function approvePending($updateId)
    {
        try {
            $update = PendingCustomerUpdate::with(['customer', 'staff'])->find($updateId);

            if (!$update) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pending update not found'
                ], 404);
            }

            // $this->authorize('approve-customer', $update->customer);

            $customer = $update->customer;
            $field = $update->field;
            $oldValue = $customer->$field;
            $newValue = $update->new_value;

            if (in_array($field, ['polygon_coordinates', 'pipe_path']) && $newValue) {
                $newValue = json_decode($newValue, true);
            }

            $customer->update([$field => $newValue]);
            $update->update(['status' => 'approved']);

            // Log the approval of pending update event
            $customer->logAuditEvent('pending_update_approved', [
                'old' => [$field => $oldValue],
                'new' => [$field => $newValue],
                'pending_update_id' => $update->id
            ]);

            return response()->json([
                'success' => true,
                'message' : 'Update approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving pending update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a pending customer update
     *
     * @param  int  $updateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectPending($updateId)
    {
        try {
            $update = PendingCustomerUpdate::with(['customer', 'staff'])->find($updateId);

            if (!$update) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pending update not found'
                ], 404);
            }

            // $this->authorize('reject-customer', $update->customer);

            $update->update(['status' => 'rejected']);

            // Log the rejection of pending update event
            $update->customer->logAuditEvent('pending_update_rejected', [
                'pending_update_id' => $update->id,
                'field' => $update->field,
                'old_value' => $update->old_value,
                'new_value' => $update->new_value
            ]);

            return response()->json([
                'success' => true,
                'message' : 'Update rejected successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' : 'Error rejecting pending update: ' . $e->getMessage()
            ], 500);
        }
    }
}