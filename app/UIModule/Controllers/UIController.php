<?php

namespace App\UIModule\Controllers;

use App\UIModule\Events\ForgotPasswordEvent;
use Exception;
use MkyCore\Abstracts\Controller;
use MkyCore\AuthManager;
use MkyCore\Exceptions\Container\FailedToResolveContainerException;
use MkyCore\Exceptions\Container\NotInstantiableContainerException;
use MkyCore\Exceptions\Dispatcher\EventNotFoundException;
use MkyCore\Exceptions\Dispatcher\EventNotImplementException;
use MkyCore\Exceptions\Dispatcher\ListenerNotFoundException;
use MkyCore\Exceptions\Dispatcher\ListenerNotImplementException;
use MkyCore\RedirectResponse;
use MkyCore\Str;
use MkyCore\View;
use ReflectionException;

class UIController extends Controller
{

    /**
     * @Router('/login', as: 'ui.login', middlewares: ['@:guest'])
     * @return View
     */
    public function login(): View
    {
        return view('@/login.twig');
    }

    /**
     * @Router('/login', methods: ['POST'])
     * @param AuthManager $authManager
     * @return RedirectResponse
     * @throws ReflectionException
     */
    public function signIn(AuthManager $authManager): RedirectResponse
    {
        $this->request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        if (!$authManager->attempt($this->request->post())) {
            return redirect()->back()->session('error', 'Wrong credentials');
        }
        return redirect()->route($this->kernel::REDIRECT_LOGIN)->session('success_login', 'You are login');
    }

    /**
     * @Router('/forgot-password', as: 'ui.forgot_password', middlewares: ['@:guest'])
     *
     * @return View
     */
    public function forgotPassword(): View
    {
        return view('@/forgotPassword.twig');
    }

    /**
     * @Router('/forgot-password', methods: ['POST'])
     *
     * @param AuthManager $authManager
     * @return RedirectResponse
     * @throws ReflectionException
     * @throws FailedToResolveContainerException
     * @throws NotInstantiableContainerException
     * @throws EventNotFoundException
     * @throws EventNotImplementException
     * @throws ListenerNotFoundException
     * @throws ListenerNotImplementException
     */
    public function sendResetPasswordLink(AuthManager $authManager): RedirectResponse
    {
        $this->request->validate([
            'email' => ['required', 'email']
        ]);

        $userManager = $this->app->get($authManager->getProviderConfig('manager'));
        if (!($user = $userManager->where('email', $this->request->post('email'))->first())) {
            return redirect()->back()->session('email', 'There is no user with this email')->oldInput('email', $this->request->post('email'));
        }
        ForgotPasswordEvent::dispatch($user, ['send_link', 'save_token']);
        return redirect()->route('ui.login')->session('success', 'Password reset link sent successfully');
    }

    /**
     * @Router('/reset-password', as: 'ui.reset_password', middlewares: ['@:guest'])
     *
     * @return View|RedirectResponse
     * @throws Exception
     */
    public function resetPassword(): View|RedirectResponse
    {
        if (!$this->request->has('token', 'query')) {
            return redirect()->error(498, 'Password reset token has expired or does not exist');
        }

        $passwordResetManager = $this->kernel->getPasswordResetManager();
        $passwordReset = $passwordResetManager->where('token', $this->request->query('token'))->first();
        if (!$passwordReset) {
            return redirect()->error(498, 'Password reset token has expired or does not exist');
        }
        if (!$passwordReset->isValid()) {
            $passwordResetManager->delete($passwordReset);
            return redirect()->error(498, 'Password reset token has expired or does not exist');
        }
        return view('@/resetPassword.twig');
    }

    /**
     * @Router('/reset-password', methods: ['POST'])
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function updatePassword(): RedirectResponse
    {
        if (!$this->request->has('token', 'query')) {
            return redirect()->error(498, 'Password reset token has expired or does not exist');
        }
        $passwordResetManager = $this->kernel->getPasswordResetManager();
        $passwordReset = $passwordResetManager->where('token', $this->request->query('token'))->first();
        if (!$passwordReset) {
            return redirect()->error(498);
        }
        if (!$passwordReset->isValid()) {
            $passwordResetManager->delete($passwordReset);
            return redirect()->error(498, 'Password reset token has expired or does not exist');
        }
        $this->request->validate([
            'password' => ['required', 'confirmed:confirm_password']
        ]);
        $newPassword = Str::hashPassword($this->request->post('password'));
        $user = $passwordReset->user();
        $user->setPassword($newPassword);
        $manager = $user->getManager();
        $manager->update($user);
        $passwordResetManager->delete($passwordReset);
        return redirect()->route('ui.login')->session('success', 'Password updated successfully');
    }

    /**
     * @Router('/logout', as: 'ui.logout', middlewares: ['@:auth'])
     *
     * @param AuthManager $authManager
     * @return RedirectResponse
     */
    public function logout(AuthManager $authManager): RedirectResponse
    {
        $authManager->logout();
        return redirect()->route('home.index');
    }
}