<?php


namespace Core\Traits;


use Core\App;
use Core\Interfaces\NotificationInterface;

trait Notify
{

    public function notify(NotificationInterface $notification)
    {
        foreach ($notification->via($this) as $via){
            $class = new \ReflectionClass(App::getAlias($via));
            $message = $notification->{'to'.ucfirst($via)}($this);
            call_user_func_array([$class->newInstance(), 'send'], [$this, $message]);
        }
    }
}