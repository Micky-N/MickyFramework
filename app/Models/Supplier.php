<?php

namespace App\Models;

use Core\Model;

class Supplier extends Model
{
    protected string $primaryKey = 'code_supplier';

    public function getAddress()
    {
        return sprintf('%d %s, %s %s', $this->num_street, $this->name_street, $this->postcode, $this->city);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, '', 'code_supplier', 'code_product');
    }

    public function getPurchase_price(){
        return number_format($this->purchase_price, 2, ',', ''). '€';
    }
}