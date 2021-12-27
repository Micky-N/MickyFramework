<?php

namespace App\Models;

use Core\Interfaces\NotificationInterface;
use Core\Model;
use Core\Traits\Notify;


class User extends Model
{
    use Notify;

    protected array $datetimes = ['created' => 'created'];

    public function fullName()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }

    public function notifiable()
    {
        return $this->hasOne(Notifiables::class, 'notifiable_id');
    }

    public static function create(array $data, string $table = '')
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return parent::create($data, $table);
    }
}