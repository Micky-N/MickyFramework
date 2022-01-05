<?php

namespace Core\Interfaces;


use Core\Traits\Dispatcher;

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

    /**
     * Retourne les actions de l'event
     * @return array
     */
    public function getActions(): array;

    /**
     * Retourne les paramètres de l'event
     * @return array
     */
    public function getParams(): array;

    /**
     * Retourne un paramètre de l'event
     * @param string $key
     * @return mixed
     */
    public function getParam(string $key);

    /**
     * Retourne la cible de l'event
     * @return mixed
     */
    public function getTarget();
}
