<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Facades\View;
use App\Models\Category;


class CategoryController extends Controller
{
    
    public function index()
    {
        $categories = Category::all();
        return View::render('categories', compact('categories'));
    }

    public function show($id)
    {
        // show
    }

    public function new()
    {
        // new
    }

    public function create(array $data)
    {
        // create
    }

    public function edit($id)
    {
        // edit
    }

    public function update($id, array $data)
    {
        // update
    }
    
    public function delete($id)
    {
        // delete
    }
}