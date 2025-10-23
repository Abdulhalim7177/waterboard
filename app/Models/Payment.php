<?php
namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Payment extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'customer_id', 'bill_id', 'payer_ref_no', 'bill_ids', 'amount',
        'payment_date', 'method', 'status', 'transaction_ref',
        'payment_code', 'payment_status', 'channel'
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $validator = Validator::make($payment->getAttributes(), [
                'amount' => 'required|numeric|min:0.01',
                'method' => 'required|in:NABRoll,Account Balance',
                'customer_id' => 'required|exists:customers,id',
                'bill_id' => 'nullable|exists:bills,id',
                'bill_ids' => 'nullable|string',
                'payment_date' => 'required|date',
                'transaction_ref' => 'nullable|string|max:255',
                'payment_code' => 'nullable|string|max:255',
                'payment_status' => 'required|in:pending,SUCCESSFUL,FAILED',
                'channel' => 'nullable|string|max:255',
            ]);
            // Validate bill_ids if provided
            if ($payment->bill_ids) {
                $billIds = array_filter(explode(',', $payment->bill_ids));
                $bills = Bill::whereIn('id', $billIds)
                    ->where('customer_id', $payment->customer_id)
                    ->where('approval_status', 'approved')
                    ->get();
                if ($bills->count() !== count($billIds)) {
                    throw new ValidationException($validator, response()->json([
                        'errors' => ['bill_ids' => 'Invalid or unapproved bills selected'],
                    ], 422));
                }
            }
        });
    }
}