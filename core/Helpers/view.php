<?php


if(!function_exists('assets')){
    function assets($path)
    {
        $newPath = "https://mickyframework.loc/" . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $path;
        $newPath = str_replace('\\', '/', $newPath);
        return $newPath;
    }
}

if(!function_exists('auth')){
    function auth()
    {
        return (new \Core\AuthManager())->getAuth();
    }
}

if(!function_exists('isLoggin')){
    function isLoggin()
    {
        return (new \Core\AuthManager())->isLoggin();
    }
}

if(!function_exists('permission')){
    function permission(string $permission, $subject = null)
    {
        return \Core\Facades\Permission::test($permission, $subject);
    }
}
