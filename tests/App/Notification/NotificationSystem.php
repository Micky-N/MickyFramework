<?php


namespace Tests\App\Notification;


class NotificationSystem
{

    private array $messageTest;

    public function __construct()
    {
        $this->messageTest = [];
    }

    public function send($notifiable, $message)
    {
        ReturnNotificationClass::setReturn($message);
    }
}