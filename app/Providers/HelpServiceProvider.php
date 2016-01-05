<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sort', function () {
            return new \App\Helps\Sort;
        });
        
        $this->app->singleton('tool', function () {
            return new \App\Helps\Tool;
        });
    }
}
