<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SupplierNotification;
use Core\Controller;
use Core\Facades\Permission;
use Core\Facades\View;
use Core\Facades\Route;
use App\Models\Supplier;
use Core\Notification;


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