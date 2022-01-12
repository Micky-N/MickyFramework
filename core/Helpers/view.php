<?php


if(!function_exists('assets')){
    function assets($path)
    {
        return BASE_ULR . 'public/' . 'assets/' . $path;
    }
}

if(!function_exists('auth')){
    function auth()
    {
        return (new \Core\AuthManager())->getAuth();
    }
}

if(!function_exists('isLogin')){
    function isLogin()
    {
        return (new \Core\AuthManager())->isLogin();
    }
}

if(!function_exists('permission')){
    function permission(string $permission, $subject = null)
    {
        return \Core\Facades\Permission::authorizeAuth($permission, $subject);
    }
}
