<?php


namespace Core;

use Core\Facades\Session;
use App\Models\User;
use Core\Facades\Route;
use Exception;

class AuthManager
{
    /**
     * @var string|null
     */
    private ?string $auth = null;

    public function __construct()
    {
        $this->auth = Session::get('auth');
    }

    /**
     * Retourne l'utilisateur connecté
     *
     * @return User|null|Router
     * @throws Exception
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
            Session::set('auth', $logId);
        }
    }

    /**
     * Déconnecte l'utilisateur d la session
     *
     * @return Router
     */
    public function logout()
    {
        Session::delete('auth');
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