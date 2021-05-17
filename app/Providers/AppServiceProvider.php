<?php

namespace App\Providers;

use App\Flow\Flow;
use App\Flow\regFlow;
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
        Flow::setMainFlow(new regFlow);
    }
}
