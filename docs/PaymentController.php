<?php
namespace App\Http\Controllers;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    protected $baseUrl;
    protected $apiKey;
    protected $secret;

    public function __construct()
    {
        $this->baseUrl = config('services.nabroll.base_url');
        $this->apiKey = config('services.nabroll.api_key');
        $this->secret = config('services.nabroll.secret_key');
    }

    public function callback(Request $request)
    {
        $transactionRef = $request->query('TransactionRef');
        if (!$transactionRef) {
            Log::error('Missing transaction reference in callback', ['query' => $request->query()]);
            return redirect()->route('customer.bills')->with('error', 'Missing transaction reference.');
        }

        $payment = Payment::where('transaction_ref', $transactionRef)->first();
        if (!$payment) {
            Log::error('Payment not found for transaction reference', ['transaction_ref' => $transactionRef]);
            return redirect()->route('customer.bills')->with('error', 'Payment not found.');
        }

        // Verify transaction with NABRoll
        $amount = number_format($payment->amount, 2, '.', '');
        $hashString = $payment->payer_ref_no . $amount . $transactionRef . $this->apiKey;
        $hash = hash_hmac('sha256', $hashString, $this->secret);

        $verifyPayload = [
            'ApiKey' => $this->apiKey,
            'Hash' => $hash,
            'TransactionRef' => $transactionRef,
        ];

        Log::debug('Verifying NABRoll transaction', ['payload' => $verifyPayload]);

        $response = Http::asForm()->post("{$this->baseUrl}/transactions/verify", $verifyPayload);
        $result = $response->json();

        Log::debug('NABRoll verify response', [
            'payment_id' => $payment->id,
            'transaction_ref' => $transactionRef,
            'response' => $result,
        ]);

        if ($response->failed() || !isset($result['status']) || $result['status'] !== 'SUCCESSFUL') {
            $payment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
            Log::error('Payment verification failed', [
                'payment_id' => $payment->id,
                'transaction_ref' => $transactionRef,
                'response' => $result,
            ]);
            return redirect()->route('customer.bills')->with('error', 'Payment verification failed: ' . ($result['msg'] ?? 'Unknown error'));
        }

        // Process payment
        DB::beginTransaction();
        try {
            $customer = $payment->customer;
            $customer->account_balance += $payment->amount;
            $customer->save();

            Log::info('Payment added to account balance', [
                'customer_id' => $customer->id,
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'new_account_balance' => $customer->account_balance,
            ]);

            // Apply account balance to outstanding bills
            $customer->applyAccountBalanceToBills();

            $payment->update([
                'payment_status' => 'SUCCESSFUL',
                'status' => 'successful',
                'channel' => $result['Channel'] ?? 'Unknown',
            ]);

            DB::commit();
            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'transaction_ref' => $transactionRef,
                'new_account_balance' => $customer->account_balance,
                'total_bill_after_payment' => $customer->total_bill,
            ]);

            return redirect()->route('customer.bills')->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $payment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
            Log::error('Payment processing failed', ['error' => $e->getMessage()]);
            return redirect()->route('customer.bills')->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }
}