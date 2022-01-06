<?php


namespace Tests\App\Notification;


use Core\Interfaces\NotificationInterface;

class NotificationNotInstantiableTest implements NotificationInterface
{

    public function via($notifiable)
    {
        return true;
    }
}