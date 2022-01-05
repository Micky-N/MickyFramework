<?php


namespace Tests\Core\App\Event;


use Core\Interfaces\EventInterface;

class TestNoAliasListener implements \Core\Interfaces\ListenerInterface
{

    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        $event->getTarget()->setName('burger eaten');
    }
}