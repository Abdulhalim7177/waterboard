<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class VendorController extends Controller
{
    /**
     * Register a new vendor
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vendor registered successfully',
            'data' => $vendor
        ], 201);
    }

    /**
     * Login a vendor
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::guard('vendor')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $vendor = Auth::guard('vendor')->user();
        $token = $vendor->createToken('vendor-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'vendor' => $vendor,
                'token' => $token,
            ]
        ]);
    }

    /**
     * Logout a vendor
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Make a payment for a customer using billing ID
     */
    public function makePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_id' => 'required|string|exists:customers,billing_id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,pos,transfer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the customer by billing ID
        $customer = Customer::where('billing_id', $request->billing_id)->first();
        
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        // Get the vendor making the payment
        $vendor = Auth::guard('vendor')->user();

        // Create a payment record
        $payment = Payment::create([
            'customer_id' => $customer->id,
            'amount' => $request->amount,
            'payment_date' => now(),
            'method' => $request->payment_method,
            'status' => 'pending',
            'payment_status' => 'SUCCESSFUL', // Assuming vendor payments are immediately successful
            'channel' => 'Vendor Payment',
            'transaction_ref' => 'VENDOR_' . uniqid(),
            'payment_code' => 'VPC_' . uniqid(),
            'payer_ref_no' => 'VENDOR_PAYMENT_' . $vendor->id . '_' . time(),
        ]);

        // If you want to associate with a specific bill, you can find the latest unpaid bill
        $bill = $customer->bills()
            ->where('approval_status', 'approved')
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->first();

        if ($bill) {
            $payment->bill_id = $bill->id;
            $payment->bill_ids = (string) $bill->id;
            $payment->save();
            
            // Update the bill balance and status
            $bill->updateBalanceAndStatus();
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => [
                'payment' => $payment,
                'customer' => $customer,
            ]
        ]);
    }

    /**
     * Get vendor profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}