<?php


namespace Tests\App\Event;


use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;

class TestPropagationListener implements ListenerInterface
{

    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        $event->stopPropagation(true);
    }
}