<?php


if (!function_exists('assets')) {
    function assets($path)
    {
        $newPath = "http://mickyframework.loc/" . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $path;
        $newPath = str_replace('\\', '/', $newPath);
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
