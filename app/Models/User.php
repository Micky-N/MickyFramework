<?php

namespace App\Models;

use Core\Model;
use Core\Traits\Notify;


class User extends Model
{
    use Notify;

    protected array $datetimes = ['CREATED_AT' => 'created'];
    protected array $settable = ['username', 'password', 'email', 'first_name', 'last_name', 'role_id', 'created'];

    public function fullName()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }

    public function webPushUser()
    {
        return $this->hasOne(Notifiable::class, 'notifiable_id');
    }

    public function routeNotificationFor(string $channel)
    {
        switch ($channel):
            default:
                return $this->hasOne(Notifiable::class, 'notifiable_id');
                break;
        endswitch;
    }

    public static function create(array $data, string $table = '')
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return parent::create($data, $table);
    }
}