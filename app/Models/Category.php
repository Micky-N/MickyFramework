<?php

namespace App\Models;

use App\Product\Models\Product;
use Core\Model;

class Category extends Model
{
    protected string $primaryKey = 'code_category';
    protected array $settable = ['name', 'description'];

    public function products(){
        return $this->hasMany(Product::class, 'code_category');
    }
}