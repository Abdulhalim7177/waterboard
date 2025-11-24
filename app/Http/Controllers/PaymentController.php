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

        // Verify transaction with NABRoll - format: PayerRefNo + Amount + TransactionRef + ApiKey
        $amount = number_format($payment->amount, 2, '.', '');
        $hashString = $payment->payer_ref_no . $amount . $transactionRef . $this->apiKey;
        $hash = hash_hmac('sha256', $hashString, $this->secret);

        $verifyPayload = [
            'ApiKey' => $this->apiKey,
            'Hash' => $hash,
            'TransactionRef' => $transactionRef,
        ];

        Log::debug('Verifying NABRoll transaction', ['payload' => $verifyPayload]);

        $response = Http::asForm()->timeout(30)->retry(2, 500)->post("{$this->baseUrl}/transactions/verify", $verifyPayload);

        // Log the raw response for debugging
        Log::debug('NABRoll verify response details', [
            'status_code' => $response->status(),
            'body' => (string)$response->body(),
            'headers' => $response->headers(),
        ]);

        $result = $response->json();

        Log::debug('NABRoll verify response', [
            'payment_id' => $payment->id,
            'transaction_ref' => $transactionRef,
            'response' => $result,
        ]);

        if ($response->failed() || is_null($result) || !isset($result['status']) || $result['status'] !== 'SUCCESSFUL') {
            $payment->update(['payment_status' => 'FAILED', 'status' => 'failed']);
            Log::error('Payment verification failed', [
                'payment_id' => $payment->id,
                'transaction_ref' => $transactionRef,
                'status_code' => $response->status(),
                'response_body' => (string)$response->body(),
                'response' => $result,
            ]);

            $errorMessage = $result['msg'] ?? $this->getSpecificErrorMessage((string)$response->body(), $response->status());
            return redirect()->route('customer.bills')->with('error', 'Payment verification failed: ' . $errorMessage);
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