<?php

namespace App\Models;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use Auditable;
    protected $fillable = ['code', 'name', 'lga_id', 'status'];

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}