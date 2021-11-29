<?php

use Core\Facades\Route;

if(!function_exists('route')){
    function route($routeName, $params = []){
        try {
            return Route::generateUrlByName($routeName, $params);
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}

if(!function_exists('currentRoute')){
    function currentRoute($route = ''){
        return Route::currentRoute($route);
    }
}