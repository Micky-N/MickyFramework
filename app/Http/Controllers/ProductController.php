<?php

namespace App\Http\Controllers;

use Core\DB;
use Core\Controller;
use Core\Facades\View;
use App\Models\Product;
use Core\Facades\Route;
use App\Models\Category;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Cake\Database\Query;

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
        $categories = Category::all();
        $suppliers = Supplier::all();
        return View::render('products.show', compact('product', 'categories', 'suppliers'));
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
        return Route::redirectName('products.index');
    }

    public function updateSupplier($product, $supplier, array $data)
    {
        ProductSupplier::where('code_product', $product)
            ->where('code_supplier', $supplier)
            ->first()->modify($data);

        return Route::back();
    }

    public function delete($product)
    {
        Product::delete($product);
        return Route::redirectName('products.index');
    }

    public function deleteSupplier($product, $supplier)
    {
        $ps = ProductSupplier::where('code_product', $product)
            ->where('code_supplier', $supplier)
            ->first()->deleteSelf();

        return Route::back();
    }
}
