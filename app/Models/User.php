<?php

namespace App\Models;

use Core\Model;


class User extends Model
{
    protected string $table = 'users';
	protected string $primaryKey = 'code_user';
}