<?php

namespace App\Controllers;

use MkyCore\Abstracts\Controller;
use MkyCore\View;


/**
 * @Router('/', as: 'home')
 * @return View
 */
class HomeController extends Controller
{

    /**
     * @Router('/', as: 'index')
     * @return View
     */
    public function index(): View
    {
        return view('welcome.twig');
    }
}