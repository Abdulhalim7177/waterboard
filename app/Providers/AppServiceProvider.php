<?php

namespace App\Providers;

use App\Models\Customer;
use App\Observers\CustomerObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schedule;


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
        Schedule::command('bills:generate')->monthlyOn(1, '00:00');
    }

}
