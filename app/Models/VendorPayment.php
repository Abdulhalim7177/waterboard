<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class VendorPayment extends Model
{
    use Auditable;

    protected $fillable = [
        'vendor_id',
        'customer_id',
        'billing_id',
        'amount',
        'payment_date',
        'method',
        'status',
        'payment_status',
        'channel',
        'transaction_ref',
        'payment_code',
        'payer_ref_no',
        'nabroll_ref',
        'nabroll_response',
        'transaction_type', // 'funding' or 'payment'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Scope a query to only include funding transactions.
     */
    public function scopeFunding($query)
    {
        return $query->where('transaction_type', 'funding');
    }

    /**
     * Scope a query to only include payment transactions.
     */
    public function scopePayments($query)
    {
        return $query->where('transaction_type', 'payment');
    }
}
