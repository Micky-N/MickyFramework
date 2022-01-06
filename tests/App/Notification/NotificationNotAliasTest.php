<?php


namespace Tests\App\Notification;


class NotificationNotAliasTest implements \Core\Interfaces\NotificationInterface
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

    /**
     * @inheritDoc
     */
    public function via($notifiable)
    {
        return ['notFound'];
    }
}