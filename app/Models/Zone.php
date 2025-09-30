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
}