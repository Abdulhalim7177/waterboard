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
        'lga_id', 'ward_id', 'area_id', 'zone_id', 'district_id', 'paypoint_id', 
        'nationality', 'nin', 'mobile_no', 'phone_number', 'email', 'address', 'password', 
        'date_of_first_appointment', 'contract_start_date', 'contract_end_date', 'rank_id', 'staff_no', 'department_id', 'cadre_id', 'grade_level_id', 'step_id', 'expected_next_promotion', 
        'expected_retirement_date', 'status', 'employment_status', 'highest_qualifications', 
        'appointment_type_id', 'photo_path', 'years_of_service'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_first_appointment' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'expected_next_promotion' => 'date',
        'expected_retirement_date' => 'date',
    ];

    // Accessor to get full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->surname;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function cadre()
    {
        return $this->belongsTo(Cadre::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function appointmentType()
    {
        return $this->belongsTo(AppointmentType::class);
    }


    public function bank()
    {
        return $this->hasOne(StaffBank::class);
    }

    public function nextOfKin()
    {
        return $this->hasOne(NextOfKin::class);
    }

    public function pension()
    {
        return $this->hasOne(Pension::class);
    }

    public function biometricData()
    {
        return $this->hasOne(BiometricData::class);
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

    public function paypoint()
    {
        return $this->belongsTo(Paypoint::class);
    }

    /**
     * Get all wards that this staff member has access to based on their paypoint
     */
    public function getAccessibleWardIds()
    {
        $accessibleWards = [];
        if ($this->paypoint) {
            $paypoint = $this->paypoint;
            
            if ($paypoint->type === 'zone' && $paypoint->zone_id) {
                $accessibleWards = array_merge($accessibleWards, Ward::whereHas('district', function($query) use ($paypoint) {
                    $query->where('zone_id', $paypoint->zone_id);
                })->pluck('id')->toArray());
            } elseif ($paypoint->type === 'district' && $paypoint->district_id) {
                $accessibleWards = array_merge($accessibleWards, Ward::where('district_id', $paypoint->district_id)->pluck('id')->toArray());
            }
        }

        if ($this->area && $this->area->ward_id) {
            $accessibleWards[] = $this->area->ward_id;
        }
        if ($this->ward_id) {
            $accessibleWards[] = $this->ward_id;
        }

        return array_unique($accessibleWards);
    }

    /**
     * Get all LGAs that this staff member has access to based on their paypoint
     */
    public function getAccessibleLgaIds()
    {
        if (!$this->paypoint) {
            return [];
        }

        $paypoint = $this->paypoint;
        
        if ($paypoint->type === 'zone' && $paypoint->zone_id) {
            // First get all districts under the zone, then all wards under those districts, then all LGAs with those wards
            $districtWards = Ward::whereHas('district', function($query) use ($paypoint) {
                $query->where('zone_id', $paypoint->zone_id);
            })->pluck('id');
            
            return Customer::whereIn('ward_id', $districtWards)->pluck('lga_id')->unique()->toArray();
        } elseif ($paypoint->type === 'district' && $paypoint->district_id) {
            // Get all wards under the specific district, then all LGAs with those wards
            $districtWards = Ward::where('district_id', $paypoint->district_id)->pluck('id');
            
            return Customer::whereIn('ward_id', $districtWards)->pluck('lga_id')->unique()->toArray();
        }
        
        return [];
    }

    /**
     * Get all areas that this staff member has access to based on their paypoint
     */
    public function getAccessibleAreaIds()
    {
        if (!$this->paypoint) {
            return [];
        }

        $paypoint = $this->paypoint;
        
        if ($paypoint->type === 'zone' && $paypoint->zone_id) {
            // Get all districts under this zone, then all wards under those districts, then all areas under those wards
            $districtWards = Ward::whereHas('district', function($query) use ($paypoint) {
                $query->where('zone_id', $paypoint->zone_id);
            })->pluck('id');
            
            return Area::whereIn('ward_id', $districtWards)->pluck('id')->toArray();
        } elseif ($paypoint->type === 'district' && $paypoint->district_id) {
            // Get all wards under the specific district, then all areas under those wards
            $districtWards = Ward::where('district_id', $paypoint->district_id)->pluck('id');
            
            return Area::whereIn('ward_id', $districtWards)->pluck('id')->toArray();
        }
        
        return [];
    }
}
