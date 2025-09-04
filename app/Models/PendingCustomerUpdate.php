<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class PendingCustomerUpdate extends Model
{
    use Auditable;
    
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