<?php

use Core\Facades\Route;

if(!function_exists('route')){
    function route($routeName, $params = []){
        try {
            return Route::generateUrlByName($routeName, $params);
        } catch (\Exception $ex) {
            die($ex->getMessage());
        }
    }
}

if(!function_exists('currentRoute')){
    function currentRoute($route = ''){
        return Route::currentRoute($route);
    }
}

if(!function_exists('namespaceRoute')){
    function namespaceRoute($route = ''){
        return Route::namespaceRoute($route);
    }
}

if(!function_exists('redirectBack')){
    function redirectBack(){
        return 'javascript:history.back()';
    }
}