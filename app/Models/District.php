<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory, Auditable;
    
    protected $fillable = ['code', 'name', 'zone_id', 'status'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}