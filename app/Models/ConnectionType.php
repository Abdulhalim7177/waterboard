<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
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