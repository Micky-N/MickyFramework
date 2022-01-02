<?php

namespace Core\Interfaces;


interface ListenerInterface
{

    /**
     * Action à faire à l'ecoute de
     * l'évènement
     *
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event);
}
