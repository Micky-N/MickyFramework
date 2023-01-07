<?php

namespace App\UIModule\Events;

use MkyCore\Abstracts\Event;
use MkyCore\Facades\Auth;
use MkyCore\Str;

class ForgotPasswordEvent extends Event
{

    /**
     * Event constructor.
     *
     * @param mixed $target
     * @param array $actions
     * @param array $params
     */
    public function __construct(protected mixed $target, protected array $actions, protected array $params)
    {
        $this->params['token'] = Str::random(24);
        $this->params['url'] = route('ui.reset_password');
        $this->params['expiresAt'] = config('auth.password_reset.'.Auth::getProviderName().'.lifetime', 10);
    }
}