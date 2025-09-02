<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Lga extends Model
{
    use Auditable;
    protected $fillable = ['code', 'name', 'status'];

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}