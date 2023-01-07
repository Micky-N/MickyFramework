<?php

namespace App\UIModule;

use MkyCore\Abstracts\Manager;
use MkyCore\Abstracts\ModuleKernel;
use MkyCore\Facades\Auth;
use MkyCore\Interfaces\AuthSystemInterface;
use MkyCore\PasswordReset\PasswordResetManager;

class UIKernel extends ModuleKernel
{

    const REDIRECT_LOGIN = 'users.profile';
    const PASSWORD_RESET_MANAGER = PasswordResetManager::class;

    public function getManager(): AuthSystemInterface
    {
        return Auth::getManager();
    }

    public function getPasswordResetManager(): ?Manager
    {
        if(!self::PASSWORD_RESET_MANAGER){
            return null;
        }
        return $this->app->get(self::PASSWORD_RESET_MANAGER);
    }
}