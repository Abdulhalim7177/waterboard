<?php
namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Customer extends Authenticatable
{
    use HasFactory, Auditable;

    protected $fillable = [
        'first_name', 'surname', 'email', 'phone_number', 'alternate_phone_number',
        'street_name', 'house_number', 'landmark', 'area_id', 'lga_id', 'ward_id',
        'category_id', 'tariff_id', 'delivery_code', 'billing_id', 'billing_condition',
        'water_supply_status', 'latitude', 'longitude', 'polygon_coordinates', 'altitude', 'pipe_path',
        'password', 'status', 'account_balance', 'created_at', 'created_by'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['total_bill'];

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function audits()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    public function getTotalBillAttribute()
    {
        return $this->bills()
            ->where('approval_status', 'approved')
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('balance');
    }

    public static function generateBillingId($customer)
    {
        try {
            $yearMonth = $customer->created_at->format('ym');
            $tariff = Tariff::find($customer->tariff_id);
            if (!$tariff) {
                throw new \Exception('Tariff not found for customer');
            }
            $catcode = str_pad($tariff->catcode, 3, '0', STR_PAD_LEFT);

            $monthSerial = DB::table('month_serials')->lockForUpdate()->where('year_month', $yearMonth)->first();
            if (!$monthSerial) {
                DB::table('month_serials')->insert(['year_month' => $yearMonth, 'count' => 1]);
                $count = 1;
            } else {
                DB::table('month_serials')->where('year_month', $yearMonth)->increment('count');
                $count = $monthSerial->count + 1;
            }

            $monthSerialPadded = str_pad($count, 6, '0', STR_PAD_LEFT);
            return $yearMonth . $catcode . $monthSerialPadded;
        } catch (\Exception $e) {
            Log::error('Failed to generate billing ID', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function applyAccountBalanceToBills()
    {
        if ($this->account_balance <= 0) {
            return;
        }

        try {
            $unpaidBills = $this->bills()
                ->whereIn('status', ['pending', 'overdue'])
                ->where('approval_status', 'approved')
                ->orderBy('due_date', 'asc')
                ->get();

            $remainingBalance = $this->account_balance;

            foreach ($unpaidBills as $bill) {
                if ($remainingBalance <= 0) {
                    break;
                }

                $amountToApply = min($remainingBalance, $bill->balance);
                if ($amountToApply > 0) {
                    $payment = Payment::create([
                        'customer_id' => $this->id,
                        'bill_id' => $bill->id,
                        'payer_ref_no' => 'BALANCE_' . now()->format('YmdHis') . '_' . uniqid(),
                        'bill_ids' => (string) $bill->id, // Convert bill ID to string
                        'amount' => $amountToApply,
                        'payment_date' => now(),
                        'method' => 'Account Balance',
                        'status' => 'successful',
                        'transaction_ref' => 'TXN_' . uniqid(),
                        'payment_code' => 'PC_' . uniqid(),
                        'payment_status' => 'SUCCESSFUL',
                        'channel' => 'Account Balance',
                    ]);

                    $remainingBalance -= $amountToApply;
                    $bill->updateBalanceAndStatus();

                    Log::info('Account balance applied to bill', [
                        'payment_id' => $payment->id,
                        'bill_id' => $bill->id,
                        'amount' => $amountToApply,
                        'remaining_balance' => $remainingBalance,
                    ]);
                }
            }

            $this->account_balance = $remainingBalance;
            $this->save();

            Log::info('Customer account balance updated', [
                'customer_id' => $this->id,
                'new_account_balance' => $this->account_balance,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to apply account balance', [
                'customer_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Add amount to customer account balance
     */
    public function addAccountBalance($amount)
    {
        $this->account_balance += $amount;
        $this->save();
        return true;
    }
}
