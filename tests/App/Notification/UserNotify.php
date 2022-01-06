<?php


namespace Tests\App\Notification;


use Core\Traits\Notify;

class UserNotify
{
    use Notify;

    private $id;
    private $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}