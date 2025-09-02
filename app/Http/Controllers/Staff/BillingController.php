<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Tariff;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPdf\Facades\Pdf;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function index(Request $request)
    {
        $this->authorize('view-bill', Bill::class);

        $query = Bill::query()->with(['customer', 'customer.tariff', 'customer.category', 'customer.lga', 'customer.ward', 'customer.area'])
            ->join('customers', 'bills.customer_id', '=', 'customers.id')
            ->select('bills.*');

        if ($yearMonth = $request->input('year_month')) {
            $query->where('bills.year_month', $yearMonth);
        }
        if ($categoryId = $request->input('category_id')) {
            $query->where('customers.category_id', $categoryId);
        }
        if ($tariffId = $request->input('tariff_id')) {
            $query->where('customers.tariff_id', $tariffId);
        }
        if ($lgaId = $request->input('lga_id')) {
            $query->where('customers.lga_id', $lgaId);
        }
        if ($wardId = $request->input('ward_id')) {
            $query->where('customers.ward_id', $wardId);
        }
        if ($areaId = $request->input('area_id')) {
            $query->where('customers.area_id', $areaId);
        }
        if ($customerId = $request->input('customer_id')) {
            $query->where('customers.id', $customerId);
        }

        $bills = $query->orderBy('bills.created_at', 'DESC')->paginate(10)->appends($request->query());

        $categories = Category::where('status', 'approved')->get(['id', 'name']);
        $tariffs = Tariff::where('status', 'approved')->get(['id', 'name']);
        $lgas = Lga::where('status', 'approved')->get(['id', 'name']);
        $wards = Ward::where('status', 'approved')->get(['id', 'name']);
        $areas = Area::where('status', 'approved')->get(['id', 'name']);
        $customers = Customer::where('status', 'approved')->get(['id', 'first_name', 'surname', 'email']);

        $yearMonths = [];
        $currentYear = now()->year;
        for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
            for ($month = 12; $month >= 1; $month--) {
                $yearMonths[] = sprintf('%04d%02d', $year, $month);
            }
        }

        return view('staff.bills.index', compact('bills', 'categories', 'tariffs', 'lgas', 'wards', 'areas', 'yearMonths', 'customers'));
    }

    public function generateBills(Request $request)
    {
        $this->authorize('create-bill', Bill::class);

        $yearMonth = now()->format('Ym');
        $lastBillDate = Bill::where('year_month', $yearMonth)->max('created_at');
        if ($lastBillDate) {
            return redirect()->back()->with('error', 'Bills already generated for this month');
        }

        DB::beginTransaction();
        try {
            $customers = Customer::where('status', 'approved')->with('tariff')->get();
            if ($customers->isEmpty()) {
                return redirect()->back()->with('error', 'No approved customers found to generate bills');
            }

            foreach ($customers as $customer) {
                if (!$customer->tariff) {
                    Log::warning('Customer missing tariff', ['customer_id' => $customer->id]);
                    continue;
                }

                $tariff = $customer->tariff;
                $amount = $tariff->billing_type === 'Metered' && $customer->meter_reading
                    ? $tariff->rate * $customer->meter_reading + ($tariff->fixed_charge ?? 0)
                    : ($tariff->fixed_charge ?? $tariff->rate);

                $bill = Bill::create([
                    'customer_id' => $customer->id,
                    'tariff_id' => $tariff->id,
                    'billing_id' => $customer->billing_id,
                    'amount' => $amount,
                    'due_date' => now()->endOfMonth(),
                    'year_month' => $yearMonth,
                    'billing_date' => now(),
                    'status' => 'pending',
                    'balance' => $amount,
                    'approval_status' => 'pending',
                ]);
            }

            DB::commit();
            return redirect()->route('staff.bills.index')->with('success', 'Bills generated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bill generation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Bill generation failed: ' . $e->getMessage());
        }
    }

    public function approve(Bill $bill)
    {
        $this->authorize('approve-bill', Bill::class);
        try {
            $bill->update(['approval_status' => 'approved']);
            $bill->customer->applyAccountBalanceToBills();
            return redirect()->route('staff.bills.index')->with('success', 'Bill approved');
        } catch (\Exception $e) {
            Log::error('Bill approval failed', ['bill_id' => $bill->id, 'error' => $e->getMessage()]);
            return redirect()->route('staff.bills.index')->with('error', 'Bill approval failed: ' . $e->getMessage());
        }
    }

    public function approveAll(Request $request)
    {
        $this->authorize('approve-bill', Bill::class);

        DB::beginTransaction();
        try {
            $pendingBills = Bill::where('approval_status', 'pending')->with('customer')->get();
            if ($pendingBills->isEmpty()) {
                return redirect()->route('staff.bills.index')->with('error', 'No pending bills to approve');
            }

            foreach ($pendingBills as $bill) {
                if (!$bill->customer) {
                    Log::warning('Bill missing customer', ['bill_id' => $bill->id]);
                    continue;
                }
                $bill->update(['approval_status' => 'approved']);
                $bill->customer->applyAccountBalanceToBills();
                Log::info('Bill approved in bulk', [
                    'bill_id' => $bill->id,
                    'customer_id' => $bill->customer_id,
                ]);
            }

            DB::commit();
            return redirect()->route('staff.bills.index')->with('success', 'All pending bills approved');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk bill approval failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('staff.bills.index')->with('error', 'Bulk bill approval failed: ' . $e->getMessage());
        }
    }

    public function payments(Request $request)
    {
        $this->authorize('view-payment', Payment::class);

        $query = Payment::query()->with(['customer', 'bill'])
            ->join('customers', 'payments.customer_id', '=', 'customers.id')
            ->select('payments.*');

        if ($status = $request->input('status')) {
            $query->where('payments.payment_status', $status);
        }
        if ($customerId = $request->input('customer_id')) {
            $query->where('customers.id', $customerId);
        }
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('payments.payment_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('payments.payment_date', '<=', $endDate);
        }
        if ($categoryId = $request->input('category_id')) {
            $query->where('customers.category_id', $categoryId);
        }
        if ($tariffId = $request->input('tariff_id')) {
            $query->where('customers.tariff_id', $tariffId);
        }
        if ($lgaId = $request->input('lga_id')) {
            $query->where('customers.lga_id', $lgaId);
        }
        if ($wardId = $request->input('ward_id')) {
            $query->where('customers.ward_id', $wardId);
        }
        if ($areaId = $request->input('area_id')) {
            $query->where('customers.area_id', $areaId);
        }

        $payments = $query->orderBy('payments.payment_date', 'DESC')->paginate(10)->appends($request->query());

        $customers = Customer::where('status', 'approved')->get(['id', 'first_name', 'surname', 'email']);
        $statuses = ['pending', 'SUCCESSFUL', 'FAILED'];
        $categories = Category::where('status', 'approved')->get(['id', 'name']);
        $tariffs = Tariff::where('status', 'approved')->get(['id', 'name']);
        $lgas = Lga::where('status', 'approved')->get(['id', 'name']);
        $wards = Ward::where('status', 'approved')->get(['id', 'name']);
        $areas = Area::where('status', 'approved')->get(['id', 'name']);

        return view('staff.payments.index', compact('payments', 'customers', 'statuses', 'categories', 'tariffs', 'lgas', 'wards', 'areas'));
    }

    public function reject(Bill $bill)
    {
        $this->authorize('reject-bill', Bill::class);
        try {
            $bill->update(['approval_status' => 'rejected']);
            return redirect()->route('staff.bills.index')->with('success', 'Bill rejected');
        } catch (\Exception $e) {
            Log::error('Bill rejection failed', ['bill_id' => $bill->id, 'error' => $e->getMessage()]);
            return redirect()->route('staff.bills.index')->with('error', 'Bill rejection failed: ' . $e->getMessage());
        }
    }

    public function downloadPdf(Bill $bill, Request $request)
    {
        $guard = $request->user('customer') ? 'customer' : 'staff';
        $this->authorize('view-bill', Bill::class);

        try {
            return Pdf::view('pdf.bill', ['bill' => $bill])
                ->format('A4')
                ->download('bill_' . $bill->billing_id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to generate bill PDF', [
                'bill_id' => $bill->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    public function downloadBulkPdf(Request $request)
    {
        $this->authorize('view-bill', Bill::class);

        $query = Bill::query()->with(['customer', 'customer.tariff', 'customer.category', 'customer.lga', 'customer.ward', 'customer.area'])
            ->join('customers', 'bills.customer_id', '=', 'customers.id')
            ->select('bills.*')
            ->where('bills.approval_status', 'approved');

        if ($yearMonth = $request->input('year_month')) {
            $query->where('bills.year_month', $yearMonth);
        }
        if ($categoryId = $request->input('category_id')) {
            $query->where('customers.category_id', $categoryId);
        }
        if ($tariffId = $request->input('tariff_id')) {
            $query->where('customers.tariff_id', $tariffId);
        }
        if ($lgaId = $request->input('lga_id')) {
            $query->where('customers.lga_id', $lgaId);
        }
        if ($wardId = $request->input('ward_id')) {
            $query->where('customers.ward_id', $wardId);
        }
        if ($areaId = $request->input('area_id')) {
            $query->where('customers.area_id', $areaId);
        }
        if ($customerId = $request->input('customer_id')) {
            $query->where('customers.id', $customerId);
        }

        $bills = $query->orderBy('bills.created_at', 'DESC')->get();

        if ($bills->isEmpty()) {
            return redirect()->route('staff.bills.index')->with('error', 'No approved bills found for bulk download');
        }

        try {
            return Pdf::view('pdf.bulk_bills', ['bills' => $bills])
                ->format('A4')
                ->withBrowsershot(function ($browsershot) {
                    $browsershot->setOption('dpi', 96)
                                ->setOption('defaultFont', 'DejaVu Sans');
                })
                ->download('bulk_bills_' . now()->format('YmdHis') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to generate bulk bill PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('staff.bills.index')->with('error', 'Failed to generate bulk bill PDF: ' . $e->getMessage());
        }
    }

    public function generateCombinedReport(Request $request)
    {
        $this->authorize('view-report', Bill::class);

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'customer_id' => 'nullable|exists:customers,id',
            'category_id' => 'nullable|exists:categories,id',
            'tariff_id' => 'nullable|exists:tariffs,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $billQuery = Bill::query()
            ->join('customers', 'bills.customer_id', '=', 'customers.id')
            ->leftJoin('tariffs', 'bills.tariff_id', '=', 'tariffs.id')
            ->leftJoin('categories', 'customers.category_id', '=', 'categories.id')
            ->leftJoin('lgas', 'customers.lga_id', '=', 'lgas.id')
            ->leftJoin('wards', 'customers.ward_id', '=', 'wards.id')
            ->leftJoin('areas', 'customers.area_id', '=', 'areas.id')
            ->select(
                'bills.*',
                'customers.first_name',
                'customers.surname',
                'customers.email',
                'tariffs.name as tariff_name',
                'categories.name as category_name',
                'lgas.name as lga_name',
                'wards.name as ward_name',
                'areas.name as area_name'
            );

        if ($startDate = $request->input('start_date')) {
            $billQuery->whereDate('bills.billing_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $billQuery->whereDate('bills.billing_date', '<=', $endDate);
        }
        if ($customerId = $request->input('customer_id')) {
            $billQuery->where('customers.id', $customerId);
        }
        if ($categoryId = $request->input('category_id')) {
            $billQuery->where('customers.category_id', $categoryId);
        }
        if ($tariffId = $request->input('tariff_id')) {
            $billQuery->where('customers.tariff_id', $tariffId);
        }
        if ($lgaId = $request->input('lga_id')) {
            $billQuery->where('customers.lga_id', $lgaId);
        }
        if ($wardId = $request->input('ward_id')) {
            $billQuery->where('customers.ward_id', $wardId);
        }
        if ($areaId = $request->input('area_id')) {
            $billQuery->where('customers.area_id', $areaId);
        }

        $bills = $billQuery->get();
        $payments = Payment::whereIn('bill_id', $bills->pluck('id'))
            ->where('payment_status', 'SUCCESSFUL')
            ->get();

        $reportData = [
            'bills' => $bills,
            'payments' => $payments,
            'total_bills' => $bills->count(),
            'total_amount' => $bills->sum('amount'),
            'total_paid' => $payments->sum('amount'),
            'total_balance' => $bills->sum('balance'),
            'filters' => [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'customer_id' => $request->input('customer_id'),
                'category_id' => $request->input('category_id'),
                'tariff_id' => $request->input('tariff_id'),
                'lga_id' => $request->input('lga_id'),
                'ward_id' => $request->input('ward_id'),
                'area_id' => $request->input('area_id'),
            ],
        ];

        try {
            return Pdf::view('staff.reports.report', $reportData)
                ->format('A4')
                ->download('combined_report_' . now()->format('YmdHis') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to generate combined report PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('staff.bills.index')->with('error', 'Failed to generate combined report PDF: ' . $e->getMessage());
        }
    }

    public function generateBillingReport(Request $request)
    {
        $this->authorize('view-report', Bill::class);

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'customer_id' => 'nullable|exists:customers,id',
            'category_id' => 'nullable|exists:categories,id',
            'tariff_id' => 'nullable|exists:tariffs,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $query = Bill::query()
            ->join('customers', 'bills.customer_id', '=', 'customers.id')
            ->leftJoin('tariffs', 'bills.tariff_id', '=', 'tariffs.id')
            ->leftJoin('categories', 'customers.category_id', '=', 'categories.id')
            ->leftJoin('lgas', 'customers.lga_id', '=', 'lgas.id')
            ->leftJoin('wards', 'customers.ward_id', '=', 'wards.id')
            ->leftJoin('areas', 'customers.area_id', '=', 'areas.id')
            ->select(
                'bills.*',
                'customers.first_name',
                'customers.surname',
                'customers.email',
                'tariffs.name as tariff_name',
                'categories.name as category_name',
                'lgas.name as lga_name',
                'wards.name as ward_name',
                'areas.name as area_name'
            );

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('bills.billing_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('bills.billing_date', '<=', $endDate);
        }
        if ($customerId = $request->input('customer_id')) {
            $query->where('customers.id', $customerId);
        }
        if ($categoryId = $request->input('category_id')) {
            $query->where('customers.category_id', $categoryId);
        }
        if ($tariffId = $request->input('tariff_id')) {
            $query->where('customers.tariff_id', $tariffId);
        }
        if ($lgaId = $request->input('lga_id')) {
            $query->where('customers.lga_id', $lgaId);
        }
        if ($wardId = $request->input('ward_id')) {
            $query->where('customers.ward_id', $wardId);
        }
        if ($areaId = $request->input('area_id')) {
            $query->where('customers.area_id', $areaId);
        }

        $bills = $query->get();

        $reportData = [
            'bills' => $bills,
            'total_bills' => $bills->count(),
            'total_amount' => $bills->sum('amount'),
            'total_balance' => $bills->sum('balance'),
            'filters' => [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'customer_id' => $request->input('customer_id'),
                'category_id' => $request->input('category_id'),
                'tariff_id' => $request->input('tariff_id'),
                'lga_id' => $request->input('lga_id'),
                'ward_id' => $request->input('ward_id'),
                'area_id' => $request->input('area_id'),
            ],
        ];

        try {
            return Pdf::view('staff.reports.billing-report', $reportData)
                ->format('A4')
                ->download('billing_report_' . now()->format('YmdHis') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to generate billing report PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('staff.bills.index')->with('error', 'Failed to generate billing report PDF: ' . $e->getMessage());
        }
    }

    public function generatePaymentReport(Request $request)
    {
        $this->authorize('view-report', Bill::class);

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'customer_id' => 'nullable|exists:customers,id',
            'category_id' => 'nullable|exists:categories,id',
            'tariff_id' => 'nullable|exists:tariffs,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $query = Payment::query()
            ->join('customers', 'payments.customer_id', '=', 'customers.id')
            ->leftJoin('bills', 'payments.bill_id', '=', 'bills.id')
            ->leftJoin('tariffs', 'bills.tariff_id', '=', 'tariffs.id')
            ->leftJoin('categories', 'customers.category_id', '=', 'categories.id')
            ->leftJoin('lgas', 'customers.lga_id', '=', 'lgas.id')
            ->leftJoin('wards', 'customers.ward_id', '=', 'wards.id')
            ->leftJoin('areas', 'customers.area_id', '=', 'areas.id')
            ->select(
                'payments.*',
                'customers.first_name',
                'customers.surname',
                'customers.email',
                'tariffs.name as tariff_name',
                'categories.name as category_name',
                'lgas.name as lga_name',
                'wards.name as ward_name',
                'areas.name as area_name'
            )
            ->where('payments.payment_status', 'SUCCESSFUL');

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('payments.payment_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('payments.payment_date', '<=', $endDate);
        }
        if ($customerId = $request->input('customer_id')) {
            $query->where('customers.id', $customerId);
        }
        if ($categoryId = $request->input('category_id')) {
            $query->where('customers.category_id', $categoryId);
        }
        if ($tariffId = $request->input('tariff_id')) {
            $query->where('bills.tariff_id', $tariffId);
        }
        if ($lgaId = $request->input('lga_id')) {
            $query->where('customers.lga_id', $lgaId);
        }
        if ($wardId = $request->input('ward_id')) {
            $query->where('customers.ward_id', $wardId);
        }
        if ($areaId = $request->input('area_id')) {
            $query->where('customers.area_id', $areaId);
        }

        $payments = $query->get();

        $reportData = [
            'payments' => $payments,
            'total_paid' => $payments->sum('amount'),
            'total_payments' => $payments->count(),
            'filters' => [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'customer_id' => $request->input('customer_id'),
                'category_id' => $request->input('category_id'),
                'tariff_id' => $request->input('tariff_id'),
                'lga_id' => $request->input('lga_id'),
                'ward_id' => $request->input('ward_id'),
                'area_id' => $request->input('area_id'),
            ],
        ];

        try {
            return Pdf::view('staff.reports.payment-report', $reportData)
                ->format('A4')
                ->download('payment_report_' . now()->format('YmdHis') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to generate payment report PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('staff.bills.index')->with('error', 'Failed to generate payment report PDF: ' . $e->getMessage());
        }
    }
}