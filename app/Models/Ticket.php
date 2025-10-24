<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Paypoint;

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
}
