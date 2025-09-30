<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Auditable;

class Staff extends Authenticatable
{
    use HasFactory, HasRoles, Auditable, HasApiTokens;

    protected $guard = 'staff';

    protected $fillable = [
        'staff_id', 'first_name', 'surname', 'middle_name', 'gender', 'date_of_birth',
        'state_of_origin', 'lga_id', 'ward_id', 'area_id', 'nationality', 'nin', 'mobile_no', 
        'phone_number', 'email', 'address', 'password', 'date_of_first_appointment', 'rank', 
        'staff_no', 'department', 'expected_next_promotion', 'expected_retirement_date', 
        'status', 'employment_status', 'highest_qualifications', 'grade_level_limit', 'appointment_type', 
        'photo_path', 'years_of_service'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_first_appointment' => 'date',
        'expected_next_promotion' => 'date',
        'expected_retirement_date' => 'date',
    ];

    // Accessor to get full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->surname;
    }

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

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
