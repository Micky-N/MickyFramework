<?php

namespace App\Product\Models;

use App\Models\User;
use MkyCore\Model;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Category;

class Product extends Model
{
    protected string $primaryKey = 'code_product';
    protected array $settable = ['code_category', 'name', 'user_id', 'selling_price', 'photo'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'code_category');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'code_stock');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier', 'code_product', 'code_supplier');
    }

    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function getSelling_price(){
        return number_format($this->selling_price, 2, ',', ''). '€';
    }
}
