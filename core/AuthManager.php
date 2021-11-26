<?php


namespace Core;


use App\Models\User;

class AuthManager
{
    /**
     * @var User|null
     */
    private ?User $auth;

    public function __construct()
    {
        $this->auth = (new Session())->get('auth');
    }

    /**
     * @return User|null
     */
    public function getAuth(): ?User
    {
       return $this->auth;
    }
}