<?php

namespace App\UserModule\Controllers;

use App\UserModule\Entities\User;
use App\UserModule\Managers\UserManager;
use Exception;
use MkyCore\Abstracts\Controller;
use MkyCore\AuthManager;
use MkyCore\Exceptions\Container\FailedToResolveContainerException;
use MkyCore\Exceptions\Container\NotInstantiableContainerException;
use MkyCore\Facades\Config;
use MkyCore\RedirectResponse;
use MkyCore\View;
use ReflectionException;

class UserController extends Controller
{
    /**
     * @Router('users/create', as: 'users.create')
     * @return View
     */
    public function create(): View
    {
        return view('@:create');
    }

    /**
     * @Router('users', as: 'users.store', methods: ['POST'])
     * @param UserManager $userManager
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(UserManager $userManager): RedirectResponse
    {
        $this->request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed:confirm_password'],
            'name' => 'required'
        ]);
        if (!$userManager->save($user = new User($this->request->post()))) {
            return redirect()->back()->session('error', 'Registration failed');
        }
        return redirect()->route('login')->session('success', 'Good, you can login')->queries(['email' => $user->email()]);
    }

    /**
     * @Router('/profile', as: 'users.profile', middlewares: ['@:auth'])
     * @param AuthManager $authManager
     * @return View
     * @throws ReflectionException
     * @throws FailedToResolveContainerException
     * @throws NotInstantiableContainerException
     */
    public function profile(AuthManager $authManager): View
    {
        /** @var User $user */
        $user = $authManager->user();
        return view('@:profile', compact('user'));
    }

    /**
     * @Router('/profile', methods: ['PUT'])
     *
     * @param UserManager $userManager
     * @return RedirectResponse
     * @throws FailedToResolveContainerException
     * @throws NotInstantiableContainerException
     * @throws ReflectionException
     */
    public function update(UserManager $userManager): RedirectResponse
    {
        /** @var User $user */
        $user = $this->request->auth()->user();

        if ($this->request->has('email')) {
            $this->request->validate([
                'email' => 'email'
            ]);
            $user->setEmail($this->request->post('email'));
        }

        if ($this->request->has('new_password')) {
            if (!password_verify($this->request->post('old_password', ''), $user->password())) {
                return redirect()->back()->session('old_password', 'Wrong password');
            }
            $this->request->validate([
                'new_password' => ['required', 'confirmed:confirm_password']
            ]);
            $user->setPassword(password_hash($this->request->post('new_password'), PASSWORD_BCRYPT));
            $userManager->update($user);
        }

        return redirect()->back()->session('success', 'Profile update successfully');
    }
}