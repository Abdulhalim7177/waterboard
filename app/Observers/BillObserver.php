<?php

namespace App\Observers;

use App\Models\Bill;
use App\Models\ConnectionTask;

class BillObserver
{
    /**
     * Handle the Bill "created" event.
     */
    public function created(Bill $bill): void
    {
        //
    }

    /**
     * Handle the Bill "updated" event.
     */
    public function updated(Bill $bill): void
    {
        if ($bill->isDirty('approval_status') && $bill->approval_status === 'approved') {
            if ($bill->tariff->type === 'service') {
                ConnectionTask::create([
                    'bill_id' => $bill->id,
                ]);
            }
        }
    }

    /**
     * Handle the Bill "deleted" event.
     */
    public function deleted(Bill $bill): void
    {
        //
    }

    /**
     * Handle the Bill "restored" event.
     */
    public function restored(Bill $bill): void
    {
        //
    }

    /**
     * Handle the Bill "force deleted" event.
     */
    public function forceDeleted(Bill $bill): void
    {
        //
    }
}
