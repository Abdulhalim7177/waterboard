<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'approved',
        'account_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'approved' => 'boolean',
        'account_balance' => 'decimal:2',
    ];

    /**
     * Get the guard name for the model.
     */
    public function getGuardName(): string
    {
        return 'vendor';
    }

    /**
     * Add amount to vendor account balance
     */
    public function addAccountBalance($amount)
    {
        $this->account_balance += $amount;
        $this->save();
        return true;
    }

    /**
     * Deduct amount from vendor account balance
     */
    public function deductAccountBalance($amount)
    {
        if ($this->account_balance >= $amount) {
            $this->account_balance -= $amount;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Fund vendor account through NABRoll payment
     */
    public function fundAccount($amount)
    {
        // This method will be called after successful NABRoll payment
        return $this->addAccountBalance($amount);
    }

    /**
     * Get vendor payments
     */
    public function vendorPayments()
    {
        return $this->hasMany(VendorPayment::class);
    }
}
