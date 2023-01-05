<?php

namespace App\Controllers;

use MkyCore\Abstracts\Controller;
use MkyCore\Cookie;
use MkyCore\Crypt;
use MkyCore\Str;
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
        dd($this->test());

        return view('welcome.twig');
    }

    public function test()
    {
        $plaintext = "message to be encrypted";
        return \MkyCore\Facades\Cookie::get('test');
        return null;
    }
}