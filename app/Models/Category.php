<?php

namespace App\Models;

use Core\Model;

class Category extends Model
{
    protected string $primaryKey = 'code_category';

    public function products(){
        return $this->hasMany(Product::class, 'code_category');
    }
}