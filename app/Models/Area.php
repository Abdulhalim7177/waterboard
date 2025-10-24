<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory, Auditable;
    protected $fillable = ['code', 'name', 'ward_id', 'status'];

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
