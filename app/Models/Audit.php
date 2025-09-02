<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'user_type',
        'user_id',
        'related_type',
        'related_id',
    ];

    public function user()
    {
        return $this->morphTo('user', 'user_type', 'user_id');
    }
}
