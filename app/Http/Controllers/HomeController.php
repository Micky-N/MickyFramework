<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Core\Controller;
use Core\Facades\View;

class HomeController extends Controller
{
    public function index()
    {
        $product = Product::find(2);
        return View::render('welcome', compact('product'));
    }
}