<?php
namespace App\Http\Controllers;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    protected $baseUrl;
    protected $apiKey;
    protected $secret;

    public function __construct()
    {
        $this->middleware(['auth:customer', 'restrict.login'])->only([
            'dashboard', 'profile', 'updateProfile', 'bills', 'initiateNABRollPayment', 'payments', 'complaints', 'storeComplaint'
        ]);
        $this->baseUrl = config('services.nabroll.base_url');
        $this->apiKey = config('services.nabroll.api_key');
        $this->secret = config('services.nabroll.secret_key');
    }

    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.dashboard', compact('customer'));
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $validated = $request->validate([
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone_number' => 'required|unique:customers,phone_number,' . $customer->id,
            'alternate_phone_number' => 'nullable',
        ]);

        $customer->update($validated);
        return redirect()->route('customer.profile')->with('success', 'Profile updated');
    }

    public function bills()
    {
        $customer = Auth::guard('customer')->user();
        $query = $customer->bills()
            ->where('approval_status', 'approved')
            ->with('tariff')
            ->orderBy('due_date', 'asc');

        // Apply date filters if provided
        if (request('start_date')) {
            $query->where('billing_date', '>=', request('start_date'));
        }
        if (request('end_date')) {
            $query->where('billing_date', '<=', request('end_date'));
        }

        $bills = $query->paginate(10);
        return view('customer.bills.index', compact('customer', 'bills'));
    }

    public function payments()
    {
        $customer = Auth::guard('customer')->user();
        $payments = $customer->payments()->orderBy('payment_date', 'desc')->paginate(10);
        return view('customer.payments.index', compact('payments'));
    }

    public function initiateNABRollPayment(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = number_format($validated['amount'], 2, '.', '');
        $payerRefNo = 'REF' . now()->format('YmdHis') . '_' . Str::random(10);

        // Generate hash for NABRoll - format: PayerRefNo + Amount + ApiKey
        $hashString = $payerRefNo . $amount . $this->apiKey;
        $hash = hash_hmac('sha256', $hashString, $this->secret);

        // Create temporary payment record
        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'customer_id' => $customer->id,
                'bill_id' => null,
                'bill_ids' => null,
                'amount' => $amount,
                'payment_date' => now(),
                'method' => 'NABRoll',
                'payer_ref_no' => $payerRefNo,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Initiate NABRoll transaction
            $metadata = "payment_id:{$payment->id}";
            $payload = [
                'ApiKey' => $this->apiKey,
                'Hash' => $hash,
                'Amount' => $amount,
                'PayerRefNo' => $payerRefNo,
                'PayerName' => $customer->first_name . ' ' . $customer->surname,
                'Email' => $customer->email,
                'Mobile' => $customer->phone_number ?? '08000000000',
                'Description' => 'Payment for water bill(s)',
                'ResponseUrl' => route('payments.callback'),
                'FeeBearer' => 'Customer', // Options: Customer, Merchant
                'MetaData' => $metadata,
            ];

            Log::debug('Initiating NABRoll transaction', ['payload' => $payload]);

            $response = Http::asForm()->post("{$this->baseUrl}/transactions/initiate", $payload);
            $result = $response->json();

            if ($response->failed() || !isset($result['status']) || $result['status'] !== 'SUCCESSFUL') {
                Log::error('NABRoll transaction initiation failed', [
                    'customer_id' => $customer->id,
                    'payment_id' => $payment->id,
                    'response' => $result,
                ]);
                $payment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
                DB::commit();
                return redirect()->route('customer.bills')->with('error', 'Failed to initiate payment: ' . ($result['msg'] ?? 'Unknown error'));
            }

            $payment->update([
                'transaction_ref' => $result['TransactionRef'],
                'payment_code' => $result['PaymentCode'],
            ]);

            Log::info('NABRoll transaction initiated', [
                'payment_id' => $payment->id,
                'transaction_ref' => $result['TransactionRef'],
                'payment_code' => $result['PaymentCode'],
                'payment_url' => $result['PaymentUrl'],
            ]);

            DB::commit();
            return redirect($result['PaymentUrl']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment initiation failed', ['error' => $e->getMessage()]);
            return redirect()->route('customer.bills')->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }
}