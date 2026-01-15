<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'staff_id',
        'status',
        'notes',
        'pipe_path',
    ];

    protected $casts = [
        'pipe_path' => 'array', // Cast to array for easier handling of JSON
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}