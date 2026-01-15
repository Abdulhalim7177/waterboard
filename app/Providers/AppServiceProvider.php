<?php

namespace App\Providers;

use App\Models\Customer;
use App\Observers\CustomerObserver;
use App\Models\Bill;
use App\Observers\BillObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schedule;
use Spatie\Browsershot\Browsershot;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
     public function boot()
    {
        Customer::observe(CustomerObserver::class);
        Bill::observe(BillObserver::class); // Register BillObserver
        Schedule::command('bills:generate')->monthlyOn(1, '00:00');

        //
    }

}
