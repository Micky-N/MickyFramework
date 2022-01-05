<?php


namespace Tests\Core\App\Event;


use Core\Interfaces\EventInterface;

class TestPropagationListener implements \Core\Interfaces\ListenerInterface
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