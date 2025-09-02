<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use Auditable;
    protected $fillable = ['code', 'name', 'ward_id', 'status'];

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
}