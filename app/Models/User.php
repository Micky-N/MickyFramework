<?php

namespace App\Models;

use Core\Model;


class User extends Model
{
    protected array $datetimes = ['created' => 'created'];

    public function fullName()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }

    public static function create(array $data, string $table = '')
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return parent::create($data, $table);
    }
}