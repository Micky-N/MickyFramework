<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Facades\View;
use App\Models\Product;
use Core\Facades\Route;


class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $product->with('stock', ['quantity']);
        }
        return View::render('products.index', compact('products'));
    }

    public function show($product)
    {
        $product = Product::find($product);
        return View::render('products.show', compact('product'));
    }

    public function new()
    {
        // new
    }

    public function create(array $data)
    {
        Product::create($data);
        return Route::redirectName('products.index');
    }

    public function edit($product)
    {
        // edit
    }

    public function update($product, array $data)
    {
        Product::update($product, $data);
        return Route::redirectName('products.index');
    }
    
    public function delete($product)
    {
        Product::delete($product);
        return Route::redirectName('products.index');
    }
}