<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lga extends Model
{
    use HasFactory, Auditable;
    protected $fillable = ['code', 'name', 'status'];

    public function wards()
    {
        return $this->hasMany(Ward::class);
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
