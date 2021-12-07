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
        foreach ($categories as $category) {
            $category->products = array_map(function($product){
                $product->selling_price = $product->getSelling_price();
                $product->seller = $product->seller->fullname;
                $product->suppliers = array_map(function($supplier){
                    $supplier->purchase_price = $supplier->getPurchase_price();
                    return $supplier;
                },$product->suppliers);
                return $product->with('stock', ['quantity']);
            },$category->products);
        }
        return View::render('welcome', compact('categories'));
    }
}