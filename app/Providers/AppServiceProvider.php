<?php

namespace App\Providers;

use App\Http\Controllers\Admin\ReportDataController;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(provider: \Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(provider: TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ReportDataController::class, function () {
            ReportDataController::init();
        });
    }
}
