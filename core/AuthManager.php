<?php


namespace Core;


use App\Models\User;

class AuthManager
{
    /**
     * @var string|null
     */
    private $auth;

    public function __construct()
    {
        $this->auth = (new Session())->get('auth');
    }

    /**
     * @return User|null
     */
    public function getAuth(): ?User
    {
        if($this->isLoggin()){
            return User::find($this->auth);
        }
        return null;
    }

    public function isLoggin()
    {
        return $this->auth !== null;
    }
}