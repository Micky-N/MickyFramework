<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Facades\View;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->products = array_map(function($p){
                $p->suppliers = $p->suppliers;
                return $p->with('stock', ['quantity']);
            },$category->products);
        }
        return View::render('welcome', compact('categories'));
    }
}