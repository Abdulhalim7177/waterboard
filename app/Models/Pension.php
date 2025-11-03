<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'rsa_balance', 'pfa_contribution_rate', 'pension_administrator', 'rsa_pin'];
}
