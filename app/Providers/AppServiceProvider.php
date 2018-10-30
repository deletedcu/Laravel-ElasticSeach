<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //view()->composer('master', 'App\Http\ViewComposers\MasterViewComposer');
        view()->composer('formWrapper', 'App\Http\ViewComposers\FormViewComposer');
        view()->composer('layouts.sidebar', 'App\Http\ViewComposers\SidebarViewComposer');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
