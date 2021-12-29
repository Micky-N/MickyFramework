<?php


namespace App\Listeners;


use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;

class TestCategoryListener implements ListenerInterface
{

    public function __construct()
    {
    }

    public function handle(EventInterface $event)
    {

    }
}