<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthSerial extends Model
{
    protected $table = 'month_serials';
    
    protected $primaryKey = 'year_month';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'year_month',
        'count',
    ];
}