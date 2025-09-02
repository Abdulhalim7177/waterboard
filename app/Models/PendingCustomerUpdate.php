<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingCustomerUpdate extends Model
{
    protected $fillable = ['customer_id', 'field', 'old_value', 'new_value', 'updated_by', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }
}