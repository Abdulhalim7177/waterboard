<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Paypoint;
use App\Models\District;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'glpi_ticket_id',
        'customer_id',
        'staff_id',
        'paypoint_id',
        'title',
        'description',
        'status',
        'type',
        'category',
        'priority',
        'urgency',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function paypoint()
    {
        return $this->belongsTo(Paypoint::class);
    }
    
    public function district()
    {
        if ($this->paypoint) {
            return $this->paypoint->district;
        }
        
        // If no paypoint, try to get it via customer's location
        if ($this->customer && $this->customer->ward) {
            return $this->customer->ward->district;
        }
        
        return null;
    }

    public function getStatusNameAttribute()
    {
        return match ($this->status) {
            1 => 'New',
            2 => 'Processing (assigned)',
            3 => 'Processing (planned)',
            4 => 'Pending',
            5 => 'Solved',
            6 => 'Closed',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            1 => 'primary',
            2, 3 => 'info',
            4 => 'warning',
            5 => 'success',
            6 => 'danger',
            default => 'secondary',
        };
    }
}
