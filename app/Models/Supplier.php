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
}