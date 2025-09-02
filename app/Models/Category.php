<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Category extends Model
{
    use Auditable;

    protected $fillable = ['name', 'code', 'description', 'status'];

    public function tariffs()
    {
        return $this->hasMany(Tariff::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}