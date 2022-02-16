<?php

namespace App\Product\Http\Controllers\Admin;

use App\Models\Category;
use App\Product\Models\Product;
use App\Models\Supplier;
use MkyCore\Controller;
use MkyCore\Facades\Route;
use MkyCore\Facades\View;


class ProductController extends Controller
{

    public function index()
    {
        $products = Product::where('user_id', auth()->id)->get();
        foreach ($products as $product){
            $product->category = $product->category->name;
        }
        return View::render('admin.index', compact('products'));
    }

    public function show($product)
    {
        $product = Product::find($product);
        $product->seller = $product->seller->fullname;
        $product->with('suppliers');
        $categories = Category::all();
        $suppliers = Supplier::all();
        return View::render('admin.show', compact('product', 'categories', 'suppliers'));
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