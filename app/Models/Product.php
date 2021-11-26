<?php

namespace App\Models;

use Core\Model;
use App\Models\Stock;
use App\Models\Supply;
use App\Models\Category;

class Product extends Model
{
    protected string $primaryKey = 'code_product';


    public function category()
    {
        return $this->belongsTo(Category::class, 'code_category');
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class,'','code_product', 'code_stock');
    }

    public function supplies()
    {
        return $this->belongsToMany(Supply::class,'','code_product', 'code_supply');
    }
}