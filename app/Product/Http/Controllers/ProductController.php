<?php

namespace App\Product\Http\Controllers;

use Core\Controller;
use Core\Facades\View;
use App\Product\Models\Product;
use Core\Facades\Route;
use App\Models\Category;
use App\Product\Models\ProductSupplier;
use App\Models\Supplier;
use App\Models\User;
use App\Utils\Paginate;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        $users = User::select('username, id')->where('role_id', '!=', 3)->get();
        $categories = Category::all();
        $products = new Paginate($products, 10);
        return View::render('index', compact('products', 'categories', 'users'));
    }

    public function show($product)
    {
        $product = Product::find($product);
        $product->seller = $product->seller->fullname;
        $categories = Category::all();
        $suppliers = Supplier::all();
        
        return View::render('show', compact('product', 'categories', 'suppliers'));
    }

    public function new()
    {
        // new
    }

    public function create(array $data)
    {
        Product::create($data);
        return Route::back();
    }

    public function createSupplier($product, array $data)
    {
        Product::find($product)->attach('product_supplier', $data);
        return Route::back();
    }

    public function edit($product)
    {
        // edit
    }

    public function update($product, array $data)
    {
        if (isset($data['quantity'])) {
            $productStock = Product::find($product);
            $productStock->stock->modify(['quantity' => $data['quantity']]);
            unset($data['quantity']);
        }

        Product::update($product, $data);
        return Route::redirectName('index');
    }

    public function updateSupplier($product, $supplier, array $data)
    {
        ProductSupplier::where('code_product', $product)
            ->where('code_supplier', $supplier)
            ->first()
            ->modify($data);

        return Route::back();
    }

    public function delete($product)
    {
        Product::delete($product);
        return Route::redirectName('index');
    }

    public function deleteSupplier($product, $supplier)
    {
        $ps = ProductSupplier::where('code_product', $product)
            ->where('code_supplier', $supplier)
            ->first()->destroy();

        return Route::back();
    }
}
