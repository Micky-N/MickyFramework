<?php

namespace App\Http\Controllers;

use MkyCore\Controller;
use MkyCore\Facades\View;
use App\Models\Category;
use App\Product\Models\Product;
use App\Product\Models\ProductSupplier;
use App\Models\Supplier;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $products = Product::all();
        $productSuppliers = ProductSupplier::all();
        return View::render('welcome', compact('categories', 'products', 'suppliers', 'productSuppliers'));
    }
}