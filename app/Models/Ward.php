<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory, Auditable;
    protected $fillable = ['code', 'name', 'lga_id', 'district_id', 'status'];

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
