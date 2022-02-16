<?php

namespace App\Models;

use App\Product\Models\Product;
use MkyCore\Model;

class Supplier extends Model
{
    protected string $primaryKey = 'code_supplier';
    protected array $settable = ['name', 'informations', 'num_street', 'name_street', 'postcode', 'city'];

    public function getAddress()
    {
        return sprintf('%d %s, %s %s', $this->num_street, $this->name_street, $this->postcode, $this->city);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, '', 'code_supplier', 'code_product');
    }
}