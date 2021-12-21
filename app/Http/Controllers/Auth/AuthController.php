<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use Core\AuthManager;
use Core\Controller;
use Core\Facades\Route;
use Core\Facades\View;
use App\Models\User;
use Core\Validate\Validator;

class AuthController extends Controller
{

    public function signIn()
    {
        return View::render('auth.signin');
    }

    public function login(array $data)
    {
        $user = User::where('username', $data['username'])->first();
        if(!empty($user)){
            if(password_verify($data['password'], $user->password)){
                (new AuthManager())->login($user->id);
                return Route::redirectName('home.index');
            }
        }

        return Route::back();
    }

    public function logout()
    {
        (new AuthManager())->logout();
        return Route::redirectName('home.index');
    }

    public function subscribe()
    {
        $roles = Role::map('id', 'name');
        return View::render('auth.subscribe', compact('roles'));
    }

    public function create(array $data)
    {
        $newUser = User::create(Validator::check($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|different:field.first_name',
            'email' => 'required',
            'role_id' => 'required',
            'password' => 'required|minL:8|confirmed:field.confirm_password',
            'confirm_password' => 'required',
            'date_naissance' => 'required|beforeDate:now'
        ]));
        $auth = new AuthManager();
        $primaryKey = $newUser->getPrimaryKey();
        $auth->login($newUser->{$primaryKey});

        return Route::redirectName('home.index');

    }
}
