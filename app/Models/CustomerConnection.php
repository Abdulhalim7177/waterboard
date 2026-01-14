<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'connection_type_id',
        'connection_size_id',
        'status',
        'notes',
        'installation_date',
        'installed_by',
    ];

    protected $casts = [
        'installation_date' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function connectionType()
    {
        return $this->belongsTo(ConnectionType::class);
    }

    public function connectionSize()
    {
        return $this->belongsTo(ConnectionSize::class);
    }

    public function installedBy()
    {
        return $this->belongsTo(Staff::class, 'installed_by');
    }
}