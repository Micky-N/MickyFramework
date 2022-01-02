<?php


namespace Core;


use App\Models\User;
use Core\Facades\Route;

class AuthManager
{
    /**
     * @var string|null
     */
    private ?string $auth = null;

    public function __construct()
    {
        $this->auth = (new Session())->get('auth');
    }

    /**
     * Retourne l'utilisateur connecté
     *
     * @return User|null|\Core\Route
     * @throws \Exception
     */
    public function getAuth()
    {
        if($this->isLoggin()){
            return !is_null($this->auth) ? (User::find($this->auth) ?? $this->logout()) : $this->logout();
        }
        return null;
    }

    /**
     * Connecte l'utilisateur à la session
     *
     * @param mixed $logId
     * @return void
     */
    public function login($logId)
    {
        if(!$this->isLoggin()){
            (new Session())->set('auth', $logId);
        }
    }

    /**
     * Déconnecte l'utilisateur d la session
     *
     * @return \Core\Route
     */
    public function logout()
    {
        (new Session())->delete('auth');
        $this->auth = null;
        if(!currentRoute(route('home.index'))){
            return Route::redirectName('auth.signin');
        }
        return Route::back();
    }

    /**
     * Vérifie si l'utilisateur est connecté
     *
     * @return bool
     */
    public function isLoggin()
    {
        return $this->auth !== null;
    }
}