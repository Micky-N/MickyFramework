<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Facades\View;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return View::render('welcome', compact('categories'));
    }
}