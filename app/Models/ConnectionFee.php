<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'connection_type_id',
        'connection_size_id',
        'fee_amount',
        'is_active',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function connectionType()
    {
        return $this->belongsTo(ConnectionType::class);
    }

    public function connectionSize()
    {
        return $this->belongsTo(ConnectionSize::class);
    }
}