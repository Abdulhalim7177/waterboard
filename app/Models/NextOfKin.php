<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    use HasFactory;

    protected $table = 'next_of_kins';

    protected $fillable = ['staff_id', 'name', 'relationship', 'mobile_no', 'address', 'occupation', 'place_of_work'];
}
