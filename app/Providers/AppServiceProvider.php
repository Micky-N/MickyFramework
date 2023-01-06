<?php

namespace App\Providers;

use App\RootKernel;
use MkyCore\Abstracts\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Module list
     *
     * @var array<string, string>
     */
    private array $modules = [
        'root' => RootKernel::class,
	    'ui' => \App\UIModule\UIKernel::class,
	    'user' => \App\UserModule\UserKernel::class,
    ];

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

    /**
     * Get all modules
     *
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }
}