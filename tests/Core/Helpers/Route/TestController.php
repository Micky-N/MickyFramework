<?php

namespace Tests\Core\Helpers\Route;

class TestController
{
    public function index(){
        return 'green';
    }

    public function show($id){
        return 'green'.$id;
    }
}
