<?php


namespace App\Listeners;


use Core\Facades\Session;
use Core\Interfaces\EventInterface;
use Core\Interfaces\ListenerInterface;

class TestCategoryListener implements ListenerInterface
{

    public function __construct()
    {
    }

    public function handle(EventInterface $event)
    {
        $message = sprintf("La categorie %s a été testé et approuvé par %s", $event->getTarget()->name, $event->getParam('username'));
        Session::setFlashMessageOnType('flashMessage', 'test', $message);
    }
}