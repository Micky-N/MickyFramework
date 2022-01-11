<?php


if (!function_exists('assets')) {
    function assets($path)
    {
        return ROOT . '/public/' . 'assets/' . $path;
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        return (new \Core\AuthManager())->getAuth();
    }
}

if (!function_exists('isLogin')) {
    function isLogin()
    {
        return (new \Core\AuthManager())->isLogin();
    }
}

if (!function_exists('permission')) {
    function permission(string $permission, $subject = null)
    {
        if (!isLogin()) return false;
        return \Core\Facades\Permission::test($permission, $subject);
    }
}
