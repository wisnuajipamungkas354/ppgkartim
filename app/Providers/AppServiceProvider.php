<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Helpers\RolePermission;
use App\Models\PjpSchedule;
use App\Observers\PjpScheduleObserver;

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
        Gate::before(function ($user, string $ability) {
            return RolePermission::can($ability);
        });

        PjpSchedule::observe(PjpScheduleObserver::class);
    }
}
