<?php

namespace App\Http\Controllers;

use App\Models\VendorPayment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VendorPaymentController extends Controller
{
    protected $baseUrl;
    protected $apiKey;
    protected $secret;

    public function __construct()
    {
        $this->middleware(['auth:vendor', 'restrict.login']);
        $this->baseUrl = config('services.nabroll.base_url');
        $this->apiKey = config('services.nabroll.api_key');
        $this->secret = config('services.nabroll.secret_key');
    }

    public function fundAccount(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = number_format($validated['amount'], 2, '.', '');
        $payerRefNo = 'VENDOR_FUND_' . now()->format('YmdHis') . '_' . Str::random(10);

        // Generate hash for NABRoll - format: PayerRefNo + Amount + ApiKey
        $hashString = $payerRefNo . $amount . $this->apiKey;
        $hash = hash_hmac('sha256', $hashString, $this->secret);

        // Create vendor payment record for funding
        DB::beginTransaction();
        try {
            $vendorPayment = VendorPayment::create([
                'vendor_id' => $vendor->id,
                'amount' => $amount,
                'payment_date' => now(),
                'method' => 'NABRoll',
                'status' => 'pending',
                'payment_status' => 'pending',
                'payer_ref_no' => $payerRefNo,
                'channel' => 'Vendor Account Funding',
                'transaction_type' => 'funding', // Mark as funding transaction
            ]);

            // Initiate NABRoll transaction for funding
            $metadata = "vendor_funding_id:{$vendorPayment->id}";
            $payload = [
                'ApiKey' => $this->apiKey,
                'Hash' => $hash,
                'Amount' => $amount,
                'PayerRefNo' => $payerRefNo,
                'PayerName' => $vendor->name,
                'Email' => $vendor->email,
                'Mobile' => '08000000000', // Default mobile for vendor
                'Description' => 'Vendor account funding',
                'ResponseUrl' => route('vendor.payments.fund.callback'),
                'FeeBearer' => 'Merchant', // Options: Customer, Merchant
                'MetaData' => $metadata,
            ];

            Log::debug('Initiating NABRoll transaction for vendor funding', ['payload' => $payload]);

            $response = Http::asForm()->post("{$this->baseUrl}/transactions/initiate", $payload);
            $result = $response->json();

            if ($response->failed() || !isset($result['status']) || $result['status'] !== 'SUCCESSFUL') {
                Log::error('NABRoll transaction initiation failed for vendor funding', [
                    'vendor_id' => $vendor->id,
                    'vendor_payment_id' => $vendorPayment->id,
                    'response' => $result,
                ]);
                $vendorPayment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
                DB::commit();
                return back()->withErrors(['error' => 'Failed to initiate funding: ' . ($result['msg'] ?? 'Unknown error')])->withInput();
            }

            $vendorPayment->update([
                'transaction_ref' => $result['TransactionRef'],
                'payment_code' => $result['PaymentCode'],
                'nabroll_ref' => $result['TransactionRef'],
            ]);

            Log::info('NABRoll transaction initiated for vendor funding', [
                'vendor_payment_id' => $vendorPayment->id,
                'transaction_ref' => $result['TransactionRef'],
                'payment_code' => $result['PaymentCode'],
                'payment_url' => $result['PaymentUrl'],
            ]);

            DB::commit();
            return redirect($result['PaymentUrl']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor funding initiation failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Funding initiation failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function fundCallback(Request $request)
    {
        $transactionRef = $request->query('TransactionRef');
        if (!$transactionRef) {
            Log::error('Missing transaction reference in vendor funding callback', ['query' => $request->query()]);
            return redirect()->route('vendor.dashboard')->with('error', 'Missing transaction reference.');
        }

        $vendorPayment = VendorPayment::where('transaction_ref', $transactionRef)
            ->where('channel', 'Vendor Account Funding')
            ->first();
            
        if (!$vendorPayment) {
            Log::error('Vendor funding payment not found for transaction reference', ['transaction_ref' => $transactionRef]);
            return redirect()->route('vendor.dashboard')->with('error', 'Funding payment not found.');
        }

        // Verify transaction with NABRoll - format: PayerRefNo + Amount + TransactionRef + ApiKey
        $amount = number_format($vendorPayment->amount, 2, '.', '');
        $hashString = $vendorPayment->payer_ref_no . $amount . $transactionRef . $this->apiKey;
        $hash = hash_hmac('sha256', $hashString, $this->secret);

        $verifyPayload = [
            'ApiKey' => $this->apiKey,
            'Hash' => $hash,
            'TransactionRef' => $transactionRef,
        ];

        Log::debug('Verifying NABRoll transaction for vendor funding', ['payload' => $verifyPayload]);

        $response = Http::asForm()->post("{$this->baseUrl}/transactions/verify", $verifyPayload);
        $result = $response->json();

        Log::debug('NABRoll verify response for vendor funding', [
            'vendor_payment_id' => $vendorPayment->id,
            'transaction_ref' => $transactionRef,
            'response' => $result,
        ]);

        if ($response->failed() || !isset($result['status']) || $result['status'] !== 'SUCCESSFUL') {
            $vendorPayment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
            Log::error('Vendor funding verification failed', [
                'vendor_payment_id' => $vendorPayment->id,
                'transaction_ref' => $transactionRef,
                'response' => $result,
            ]);
            return redirect()->route('vendor.dashboard')->with('error', 'Funding verification failed: ' . ($result['msg'] ?? 'Unknown error'));
        }

        // Process funding - add to vendor account balance
        DB::beginTransaction();
        try {
            $vendor = $vendorPayment->vendor;
            $vendor->fundAccount($vendorPayment->amount);

            Log::info('Vendor account funded successfully', [
                'vendor_id' => $vendor->id,
                'vendor_payment_id' => $vendorPayment->id,
                'amount' => $vendorPayment->amount,
                'new_account_balance' => $vendor->account_balance,
            ]);

            $vendorPayment->update([
                'payment_status' => 'SUCCESSFUL',
                'status' => 'successful',
                'channel' => $result['Channel'] ?? 'Unknown',
                'nabroll_response' => json_encode($result),
            ]);

            DB::commit();
            Log::info('Vendor funding processed successfully', [
                'vendor_payment_id' => $vendorPayment->id,
                'transaction_ref' => $transactionRef,
                'vendor_new_account_balance' => $vendor->account_balance,
            ]);

            return redirect()->route('vendor.dashboard')->with('success', 'Account funded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $vendorPayment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
            Log::error('Vendor funding processing failed', ['error' => $e->getMessage()]);
            return redirect()->route('vendor.dashboard')->with('error', 'Funding processing failed: ' . $e->getMessage());
        }
    }

    public function initiatePayment(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        $validated = $request->validate([
            'billing_id' => 'required|string|exists:customers,billing_id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|string|in:online,account',
        ]);

        // Find customer by billing ID
        $customer = Customer::where('billing_id', $validated['billing_id'])->first();
        if (!$customer) {
            return back()->withErrors(['billing_id' => 'Customer not found'])->withInput();
        }

        $amount = number_format($validated['amount'], 2, '.', '');

        // Handle payment based on type
        if ($validated['payment_type'] === 'account') {
            // Pay from account balance
            return $this->processAccountPayment($vendor, $customer, $amount);
        } else {
            // Pay online through NABRoll
            return $this->initiateOnlinePayment($vendor, $customer, $amount);
        }
    }

    private function processAccountPayment($vendor, $customer, $amount)
    {
        // Check if vendor has sufficient balance
        if ($vendor->account_balance < $amount) {
            return back()->withErrors(['amount' => 'Insufficient account balance'])->withInput();
        }

        // Deduct amount from vendor account balance
        $vendor->deductAccountBalance($amount);

        // Add amount to customer account balance
        $customer->account_balance += $amount;
        $customer->save();

        // Create vendor payment record for account payment
        $vendorPayment = VendorPayment::create([
            'vendor_id' => $vendor->id,
            'customer_id' => $customer->id,
            'billing_id' => $customer->billing_id,
            'amount' => $amount,
            'payment_date' => now(),
            'method' => 'Account Balance',
            'status' => 'successful',
            'payment_status' => 'SUCCESSFUL',
            'payer_ref_no' => 'ACCOUNT_PAYMENT_' . now()->format('YmdHis') . '_' . uniqid(),
            'channel' => 'Vendor Account Payment',
            'transaction_type' => 'payment', // Mark as payment transaction
        ]);

        // Also create a record in the customer's payment table
        $customerPayment = \App\Models\Payment::create([
            'customer_id' => $customer->id,
            'amount' => $amount,
            'payment_date' => now(),
            'method' => 'Vendor Account',
            'status' => 'successful',
            'payment_status' => 'SUCCESSFUL',
            'payer_ref_no' => 'VENDOR_ACCOUNT_' . now()->format('YmdHis') . '_' . uniqid(),
            'channel' => 'Vendor Payment',
            'transaction_ref' => 'VENDOR_PAYMENT_' . $vendorPayment->id,
            'payment_code' => 'VP_' . $vendorPayment->id,
        ]);

        // Apply account balance to outstanding bills
        $customer->applyAccountBalanceToBills();

        Log::info('Vendor account payment processed successfully', [
            'vendor_payment_id' => $vendorPayment->id,
            'customer_payment_id' => $customerPayment->id,
            'vendor_id' => $vendor->id,
            'customer_id' => $customer->id,
            'amount' => $amount,
            'vendor_new_account_balance' => $vendor->account_balance,
            'customer_new_account_balance' => $customer->account_balance,
        ]);

        return redirect()->route('vendor.dashboard')->with('success', 'Payment processed successfully from account balance.');
    }

    private function initiateOnlinePayment($vendor, $customer, $amount)
    {
        $payerRefNo = 'VENDOR_PAYMENT_' . now()->format('YmdHis') . '_' . Str::random(10);

        // Generate hash for NABRoll - format: PayerRefNo + Amount + ApiKey
        $hashString = $payerRefNo . $amount . $this->apiKey;
        $hash = hash_hmac('sha256', $hashString, $this->secret);

        // Create vendor payment record
        DB::beginTransaction();
        try {
            $vendorPayment = VendorPayment::create([
                'vendor_id' => $vendor->id,
                'customer_id' => $customer->id,
                'billing_id' => $customer->billing_id,
                'amount' => $amount,
                'payment_date' => now(),
                'method' => 'NABRoll',
                'status' => 'pending',
                'payment_status' => 'pending',
                'payer_ref_no' => $payerRefNo,
                'channel' => 'Vendor Online Payment',
                'transaction_type' => 'payment', // Mark as payment transaction
            ]);

                    // Initiate NABRoll transaction
                    $metadata = "vendor_payment_id:{$vendorPayment->id}";
                            $payload = [
                                'ApiKey' => $this->apiKey,
                                'Hash' => $hash,
                                'Amount' => $amount,
                                'PayerRefNo' => $payerRefNo,
                                'PayerName' => $vendor->name,
                                'Email' => $vendor->email,
                                'Mobile' => '08000000000', // Default mobile for vendor
                                'Description' => 'Payment for customer water bill',
                                'ResponseUrl' => route('vendor.payments.callback'),
                                'FeeBearer' => 'Merchant', // Options: Customer, Merchant
                                'MetaData' => $metadata,
                            ];            Log::debug('Initiating NABRoll transaction for vendor payment', ['payload' => $payload]);

            $response = Http::asForm()->timeout(30)->retry(2, 500)->post("{$this->baseUrl}/transactions/initiate", $payload);

            // Log the raw response for debugging
            Log::debug('NABRoll response details for vendor payment', [
                'status_code' => $response->status(),
                'body' => (string)$response->body(),
                'headers' => $response->headers(),
            ]);

            $result = $response->json();

            if ($response->failed() || is_null($result) || !isset($result['status']) || $result['status'] !== 'SUCCESSFUL') {
                Log::error('NABRoll transaction initiation failed for vendor payment', [
                    'vendor_id' => $vendor->id,
                    'vendor_payment_id' => $vendorPayment->id,
                    'status_code' => $response->status(),
                    'response_body' => (string)$response->body(),
                    'result' => $result,
                ]);
                $vendorPayment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
                DB::commit();
                $errorMessage = $result['msg'] ?? $this->getSpecificErrorMessage((string)$response->body(), $response->status());
                return back()->withErrors(['error' => 'Failed to initiate payment: ' . $errorMessage])->withInput();
            }

            $vendorPayment->update([
                'transaction_ref' => $result['TransactionRef'],
                'payment_code' => $result['PaymentCode'],
                'nabroll_ref' => $result['TransactionRef'],
            ]);

            Log::info('NABRoll transaction initiated for vendor payment', [
                'vendor_payment_id' => $vendorPayment->id,
                'transaction_ref' => $result['TransactionRef'],
                'payment_code' => $result['PaymentCode'],
                'payment_url' => $result['PaymentUrl'],
            ]);

            DB::commit();
            return redirect($result['PaymentUrl']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor payment initiation failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Payment initiation failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function callback(Request $request)
    {
        $transactionRef = $request->query('TransactionRef');
        if (!$transactionRef) {
            Log::error('Missing transaction reference in vendor callback', ['query' => $request->query()]);
            return redirect()->route('vendor.dashboard')->with('error', 'Missing transaction reference.');
        }

        $vendorPayment = VendorPayment::where('transaction_ref', $transactionRef)
            ->where('channel', 'Vendor Online Payment')
            ->first();
            
        if (!$vendorPayment) {
            Log::error('Vendor payment not found for transaction reference', ['transaction_ref' => $transactionRef]);
            return redirect()->route('vendor.dashboard')->with('error', 'Payment not found.');
        }

        // Verify transaction with NABRoll - format: PayerRefNo + Amount + TransactionRef + ApiKey
        $amount = number_format($vendorPayment->amount, 2, '.', '');
        $hashString = $vendorPayment->payer_ref_no . $amount . $transactionRef . $this->apiKey;
        $hash = hash_hmac('sha256', $hashString, $this->secret);

        $verifyPayload = [
            'ApiKey' => $this->apiKey,
            'Hash' => $hash,
            'TransactionRef' => $transactionRef,
        ];

        Log::debug('Verifying NABRoll transaction for vendor payment', ['payload' => $verifyPayload]);

        $response = Http::asForm()->post("{$this->baseUrl}/transactions/verify", $verifyPayload);
        $result = $response->json();

        Log::debug('NABRoll verify response for vendor payment', [
            'vendor_payment_id' => $vendorPayment->id,
            'transaction_ref' => $transactionRef,
            'response' => $result,
        ]);

        if ($response->failed() || !isset($result['status']) || $result['status'] !== 'SUCCESSFUL') {
            $vendorPayment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
            Log::error('Vendor payment verification failed', [
                'vendor_payment_id' => $vendorPayment->id,
                'transaction_ref' => $transactionRef,
                'response' => $result,
            ]);
            return redirect()->route('vendor.dashboard')->with('error', 'Payment verification failed: ' . ($result['msg'] ?? 'Unknown error'));
        }

        // Process payment - add to customer account balance
        DB::beginTransaction();
        try {
            $customer = $vendorPayment->customer;
            $customer->account_balance += $vendorPayment->amount;
            $customer->save();

            Log::info('Vendor payment added to customer account balance', [
                'customer_id' => $customer->id,
                'vendor_payment_id' => $vendorPayment->id,
                'amount' => $vendorPayment->amount,
                'new_account_balance' => $customer->account_balance,
            ]);

            // Also create a record in the customer's payment table
            $customerPayment = \App\Models\Payment::create([
                'customer_id' => $customer->id,
                'amount' => $vendorPayment->amount,
                'payment_date' => now(),
                'method' => 'Vendor Online',
                'status' => 'successful',
                'payment_status' => 'SUCCESSFUL',
                'payer_ref_no' => 'VENDOR_ONLINE_' . now()->format('YmdHis') . '_' . uniqid(),
                'channel' => 'Vendor Payment',
                'transaction_ref' => $vendorPayment->transaction_ref,
                'payment_code' => $vendorPayment->payment_code,
            ]);

            // Apply account balance to outstanding bills
            $customer->applyAccountBalanceToBills();

            $vendorPayment->update([
                'payment_status' => 'SUCCESSFUL',
                'status' => 'successful',
                'channel' => $result['Channel'] ?? 'Unknown',
                'nabroll_response' => json_encode($result),
            ]);

            DB::commit();
            Log::info('Vendor payment processed successfully', [
                'vendor_payment_id' => $vendorPayment->id,
                'customer_payment_id' => $customerPayment->id,
                'transaction_ref' => $transactionRef,
                'customer_new_account_balance' => $customer->account_balance,
                'customer_total_bill_after_payment' => $customer->total_bill,
            ]);

            return redirect()->route('vendor.dashboard')->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $vendorPayment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
            Log::error('Vendor payment processing failed', ['error' => $e->getMessage()]);
            return redirect()->route('vendor.dashboard')->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        
        $query = $vendor->vendorPayments()
            ->payments() // Only payment transactions
            ->with('customer');
            
        // Apply filters
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('payment_status', $request->status);
        }
        
        if ($request->has('min_amount') && $request->min_amount) {
            $query->where('amount', '>=', $request->min_amount);
        }
        
        if ($request->has('max_amount') && $request->max_amount) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        $perPage = $request->input('per_page', 10);
        if ($perPage == 'all') {
            $vendorPayments = $query->orderBy('created_at', 'desc')->get();
        } else {
            $vendorPayments = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->except('page'));
        }
        
        // Get customers for filter dropdown
        $customers = $vendor->vendorPayments()
            ->payments()
            ->whereHas('customer')
            ->with('customer')
            ->get()
            ->pluck('customer')
            ->unique('id')
            ->sortBy('first_name');
        
        return view('vendor.payments.index', compact('vendorPayments', 'customers'));
    }

    public function fundingHistory(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        
        $query = $vendor->vendorPayments()
            ->funding() // Only funding transactions
            ->orderBy('created_at', 'desc');
            
        // Apply filters
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('payment_status', $request->status);
        }
        
        if ($request->has('min_amount') && $request->min_amount) {
            $query->where('amount', '>=', $request->min_amount);
        }
        
        if ($request->has('max_amount') && $request->max_amount) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        $perPage = $request->input('per_page', 10);
        if ($perPage == 'all') {
            $vendorPayments = $query->orderBy('created_at', 'desc')->get();
        } else {
            $vendorPayments = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->except('page'));
        }
        
        return view('vendor.payments.funding', compact('vendorPayments'));
    }

    /**
     * Get specific error message based on response and status code
     * Used to provide better error messages for different API response codes
     */
    private function getSpecificErrorMessage($responseBody, $statusCode) {
        if ($statusCode == 526) {
            return 'Payment gateway connection error (SSL/TLS failure). Please try again later.';
        } elseif ($statusCode == 502) {
            return 'Payment gateway temporarily unavailable. Please try again later.';
        } elseif ($statusCode == 503) {
            return 'Payment gateway service unavailable. Please try again later.';
        } elseif ($statusCode >= 500) {
            return "Server error ({$statusCode}). Please contact support.";
        } elseif ($statusCode == 400) {
            return 'Invalid request parameters. Please verify your information.';
        } elseif ($statusCode == 401) {
            return 'Authentication failed. Please contact support.';
        } elseif ($statusCode == 403) {
            return 'Access forbidden. Please contact support.';
        } elseif ($statusCode == 404) {
            return 'Payment gateway endpoint not found. Please contact support.';
        } elseif (strpos(strtolower($responseBody), 'ssl') !== false) {
            return 'Secure connection error. Please contact support.';
        } elseif (strpos(strtolower($responseBody), 'certificate') !== false) {
            return 'Certificate validation error. Please contact support.';
        }

        return "Error code: {$statusCode}";
    }
}
