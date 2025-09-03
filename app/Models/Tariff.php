<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'catcode', 'category_id', 'amount', 'rate', 'fixed_charge', 'billing_type', 'description', 'status', 'rate'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
