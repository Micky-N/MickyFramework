<?php

namespace App\UIModule\Providers;

use MkyCore\Abstracts\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    
    /**
     * Register classes in the container
     * @example app->bind(alias, callback)
     * or use app->singleton(alias, callback) to share the same instance throughout the application
     * 
     * @return void
     */
    public function register(): void
    {
        //
    }
}