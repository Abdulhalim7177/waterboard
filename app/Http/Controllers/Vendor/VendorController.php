<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\Payment;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:vendor');
    }

    public function dashboard()
    {
        return view('vendor.dashboard');
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'billing_id' => 'required|string|exists:customers,billing_id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,pos,transfer',
        ]);

        // Find the customer by billing ID
        $customer = Customer::where('billing_id', $request->billing_id)->first();
        
        if (!$customer) {
            return back()->withErrors(['billing_id' => 'Customer not found'])->withInput();
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

        return back()->with('success', 'Payment processed successfully');
    }

    public function payments()
    {
        $vendor = Auth::guard('vendor')->user();
        
        // Get payments made by this vendor (you might want to adjust this query based on your needs)
        $payments = Payment::where('channel', 'Vendor Payment')
            ->with('customer')
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('vendor.payments', compact('payments'));
    }
}