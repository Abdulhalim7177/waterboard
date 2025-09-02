<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Complaint extends Model
{
    use Auditable;

    protected $fillable = [
        'customer_id', 'type', 'description', 'status', 'resolution_notes', 'assigned_to_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to_id');
    }
}