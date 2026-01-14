<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size_mm',
        'size_inches',
        'is_active',
    ];

    // Relationship with ConnectionFee
    public function fees()
    {
        return $this->hasMany(ConnectionFee::class);
    }

    // Relationship with CustomerConnection
    public function customerConnections()
    {
        return $this->hasMany(CustomerConnection::class);
    }
}