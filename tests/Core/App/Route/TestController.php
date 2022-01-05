<?php

namespace Tests\Core\App\Route;

use Core\Controller;

class TestController extends Controller
{
    public function index(){
        return 'green';
    }

    public function show($id){
        return 'red '.$id;
    }

    public function post(array $data){
        return $data['name'];
    }
}
