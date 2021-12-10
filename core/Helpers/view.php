<?php

use Core\Facades\Template;
use Core\Facades\View;


if (!function_exists('asset')) {
    function asset($path)
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


if (!function_exists('authorize')) {
    function authorize(string $permission, $subject)
    {
        return \Core\Facades\Permission::test($permission, $subject);
    }
}

if (!function_exists('content')) {
    function content(string $name)
    {
        echo Template::content($name);;
    }
}

if (!function_exists('layout')) {
    function layout(string $path)
    {
        Template::layout($path);
    }
}

if (!function_exists('section')) {
    function section(string $name)
    {
        Template::section($name);
    }
}

if (!function_exists('endsection')) {
    function endsection()
    {
        Template::endsection();
    }
}
