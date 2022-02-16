<?php


namespace App\Listeners;


use MkyCore\Facades\Session;
use MkyCore\Interfaces\EventInterface;
use MkyCore\Interfaces\ListenerInterface;

class UpdateCategoryListener implements ListenerInterface
{

    public function __construct()
    {
    }

    public function handle(EventInterface $event)
    {
        $message = sprintf("La categorie %s a été modifié par %s", $event->getTarget()->name, $event->getParam('username'));
        Session::setFlashMessageOnType('flashMessage', 'update', $message);
    }
}