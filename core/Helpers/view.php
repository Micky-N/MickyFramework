<?php


if (!function_exists('_root')) {
    function _root()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "/";
    }
}

if (!function_exists('assets')) {
    function assets($path)
    {
        $newPath = _root() . 'public/' . 'assets/' . $path;
        return $newPath;
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        return (new \Core\AuthManager())->getAuth();
    }
}

if (!function_exists('isLoggin')) {
    function isLoggin()
    {
        return (new \Core\AuthManager())->isLoggin();
    }
}

if (!function_exists('permission')) {
    function permission(string $permission, $subject = null)
    {
        if (!isLoggin()) return false;
        return \Core\Facades\Permission::test($permission, $subject);
    }
}
