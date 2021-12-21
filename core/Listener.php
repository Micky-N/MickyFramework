<?php


namespace Core;

use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;

class Listener implements ListenerInterface
{

    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        // TODO: Implement handle() method.
    }
}