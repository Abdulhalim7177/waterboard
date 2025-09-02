<?php
namespace App\Http\Controllers\Customer;
use App\Models\Bill;
use App\Models\Payment;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index(Request $request)
    {
        $query = Bill::query()
            ->where('customer_id', Auth::guard('customer')->id())
            ->with(['tariff']);

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('billing_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('billing_date', '<=', $endDate);
        }

        $bills = $query->orderBy('billing_date', 'DESC')->paginate(10)->appends($request->query());

        return view('customer.bills', compact('bills'));
    }

    public function pay(Request $request)
    {
        $request->validate([
            'bill_ids' => 'required|array',
            'bill_ids.*' => 'exists:bills,id,customer_id,' . Auth::guard('customer')->id(),
            'amount' => 'required|numeric|min:0',
        ]);

        $billIds = $request->input('bill_ids');
        $amount = $request->input('amount');

        $bills = Bill::whereIn('id', $billIds)
            ->where('customer_id', Auth::guard('customer')->id())
            ->where('approval_status', 'approved')
            ->get();

        if ($bills->isEmpty()) {
            return redirect()->back()->with('error', 'No valid bills selected for payment');
        }

        $totalDue = $bills->sum('balance');
        if ($amount > Auth::guard('customer')->user()->account_balance) {
            return redirect()->back()->with('error', 'Insufficient account balance');
        }

        if ($amount < $totalDue) {
            return redirect()->back()->with('error', 'Amount is less than the total due');
        }

        try {
            foreach ($bills as $bill) {
                $paymentAmount = min($amount, $bill->balance);
                if ($paymentAmount <= 0) {
                    continue;
                }

                Payment::create([
                    'customer_id' => Auth::guard('customer')->id(),
                    'bill_id' => $bill->id,
                    'amount' => $paymentAmount,
                    'payment_date' => now(),
                    'payment_status' => 'SUCCESSFUL',
                    'transaction_ref' => 'NABRoll_' . uniqid(),
                ]);

                $amount -= $paymentAmount;
                $bill->updateBalanceAndStatus();
            }

            Auth::guard('customer')->user()->update([
                'account_balance' => Auth::guard('customer')->user()->account_balance - $totalDue,
            ]);

            return redirect()->route('customer.bills')->with('success', 'Payment successful');
        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'customer_id' => Auth::guard('customer')->id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function downloadPdf(Bill $bill, Request $request)
    {
        if ($bill->customer_id !== Auth::guard('customer')->id()) {
            return redirect()->back()->with('error', 'Unauthorized access to bill');
        }

        if ($bill->approval_status !== 'approved') {
            return redirect()->back()->with('error', 'Cannot download PDF for unapproved bill');
        }

        try {
            return Pdf::view('pdf.bill', ['bill' => $bill])
                ->format('A4')
                ->withBrowsershot(function ($browsershot) {
                    $browsershot->setOption('dpi', 96)
                                ->setOption('defaultFont', 'DejaVu Sans');
                })
                ->download('bill_' . $bill->billing_id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to generate bill PDF', [
                'bill_id' => $bill->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}