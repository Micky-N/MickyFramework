<?php

namespace App\Models;

use Core\Model;


class User extends Model
{

    public function fullName()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }
}