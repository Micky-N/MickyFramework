<?php

namespace App\Models;

use Core\Model;


class Notifiable extends Model
{
    protected string $table = 'notifiables';
    protected array $settable = ['notifiable_id', 'endpoint', 'auth', 'p256dh', 'expirationTime'];

}