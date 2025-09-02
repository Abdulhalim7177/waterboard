<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\Auditable;

class Role extends SpatieRole
{
    use Auditable;

    protected $fillable = ['name', 'guard_name', 'status'];
}