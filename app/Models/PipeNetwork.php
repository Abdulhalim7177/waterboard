<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PipeNetwork extends Model
{
    use Auditable;

    protected $fillable = ['name', 'description', 'coordinates', 'status', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }
}