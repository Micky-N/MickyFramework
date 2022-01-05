<?php


namespace Tests\Core\App\Event;


use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;

class TestAliasListener implements ListenerInterface
{

    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        $event->getTarget()->setCompleted(true);
    }
}