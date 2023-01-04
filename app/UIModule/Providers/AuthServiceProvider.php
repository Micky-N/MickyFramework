<?php

namespace App\UIModule\Providers;

use MkyCore\Abstracts\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    
    /**
     * Register all permissions with Allows facade
     * @example \MkyCore\Facades\Allows::define(alias, callback(user, entity))
     * To use the permission, alias must be implements in a route definition like
     * @exemple Route('/', allows: [alias])
     * 
     * @return void
     */
    public function register(): void
    {
        //
    }
}