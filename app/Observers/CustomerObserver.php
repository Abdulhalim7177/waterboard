<?php

// app/Observers/CustomerObserver.php
namespace App\Observers;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerObserver
{
    public function created(Customer $customer)
    {
        // Increment monthserial on creation (regardless of approval)
        $yearMonth = $customer->created_at->format('ym');
        DB::table('month_serials')->lockForUpdate()->where('year_month', $yearMonth)->first()
            ?: DB::table('month_serials')->insert(['year_month' => $yearMonth, 'count' => 0]);
        DB::table('month_serials')->where('year_month', $yearMonth)->increment('count');
    }

    public function updated(Customer $customer)
    {
        if ($customer->wasChanged('status') && $customer->status === 'approved' && !$customer->billing_id) {
            $customer->billing_id = Customer::generateBillingId($customer);
            $customer->save();
        }
    }
}