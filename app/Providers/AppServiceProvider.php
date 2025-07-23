<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
    use App\Models\Booking;
use App\Policies\TableReservationPolicy;

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
    public function boot(): void
    {
        //
    }


protected $policies = [
    Booking::class => TableReservationPolicy::class,
];

}
