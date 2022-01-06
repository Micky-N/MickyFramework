<?php


namespace Tests\App\Event;


use Core\Interfaces\EventInterface;

class TestNotSubscribeListener
{

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        return true;
    }
}