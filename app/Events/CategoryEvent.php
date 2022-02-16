<?php


namespace App\Events;

use MkyCore\Event;

class CategoryEvent extends Event
{
    /**
     * Event constructor.
     * @param mixed $target
     * @param array $actions
     * @param array $params
     */
    public function __construct($target, array $actions, array $params = [])
    {
        parent::__construct($target, $actions, $params);
    }
}