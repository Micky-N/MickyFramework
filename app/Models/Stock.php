<?php

namespace App\Models;

use App\Product\Models\Product;
use MkyCore\Model;

class Stock extends Model
{
    protected string $primaryKey = 'code_stock';
    protected array $settable = ['code_product', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'code_product');
    }
}