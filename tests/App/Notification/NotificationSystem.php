<?php


namespace Tests\App\Notification;


use Core\Interfaces\NotificationSystemInterface;

class NotificationSystem implements NotificationSystemInterface
{

    private array $messageTest;

    public function __construct()
    {
        $this->messageTest = [];
    }

    public function send($notifiable, array $message): void
    {
        ReturnNotificationClass::setReturn($message);
    }
}