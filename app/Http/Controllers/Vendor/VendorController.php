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
        $vendor = Auth::guard('vendor')->user();
        return view('vendor.dashboard', compact('vendor'));
    }

    public function getCustomerInfo($billingId)
    {
        try {
            $customer = Customer::with(['tariff', 'category'])->where('billing_id', $billingId)->first();
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name ?? '',
                    'surname' => $customer->surname ?? '',
                    'billing_id' => $customer->billing_id ?? '',
                    'tariff' => $customer->tariff ? $customer->tariff->name : 'N/A',
                    'category' => $customer->category ? $customer->category->name : 'N/A',
                    'account_balance' => $customer->account_balance ?? 0,
                    'total_bill' => $customer->total_bill ?? 0,
                    'status' => $customer->status ?? 'inactive',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching customer information: ' . $e->getMessage()
            ], 500);
        }
    }
}