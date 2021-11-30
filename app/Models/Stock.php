<?php

namespace App\Models;

use Core\Model;

class Stock extends Model
{
    protected string $primaryKey = 'code_stock';

    public function product()
    {
        return $this->belongsTo(Product::class, 'code_product');
    }
}