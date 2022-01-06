<?php


namespace Tests\App\Notification;


use Core\Interfaces\NotificationInterface;

class NotificationNotViaTest implements NotificationInterface
{
    /**
     * @var mixed
     */
    private $process;
    private string $action;

    public function __construct($process, string $action = '')
    {
        $this->process = $process;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ;
    }
}