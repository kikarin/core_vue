<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        require_once app_path('Helpers/GlobalHelper.php');
        require_once app_path('Helpers/dateid_helper.php');
    }
}
