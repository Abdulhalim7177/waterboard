<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paypoint extends Model
{
    use HasFactory, Auditable;
    
    protected $fillable = [
        'name', 
        'code', 
        'type', 
        'zone_id', 
        'district_id', 
        'description', 
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'paypoint_id');
    }
    
    public function customers()
    {
        if ($this->type === 'zone' && $this->zone_id) {
            return Customer::whereHas('ward.district', function($query) {
                $query->where('zone_id', $this->zone_id);
            });
        } elseif ($this->type === 'district' && $this->district_id) {
            return Customer::whereHas('ward', function($query) {
                $query->where('district_id', $this->district_id);
            });
        }
        return Customer::whereNull('id'); // Empty query if no valid type
    }
}