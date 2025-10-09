<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory, Auditable;
    
    protected $fillable = ['code', 'name', 'status'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
    
    public function staffs()
    {
        return $this->hasManyThrough(Staff::class, District::class, 'zone_id', 'district_id', 'id', 'id');
    }
    
    public function customers()
    {
        return Customer::whereHas('ward.district', function($query) {
            $query->where('zone_id', $this->id);
        });
    }
}