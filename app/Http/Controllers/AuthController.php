<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Facades\Route;
use Core\Facades\View;
use App\Models\User;
use Core\Session;


class AuthController extends Controller
{

    public function signIn()
    {
        return View::render('auth.signIn');
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();
        if(!empty($user)){
            if(password_verify($data['password'], $user->password)){
                (new Session())->set('auth', $user->id);
                return Route::redirectName('home.index');
            }
        }

        return Route::back();
    }

    public function logout()
    {
        (new Session())->delete('auth');
        return Route::redirectName('home.index');
    }
}