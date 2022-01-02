<?php

namespace Core\Interfaces;


interface EventInterface
{

    /**
     * Indique si la propagation des events
     * doit être stopper
     *
     * @param bool $flag
     */
    public function stopPropagation(bool $flag);

    /**
     * Retourne si la propagation est
     * stoppée
     *
     * @return bool
     */
    public function isPropagationStopped(): bool;
}
