<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffBank extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'bank_name', 'bank_code', 'account_name', 'account_no'];
}
