<?php


if (!function_exists('authorize')) {
    function authorize(string $permission, $subject = null)
    {
        return \Core\Facades\Template::authorize($permission, $subject);
    }
}

if (!function_exists('endauthorize')) {
    function endauthorize()
    {
        return \Core\Facades\Template::endauthorize();
    }
}

if (!function_exists('content')) {
    function content(string $name)
    {
        echo \Core\Facades\Template::content($name);;
    }
}

if (!function_exists('layout')) {
    function layout(string $path)
    {
        \Core\Facades\Template::layout($path);
    }
}

if (!function_exists('section')) {
    function section(string $name)
    {
        \Core\Facades\Template::section($name);
    }
}

if (!function_exists('endsection')) {
    function endsection()
    {
        \Core\Facades\Template::endsection();
    }
}
