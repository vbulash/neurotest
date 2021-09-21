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
        //
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
