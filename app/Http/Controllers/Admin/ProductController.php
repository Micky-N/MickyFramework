<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Core\Controller;
use Core\Facades\Route;
use Core\Facades\View;



class ProductController extends Controller
{

    public function index()
    {
        $products = Product::where('user_id', auth()->id)->get();
        foreach ($products as $product) {
            $product->with('stock', ['quantity']);
            $product->selling_price =  $product->getSelling_price();
            $product->with('category', ['name']);
        }
        $categories = Category::all();
        return View::render('admin.products.index', compact('products', 'categories'));
    }

    public function show($product)
    {
        $product = Product::find($product);
        $product->seller = $product->seller->fullname;
        $product->with('suppliers');
        $categories = Category::all();
        $suppliers = Supplier::all();
        return View::render('admin.products.show', compact('product', 'categories', 'suppliers'));
    }

    public function update($product, array $data)
    {
        if (isset($data['quantity'])) {
            $productStock = Product::find($product);
            $productStock->stock->modify(['quantity' => $data['quantity']]);
            unset($data['quantity']);
        }

        Product::update($product, $data);
        return Route::redirectName('admin.products.index');
    }

    public function delete($product)
    {
        Product::delete($product);
        return Route::redirectName('admin.products.index');
    }
}