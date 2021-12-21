<?php


namespace App\Listeners;


use Core\Interfaces\EventInterface;
use Core\Listener;

class UpdateCategoryListener extends Listener
{
    public function handle(EventInterface $event)
    {
        dd($event);
    }
}