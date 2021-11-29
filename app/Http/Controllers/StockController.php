<?php

namespace App\Http\Controllers;

use Core\Controller;
use App\Models\Stock;
use Core\Facades\View;
use Core\Facades\Route;


class StockController extends Controller
{
    
    public function index()
    {
        $stocks = Stock::all();
        return View::render('stocks.index', compact('stocks'));
    }

    public function show($stock)
    {
        $stock = Stock::find($stock);
        return View::render('stocks.show', compact('stock'));
    }

    public function new()
    {
        // new
    }

    public function create(array $data)
    {
        Stock::create($data);
        return Route::redirectName('stocks.index');
    }

    public function edit($stock)
    {
        // edit
    }

    public function update($stock, array $data)
    {
        Stock::update($stock, $data);
        return Route::redirectName('stocks.index');
    }
    
    public function delete($stock)
    {
        Stock::delete($stock);
        return Route::redirectName('stocks.index');
    }
}