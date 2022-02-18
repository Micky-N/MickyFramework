<?php

namespace App\Http\Controllers;

use MkyCore\Controller;
use MkyCore\Facades\View;


class HomeController extends Controller
{
    public function index()
    {
        return View::render('welcome');
    }
}