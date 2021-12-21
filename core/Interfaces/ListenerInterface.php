<?php

namespace Core\Interfaces;


interface ListenerInterface
{

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event);
}
