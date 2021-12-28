<?php


namespace Core;


use Core\Interfaces\NotificationInterface;

class Notification
{
    public static function send(array $notifiables, NotificationInterface $notification)
    {
        foreach ($notifiables as $notifiable){
            $notifiable->notify($notification);
        }
    }
}