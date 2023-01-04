<?php

namespace App\UIModule\Providers;

use App\UIModule\Events\ForgotPasswordEvent;
use App\UIModule\Listeners\SaveResetPasswordTokenListener;
use App\UIModule\Listeners\SendResetPasswordLinkListener;
use MkyCore\Abstracts\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    
    /**
     * Register events and their listeners
     * @example app->addEvent(Event::class, ['action' => Listener::class]);
     * and register notification systems
     * @example app->addNotificationSystem('example', ExampleNotificationSystem::class);
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->addEvent(ForgotPasswordEvent::class, [
            'send_link' => SendResetPasswordLinkListener::class,
            'save_token' => SaveResetPasswordTokenListener::class
        ]);
    }
}