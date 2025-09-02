<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Auditable;

class Staff extends Authenticatable
{
    use HasRoles, Auditable;

    protected $guard = 'staff';

    protected $fillable = [
        'name', 'email', 'password', 'district', 'zone', 'subzone', 'road', 'succ',
        'lga_id', 'ward_id', 'area_id', 'status', 'phone_number'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pendingUpdates()
    {
        return $this->hasMany(PendingCustomerUpdate::class, 'updated_by');
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'assigned_to_id');
    }
}