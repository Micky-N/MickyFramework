<?php


namespace Tests\App\Event;


use Core\Traits\Dispatcher;

class TestNotImplementEvent
{
    use Dispatcher;

    public function __construct()
    {
    }
}