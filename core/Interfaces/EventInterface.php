<?php

namespace Core\Interfaces;


interface EventInterface
{

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|self
     */
    public function getTarget();

    /**
     * Get parameters passed to the event
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * Get a single parameter by name
     *
     * @param  string $name
     * @return mixed
     */
    public function getParam(string $name);

    /**
     * Set the event target
     *
     * @param  null|string|object $target
     * @return void
     */
    public function setTarget($target);

    /**
     * Set event parameters
     *
     * @param  array $params
     * @return void
     */
    public function setParams(array $params);

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @param  bool $flag
     */
    public function stopPropagation(bool $flag);

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped(): bool;
}
