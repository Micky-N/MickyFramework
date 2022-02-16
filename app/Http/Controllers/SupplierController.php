<?php

namespace App\Http\Controllers;

use MkyCore\Controller;
use MkyCore\Facades\View;
use MkyCore\Facades\Route;
use App\Models\Supplier;


class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::all();
        return View::render('suppliers.index', compact('suppliers'));
    }

    public function show($supplier)
    {
        $supplier = Supplier::find($supplier);
        return View::render('suppliers.show', compact('supplier'));
    }

    public function new()
    {
        // new
    }

    public function create(array $data)
    {
        Supplier::create($data);
        return Route::redirectName('suppliers.index');
    }

    public function edit($supplier)
    {
        // edit
    }

    public function update($supplier, array $data)
    {
        $supplier = Supplier::update($supplier, $data);
        return Route::redirectName('suppliers.index');
    }

    public function delete($supplier)
    {
        Supplier::delete($supplier);
        return Route::redirectName('suppliers.index');
    }
}