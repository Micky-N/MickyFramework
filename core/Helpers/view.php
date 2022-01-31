<?php


if(!function_exists('auth')){
    function auth()
    {
        return (new \Core\AuthManager())->getAuth();
    }
}