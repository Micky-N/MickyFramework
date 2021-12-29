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

    public function webPushUser()
    {
        return $this->hasOne(Notifiables::class, 'notifiable_id');
    }

    public function routeNotificationFor(string $channel)
    {
        switch ($channel):
            case 'beams':
                $class = str_replace('\\', '.', get_class($this));
                return $class . '.' . $this->id;
                break;
            case 'webPush':
                return $this->webPushUser();
                break;
        endswitch;
    }

    public static function create(array $data, string $table = '')
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return parent::create($data, $table);
    }
}